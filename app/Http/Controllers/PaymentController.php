<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createCheckoutSession(Event $event, Request $request)
    {
        // Load category relationship
        $event->load('category');
        
        // Validate that the event is paid
        if ($event->isFree()) {
            return redirect()->route('events.register', $event)
                ->with('error', 'This event is free and does not require payment.');
        }

        // Validate that the event has proper pricing data
        if (!$event->price || !$event->currency) {
            return redirect()->route('events.public.show', $event)
                ->with('error', 'Event pricing information is incomplete.');
        }

        // Validate registration data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:registrations,email,NULL,id,event_id,' . $event->id,
            'phone' => 'nullable|string|max:20',
        ]);

        // Check if event is full
        if ($event->isFull()) {
            return redirect()->route('events.public.show', $event)
                ->with('error', 'Sorry, this event is full!');
        }

        // Check category registration limits for guest users
        if ($event->category) {
            if (!$event->category->canGuestRegister($validated['email'])) {
                $remaining = $event->category->getRemainingGuestSlots($validated['email']);
                return redirect()->route('events.public.show', $event)->with('error', 
                    "You have reached the maximum number of registrations for {$event->category->name} events. " .
                    "You can register for up to {$event->category->max_registrations_per_user} events in this category. " .
                    "You have {$remaining} slots remaining."
                );
            }
        }

        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => strtolower($event->currency),
                            'product_data' => [
                                'name' => $event->title,
                                'description' => "Registration for {$event->title} on " . $event->date->format('M j, Y'),
                            ],
                            'unit_amount' => $event->getPriceInCentsAttribute(),
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => route('payment.success', ['event' => $event->id]) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('events.public.show', $event),
                'metadata' => [
                    'event_id' => $event->id,
                    'user_email' => $validated['email'],
                    'user_name' => $validated['name'],
                    'user_phone' => $validated['phone'] ?? '',
                ],
            ]);

            return redirect($session->url);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Payment processing failed: ' . $e->getMessage());
        }
    }

    public function success(Request $request, Event $event)
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect()->route('events.public.show', $event)
                ->with('error', 'Invalid payment session.');
        }

        try {
            $session = Session::retrieve($sessionId);

            if ($session->payment_status === 'paid') {
                // Check if registration already exists (prevent duplicates)
                $existingRegistration = Registration::where('stripe_session_id', $sessionId)->first();
                
                if ($existingRegistration) {
                    return view('public.payment.success', [
                        'event' => $event, 
                        'registration' => $existingRegistration
                    ]);
                }

                // Create registration record
                $registration = Registration::create([
                    'event_id' => $event->id,
                    'user_id' => null, // Guest registration, no user account
                    'name' => $session->metadata->user_name ?? 'Guest User',
                    'email' => $session->metadata->user_email ?? 'no-email@example.com',
                    'phone' => $session->metadata->user_phone ?? null,
                    'status' => 'confirmed',
                    'payment_status' => 'paid',
                    'stripe_session_id' => $sessionId,
                ]);

                // Increment event capacity
                $event->increment('current_capacity');

                // Send confirmation emails
                try {
                    // Send confirmation email to attendee
                    Mail::to($registration->email)->send(new \App\Mail\AttendeeConfirmation($registration, $event));

                    // Send alert email to admin
                    $adminEmail = config('mail.from.address');
                    Mail::to($adminEmail)->send(new \App\Mail\AdminAlert($registration, $event));
                } catch (\Exception $mailException) {
                    // Log email error but don't fail the registration
                    Log::warning('Failed to send registration emails: ' . $mailException->getMessage());
                }

                return view('public.payment.success', compact('event', 'registration'));
            } else {
                return redirect()->route('events.public.show', $event)
                    ->with('error', 'Payment was not completed successfully.');
            }
        } catch (\Exception $e) {
            return redirect()->route('events.public.show', $event)
                ->with('error', 'Failed to verify payment: ' . $e->getMessage());
        }
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $webhookSecret);

            switch ($event->type) {
                case 'checkout.session.completed':
                    $session = $event->data->object;
                    
                    // Update registration status if needed
                    if (isset($session->metadata->event_id)) {
                        $registration = Registration::where('stripe_session_id', $session->id)->first();
                        if ($registration) {
                            $registration->update(['payment_status' => 'paid']);
                        }
                    }
                    break;

                default:
                    // Handle other webhook events as needed
                    break;
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}

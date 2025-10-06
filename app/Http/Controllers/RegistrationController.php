<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use App\Models\HomepageContentBlock;
use App\Mail\AttendeeConfirmation;
use App\Mail\AdminAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class RegistrationController extends Controller
{
    /**
     * Display a listing of upcoming events for public registration
     */
    public function index(): View
    {
        $events = \App\Models\Event::where('date', '>=', now()->toDateString())
            ->orderBy('date', 'asc')
            ->get();
            
        // Get active content blocks ordered by sort_order
        $contentBlocks = HomepageContentBlock::getActiveBlocks();

        return view('public.events.index', compact('events', 'contentBlocks'));
    }

    /**
     * Show event details and registration form
     */
    public function show(\App\Models\Event $event): View
    {
        return view('public.events.show', compact('event'));
    }

    /**
     * Store a new registration
     */
    public function store(Request $request, \App\Models\Event $event): RedirectResponse
    {
        // Check if event is full
        if ($event->isFull()) {
            return back()->with('error', 'Sorry, this event is full!');
        }

        // Check if event is paid - should redirect to payment
        if (!$event->isFree()) {
            return redirect()->route('payment.checkout', $event)
                ->with('error', 'This is a paid event. Please complete payment to register.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:registrations,email,NULL,id,event_id,' . $event->id,
            'phone' => 'nullable|string|max:20',
        ]);

        try {
            DB::transaction(function () use ($validated, $event) {
                // Create registration
                $registration = Registration::create([
                    'event_id' => $event->id,
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'] ?? null,
                    'status' => 'confirmed',
                    'payment_status' => 'free',
                ]);

                // Increment current capacity atomically
                $event->increment('current_capacity');

                // Try to send emails, but don't fail the transaction if emails fail
                try {
                    // Send confirmation email to attendee
                    Mail::to($registration->email)->send(new AttendeeConfirmation($registration, $event));

                    // Send alert email to admin
                    $adminEmail = config('mail.from.address');
                    Mail::to($adminEmail)->send(new AdminAlert($registration, $event));
                } catch (\Exception $mailException) {
                    // Log email error but don't fail the registration
                    Log::warning('Failed to send registration emails: ' . $mailException->getMessage());
                }
            });

            return redirect()->route('events.public.show', $event)
                ->with('success', 'Registration successful! You will receive a confirmation email shortly.');

        } catch (\Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage());
            return back()->with('error', 'Registration failed. Please try again.');
        }
    }
}

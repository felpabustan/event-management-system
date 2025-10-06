<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\Event;
use App\Models\User;
use App\Mail\RegistrationCancelled;
use App\Mail\AdminRegistrationCancelled;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RegistrationController extends Controller
{
    /**
     * Delete a registration and send email notifications
     */
    public function destroy(Registration $registration): RedirectResponse
    {
        // Only super admins can delete registrations
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Only super admins can delete registrations.');
        }

        $event = $registration->event;
        $registrationData = [
            'name' => $registration->name,
            'email' => $registration->email,
            'phone' => $registration->phone,
            'event_title' => $event->title,
            'event_date' => $event->date->format('M j, Y'),
            'event_time' => \Carbon\Carbon::createFromFormat('H:i:s', $event->time)->format('g:i A'),
            'event_venue' => $event->venue,
            'payment_status' => $registration->payment_status,
            'is_paid_event' => $event->is_paid,
            'price' => $event->is_paid ? $event->getFormattedPriceAttribute() : null,
            'cancelled_by' => Auth::user()->name,
            'cancellation_date' => now()->format('M j, Y g:i A'),
            'stripe_session_id' => $registration->stripe_session_id,
        ];

        try {
            // Send email to the registrant
            if ($registration->email) {
                Mail::to($registration->email)->send(new RegistrationCancelled($registrationData));
                Log::info('Cancellation email sent to registrant', [
                    'registration_id' => $registration->id,
                    'email' => $registration->email
                ]);
            }

            // Send email to all admin users
            $adminUsers = User::whereIn('role', ['admin', 'super_admin'])->get();
            foreach ($adminUsers as $admin) {
                Mail::to($admin->email)->send(new AdminRegistrationCancelled($registrationData));
            }
            
            Log::info('Admin notification emails sent', [
                'registration_id' => $registration->id,
                'admin_count' => $adminUsers->count()
            ]);

            // Delete the registration
            $registration->delete();

            // Update event capacity
            $event->decrement('current_capacity');

            $message = "Registration for {$registrationData['name']} has been cancelled successfully. ";
            $message .= "Email notifications have been sent to the registrant and all admin users.";
            
            if ($event->is_paid && $registration->payment_status === 'paid') {
                $message .= " Please process the refund manually in the Stripe dashboard.";
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Failed to delete registration or send emails:', [
                'registration_id' => $registration->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Failed to delete registration: ' . $e->getMessage());
        }
    }
}

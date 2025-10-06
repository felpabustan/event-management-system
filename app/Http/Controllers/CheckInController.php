<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckInController extends Controller
{
    /**
     * Show the QR scanner interface for an event
     */
    public function scanner(Event $event)
    {
        $registrations = $event->registrations()
            ->with('checkedInBy')
            ->orderBy('created_at', 'desc')
            ->get();

        $checkedInCount = $registrations->where('checked_in', true)->count();
        $totalRegistrations = $registrations->count();

        return view('admin.checkin.scanner', compact('event', 'registrations', 'checkedInCount', 'totalRegistrations'));
    }

    /**
     * Verify QR code and check in attendee
     */
    public function verify(Event $event, Request $request)
    {
        $token = $request->input('token') ?? $request->route('token');

        Log::info('QR Check-in attempt', [
            'event_id' => $event->id,
            'token' => $token,
            'request_body' => $request->all()
        ]);

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'No QR token provided'
            ], 400);
        }

        $registration = Registration::where('qr_code_token', $token)
            ->where('event_id', $event->id)
            ->first();

        Log::info('Registration lookup result', [
            'token' => $token,
            'event_id' => $event->id,
            'registration_found' => $registration ? $registration->id : null
        ]);

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid QR code or registration not found'
            ], 404);
        }

        if ($registration->checked_in) {
            return response()->json([
                'success' => false,
                'message' => 'Attendee already checked in at ' . $registration->checked_in_at->format('M j, Y g:i A'),
                'registration' => [
                    'name' => $registration->name,
                    'email' => $registration->email,
                    'checked_in_at' => $registration->checked_in_at->format('M j, Y g:i A'),
                    'checked_in_by' => $registration->checkedInBy->name ?? 'Unknown'
                ]
            ], 409);
        }

        // Check in the attendee
        $registration->checkIn(Auth::user());

        return response()->json([
            'success' => true,
            'message' => 'Attendee successfully checked in!',
            'registration' => [
                'id' => $registration->id,
                'name' => $registration->name,
                'email' => $registration->email,
                'phone' => $registration->phone,
                'payment_status' => $registration->payment_status,
                'checked_in_at' => $registration->checked_in_at->format('M j, Y g:i A'),
                'checked_in_by' => Auth::user()->name
            ]
        ]);
    }

    /**
     * Manual check-in (without QR code)
     */
    public function manualCheckIn(Request $request, Registration $registration)
    {
        if ($registration->checked_in) {
            return redirect()->back()->with('error', 'Attendee already checked in.');
        }

        $registration->checkIn(Auth::user());

        return redirect()->back()->with('success', 'Attendee checked in successfully!');
    }

    /**
     * Undo check-in
     */
    public function undoCheckIn(Registration $registration)
    {
        if (!$registration->checked_in) {
            return redirect()->back()->with('error', 'Attendee is not checked in.');
        }

        $registration->update([
            'checked_in' => false,
            'checked_in_at' => null,
            'checked_in_by' => null,
        ]);

        return redirect()->back()->with('success', 'Check-in undone successfully!');
    }
}

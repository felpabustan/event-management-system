<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $events = Event::with('category')->orderBy('date', 'asc')->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.events.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'venue' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'max_capacity' => 'required|integer|min:1',
            'is_paid' => 'nullable|boolean',
            'price' => 'nullable|numeric|min:0|required_if:is_paid,1',
            'currency' => 'nullable|string|max:3|required_if:is_paid,1',
            'stripe_price_id' => 'nullable|string|max:255',
        ]);

        // Set default values for pricing
        $validated['is_paid'] = $request->has('is_paid') && $request->boolean('is_paid');
        
        if (!$validated['is_paid']) {
            $validated['price'] = null;
            $validated['currency'] = null;
            $validated['stripe_price_id'] = null;
        } else {
            // Ensure currency has a default value if not provided
            $validated['currency'] = $validated['currency'] ?? 'USD';
        }

        Event::create($validated);

        return redirect()->route('events.index')
            ->with('success', 'Event created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event): View
    {
        $event->load('category');
        $registrations = $event->registrations()
            ->with(['checkedInBy', 'adminUser'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('admin.events.show', compact('event', 'registrations'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event): View
    {
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.events.edit', compact('event', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'venue' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'max_capacity' => 'required|integer|min:1|min:' . $event->current_capacity,
            'is_paid' => 'nullable|boolean',
            'price' => 'nullable|numeric|min:0|required_if:is_paid,1',
            'currency' => 'nullable|string|max:3|required_if:is_paid,1',
            'stripe_price_id' => 'nullable|string|max:255',
        ]);

        // Set default values for pricing
        $validated['is_paid'] = $request->has('is_paid') && $request->boolean('is_paid');
        
        if (!$validated['is_paid']) {
            $validated['price'] = null;
            $validated['currency'] = null;
            $validated['stripe_price_id'] = null;
        } else {
            // Ensure currency has a default value if not provided
            $validated['currency'] = $validated['currency'] ?? 'USD';
        }

        $event->update($validated);

        return redirect()->route('events.index')
            ->with('success', 'Event updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event): RedirectResponse
    {
        // Only super admins can delete events
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Only super admins can delete events.');
        }

        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully!');
    }

    /**
     * Show form to manually add registration to an event
     */
    public function showAddRegistration(Event $event): View
    {
        $event->load('category');
        return view('admin.events.add-registration', compact('event'));
    }

    /**
     * Store manual registration for an event
     */
    public function storeManualRegistration(Request $request, Event $event): RedirectResponse
    {
        // Check if event is full
        if ($event->isFull()) {
            return back()->with('error', 'Sorry, this event is full! Cannot add more registrations.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:registrations,email,NULL,id,event_id,' . $event->id,
            'phone' => 'nullable|string|max:20',
            'payment_status' => 'required|in:free,paid,pending',
            'status' => 'required|in:confirmed,pending,cancelled',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check category registration limits for guest users
        if ($event->category) {
            if (!$event->category->canGuestRegister($validated['email'])) {
                $remaining = $event->category->getRemainingGuestSlots($validated['email']);
                return back()->with('error', 
                    "This person has reached the maximum number of registrations for {$event->category->name} events. " .
                    "They can register for up to {$event->category->max_registrations_per_user} events in this category. " .
                    "They have {$remaining} slots remaining."
                )->withInput();
            }
        }

        // For paid events, ensure payment status is not 'free'
        if (!$event->isFree() && $validated['payment_status'] === 'free') {
            return back()->with('error', 'This is a paid event. Payment status cannot be "free".')
                ->withInput();
        }

        // For free events, set payment status to 'free'
        if ($event->isFree()) {
            $validated['payment_status'] = 'free';
        }

        try {
            \DB::transaction(function () use ($validated, $event) {
                // Create registration
                $registration = \App\Models\Registration::create([
                    'event_id' => $event->id,
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'] ?? null,
                    'status' => $validated['status'],
                    'payment_status' => $validated['payment_status'],
                    'notes' => $validated['notes'] ?? null,
                    'created_by_admin' => true,
                    'admin_user_id' => Auth::id(),
                ]);

                // Only increment capacity if status is confirmed
                if ($validated['status'] === 'confirmed') {
                    $event->increment('current_capacity');
                }
            });

            return redirect()->route('events.show', $event)
                ->with('success', 'Registration added successfully!');
        } catch (\Exception $e) {
            \Log::error('Manual registration failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to add registration. Please try again.')
                ->withInput();
        }
    }

    /**
     * Export registrations for a specific event
     */
    public function exportRegistrations(Event $event)
    {
        // Only super admins can export CSV data
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Only super admins can export registration data.');
        }

        $filename = 'event_' . $event->id . '_registrations_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($event) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Name', 'Email', 'Phone', 'Registration Date', 'Status', 'Payment Status']);

            foreach ($event->registrations as $registration) {
                fputcsv($file, [
                    $registration->name,
                    $registration->email,
                    $registration->phone ?? '',
                    $registration->created_at->format('Y-m-d H:i:s'),
                    ucfirst($registration->status ?? 'pending'),
                    ucfirst($registration->payment_status ?? 'pending'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

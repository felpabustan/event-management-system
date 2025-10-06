<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard
     */
    public function index(): View
    {
        $totalEvents = Event::count();
        $upcomingEvents = Event::where('date', '>=', now()->toDateString())->count();
        $totalRegistrations = Registration::count();
        $recentEvents = Event::with('registrations')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalEvents',
            'upcomingEvents', 
            'totalRegistrations',
            'recentEvents'
        ));
    }
}

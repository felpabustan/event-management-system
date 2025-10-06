<?php

namespace App\Http\Controllers;

use App\Models\HomepageSetting;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HomepageSettingsController extends Controller
{
    /**
     * Display homepage settings form
     */
    public function index(): View
    {
        $settings = HomepageSetting::orderBy('group')->orderBy('sort_order')->get()->groupBy('group');
        
        return view('admin.homepage-settings.index', compact('settings'));
    }

    /**
     * Update homepage settings
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable|string|max:1000',
        ]);

        foreach ($validated['settings'] as $key => $value) {
            HomepageSetting::setValue($key, $value);
        }

        // Clear all cache
        HomepageSetting::clearCache();

        return redirect()->route('homepage-settings.index')
            ->with('success', 'Homepage settings updated successfully!');
    }
}
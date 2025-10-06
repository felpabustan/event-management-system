<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of admin users.
     */
    public function index(): View
    {
        // Only super admins can manage users
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Only super admins can manage users.');
        }

        $users = User::where('role', '!=', 'user')->orderBy('created_at', 'desc')->get();
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new admin user.
     */
    public function create(): View
    {
        // Only super admins can create users
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Only super admins can create users.');
        }

        return view('admin.users.create');
    }

    /**
     * Store a newly created admin user in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Only super admins can create users
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Only super admins can create users.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['admin', 'super_admin'])],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Admin user created successfully!');
    }

    /**
     * Display the specified admin user.
     */
    public function show(User $user): View
    {
        // Only super admins can view user details
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Only super admins can view user details.');
        }

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified admin user.
     */
    public function edit(User $user): View
    {
        // Only super admins can edit users
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Only super admins can edit users.');
        }

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified admin user in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        // Only super admins can update users
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Only super admins can update users.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['admin', 'super_admin'])],
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];

        // Only update password if provided
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('admin.users.index')
            ->with('success', 'Admin user updated successfully!');
    }

    /**
     * Remove the specified admin user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        // Only super admins can delete users
        if (!Auth::user()->isSuperAdmin()) {
            abort(403, 'Only super admins can delete users.');
        }

        // Prevent deleting yourself
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Admin user deleted successfully!');
    }
}

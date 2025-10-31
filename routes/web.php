<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomepageSettingsController;
use App\Http\Controllers\HomepageContentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RegistrationController as AdminRegistrationController;
use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [RegistrationController::class, 'index'])->name('home');
Route::get('/events', [RegistrationController::class, 'index'])->name('events.public.index');
Route::get('/events/{event}', [RegistrationController::class, 'show'])->name('events.public.show');
Route::post('/events/{event}/register', [RegistrationController::class, 'store'])->name('events.register');
Route::post('/events/{event}/check-category-status', [RegistrationController::class, 'checkCategoryStatus'])->name('events.check.category');

// Payment routes
Route::post('/events/{event}/payment/checkout', [PaymentController::class, 'createCheckoutSession'])
    ->name('payment.checkout');
Route::get('/events/{event}/payment/success', [PaymentController::class, 'success'])
    ->name('payment.success');
Route::post('/stripe/webhook', [PaymentController::class, 'webhook'])
    ->name('stripe.webhook');

// Admin routes (requires authentication)
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Event management
    Route::resource('admin/events', EventController::class)->names([
        'index' => 'events.index',
        'create' => 'events.create',
        'store' => 'events.store',
        'show' => 'events.show',
        'edit' => 'events.edit',
        'update' => 'events.update',
        'destroy' => 'events.destroy',
    ]);
    
    // Category management
    Route::resource('/admin/categories', CategoryController::class, [
        'names' => [
            'index' => 'admin.categories.index',
            'create' => 'admin.categories.create',
            'store' => 'admin.categories.store',
            'show' => 'admin.categories.show',
            'edit' => 'admin.categories.edit',
            'update' => 'admin.categories.update',
            'destroy' => 'admin.categories.destroy',
        ]
    ]);
    
    // Export registrations
    Route::get('/admin/events/{event}/export', [EventController::class, 'exportRegistrations'])
        ->name('events.export');
    
    // Manual Registration Management
    Route::get('/admin/events/{event}/add-registration', [EventController::class, 'showAddRegistration'])
        ->name('events.add-registration');
    Route::post('/admin/events/{event}/add-registration', [EventController::class, 'storeManualRegistration'])
        ->name('events.store-manual-registration');
    
    // Check-in Management
    Route::get('/admin/events/{event}/checkin', [CheckInController::class, 'scanner'])
        ->name('admin.checkin.scanner');
    Route::post('/admin/events/{event}/checkin/verify', [CheckInController::class, 'verify'])
        ->name('admin.checkin.verify');
    Route::post('/admin/registrations/{registration}/checkin', [CheckInController::class, 'manualCheckIn'])
        ->name('admin.checkin.manual');
    Route::post('/admin/registrations/{registration}/undo-checkin', [CheckInController::class, 'undoCheckIn'])
        ->name('admin.checkin.undo');
    
    // Homepage Settings
    Route::get('/admin/homepage-settings', [HomepageSettingsController::class, 'index'])
        ->name('homepage-settings.index');
    Route::put('/admin/homepage-settings', [HomepageSettingsController::class, 'update'])
        ->name('homepage-settings.update');
    
    // Homepage Content Blocks
    Route::resource('/admin/homepage-content', HomepageContentController::class, [
        'names' => [
            'index' => 'homepage-content.index',
            'create' => 'homepage-content.create',
            'store' => 'homepage-content.store',
            'edit' => 'homepage-content.edit',
            'update' => 'homepage-content.update',
            'destroy' => 'homepage-content.destroy',
        ],
        'parameters' => [
            'homepage-content' => 'contentBlock'
        ]
    ]);
    Route::post('/admin/homepage-content/update-order', [HomepageContentController::class, 'updateOrder'])
        ->name('homepage-content.update-order');
    
    // Admin User Management (Super Admin only)
    Route::resource('/admin/users', UserController::class, [
        'names' => [
            'index' => 'admin.users.index',
            'create' => 'admin.users.create',
            'store' => 'admin.users.store',
            'show' => 'admin.users.show',
            'edit' => 'admin.users.edit',
            'update' => 'admin.users.update',
            'destroy' => 'admin.users.destroy',
        ]
    ]);
    
    // Registration Management (Super Admin only)
    Route::delete('/admin/registrations/{registration}', [AdminRegistrationController::class, 'destroy'])
        ->name('admin.registrations.destroy');
    
    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
        'max_registrations_per_user',
        'color',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the events for this category
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Get active events for this category
     */
    public function activeEvents(): HasMany
    {
        return $this->hasMany(Event::class)->where('date', '>=', now()->toDateString());
    }

    /**
     * Check how many times a guest user (by email) has registered for events in this category
     */
    public function getGuestRegistrationCount(string $email): int
    {
        return Registration::whereHas('event', function ($query) {
                $query->where('category_id', $this->id);
            })
            ->where('email', $email)
            ->whereNull('user_id') // Ensure we're only counting guest registrations
            ->count();
    }

    /**
     * Check if a guest user can register for more events in this category
     */
    public function canGuestRegister(string $email): bool
    {
        return $this->getGuestRegistrationCount($email) < $this->max_registrations_per_user;
    }

    /**
     * Get remaining registration slots for a guest user in this category
     */
    public function getRemainingGuestSlots(string $email): int
    {
        return max(0, $this->max_registrations_per_user - $this->getGuestRegistrationCount($email));
    }

    /**
     * Scope to get only active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
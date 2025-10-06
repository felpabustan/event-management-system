<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Event extends Model
{
    protected $fillable = [
        'title',
        'date',
        'time',
        'venue',
        'description',
        'max_capacity',
        'current_capacity',
        'is_paid',
        'price',
        'currency',
    ];

    protected $casts = [
        'date' => 'date',
        'is_paid' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function isFull(): bool
    {
        return $this->current_capacity >= $this->max_capacity;
    }

    public function availableSpots(): int
    {
        return max(0, $this->max_capacity - $this->current_capacity);
    }

    public function getFormattedTimeAttribute(): string
    {
        try {
            return Carbon::createFromFormat('H:i:s', $this->time)->format('g:i A');
        } catch (\Exception $e) {
            return Carbon::createFromFormat('H:i', $this->time)->format('g:i A');
        }
    }

    public function getTimeForInputAttribute(): string
    {
        try {
            return Carbon::createFromFormat('H:i:s', $this->time)->format('H:i');
        } catch (\Exception $e) {
            return Carbon::createFromFormat('H:i', $this->time)->format('H:i');
        }
    }

    public function isFree(): bool
    {
        return !$this->is_paid || $this->price <= 0;
    }

    public function getFormattedPriceAttribute(): string
    {
        if ($this->isFree()) {
            return 'Free';
        }
        
        $currency = $this->currency ?: 'USD';
        return strtoupper($currency) . ' ' . number_format($this->price, 2);
    }

    public function getPriceInCentsAttribute(): int
    {
        return (int) ($this->price * 100);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Registration extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',
        'name',
        'email',
        'phone',
        'status',
        'payment_status',
        'stripe_session_id',
        'qr_code_token',
        'checked_in',
        'checked_in_at',
        'checked_in_by',
    ];

    protected $casts = [
        'checked_in' => 'boolean',
        'checked_in_at' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function checkedInBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }

    /**
     * Generate a unique QR code token for this registration
     */
    public function generateQrCodeToken(): string
    {
        do {
            $token = Str::random(32);
        } while (self::where('qr_code_token', $token)->exists());

        $this->update(['qr_code_token' => $token]);
        return $token;
    }

    /**
     * Get the QR code data for this registration
     */
    public function getQrCodeData(): string
    {
        if (!$this->qr_code_token) {
            $this->generateQrCodeToken();
        }

        // Return just the token - the scanner will handle the verification
        return $this->qr_code_token;
    }

    /**
     * Check if this registration is checked in
     */
    public function isCheckedIn(): bool
    {
        return $this->checked_in;
    }

    /**
     * Check in this registration
     */
    public function checkIn(User $user): bool
    {
        if ($this->checked_in) {
            return false; // Already checked in
        }

        return $this->update([
            'checked_in' => true,
            'checked_in_at' => now(),
            'checked_in_by' => $user->id,
        ]);
    }
}

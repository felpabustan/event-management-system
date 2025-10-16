<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class RecaptchaRule implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            $fail('The reCAPTCHA verification is required.');
            return;
        }

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key'),
            'response' => $value,
            'remoteip' => request()->ip(),
        ]);

        $result = $response->json();

        if (!$result['success']) {
            $fail('The reCAPTCHA verification failed. Please try again.');
            return;
        }

        // For reCAPTCHA v3, check the score (0.0 to 1.0, where 1.0 is very likely human)
        if (isset($result['score'])) {
            $score = $result['score'];
            $threshold = 0.5; // Adjust this threshold as needed (0.5 is a common default)
            
            if ($score < $threshold) {
                $fail('The reCAPTCHA verification indicates suspicious activity. Please try again.');
                return;
            }
        }

        // Optional: Check the action if you want to verify specific form actions
        if (isset($result['action'])) {
            $expectedActions = ['free_registration', 'paid_registration'];
            if (!in_array($result['action'], $expectedActions)) {
                $fail('The reCAPTCHA verification failed. Invalid action.');
                return;
            }
        }
    }
}

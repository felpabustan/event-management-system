<?php

namespace App\Console\Commands;

use App\Models\Registration;
use Illuminate\Console\Command;

class GenerateQrTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-qr-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate QR tokens for registrations that don\'t have them';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $registrations = Registration::whereNull('qr_code_token')->get();
        
        if ($registrations->isEmpty()) {
            $this->info('All registrations already have QR tokens.');
            return;
        }
        
        $this->info("Generating QR tokens for {$registrations->count()} registrations...");
        
        foreach ($registrations as $registration) {
            $registration->generateQrCodeToken();
            $this->info("Generated token for registration ID: {$registration->id}");
        }
        
        $this->info('QR token generation completed!');
    }
}

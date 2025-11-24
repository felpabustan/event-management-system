<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class FormSubmission extends Model
{
    protected $fillable = [
        'content_block_id',
        'form_title',
        'csv_filename',
        'submission_count',
    ];

    protected $casts = [
        'submission_count' => 'integer',
    ];

    /**
     * Get the content block that owns this form submission
     */
    public function contentBlock(): BelongsTo
    {
        return $this->belongsTo(HomepageContentBlock::class, 'content_block_id');
    }

    /**
     * Get the full path to the CSV file
     */
    public function getCsvPath(): string
    {
        return storage_path('app/form-submissions/' . $this->csv_filename);
    }

    /**
     * Check if CSV file exists
     */
    public function hasCsvFile(): bool
    {
        return $this->csv_filename && file_exists($this->getCsvPath());
    }

    /**
     * Add a new submission to the CSV file
     */
    public function addSubmission(array $data): void
    {
        $directory = storage_path('app/form-submissions');
        
        // Create directory if it doesn't exist
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        // Generate filename if not set
        if (!$this->csv_filename) {
            $this->csv_filename = 'form_' . $this->id . '_' . time() . '.csv';
            $this->save();
        }

        $filePath = $this->getCsvPath();
        $isNewFile = !file_exists($filePath);

        // Open file for appending
        $file = fopen($filePath, 'a');
        
        // Add headers if new file
        if ($isNewFile) {
            $headers = array_merge(['Submitted At'], array_keys($data));
            fputcsv($file, $headers);
        }

        // Add timestamp to data
        $row = array_merge([now()->toDateTimeString()], array_values($data));
        fputcsv($file, $row);
        
        fclose($file);

        // Increment submission count
        $this->increment('submission_count');
    }

    /**
     * Get all submissions from CSV
     */
    public function getSubmissions(): array
    {
        if (!$this->hasCsvFile()) {
            return [];
        }

        $submissions = [];
        $file = fopen($this->getCsvPath(), 'r');
        
        // Get headers
        $headers = fgetcsv($file);
        
        // Read all rows
        while (($row = fgetcsv($file)) !== false) {
            $submissions[] = array_combine($headers, $row);
        }
        
        fclose($file);

        return $submissions;
    }

    /**
     * Download the CSV file
     */
    public function downloadCsv()
    {
        if (!$this->hasCsvFile()) {
            abort(404, 'CSV file not found');
        }

        return response()->download(
            $this->getCsvPath(),
            $this->form_title . '_submissions.csv',
            ['Content-Type' => 'text/csv']
        );
    }

    /**
     * Delete CSV file when model is deleted
     */
    protected static function booted(): void
    {
        static::deleting(function (FormSubmission $formSubmission) {
            if ($formSubmission->hasCsvFile()) {
                unlink($formSubmission->getCsvPath());
            }
        });
    }
}

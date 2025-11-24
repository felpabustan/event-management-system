<?php

namespace App\Http\Controllers;

use App\Models\HomepageContentBlock;
use App\Models\FormSubmission;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class FormSubmissionController extends Controller
{
    /**
     * Submit a form from the public site
     */
    public function submit(Request $request, HomepageContentBlock $contentBlock): RedirectResponse|JsonResponse
    {
        // Verify this is a form block
        if ($contentBlock->type !== 'form') {
            abort(404, 'Invalid form block');
        }

        // Get form configuration
        $formConfig = $contentBlock->getFormConfig();
        if (!$formConfig || !isset($formConfig['fields'])) {
            abort(500, 'Form configuration not found');
        }

        // Build validation rules dynamically
        $rules = [];
        $fieldLabels = [];
        
        foreach ($formConfig['fields'] as $field) {
            $fieldRules = [];
            
            if ($field['required'] ?? false) {
                $fieldRules[] = 'required';
            }
            
            switch ($field['type']) {
                case 'email':
                    $fieldRules[] = 'email';
                    break;
                case 'number':
                    $fieldRules[] = 'numeric';
                    break;
                case 'tel':
                    $fieldRules[] = 'string';
                    break;
                default:
                    $fieldRules[] = 'string';
            }
            
            if (!empty($fieldRules)) {
                $rules['field_' . $field['id']] = $fieldRules;
                $fieldLabels[$field['id']] = $field['label'];
            }
        }

        // Validate the submission
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Get or create FormSubmission record
            $formSubmission = FormSubmission::firstOrCreate(
                ['content_block_id' => $contentBlock->id],
                [
                    'form_title' => $contentBlock->title ?? 'Form ' . $contentBlock->id,
                    'submission_count' => 0
                ]
            );

            // Prepare data for CSV
            $submissionData = [];
            foreach ($formConfig['fields'] as $field) {
                $fieldKey = 'field_' . $field['id'];
                $submissionData[$field['label']] = $request->input($fieldKey, '');
            }

            // Add IP address if configured
            if ($formConfig['collect_ip'] ?? false) {
                $submissionData['IP Address'] = $request->ip();
            }

            // Save to CSV
            $formSubmission->addSubmission($submissionData);

            // Return success response
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $formConfig['success_message'] ?? 'Thank you for your submission!'
                ]);
            }

            return redirect()->back()->with('success', $formConfig['success_message'] ?? 'Thank you for your submission!');

        } catch (\Exception $e) {
            Log::error('Form submission failed:', [
                'error' => $e->getMessage(),
                'block_id' => $contentBlock->id
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Submission failed. Please try again.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Submission failed. Please try again.')
                ->withInput();
        }
    }

    /**
     * View submissions for a form block (Admin only)
     */
    public function index(HomepageContentBlock $contentBlock): View
    {
        // Verify this is a form block
        if ($contentBlock->type !== 'form') {
            abort(404, 'Invalid form block');
        }

        $formSubmission = FormSubmission::where('content_block_id', $contentBlock->id)->first();
        $submissions = [];

        if ($formSubmission && $formSubmission->hasCsvFile()) {
            $submissions = $formSubmission->getSubmissions();
        }

        return view('admin.form-submissions.index', compact('contentBlock', 'formSubmission', 'submissions'));
    }

    /**
     * Download CSV file (Admin only)
     */
    public function downloadCsv(HomepageContentBlock $contentBlock)
    {
        // Verify this is a form block
        if ($contentBlock->type !== 'form') {
            abort(404, 'Invalid form block');
        }

        $formSubmission = FormSubmission::where('content_block_id', $contentBlock->id)->first();

        if (!$formSubmission || !$formSubmission->hasCsvFile()) {
            return redirect()->back()->with('error', 'No submissions found for this form.');
        }

        return $formSubmission->downloadCsv();
    }

    /**
     * Clear all submissions for a form (Admin only)
     */
    public function clear(Request $request, HomepageContentBlock $contentBlock): RedirectResponse
    {
        // Verify this is a form block
        if ($contentBlock->type !== 'form') {
            abort(404, 'Invalid form block');
        }

        $formSubmission = FormSubmission::where('content_block_id', $contentBlock->id)->first();

        if ($formSubmission) {
            // Delete CSV file
            if ($formSubmission->hasCsvFile()) {
                unlink($formSubmission->getCsvPath());
            }

            // Reset submission record
            $formSubmission->update([
                'csv_filename' => null,
                'submission_count' => 0
            ]);
        }

        return redirect()->back()->with('success', 'All submissions have been cleared.');
    }
}

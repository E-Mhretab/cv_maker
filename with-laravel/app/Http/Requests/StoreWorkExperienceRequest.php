<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkExperienceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow authenticated users to create work experience
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cv_id' => 'required|integer|exists:cv,id',
            'job_title' => 'required|string|max:100',
            'company_name' => 'required|string|max:150',
            'work_start' => 'required|date|before_or_equal:today',
            'work_end' => 'nullable|date|after_or_equal:work_start|before_or_equal:today',
            'description' => 'nullable|string|max:1000',
            'is_current' => 'boolean',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'cv_id.required' => 'CV ID is required.',
            'cv_id.exists' => 'The selected CV does not exist.',
            'job_title.required' => 'Job title is required.',
            'job_title.max' => 'Job title cannot exceed 100 characters.',
            'company_name.required' => 'Company name is required.',
            'company_name.max' => 'Company name cannot exceed 150 characters.',
            'work_start.required' => 'Work start date is required.',
            'work_start.before_or_equal' => 'Work start date cannot be in the future.',
            'work_end.after_or_equal' => 'Work end date must be on or after the start date.',
            'work_end.before_or_equal' => 'Work end date cannot be in the future.',
            'description.max' => 'Description cannot exceed 1000 characters.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // If is_current is true, work_end should be null
            if ($this->is_current && $this->work_end) {
                $validator->errors()->add('work_end', 'End date should be empty for current positions.');
            }
            
            // If is_current is false, work_end should be provided
            if (!$this->is_current && !$this->work_end) {
                $validator->errors()->add('work_end', 'End date is required for past positions.');
            }
        });
    }
}
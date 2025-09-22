<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEducationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow authenticated users to create education records
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
            'degree' => 'required|string|max:150',
            'institution' => 'required|string|max:150',
            'education_start' => 'required|date|before_or_equal:today',
            'education_end' => 'nullable|date|after_or_equal:education_start|before_or_equal:today',
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
            'degree.required' => 'Degree is required.',
            'degree.max' => 'Degree cannot exceed 150 characters.',
            'institution.required' => 'Institution name is required.',
            'institution.max' => 'Institution name cannot exceed 150 characters.',
            'education_start.required' => 'Education start date is required.',
            'education_start.before_or_equal' => 'Education start date cannot be in the future.',
            'education_end.after_or_equal' => 'Education end date must be on or after the start date.',
            'education_end.before_or_equal' => 'Education end date cannot be in the future.',
            'description.max' => 'Description cannot exceed 1000 characters.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // If is_current is true, education_end should be null
            if ($this->is_current && $this->education_end) {
                $validator->errors()->add('education_end', 'End date should be empty for current education.');
            }
            
            // If is_current is false, education_end should be provided
            if (!$this->is_current && !$this->education_end) {
                $validator->errors()->add('education_end', 'End date is required for completed education.');
            }
        });
    }
}
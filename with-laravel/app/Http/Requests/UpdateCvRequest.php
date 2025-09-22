<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCvRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow authenticated users to update their CVs
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Personal Information
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'phone_number' => 'nullable|string|max:20|regex:/^\+?[0-9]{7,15}$/',
            'date_of_birth' => 'nullable|date|before:today',
            'address' => 'nullable|string|max:255',
            'linkedin_profile' => 'nullable|url|max:255',
            'portfolio' => 'nullable|url|max:255',
            'profile_summary' => 'required|string|max:1000',
            
            // Template and Publication
            'template_type' => 'required|integer|in:1,2,3',
            'is_public' => 'boolean',
            
            // Work Experience (arrays)
            'work_title' => 'array',
            'work_title.*' => 'nullable|string|max:100',
            'work_company' => 'array',
            'work_company.*' => 'nullable|string|max:150',
            'work_start' => 'array',
            'work_start.*' => 'nullable|date',
            'work_end' => 'array',
            'work_end.*' => 'nullable|date|after_or_equal:work_start.*',
            'work_current' => 'array',
            'work_current.*' => 'boolean',
            'work_description' => 'array',
            'work_description.*' => 'nullable|string|max:1000',
            
            // Education (arrays)
            'education_degree' => 'array',
            'education_degree.*' => 'nullable|string|max:150',
            'education_institution' => 'array',
            'education_institution.*' => 'nullable|string|max:150',
            'education_start' => 'array',
            'education_start.*' => 'nullable|date',
            'education_end' => 'array',
            'education_end.*' => 'nullable|date|after_or_equal:education_start.*',
            'education_current' => 'array',
            'education_current.*' => 'boolean',
            'education_description' => 'array',
            'education_description.*' => 'nullable|string|max:1000',
            
            // Skills (arrays)
            'skill_name' => 'array',
            'skill_name.*' => 'nullable|string|max:100',
            'skill_description' => 'array',
            'skill_description.*' => 'nullable|string|max:1000',
            
            // Languages (arrays)
            'language_name' => 'array',
            'language_name.*' => 'nullable|string|max:100',
            'language_proficiency' => 'array',
            'language_proficiency.*' => 'nullable|in:basic,conversational,fluent,native',
            
            // Hobbies (arrays)
            'hobby_name' => 'array',
            'hobby_name.*' => 'nullable|string|max:100',
            'hobby_description' => 'array',
            'hobby_description.*' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Full name is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'phone_number.regex' => 'Please enter a valid phone number (7-15 digits, optional + at start).',
            'date_of_birth.before' => 'Date of birth must be in the past.',
            'linkedin_profile.url' => 'Please enter a valid LinkedIn profile URL.',
            'portfolio.url' => 'Please enter a valid portfolio URL.',
            'profile_summary.required' => 'Profile summary is required.',
            'template_type.required' => 'Template type is required.',
            'template_type.in' => 'Template type must be 1, 2, or 3.',
            'work_end.*.after_or_equal' => 'Work end date must be on or after the start date.',
            'education_end.*.after_or_equal' => 'Education end date must be on or after the start date.',
            'language_proficiency.*.in' => 'Invalid language proficiency level selected.',
        ];
    }
}

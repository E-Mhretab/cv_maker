<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLanguageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow authenticated users to create language records
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
            'language_name' => 'required|string|max:100|regex:/^[a-zA-Z\s\-]+$/',
            'proficiency' => 'required|in:basic,conversational,fluent,native',
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
            'language_name.required' => 'Language name is required.',
            'language_name.max' => 'Language name cannot exceed 100 characters.',
            'language_name.regex' => 'Language name can only contain letters, spaces, and hyphens.',
            'proficiency.required' => 'Proficiency level is required.',
            'proficiency.in' => 'Proficiency must be one of: basic, conversational, fluent, native.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Check for duplicate languages for the same CV
            if ($this->cv_id && $this->language_name) {
                $existingLanguage = \App\Models\Language::where('cv_id', $this->cv_id)
                    ->where('language_name', $this->language_name)
                    ->exists();
                
                if ($existingLanguage) {
                    $validator->errors()->add('language_name', 'This language already exists for this CV.');
                }
            }
        });
    }
}
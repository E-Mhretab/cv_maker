<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSkillRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow authenticated users to create skills
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
            'skill_name' => 'required|string|max:100|regex:/^[a-zA-Z0-9\s\-\+\.\/\(\)]+$/',
            'description' => 'nullable|string|max:1000',
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
            'skill_name.required' => 'Skill name is required.',
            'skill_name.max' => 'Skill name cannot exceed 100 characters.',
            'skill_name.regex' => 'Skill name can only contain letters, numbers, spaces, hyphens, plus signs, dots, slashes, and parentheses.',
            'description.max' => 'Description cannot exceed 1000 characters.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Check for duplicate skills for the same CV
            if ($this->cv_id && $this->skill_name) {
                $existingSkill = \App\Models\Skill::where('cv_id', $this->cv_id)
                    ->where('skill_name', $this->skill_name)
                    ->exists();
                
                if ($existingSkill) {
                    $validator->errors()->add('skill_name', 'This skill already exists for this CV.');
                }
            }
        });
    }
}
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHobbyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow authenticated users to create hobbies
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
            'hobby_name' => 'required|string|max:100|regex:/^[a-zA-Z0-9\s\-\.]+$/',
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
            'hobby_name.required' => 'Hobby name is required.',
            'hobby_name.max' => 'Hobby name cannot exceed 100 characters.',
            'hobby_name.regex' => 'Hobby name can only contain letters, numbers, spaces, hyphens, and dots.',
            'description.max' => 'Description cannot exceed 1000 characters.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Check for duplicate hobbies for the same CV
            if ($this->cv_id && $this->hobby_name) {
                $existingHobby = \App\Models\Hobby::where('cv_id', $this->cv_id)
                    ->where('hobby_name', $this->hobby_name)
                    ->exists();
                
                if ($existingHobby) {
                    $validator->errors()->add('hobby_name', 'This hobby already exists for this CV.');
                }
            }
        });
    }
}
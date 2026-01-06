<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreCandidateApplicationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'phone' => ['nullable', 'string', 'max:50'],

            'linkedin_url' => ['nullable', 'url'],
            'github_url' => ['nullable', 'url'],
            'portfolio_url' => ['nullable', 'url'],

            'cv' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
            'cover_letter' => ['nullable', 'string', 'max:2000'],
        ];
    }
}

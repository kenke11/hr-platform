<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVacancyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()
            && $this->user()->hasRoleInCompany('hr');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'employment_type' => ['required', 'in:full_time,part_time,contract'],
            'expiration_date' => [
                'nullable',
                'date',
                Rule::requiredIf(fn () => $this->action === 'published'),
            ],
            'action' => [
                'required',
                Rule::in(['draft', 'published']),
            ],
        ];
    }
}

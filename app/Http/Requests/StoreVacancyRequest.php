<?php

namespace App\Http\Requests;

use App\Enums\EmploymentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVacancyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check()
            && auth()->user()->hasRoleInCompany('hr');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'company_id' => ['required', 'integer', 'exists:companies,id'],

            'title' => ['required', 'string', 'max:255'],

            'description' => ['required', 'string'],

            'location' => ['nullable', 'string', 'max:255'],

            'employment_type' => [
                'required',
                Rule::in(EmploymentType::values()),
            ],

            'action' => [
                'required',
                Rule::in(['draft', 'published']),
            ],
        ];
    }
}

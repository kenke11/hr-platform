<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateVacancyRequest extends FormRequest
{
    /**
     * Only HR can open create vacancy page
     */
    public function authorize(): bool
    {
        return $this->user()
            && $this->user()->hasRoleInCompany('hr');
    }

    /**
     * Validate query params
     */
    public function rules(): array
    {
        return [
            'company' => ['nullable', 'string', 'exists:companies,slug'],
        ];
    }
}

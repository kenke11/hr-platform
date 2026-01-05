<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexVacancyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user
            && (
                $user->canAccessAllCompanies()
                || $user->hasRoleInCompany('admin')
            );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => ['nullable', 'in:draft,published'],
            'company_id' => ['nullable', 'integer', 'exists:companies,id'],
            'expired' => ['nullable', 'boolean'],
        ];
    }
}

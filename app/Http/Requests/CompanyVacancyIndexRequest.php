<?php

namespace App\Http\Requests;

use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;

class CompanyVacancyIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        $company = $this->route('company');

        // HR / Admin → can see any company
        if ($user->canAccessAllCompanies() || $this->user()->hasRoleInCompany('hr')) {
            return true;
        }

        // Company Admin → only own company
        if ($user->hasRoleInCompany('company-admin')) {
            return $user->company_id === $company->id;
        }

        return false;
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
            'expired' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Normalize boolean values
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('expired')) {
            $this->merge([
                'expired' => filter_var($this->expired, FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }
}

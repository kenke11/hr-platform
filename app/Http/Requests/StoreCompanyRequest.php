<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()
            && (
                $this->user()->hasRoleInCompany('admin')
                || $this->user()->hasRoleInCompany('hr')
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
            'name' => ['required', 'string', 'max:255'],

            'domain' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9.-]+\.[a-z]{2,}$/i',
                'unique:companies,domain',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'domain.regex' => 'Domain must be like example.com (without http or slashes)',
        ];
    }
}

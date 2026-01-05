<?php

namespace App\Http\Requests;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(Company $company): bool
    {
        /** @var Company|null $company */
        $company = $this->route('company');

        return $this->user()?->canCrudEmployee($company) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'company_id' => ['required', 'exists:companies,id'],

            'name' => ['required', 'string', 'max:255'],

            'email' => ['required', 'email', 'unique:users,email'],

            'position_id' => [
                'required',
                Rule::exists('positions', 'id')
                    ->where('company_id', $this->company_id),
            ],

            'manager_id' => [
                'nullable',
                Rule::exists('users', 'id')
                    ->where('company_id', $this->company_id),

                function ($attr, $value, $fail) {
                    $manager = User::find($value);

                    if (! $manager) {
                        return;
                    }

                    // extra safety: manager must have position
                    if (! $manager->position_id) {
                        $fail('Selected manager has no position.');
                    }
                },
            ],
        ];
    }
}

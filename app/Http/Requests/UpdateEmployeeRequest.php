<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    /**
     * Authorization
     */
    public function authorize(): bool
    {
        /** @var Company|null $company */
        $company = $this->route('company');

        /** @var User|null $employee */
        $employee = $this->route('user');

        if (! $company || ! $employee) {
            return false;
        }

        // employee must belong to this company
        if ($employee->company_id !== $company->id) {
            return false;
        }

        return $this->user()?->canCrudEmployee($company) ?? false;
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        /** @var User $employee */
        $employee = $this->route('user');

        return [
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($employee->id),
            ],

            'position_id' => [
                'required',
                Rule::exists('positions', 'id')
                    ->where('company_id', $employee->company_id),
            ],

            'manager_id' => [
                'nullable',
                Rule::exists('users', 'id')
                    ->where('company_id', $employee->company_id),

                function ($attr, $value, $fail) use ($employee) {
                    if (! $value) {
                        return;
                    }

                    if ($value == $employee->id) {
                        $fail('Employee cannot be their own manager.');
                    }

                    $manager = User::find($value);

                    if (! $manager?->position_id) {
                        $fail('Selected manager has no position.');
                    }
                },
            ],
        ];
    }
}

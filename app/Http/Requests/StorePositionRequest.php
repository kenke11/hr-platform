<?php

namespace App\Http\Requests;

use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;

class StorePositionRequest extends FormRequest
{
    /**
     * Authorization
     */
    public function authorize(): bool
    {
        /** @var Company|null $company */
        $company = $this->route('company');

        return $company
            && $this->user()?->canCrudPositions($company);
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],

            'description' => ['nullable', 'string', 'max:2000'],
        ];
    }
}

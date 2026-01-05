<?php

namespace App\Http\Requests;

use App\Models\Company;
use App\Models\Position;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePositionRequest extends FormRequest
{
    /**
     * Authorization
     */
    public function authorize(): bool
    {
        /** @var Company|null $company */
        $company = $this->route('company');

        /** @var Position|null $position */
        $position = $this->route('position');

        if (! $company || ! $position) {
            return false;
        }

        // position must belong to company
        if ($position->company_id !== $company->id) {
            return false;
        }

        return $this->user()?->canCrudPositions($company) ?? false;
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

<?php

namespace App\Http\Requests;

use App\Models\Company;
use Illuminate\Foundation\Http\FormRequest;

class MarkAbsentRequest extends FormRequest
{
    /**
     * Authorization
     */
    public function authorize(): bool
    {
        return $this->user()->hasRoleInCompany('hr');
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            'reason' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }
}

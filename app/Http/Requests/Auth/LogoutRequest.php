<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LogoutRequest extends FormRequest
{
    /**
     * Only authenticated users
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * No validation rules needed
     */
    public function rules(): array
    {
        return [];
    }
}

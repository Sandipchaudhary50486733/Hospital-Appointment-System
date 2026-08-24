<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'fullname'       => ['required', 'string', 'max:150'],
            'email'          => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'date_of_birth'  => ['required', 'date', 'before:today'],
            'gender'         => ['required', 'in:male,female,other'],
            'phone'          => ['required', 'string', 'max:20'],
            'password'       => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ];
    }
}

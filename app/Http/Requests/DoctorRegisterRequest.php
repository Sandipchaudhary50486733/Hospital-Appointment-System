<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class DoctorRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'            => ['required', 'string', 'max:150'],
            'email'           => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone'           => ['required', 'string', 'max:20'],
            'specialization'  => ['required', 'string', 'max:100'],
            'license_number'  => ['required', 'string', 'max:50', 'unique:doctors,license_number'],
            'password'        => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'terms'           => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'terms.accepted' => 'You must confirm your credentials and agree to the provider terms.',
        ];
    }
}

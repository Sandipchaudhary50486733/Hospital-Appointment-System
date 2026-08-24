<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DoctorManageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $doctorId = $this->route('doctor')?->id;

        return [
            'name'            => ['required', 'string', 'max:150'],
            'specialization'  => ['required', 'string', 'max:100'],
            'license_number'  => ['required', 'string', 'max:50', Rule::unique('doctors', 'license_number')->ignore($doctorId)],
            'email'           => ['nullable', 'email', 'max:255'],
            'bio'             => ['nullable', 'string', 'max:1000'],
            'image'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active'       => ['sometimes', 'boolean'],
        ];
    }
}

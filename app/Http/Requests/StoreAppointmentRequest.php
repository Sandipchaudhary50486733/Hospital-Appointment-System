<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'doctor_id'         => ['required', 'exists:doctors,id'],
            'appointment_date'  => ['required', 'date', 'after_or_equal:today'],
            'appointment_time'  => ['required', 'date_format:H:i'],
            'contact_number'    => ['required', 'string', 'max:20'],
            'type'              => ['required', 'in:new_patient,follow_up'],
            'reason'            => ['required', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'appointment_date.after_or_equal' => 'Please choose today or a future date.',
            'reason.required' => 'Let the doctor know why you\'re booking this visit.',
        ];
    }
}

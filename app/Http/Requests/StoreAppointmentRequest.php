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

            /*
            |--------------------------------------------------------------------------
            | Hospital
            |--------------------------------------------------------------------------
            */

            'hospital_id' => [
                'nullable',
                'exists:hospitals,id',
            ],


            /*
            |--------------------------------------------------------------------------
            | Doctor
            |--------------------------------------------------------------------------
            */

            'doctor_id' => [
                'required',
                'exists:doctors,id',
            ],


            /*
            |--------------------------------------------------------------------------
            | Appointment date
            |--------------------------------------------------------------------------
            */

            'appointment_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],


            /*
            |--------------------------------------------------------------------------
            | Appointment time
            |--------------------------------------------------------------------------
            */

            'appointment_time' => [
                'required',
                'date_format:H:i',
            ],


            /*
            |--------------------------------------------------------------------------
            | Contact number
            |--------------------------------------------------------------------------
            */

            'contact_number' => [
                'required',
                'string',
                'max:20',
            ],


            /*
            |--------------------------------------------------------------------------
            | Appointment type
            |--------------------------------------------------------------------------
            */

            'type' => [
                'required',
                'in:new_patient,follow_up',
            ],


            /*
            |--------------------------------------------------------------------------
            | Reason
            |--------------------------------------------------------------------------
            */

            'reason' => [
                'required',
                'string',
                'max:500',
            ],

        ];
    }


    public function messages(): array
    {
        return [

            'appointment_date.required' =>
                'Please choose an appointment date.',

            'appointment_date.after_or_equal' =>
                'Please choose today or a future date.',

            'appointment_time.required' =>
                'Please select an available appointment time.',

            'appointment_time.date_format' =>
                'Please select a valid appointment time.',

            'doctor_id.required' =>
                'Please select a doctor.',

            'doctor_id.exists' =>
                'The selected doctor does not exist.',

            'contact_number.required' =>
                'Please enter your contact number.',

            'type.required' =>
                'Please select the appointment type.',

            'reason.required' =>
                'Let the doctor know why you are booking this visit.',

        ];
    }
}

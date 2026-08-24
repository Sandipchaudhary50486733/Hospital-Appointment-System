<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RatingRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // Must be a whole number from 1 to 5 (star rating).
            'rating'  => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:500',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'space_id' => 'required|exists:spaces,id',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'description' => 'required|string',
        ];
    }
}
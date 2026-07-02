<?php

namespace App\Http\Requests\RFID;

use Illuminate\Foundation\Http\FormRequest;

class SchoolRfidRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'uid' => [

                'required',

                'string',

                'max:100',

            ],

        ];
    }
}

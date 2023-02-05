<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'phone' => ['required', 'string', 'digits:12'],
            'email' => ['required', 'string', 'email:rfc,dns', 'unique:users,email'],
            'name' => ['required', 'string'],
            'password' => ['required', 'string', 'digits:8', 'confirmed'],
        ];
    }
}

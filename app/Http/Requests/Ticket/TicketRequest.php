<?php

namespace App\Http\Requests\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class TicketRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'description' => ['required', 'string', 'max:100'],
            'services' => ['required', 'array'],
            'services.*' => ['required', 'exists:services,id'],
            'departments' => ['required', 'array'],
            'departments.*' => ['required', 'exists:departments,id'],
        ];
    }
}

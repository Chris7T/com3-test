<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class LoginResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'token' => Arr::get($this, 'token'),
        ];
    }
}

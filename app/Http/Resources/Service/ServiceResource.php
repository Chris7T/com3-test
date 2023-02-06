<?php

namespace App\Http\Resources\Service;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class ServiceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'description' => Arr::get($this, 'description'),
        ];
    }
}

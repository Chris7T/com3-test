<?php

namespace App\Http\Resources\Department;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class DepartmentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'description' => Arr::get($this, 'description'),
        ];
    }
}

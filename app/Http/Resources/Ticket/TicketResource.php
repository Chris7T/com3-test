<?php

namespace App\Http\Resources\Ticket;

use App\Http\Resources\Department\DepartmentResource;
use App\Http\Resources\Service\ServiceResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class TicketResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'description' => Arr::get($this, 'description'),
            'services' => ServiceResource::collection(Arr::get($this, 'services')),
            'departments' => DepartmentResource::collection(Arr::get($this, 'departments')),
        ];
    }
}

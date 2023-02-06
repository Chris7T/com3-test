<?php

namespace App\Http\Resources\Ticket;

use App\Enum\Ticket\TicketStatusEnum;
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
            'ticket_status' => TicketStatusEnum::tryFrom(Arr::get($this, 'ticket_status'))?->name(),
            'services' => ServiceResource::collection(Arr::get($this, 'services')),
            'departments' => DepartmentResource::collection(Arr::get($this, 'departments')),
        ];
    }
}

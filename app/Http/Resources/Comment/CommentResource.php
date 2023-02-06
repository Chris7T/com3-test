<?php

namespace App\Http\Resources\Comment;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class CommentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'description' => Arr::get($this, 'description'),
            'ticket_id' => Arr::get($this, 'ticket_id'),
        ];
    }
}

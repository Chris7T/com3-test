<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';

    protected $primaryKey = 'id';

    protected $fillable = [
        'description',
        'ticket_id',
    ];

    public function ticketService(): HasOne
    {
        return $this->hasOne(TicketService::class, 'ticket_id', 'id');
    }
}

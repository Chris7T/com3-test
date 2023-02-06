<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketService extends Model
{
    use HasFactory;

    protected $table = 'ticket_service';

    protected $primaryKey = 'id';

    protected $fillable = [
        'ticket_id',
        'service_id'
    ];
}

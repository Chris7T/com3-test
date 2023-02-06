<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketDepartment extends Model
{
    use HasFactory;

    protected $table = 'ticket_department';

    protected $primaryKey = 'id';

    protected $fillable = [
        'ticket_id',
        'department_id'
    ];
}

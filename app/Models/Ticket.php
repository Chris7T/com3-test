<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'tickets';

    protected $primaryKey = 'id';

    protected $fillable = [
        'description',
    ];

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'ticket_service', 'ticket_id', 'service_id');
    }

    public function ticketService(): HasMany
    {
        return $this->hasMany(TicketService::class, 'ticket_id', 'id');
    }

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'ticket_department', 'ticket_id', 'department_id');
    }

    public function ticketDepartment(): HasMany
    {
        return $this->hasMany(TicketDepartment::class, 'ticket_id', 'id');
    }
}

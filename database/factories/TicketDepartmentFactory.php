<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TicketDepartment>
 */
class TicketDepartmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'ticket_id' => Ticket::factory(),
            'department_id' => Department::factory()
        ];
    }
}

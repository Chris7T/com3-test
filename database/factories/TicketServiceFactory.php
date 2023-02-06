<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TicketService>
 */
class TicketServiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'ticket_id' => Ticket::factory(),
            'service_id' => Service::factory()
        ];
    }
}

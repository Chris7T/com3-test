<?php

namespace App\Enum\Ticket;

enum TicketStatusEnum: int
{
    case PENDING = 1;
    case PROGRESS = 2;
    case CONCLUDED = 3;

    public function name(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::PROGRESS => 'Progress',
            self::CONCLUDED => 'Concluded',
        };
    }
}

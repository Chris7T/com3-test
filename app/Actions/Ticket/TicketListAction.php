<?php

namespace App\Actions\Ticket;

use App\Repositories\Ticket\TicketRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class TicketListAction
{
    public function __construct(
        private readonly TicketRepositoryInterface $ticketRepository
    ) {
    }

    public function execute(): LengthAwarePaginator
    {
        return Cache::remember(
            'ticket-list',
            config('cache.one_day'),
            fn () => $this->ticketRepository->list()
        );
    }
}

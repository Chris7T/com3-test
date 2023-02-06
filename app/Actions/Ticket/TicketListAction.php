<?php

namespace App\Actions\Ticket;

use App\Repositories\Ticket\TicketRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TicketListAction
{
    public function __construct(
        private readonly TicketRepositoryInterface $ticketRepository
    ) {
    }

    public function execute(): LengthAwarePaginator
    {
        $userId = Auth::id();
        $isUserAdmin = Auth::user()->is_admin;

        if ($isUserAdmin) {
            $userId = null;
        }

        return Cache::remember(
            'ticket-list',
            config('cache.one_day'),
            fn () => $this->ticketRepository->list($userId)
        );
    }
}

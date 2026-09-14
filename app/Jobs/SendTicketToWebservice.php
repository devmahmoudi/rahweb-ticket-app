<?php

namespace App\Jobs;

use App\Models\Ticket;
use App\Notifications\SendTicketToWebserviceJobSucceed;
use App\Repositories\Ticket\WebServiceRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use RuntimeException;

class SendTicketToWebservice implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Ticket $ticket
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(WebServiceRepository $repository): void
    {
        $response = $repository->sendTicket($this->ticket);

        if (! $response->successful()) {
            throw new RuntimeException(sprintf(
                'Webservice request failed for ticket #%d.',
                $this->ticket->id,
            ));
        }

        $this->ticket->owner?->notify(new SendTicketToWebserviceJobSucceed());
    }
}

<?php

namespace App\Jobs;

use App\Models\Ticket;
use App\Notifications\SendTicketToWebserviceJobSucceed;
use App\Repositories\Ticket\WebServiceRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Notification;
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
        $this->queue = 'webservice';
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

        $targets = collect([
            $this->ticket->owner()->first(),
            $this->ticket->recipient()->first(),
        ])->filter()->unique('id')->values();

        foreach ($targets as $target) {
            Notification::send($target, new SendTicketToWebserviceJobSucceed());
        }
    }
}

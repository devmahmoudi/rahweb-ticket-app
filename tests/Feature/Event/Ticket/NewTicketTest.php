<?php

namespace Tests\Feature\Event\Ticket;

use App\Events\NewTicket;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class NewTicketTest extends TestCase
{
    public function test_the_event_dispatches_after_create_new_ticket()
    {
        Event::fake();

        Ticket::factory()->create();

        Event::assertDispatched(NewTicket::class);
    }
}

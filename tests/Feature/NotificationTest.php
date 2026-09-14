<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use App\Notifications\SendTicketToWebserviceJobSucceed;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    public function test_unread_notifications_are_displayed_in_the_notification_bell(): void
    {
        $user = User::factory()->customer()->create();
        $ticket = Ticket::factory()->create();

        $user->notify(new SendTicketToWebserviceJobSucceed($ticket));

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response
            ->assertOk()
            ->assertSee('1')
            ->assertSee('تیکت #' . $ticket->id)
            ->assertSee('تیکت شما با موفقیت به وب سرویس ارسال شد.');
    }

    public function test_a_notification_can_be_marked_as_read_from_the_notification_bell(): void
    {
        $user = User::factory()->customer()->create();
        $ticket = Ticket::factory()->create();

        $user->notify(new SendTicketToWebserviceJobSucceed($ticket));

        $notification = $user->unreadNotifications()->first();

        $response = $this->actingAs($user)->post(route('notifications.read', $notification->id));

        $response->assertRedirect();
        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_notifications_index_lists_only_unread_notifications_when_filtered(): void
    {
        $user = User::factory()->customer()->create();
        $readTicket = Ticket::factory()->create();
        $unreadTicket = Ticket::factory()->create();

        $user->notify(new SendTicketToWebserviceJobSucceed($readTicket));
        $readNotification = $user->notifications()->first();
        $readNotification->markAsRead();

        $user->notify(new SendTicketToWebserviceJobSucceed($unreadTicket));

        $response = $this->actingAs($user)->get(route('notifications.index', ['filter' => 'unread']));

        $response
            ->assertOk()
            ->assertSee('تیکت #' . $unreadTicket->id)
            ->assertDontSee('تیکت #' . $readTicket->id);
    }
}

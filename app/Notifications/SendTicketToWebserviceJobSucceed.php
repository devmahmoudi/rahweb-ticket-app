<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendTicketToWebserviceJobSucceed extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Ticket $ticket)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ["database"];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $chat = $this->ticket->chat;

        return [
            'ticket_id' => $this->ticket->id,
            'title' => 'تیکت #' . $this->ticket->id,
            'message' => 'تیکت شما با موفقیت به وب سرویس ارسال شد.',
            'link' => $chat ? route('chat', $chat) : route('ticket.index'),
            'link_text' => 'مشاهده تیکت',
        ];
    }
}

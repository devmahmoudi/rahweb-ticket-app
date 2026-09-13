## Explanation
Currently, a ticket assignment is static and assignment user can not assign that to others. We want add support ticket assignment with a graceful broadcasting. 

## How to
Add new option button to the tickets table in [TicketsIndex](../resources/views/livewire/pages/ticket/index.blade.php) page. 
After click on the button, a modal opens for let user select new user assignment and approve confirmation. 
Then the ticket.recipient_id changes to the new assignment user id and the [TicketAssignmentChanged](../app/Events/TicketAssigmentChanged.php). For broadcasting the ticket to the target user, it should dispatch the [NewTicket](../app/Events/NewTicket.php) event but only broadcast to the target user. 
It is better to dispatch the events in the [TicketObserver](../app/Observers/TicketObserver.php).

The ticket assignment policy should add in the [TicketPolicy](../app/Policies/TicketPolicy.php) and the assign ticket option button display if the policy @can authorize user.

## Acceptance & Criteria
- Only current assignment user and super admin can assign ticket to others
- Assign button should display only to authorized user in [TicketIndex](../app/Livewire/Ticket/Index.php) and [Cartable](../app/Livewire/Cartable/Tickets.php)
- Target assignment should not equal to current user
- Target assignment user type should equal to "operator" or "admin"
- Ticket should be openb
- [TicketAssignmentChanged](../app/Events/TicketAssigmentChanged.php) should be dispatched by [TicketObserver](../app/Observers/TicketObserver.php) after assignment
- [NewTicket](../app/Events/NewTicket.php) should be dispatched by [TicketObserver](../app/Observers/TicketObserver.php) after assignment
- [NewTicket](../app/Events/NewTicket.php) should broadcast only to target user of assignment
- [Tickets](../app/Livewire/Cartable/Tickets.php) Livewire component should listen to [NewTicket](../app/Events/NewTicket.php) even on the **user.{user_id}** channel and refresh the tickets list on receive the event
- An alert [Message](../app/Models/Message.php) should send in the [Ticket](../app/Models/Ticket.php)'s [Chat](../app/Models/Chat.php) about assignment. The message.user_id should equal to user who assign ticket to target user.
- Ticket assignment alert message should be: $assigner_user_name assigned your ticket to $assigny_user_name

## Notes
Before adding the assignment option, the [NewTicket](../app/Events/NewTicket.php) broadcasts to the workgroup private channel **"workgroup.{workgroup_id}"**.

```php
Broadcast::channel('workgroup.{workgroup_id}', function (User $user, int $workgroup_id){
    return in_array($workgroup_id, $user->workgroups->pluck('id')->toArray());
});
```
Now, for dispatch the event only to the target user of the assignment, we should add new channel with name **user.{user_id}** and add new  condition to the exists private channel **workgroup.{workgroup}** to prevent the event of assignment to broadcast to all of ticket's workgroup users. 

So, the [NewTicket](../app/Events/NewTicket.php) should broadcast to tow channel: conditions should be :
```php
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("workgroup.{$this->ticket->workgroup_id}") // if ticket has not recipient id
            new PrivateChannel("user.{$this->ticket->recipient_id}") // if ticket has recipient id
        ];
    }
```

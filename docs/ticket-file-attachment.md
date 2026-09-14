# Ticket File Attachment
## Explanation
Customer should, can submit ticket with an attachment file. The attachment file is optional.

## How to
Add a new file field to [CreateTicket](../app/Livewire/Ticket/Create.php) form. Store file in Storage and add file link in [Initial Ticket Message](../app/Repositories/Message/HasTicketMessageMethods.php).

## Acceptance & Criteria
- File attachment input should optional in [CreateTicket](../app/Livewire/Ticket/Create.php) form.
- [CreateTicket](../app/Livewire/Ticket/Create.php) should validate mime types of file attachment input. Allowed formats: pdf, image formats
- [CreateTicket](../app/Livewire/Ticket/Create.php) should store file attachment in storage
- [CreateTicket](../app/Livewire/Ticket/Create.php) should pass file attachment to [Initial Ticket Message](../app/Repositories/Message/HasTicketMessageMethods.php)
- Initial ticket [Message](../app/Models/Message.php) should contain attached file link

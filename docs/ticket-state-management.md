# Ticket State Management

## Explanation
For ticket state management, we use the **Template Method design pattern**.

**Context**: [Ticket](../app/Models/Ticket.php)
**Interface**: [TicketStateInterface](../app/TicketStateManagement/TicketStateInterface.php)
**Template Class**: [State](../app/TicketStateManagement/States/State.php)
**Concrete States**: [States](../app/TicketStateManagement/States)

In this design, [State.php](../app/TicketStateManagement/States/State.php) acts as the shared template. It defines the common transition workflow such as `transition()`, `rejectTransition()`, and the default method signatures (`claim()`, `delegateTo()`, `publishToWebService()`, `reject()`).

Each concrete state class — such as [PendingState](../app/TicketStateManagement/States/PendingState.php), [AcceptedState](../app/TicketStateManagement/States/AcceptedState.php), [DelegatedState](../app/TicketStateManagement/States/DelegatedState.php), [WebserviceState](../app/TicketStateManagement/States/WebserviceState.php), and [RejectedState](../app/TicketStateManagement/States/RejectedState.php) — overrides only the transitions it allows, while the rest are handled by the base template through `unsupported()`.

For concurrent claim requests, [PendingState](../app/TicketStateManagement/States/PendingState.php) uses an atomic cache lock around the full claim workflow. This prevents two or more operators from claiming the same ticket at the same time and avoids race conditions such as inconsistent `recipient_id` updates or duplicate chat membership changes.

## All Possible States For A Ticket
- PENDING: When a new ticket submit by user and wait for claim by an operator
- ACCEPTED: When operator accept to handle new ticket
- DELEGATED: When ticket delegate by operator to superadmin
- WEBSERVICE: When ticket send to webservice by superadmin
- REJECTED: When ticket rejects by operator or superadmin

## Acceptance & Criteria
- [PendingState](../app/TicketStateManagement/States/PendingState.php) should only handle claim transition and throw exception for other transitions
- [PendingState](../app/TicketStateManagement/States/PendingState.php) claim operation should be protected by an atomic cache lock so concurrent claim requests are serialized and race conditions are prevented
- [AcceptedState](../app/TicketStateManagement/States/AcceptedState.php) should only handle delegate and reject transitions and throw exception for other transitions
- [DelegatedState](../app/TicketStateManagement/States/DelegatedState.php) should only handle webservice and reject transitions and throw exception for other transitions
- [WebserviceState](../app/TicketStateManagement/States/WebserviceState.php) should handle zero transitions. This state is irreturnable
- [RejectedState](../app/TicketStateManagement/States/RejectedState.php) should handle zero transitions. This state is irreturnable
- When a ticket state transits to the **RejectedState**, send message in Ticket's [Chat](../app/Models/Chat.php) should block for all the chat members
- On each state transition, the [TicketStateChanged](../app/Events/TicketStateChanged.php) event should dispatch
- On each state transition, an alert [Message](../app/Models/Message.php) should send to correspond ticket chat
- Ticket state transition options, should display as button in tickets table - based ticket current [State](../app/TicketStateManagement/TicketState.php) and ask confirmation before submit
- Ticket current [State](../app/TicketStateManagement/TicketStateInterface.php) should automatically load in accessor and should available with straightforward syntax $ticket->stateManagement():[TicketStateInterface](../app/TicketStateManagement/TicketStateInterface.php)
- Operators should, can change status bulk of tickets
- When a ticket state transits from [AcceptedState](../app/TicketStateManagement/States/AcceptedState.php) to [DelegatedState](../app/TicketStateManagement/States/DelegatedState.php), actor user should detach from the ticket chat and target user should attach to the chat

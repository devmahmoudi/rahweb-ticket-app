# Send Ticket To Webservice
## Explanation
In the [Ticket State Management](../app/TicketStateManagement) we have a state [WebserviceState](../app/TicketStateManagement/States/WebserviceState.php).
On transit Ticket state to the **webservice** state, app should send ticket to a webservice endpoint. But the logic should implement with
Asynchronous and retry on failures.

## How to
The [SendTicketToWebserivce](../app/Jobs/SendTicketToWebservice.php) should dispatch in [DelegatedState](../app/TicketStateManagement/States/DelegatedState.php)::publishToWebService transition.
Then, the job dispatchs in **webservice** queue and a queue worker pick it for process. If process is successful, the [SendTicketToWebserviceJobSucceed](../app/Notifications/SendTicketToWebserviceJobSucceed.php) should send. Otherwise, the job should store in the failed jobs table for retry in the future.

For retry the failed jobs, a [Schedule](../routes/console.php) should execute command for retry failed jobs with queue name **webservice** every one hour.

## Acceptance & Criteria
- [DelegatedState](../app/TicketStateManagement/States/DelegatedState.php) should dispatch [SendTicketToWebserivce](../app/Jobs/SendTicketToWebservice.php) with queue name **webservice** in the publishToWebservice transition
- [SendTicketToWebserviceJobSucceed](../app/Notifications/SendTicketToWebserviceJobSucceed.php) should send to ticket owner and recipient if [SendTicketToWebserivce](../app/Jobs/SendTicketToWebservice.php) job succeed
- [SendTicketToWebserivce](../app/Jobs/SendTicketToWebservice.php) job should store in the failed jobs table if failed
- [Schedule](../routes/console.php) should retry for field jobs with queue name **webservice** every one hour
- [SendTicketToWebserivce](../app/Jobs/SendTicketToWebservice.php) should use [WebServiceRepository](../app/Repositories/Ticket/WebServiceRepository.php) instance for send request

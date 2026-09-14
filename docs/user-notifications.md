# User Notifications
## Explanation
User should, can visit its database notifications and check those to read state.

## How to
Unread notifications should display to user in [NotificationBell](../app/View/Components/NotificationBell.php).
It is a dropdown with Bell icon as trigger that displays unread notifications list on open.
When user has at least one unread notification, a collared circle alongside the Bell icon should led user to click for visit new notifications.
User can mark each notification as read.

The [NotificationBell](../app/View/Components/NotificationBell.php) component contains a link for see all notifications.
The link redirects user to [Notification List](../app/Livewire/Notification/Index.php) and this page, user can see all its notifications.

## Acceptance & Criteria
- [NotificationBell](../app/View/Components/NotificationBell.php) should render in [Layout Header](../resources/views/livewire/layout/header.blade.php)
- [NotificationBell](../app/View/Components/NotificationBell.php) should display user unread notifications list
- A collared circle should display alongside the [NotificationBell](../app/View/Components/NotificationBell.php)
- User can mark each notification as read through [NotificationBell](../app/View/Components/NotificationBell.php)
- Notification should mark as read and remove from [NotificationBell](../app/View/Components/NotificationBell.php) after click mark button
- [NotificationBell](../app/View/Components/NotificationBell.php) should contain a link for see all notifications
- After click on see all notifications, user should redirect to [Notification List](../app/Livewire/Notification/Index.php)
- [Notification List](../app/Livewire/Notification/Index.php) should display all user notifications with pagination, filter and mark as read option

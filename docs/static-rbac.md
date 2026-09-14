# Static Role Base Access Control

## Explanation
Permissions and accessibility of each user defines based its static [UserType](../app/Enums/User/UserType.php).
We don't need **dynamic** Role Base Access Control for this tiny project that including only few models.

## Supporting Roles|UserType
- Customer
- Operator
- Superadmin

## Acceptance & Criteria
- All users can send [Message](../app/Models/Message.php) to all [Chat](../app/Models/Chat.php) that are membership
- **Customer** users can send (create) ticket
- **Operator** users can visit information of **Customer** who has assigned ticket to itself
- **Operator** users can ask for close ticket that are assigned to itself
- **Superadmin** users can do everything

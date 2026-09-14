<?php

namespace App\Enums\User;

enum UserType: string
{
    case SUPERADMIN = 'superadmin';

    case OPERATOR = 'operator';

    case CUSTOMER = 'customer';
}

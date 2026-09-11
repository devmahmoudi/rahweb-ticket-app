<?php

namespace App\Enums\User;

enum UserType: string
{
    case ADMIN = 'admin';

    case OPERATOR = 'operator';

    case CUSTOMER = 'customer';
}

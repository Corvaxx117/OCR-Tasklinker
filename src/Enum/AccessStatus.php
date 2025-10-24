<?php

declare(strict_types=1);

namespace App\Enum;

enum AccessStatus: string
{
    case ADMIN = 'admin';
    case USER = 'user';
}

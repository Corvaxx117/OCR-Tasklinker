<?php

declare(strict_types=1);

namespace App\Enum;

enum ContractType: string
{
    case CDD = 'CDD';
    case CDI = 'CDI';
    case INTERN = 'stagiaire';
    case APPRENTICE = 'alternant';
    case TEMPORARY = 'interimaire';
    case FREELANCE = 'freelance';
}

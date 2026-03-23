<?php

namespace App\Enums;

enum ScopeType: string
{
    case User = 'user';
    case Personal = 'personal';
    case Division = 'division';
    case Company = 'company';
}

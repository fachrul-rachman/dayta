<?php

namespace App\Enums;

enum UserRole: string
{
    case Manager = 'manager';
    case Hod = 'hod';
    case Director = 'director';
    case Admin = 'admin';
}


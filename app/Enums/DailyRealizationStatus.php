<?php

namespace App\Enums;

enum DailyRealizationStatus: string
{
    case Locked = 'locked';
    case Open = 'open';
    case Draft = 'draft';
    case Submitted = 'submitted';
    case Closed = 'closed';
}

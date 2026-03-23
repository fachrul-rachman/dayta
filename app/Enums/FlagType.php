<?php

namespace App\Enums;

enum FlagType: string
{
    case MissingSubmission = 'missing_submission';
    case LateSubmission = 'late_submission';
    case OperationalDominance = 'operational_dominance';
    case RepetitiveInput = 'repetitive_input';
}


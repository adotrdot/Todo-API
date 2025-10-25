<?php

namespace App\Enums;

enum TodoPriority : string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';
}

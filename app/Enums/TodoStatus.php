<?php

namespace App\Enums;

enum TodoStatus : string
{
    case PENDING = 'pending';
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
}

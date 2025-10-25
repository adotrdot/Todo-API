<?php

namespace App\Models;

use App\Enums\TodoPriority;
use App\Enums\TodoStatus;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $fillable = [
        'title',
        'assignee',
        'due_date',
        'time_tracked',
        'status',
        'priority',
    ];

    protected $attributes = [
        'time_tracked' => 0,
        'status' => TodoStatus::PENDING,
    ];

    protected $casts = [
        'due_date' => 'date',
        'status' => TodoStatus::class,
        'priority' => TodoPriority::class,
    ];
}

<?php

namespace App\Http\Resources;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Enums\TodoStatus;
use App\Enums\TodoPriority;

class TodoChartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Get type parameter.
        // Valid type : status, priority, assignee
        $type = $request->query('type');

        switch ($type) {
            case 'status':
                return [
                    // Get all status enums
                    // Count masing-masing status
                    // Output dalam array
                    'status_summary' => collect(TodoStatus::cases())
                        ->mapWithKeys(fn($status) => [
                            $status->value => Todo::where('status', $status->value)->count(),
                        ])
                        ->toArray(),
                ];

            case 'priority':
                return [
                    // Get all priority enums
                    // Count masing-masing priority
                    // Output dalam array
                    'priority_summary' => collect(TodoPriority::cases())
                        ->mapWithKeys(fn($priority) => [
                            $priority->value => Todo::where('priority', $priority->value)->count(),
                        ])
                        ->toArray(),
                ];

            case 'assignee':
                // Get semua assignee
                $assignees = Todo::all()->pluck('assignee');

                $summary = [];

                // Count data yang diperlukan untuk msg2 assignee
                foreach ($assignees as $assignee) {
                    $summary[$assignee] = [
                        'total_todos' => Todo::where('assignee', $assignee)->count(),
                        'total_pending_todos' => Todo::where('assignee', $assignee)
                            ->where('status', TodoStatus::PENDING->value)
                            ->count(),
                        'total_timetracked_completed_todos' => (int) Todo::where('assignee', $assignee)
                            ->where('status', TodoStatus::COMPLETED->value)
                            ->sum('time_tracked'),
                    ];
                }

                return [
                    'assignee_summary' => $summary,
                ];

            default:
                return [
                    'error' => 'Chart type invalid. Valid types: status, priority, assignee.',
                ];
        }
    }
}

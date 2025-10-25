<?php

namespace App\Http\Controllers;

use App\Exports\TodoExport;
use App\Http\Requests\StoreTodoRequest;
use App\Http\Resources\TodoChartResource;
use App\Http\Resources\TodoResource;
use App\Models\Todo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TodoController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTodoRequest $request) : JsonResponse
    {
        // Validasi
        $validated = $request->validated();

        // Insert
        $newTodo = Todo::create($validated);

        // Response
        return (new TodoResource($newTodo))
            ->response()
            ->setStatusCode(201);
    }

    public function summary(Request $request) : JsonResponse
    {
        // Validasi
        $type = $request->query('type');
        if (!$type) {
            return response()->json([
                'error' => 'Missing parameters: type',
            ], 400);
        }

        // Response
        // (type handling ada di dalam ToDoChartResource)
        return (new TodoChartResource($request))
            ->response()
            ->setStatusCode(200);
    }

    public function excel(Request $request) : BinaryFileResponse
    {
        // Begin query
        $query = Todo::query();

        // Filter by title (partial match)
        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        // Filter by assignee (multiple values, comma-separated)
        if ($request->filled('assignee')) {
            $assignees = explode(',', $request->assignee);
            $query->whereIn('assignee', $assignees);
        }

        // Filter by due_date range (start & end)
        if ($request->filled('start') && $request->filled('end')) {
            $query->whereBetween('due_date', [$request->start, $request->end]);
        } elseif ($request->filled('start')) {
            $query->where('due_date', '>=', $request->start);
        } elseif ($request->filled('end')) {
            $query->where('due_date', '<=', $request->end);
        }

        // Filter by time_tracked range (min & max)
        if ($request->filled('min') && $request->filled('max')) {
            $query->whereBetween('time_tracked', [$request->min, $request->max]);
        } elseif ($request->filled('min')) {
            $query->where('time_tracked', '>=', $request->min);
        } elseif ($request->filled('max')) {
            $query->where('time_tracked', '<=', $request->max);
        }

        // Filter by status (multiple values)
        if ($request->filled('status')) {
            $statuses = explode(',', $request->status);
            $query->whereIn('status', $statuses);
        }

        // Filter by priority (multiple values)
        if ($request->filled('priority')) {
            $priorities = explode(',', $request->priority);
            $query->whereIn('priority', $priorities);
        }

        // Eksekusi query
        $todos = $query->get();

        // Format hasil
        $resourceCollection = TodoResource::collection($todos);

        // Export excel
        return (new TodoExport($resourceCollection))->download('todos.xlsx');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTodoRequest;
use App\Http\Resources\TodoChartResource;
use App\Http\Resources\TodoResource;
use App\Models\Todo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
}

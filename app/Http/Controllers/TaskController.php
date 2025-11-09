<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TaskController extends Controller
{
    use HttpResponses;

    // Get all tasks for authenticated user
    public function index()
    {
        $tasks = Task::where('user_id', Auth::id())->get();
        return $this->success(TaskResource::collection($tasks), 'Tasks retrieved successfully');
    }

    // Create a new task
    public function store(StoreTaskRequest $request)
    {
        $validated = $request->validated();

        $task = Task::create([
            'user_id'    => Auth::id(),
            'taskname'   => $validated['taskname'],
            'description'=> $validated['description'],
            'priority'   => $validated['priority'],
        ]);

        return $this->success(new TaskResource($task), 'Task created successfully', 201);
    }

    // Show a single task
    public function show(Task $task)
{
    if ($task->user_id !== Auth::id()) {
        return $this->error(null, 'Task not found', 404);
    }

    return $this->success(new TaskResource($task), 'Task retrieved successfully');
}


    // Update a task
    public function update(StoreTaskRequest $request, string $id)
    {
        try {
            $validated = $request->validated();
            $task = Task::where('user_id', Auth::id())->findOrFail($id);
            $task->update($validated);

            return $this->success(new TaskResource($task), 'Task updated successfully');
        } catch (ModelNotFoundException $e) {
            return $this->error(null, 'Task not found', 404);
        }
    }

    // Delete a task
    public function destroy(string $id)
    {
        try {
            $task = Task::where('user_id', Auth::id())->findOrFail($id);
            $task->delete();

            return $this->success(null, 'Task deleted successfully');
        } catch (ModelNotFoundException $e) {
            return $this->error(null, 'Task not found', 404);
        }
    }
    
}

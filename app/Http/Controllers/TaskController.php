<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Http\Requests\IndexTaskRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(IndexTaskRequest $request)
    {
        $user = $request->user();

        $tasksQuery = Task::query()->with('assignedUser', 'dependencies');

        // for users, show only their tasks
        if ($user->isUser()) {
            $tasksQuery->where('assigned_user_id', $user->id);
        }

        // for managers, apply filters & show all tasks
        if ($user->isManager()) {
            $tasksQuery->filterByStatus($request->status)
                ->filterByAssignedUser($request->assigned_user_id)
                ->filterByDateRange($request->from, $request->to);
        }

        $tasks = $tasksQuery->get();

        return $this->apiResponse(TaskResource::collection($tasks));
    }

    public function store(StoreTaskRequest $request)
    {
        DB::beginTransaction();

        $validated = $request->validated();
        $validated['status'] = TaskStatus::PENDING; // set default status

        $task = Task::create($validated);
        $task->load('assignedUser', 'dependencies');

        if ($request->dependencies) {
            $task->dependencies()->attach($request->dependencies);
        }

        DB::commit();

        return $this->apiResponse(new TaskResource($task));
    }

    public function update(UpdateTaskRequest $request, $id)
    {
        DB::beginTransaction();
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        $user = $request->user();

        if ($task->assigned_user_id != $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($user->isUser()) {
            $validated = $request->only(['status']);
        }

        if ($user->isManager()) {
            $validated = $request->validated();
        }

        // Check dependencies if updating status to completed
        if ($this->isCompletingTask($validated, $task)) {
            return response()->json(['error' => 'Cannot complete task with incomplete dependencies'], 422);
        }

        $task->update($validated);

        DB::commit();

        $task->load('assignedUser', 'dependencies');

        return $this->apiResponse(new TaskResource($task));
    }

    // private funciton for checking task status and return boolean 
    private function isCompletingTask(array $validated, Task $task): bool
    {
        return isset($validated['status']) &&
            $validated['status'] == TaskStatus::COMPLETED &&
            $task->dependencies()->where('status', '!=', TaskStatus::COMPLETED)->exists();
    }
}

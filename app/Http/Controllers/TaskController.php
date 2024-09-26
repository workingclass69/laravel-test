<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
       
        $task = $this->taskService->createTask($request->validated());

        return response()->json($task, 201);
    }


    public function update(UpdateTaskRequest $request, int $taskId): JsonResponse
    {
     
        $task = $this->taskService->updateTask($taskId, $request->validated());
        return response()->json($task, 200);
    }

    public function getAllTasks(): JsonResponse
    {
     
        $tasks = $this->taskService->getAllTasks();
        return response()->json($tasks, 200);
    }

    public function getTaskById(int $taskId): JsonResponse
    {
     
        $task = $this->taskService->getTaskById($taskId);
        return response()->json($task, 200);
    }

    public function updateStatus(int $taskId): JsonResponse
    {
     
        $task = $this->taskService->updateStatus($taskId);
        return response()->json($task, 200);
    }


    public function delete(int $taskId): JsonResponse
    {
     
        $task = $this->taskService->deleteTask($taskId);
        return response()->json("Successfully deleted", 200);
    }

    


}

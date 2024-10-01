<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Services\TaskService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function store(StoreTaskRequest $request)
    {
       

        try {
            $task = $this->taskService->createTask($request->validated());
        } catch (Exception $e) {
            return response()->json(["message" => $e->getMessage()], 400);
        }

        if ($request->wantsJson()) {
            return response()->json($task, 201);
        } else {
            return redirect()->route('dashboard')->with('success', 'Task created successfully!');
        }
    }


    public function update(UpdateTaskRequest $request, int $taskId)
    {
     
        try {
            $task = $this->taskService->updateTask($taskId, $request->validated());
        } catch (Exception $e) {
            return response()->json(["message" => $e->getMessage()], 405);
        }

        if ($request->route()->getPrefix() === 'api') {
            return response()->json($task, 200);
        } else {
            return redirect()->route('dashboard')->with('success', 'Task updated successfully!');
        }
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

    // public function updateStatus(int $taskId): JsonResponse
    // {
     
    //     $task = $this->taskService->updateStatus($taskId);
    //     return response()->json($task, 200);
    // }

    public function updateStatus(Request $request, $taskId)
    {

        try {
            $task = $this->taskService->updateStatus($taskId);
        } catch (Exception $e) {
            return response()->json(["message" => $e->getMessage()], 405);
        }
        
        if ($request->route()->getPrefix() === 'api') {
            return response()->json($task, 200);
        } else {
            return redirect()->route('dashboard')->with('success', 'Task status updated successfully!');
        }

       
    }


    public function delete(int $taskId): JsonResponse
    {
     
        $task = $this->taskService->deleteTask($taskId);
        return response()->json("Successfully deleted", 200);
    }

    


}

<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Task;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class TaskService
{

    protected $statusFlow = [
        'New' => 'In Progress',
        'In Progress' => 'Under Review',
        'Under Review' => 'Completed'
    ];

     /**
     * Automatically update the status of a task to the next sequential status.
     *
     * @param int $taskId
     * @throws ModelNotFoundException
     * @throws ValidationException
     * @return Task
     */
    public function updateStatus(int $taskId): Task
    {

        $task = Task::findOrFail($taskId);

        $currentStatus = $task->status;
    
        if (!isset($this->statusFlow[$currentStatus])) {
            throw ValidationException::withMessages([
                'status' => 'The task is already at the final status and cannot be updated further.'
            ]);
        }
    
        $task->status = $this->statusFlow[$currentStatus];
    
        switch ($task->status) {
            case 'In Progress':
                $task->in_progress_at = now(); 
                break;
            case 'Under Review':
                $task->under_review_at = now(); 
                break;
            case 'Completed':
                $task->completed_at = now(); 
                break;
        }
    
    
        $task->save();
    
        return $task;
    }

    
    /**
     * Create a new task.
     *
     * @param array $data
     * @return Task
     */
    public function createTask(array $data): Task
    {
        return Task::create($data);
    }

    /**
     * Get all tasks for a user.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllTasks()
    {
        return Task::with('category')->get();
    }

    /**
     * Get a single task by its ID.
     *
     * @param int $taskId
     * @return Task
     * @throws ModelNotFoundException
     */
    public function getTaskById(int $taskId): Task
    {
        return Task::findOrFail($taskId);
    }

    /**
     * Update a task.
     *
     * @param int $taskId
     * @param array $data
     * @return Task
     * @throws ModelNotFoundException
     */
    public function updateTask(int $taskId, array $data): Task
    {
        $task = $this->getTaskById($taskId);
        $task->update($data);
        return $task;
    }

    /**
     * Delete a task.
     *
     * @param int $taskId
     * @throws ModelNotFoundException
     */
    public function deleteTask(int $taskId)
    {
        $task = $this->getTaskById($taskId);
        $task->delete();
    }

    /**
     * Change the status of a task.
     *
     * @param int $taskId
     * @param string $status
     * @return Task
     * @throws ModelNotFoundException
     */
    public function changeTaskStatus(int $taskId, string $status): Task
    {
        $task = $this->getTaskById($taskId);
        $task->status = $status;
        $task->save();
        return $task;
    }

    /**
     * Get tasks by their status.
     *
     * @param int $userId
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTasksByStatus(int $userId, string $status)
    {
        return Task::where('user_id', $userId)->where('status', $status)->get();
    }

     /**
     * Create a new category.
     *
     * @param array $data
     * @return Category
     */
    public function createCategory(array $data): Category
    {
        return Category::create($data);
    }

    /**
     * Delete a category.
     *
     * @param int $categoryId
     * @throws ModelNotFoundException
     */
    public function deleteCategory(int $categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $category->delete();
    }
}
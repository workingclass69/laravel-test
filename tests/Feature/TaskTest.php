<?php

namespace Tests\Feature;

use App\Http\Controllers\TaskController;
use App\Http\Requests\StoreTaskRequest;
use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Mockery;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_belongs_to_user()
    {
        $task = Task::factory()->create();
        
        $this->assertInstanceOf(User::class, $task->user);
    }


    public function test_fillable_attributes()
    {
        $task = new Task();

        $this->assertEquals(
            ['user_id', 'category_id', 'title', 'description', 'status'],
            $task->getFillable()
        );
    }

    public function test_task_belongs_to_category()
    {
        $task = Task::factory()->create();
        
        $this->assertInstanceOf(Category::class, $task->category);
    }

   

    public function test_create_task()
    {

        $user = \App\Models\User::factory()->create();  
        $category = \App\Models\Category::factory()->create();  
        $taskService = new TaskService();

        $data = [
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'New Task',
            'description' => 'Test description',
            'status' => 'New'
        ];

        $task = $taskService->createTask($data);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertDatabaseHas('tasks', [
            'title' => 'New Task',
            'description' => 'Test description',
            'status' => 'New'
        ]);
    }

    public function test_create_category()
    {
        $taskService = new TaskService();

        $data = [
            'name' => 'Work',
        ];

        $category = $taskService->createCategory($data);

        $this->assertInstanceOf(Category::class, $category);
        $this->assertDatabaseHas('categories', ['name' => 'Work']);
    }



    public function test_update_task_status()
    {
        $taskService = new TaskService();
        $task = Task::factory()->create(['status' => 'New']);

        $updatedTask = $taskService->updateStatus($task->id);

        $this->assertEquals('In Progress', $updatedTask->status);
        $this->assertNotNull($updatedTask->in_progress_at);
    }

    public function test_update_task_status_final_status()
    {
        $taskService = new TaskService();
        $task = Task::factory()->create(['status' => 'Completed']);

        $this->expectException(ValidationException::class);
        $taskService->updateStatus($task->id);
    }


    public function test_update_task()
    {
        $taskService = new TaskService();
        $task = Task::factory()->create([
            'status' => 'New'
        ]);

        $updatedData = [
            'title' => 'Updated Task Title',
            'description' => 'Updated description',
            'status' => 'In Progress'
        ];

        $updatedTask = $taskService->updateTask($task->id, $updatedData);

        $this->assertEquals('Updated Task Title', $updatedTask->title);
        $this->assertEquals('In Progress', $updatedTask->status);
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task Title',
            'status' => 'In Progress'
        ]);
    }

}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = $this->taskService->createCategory($request->validated());
        return response()->json($category, 201);
    }

    public function destroy(int $categoryId): JsonResponse
    {
        $this->taskService->deleteCategory($categoryId);
        return response()->json(null, 204);
    }

    public function getAllCategories(): JsonResponse
    {
        $categories = Category::all();
        return response()->json($categories, 200);
    }
}
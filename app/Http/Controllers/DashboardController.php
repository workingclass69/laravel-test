<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $tasks = Task::with('category')->get();
        $categories = Category::all();
        $users = User::all();
        return view('dashboard', compact('tasks', 'categories', 'users'));
        
    }
}


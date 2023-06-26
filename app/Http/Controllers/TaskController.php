<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        
        return response()->json($tasks);
    }
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'task' => 'required',
            'dueDate' => 'required|date',
        ]);
        
        $task = new Task;
        $task->task = $validatedData['task'];
        $task->due_date = $validatedData['dueDate'];
        $task->save();
        
        return response()->json($task, 201);
    }
}

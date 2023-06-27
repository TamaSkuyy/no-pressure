<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        
        // return response()->json($tasks);
        return redirect()->route('home');
    }
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'newTask' => 'required',
            'dueDate' => 'required|date',
        ]);
        
        $task = new Task;
        $task->task = $validatedData['newTask'];
        $task->due_date = $validatedData['dueDate'];
        $task->save();
        
        return redirect()->route('home');
    }

    public function update_completness($id){
        $task = Task::find($id);
        if($task->completed == '' || $task->completed == '0'){
            $task->completed = 1;
        }else{
            $task->completed = 0;
        }
        $task->save();
        
        return redirect()->route('home');
    }

    public function destroy($id)
    {

        $task = Task::find($id);
        $task->delete();
        
        return redirect()->route('home');
    }
}

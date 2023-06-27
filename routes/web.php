<?php

use App\Http\Controllers\PinController;
use App\Http\Controllers\TaskController;
use App\Models\Pin;
use App\Models\Task;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Todoapp/Dashboard', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'tasks_data' => Task::all()->map( function ($tasks) {
            return [
                'title' => $tasks->title,
                'id' => $tasks->id,
                'task' => $tasks->task,
                'due_date' => $tasks->due_date,
                'completed' => $tasks->completed
            ];
        }),

    ]);
})->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    
    Route::get('/pins', function () {
        return Inertia::render('Pins');
    })->name('pins');

    Route::post('/pins/create', [PinController::class, 'create'])->name('pin.create');
    Route::post('/pins/store', [PinController::class, 'store'])->name('pin.create');
    Route::post('/pins/destroy', [PinController::class, 'destroy'])->name('pin.destroy');
});

//Task
Route::get('/tasks', [TaskController::class, 'index']);
Route::post('/tasks/store', [TaskController::class, 'store']);
Route::put('/tasks-complete/{id}', [TaskController::class, 'update_completness']);
Route::delete('/tasks/destroy/{id}', [TaskController::class, 'destroy']);

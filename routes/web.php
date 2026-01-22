<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskListController;

//route Welcome
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

//route dashbord
    Route::get('/dashboard', function () {
        return redirect()->route('tasks.index');
    })->name('dashboard');

//route profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//route tasks
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/tasks', [TaskController::class, 'index'])
        ->name('tasks.index');

    Route::get('/tasks/create', [TaskController::class, 'create'])
        ->name('tasks.create');

    Route::post('/tasks', [TaskController::class, 'store'])
        ->name('tasks.store');

    Route::put('/tasks/{task}', [TaskController::class, 'update'])
        ->name('tasks.update');

    Route::get('/tasks/{id}/info', [TaskController::class, 'info'])
        ->name('tasks.info');

    //Route::patch('/tasks/{task}/complete', [TaskController::class, 'complete'])
        //->name('tasks.complete');

    Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggleStatus'])
        ->name('tasks.toggle');

    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])
        ->name('tasks.destroy');

    Route::post('/task-lists', [TaskListController::class, 'store'])
        ->name('task-lists.store'); 
        
    Route::put('/task-lists/{taskList}', [TaskListController::class, 'update'])
        ->name('task-lists.update');
    
    Route::delete('/task-lists/{taskList}', [TaskListController::class, 'destroy'])
        ->name('task-lists.destroy');
});


require __DIR__.'/auth.php';

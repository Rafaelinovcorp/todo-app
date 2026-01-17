<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

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

});


require __DIR__.'/auth.php';

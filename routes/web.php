<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProfileController;
use App\Models\Task;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $tasks = collect();
    $totalTasks = 0;
    $completedTasks = 0;
    $pendingTasks = 0;

    if (auth()->check()) {
        $tasks = Task::where('user_id', auth()->id())->latest()->get();
        $totalTasks = $tasks->count();
        $completedTasks = $tasks->where('is_completed', true)->count();
        $pendingTasks = $totalTasks - $completedTasks;
    }

    return view('welcome', compact('tasks', 'totalTasks', 'completedTasks', 'pendingTasks'));
})->name('home');
use App\Http\Controllers\AuthController;

// AUTH ROUTES
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::middleware(['auth'])->group(function () {

    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks');

    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');

    Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggle']);

    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::patch('/tasks/{task}/favorite', [TaskController::class, 'favorite'])->name('tasks.favorite');
    Route::patch('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
});

require __DIR__.'/auth.php';

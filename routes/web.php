<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
Route::post('store', [TaskController::class, 'store'])->name('tasks.store');
Route::get('tasks', [TaskController::class, 'create'])->name('tasks.create');
Route::patch('tasks/{task}/done', [TaskController::class, 'markAsDone'])->name('tasks.markDone');
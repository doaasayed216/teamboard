<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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



Route::middleware(['auth', 'prevent.back.history'])->group(function (){
    Route::get('/', [ProjectController::class, 'index'])->name('dashboard');
    Route::get('/test', function() {
        return "test";
    });
    Route::resource('projects', ProjectController::class);
    Route::post('/projects/{project}/invitations', [MemberController::class, 'store']);
    Route::prefix('/projects/{project}/')->group(function (){
        Route::resource('tasks', TaskController::class);
        Route::post('/tasks/{task}/comments', [CommentController::class, 'store']);
    });
});



require __DIR__.'/auth.php';

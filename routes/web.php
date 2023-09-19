<?php

use App\Http\Controllers\AchievementsController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/users/{user}/achievements', [AchievementsController::class, 'index']);

// Added custom endpoints for development purposes
Route::prefix('/auth')->group(function(){
    Route::put('/register', [UserController::class, 'store']);
    Route::post('/login', [UserController::class, 'login']);
});

Route::middleware(['auth'])->put('/create-comment', [CommentController::class, 'store']);
Route::middleware(['auth'])->put('/watch-lesson/{lesson}', [LessonController::class, 'watch']);

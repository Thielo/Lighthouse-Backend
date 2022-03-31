<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
// use App\Models\Thread;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::group(['middleware' => ['role:admin']], function () {
        Route::apiResource('users', UserController::class);
        Route::delete('/user/{id}/force-delete', [UserController::class, 'forceDelete']);
        Route::put('/user/{id}/restore', [UserController::class, 'restore']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index']);
    Route::get('/profile/{hash}', [ProfileController::class, 'show']);
    Route::delete('/profile/delete', [ProfileController::class, 'delete']);
    Route::put('/profile/edit', [ProfileController::class, 'store']);

    Route::post('/report', [ReportController::class, 'create']);
});

// Threads
Route::get('/threads', [ThreadController::class, 'index']);
Route::get('/threads/{hash}', [ThreadController::class, 'show']);
Route::post('/threads', [ThreadController::class, 'create']);
Route::put('/thread/{hash}', [ThreadController::class, 'create']);

// Posts
Route::get('/post/{hash}', [PostController::class, 'show']);
Route::post('/post', [PostController::class, 'create']);
Route::put('/post/{hash}', [PostController::class, 'store']);
Route::delete('/post/{hash}', [PostController::class, 'destroy']);

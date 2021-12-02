<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\ThreadController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/* Route::middleware('auth:sanctum')->apiResource(
    'threads',
    ThreadController::class
); */

// Route::get('/thread/{hash}', [ThreadController::class, 'getAllEntries']);
// Route::get('/thread/{hash}', [ThreadController::class, 'getThread']);
// Route::get('/post/{hash}', [PostController::class, 'getPost']);

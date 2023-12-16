<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReminderController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/session', [AuthController::class, 'login']);

Route::get('/unauthorized', function (Request $request) {
    return response()->json([
        'ok' => false,
        'err' => 'ERR_INVALID_CRED',
        'msg' => 'invalid credential',
    ], Response::HTTP_UNAUTHORIZED);
})->name('unauthorized');

Route::middleware(['auth:sanctum', 'ability:access-api'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('/reminders')->group(function () {
        Route::controller(ReminderController::class)->group(function () {
            Route::get('/', 'all');
            Route::post('/', 'create');

            Route::middleware(['ensure.user'])->group(function () {
                Route::get('/{id}', 'get');
                Route::put('/{id}', 'update');
                Route::delete('/{id}', 'delete');
            });
        });
    });
});

Route::middleware(['auth:sanctum', 'ability:issue-access-token'])->group(function () {
    Route::put('/session', [AuthController::class, 'refresh']);
});

Route::any('{path}', function () {
    return response()->json(array(
        'ok' => false,
        'err' => 'ERR_INVALID_API',
        'msg' => 'invalid api',
    ), 404);
})->where('path', '.*');

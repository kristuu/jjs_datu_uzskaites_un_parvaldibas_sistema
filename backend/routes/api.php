<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\InstructorController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\ReservationController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/logout', [AuthController::class, 'logout']);
Route::post('/setlocale', function (Request $request) { App::setLocale($request->getLocale()); });

Route::middleware('auth:sanctum')->group(function() {

    Route::get('/get-permissions', function () {
        return auth()->check()?auth()->user()->jsPermissions():0;
    });

    Route::group(['middleware' => ['can:manage users']], function () {
        Route::get('/users/columns', [UserController::class, 'getUsersColumnNames']);
        Route::get('/users', [UserController::class, 'getAllUsers']);
        Route::get('/users/{id}', [UserController::class, 'findUserById']);

        Route::group(['middleware' => ['can:create instances']], function () {
            Route::post('/users', [UserController::class, 'storeUser']);
        });

        Route::group(['middleware' => ['can:edit instances']], function () {
            Route::put('/users/{id}', [UserController::class, 'updateUser']);
        });

        Route::group(['middleware' => ['can:delete instances']], function () {
            Route::delete('/users/{id}', [UserController::class, 'destroyUser']);
        });
    });

    Route::group(['middleware' => ['can:manage permissions']], function () {
       Route::get('/permissions/columns', [PermissionController::class, 'getPermissionsColumnNames']);
       Route::get('/permissions', [PermissionController::class, 'getAllPermissions']);
       Route::get('/permissions/{id}', [PermissionController::class, 'findPermissionById']);

        Route::group(['middleware' => ['can:create instances']], function () {
            Route::post('/permissions', [PermissionController::class, 'storePermission']);
        });

        Route::group(['middleware' => ['can:edit instances']], function () {
            Route::put('/permissions/{id}', [PermissionController::class, 'updatePermission']);
        });

        Route::group(['middleware' => ['can:delete instances']], function () {
            Route::delete('/permissions/{id}', [PermissionController::class, 'destroyPermission']);
        });
    });

    Route::get('/instructors', [InstructorController::class, 'getAllInstructorData']);
    Route::post('/book', [ReservationController::class, 'store']);

    Route::put('/address/{id}', [UserController::class, 'setAddress']);
});

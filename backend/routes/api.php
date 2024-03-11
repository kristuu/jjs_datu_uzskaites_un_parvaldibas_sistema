<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CertificateController;
use App\Http\Controllers\API\CountryController;
use App\Http\Controllers\API\InstructorController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\RegionController;
use App\Http\Controllers\API\RoleController;
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

function registerResourceRoutes($base, $controller, $getAllMethod, $getMethod, $storeMethod, $updateMethod, $destroyMethod) {
    Route::middleware(['auth:sanctum', 'can:manage ' . $base])->group(function () use ($base, $controller, $getAllMethod, $getMethod, $storeMethod, $updateMethod, $destroyMethod) {
        Route::get("/{$base}", [$controller, $getAllMethod]);
        Route::get("/{$base}/{id}", [$controller, $getMethod]);
        Route::middleware('can:create instances')->post("/{$base}", [$controller, $storeMethod]);
        Route::middleware('can:edit instances')->put("/{$base}/{id}", [$controller, $updateMethod]);
        Route::middleware('can:delete instances')->delete("/{$base}/{id}", [$controller, $destroyMethod]);
    });
}

Route::middleware('auth:sanctum')->group(function () {
    registerResourceRoutes('users', UserController::class, 'getAllUsers', 'findUserById', 'storeUser', 'updateUser', 'destroyUser');
    registerResourceRoutes('permissions', PermissionController::class, 'getAllPermissions', 'findPermissionById', 'storePermission', 'updatePermission', 'destroyPermission');
    registerResourceRoutes('roles', RoleController::class, 'getAllRoles', 'findRoleById', 'storeRole', 'updateRole', 'destroyRole');
    registerResourceRoutes('countries', CountryController::class, 'getAllCountries', 'findCountryById', 'storeCountry', 'updateCountry', 'destroyCountry');
    registerResourceRoutes('regions', RegionController::class, 'getAllRegions', 'findRegionById', 'storeRegion', 'updateRegion', 'destroyRegion');
    registerResourceRoutes('instructors', InstructorController::class, 'getAllInstructors', 'findInstructorById', 'storeInstructor', 'updateInstructor', 'destroyInstructor');
    registerResourceRoutes('certificates', CertificateController::class, 'getAllCertificates', 'findCertificateById', 'storeCertificate', 'updateCertificate', 'destroyCertificate');
    registerResourceRoutes('categories', CategoryController::class, 'getAllCategories', 'findCategoryById', 'storeCategory', 'updateCategory', 'destroyCategory');

    Route::middleware(["can:manage certificates|manage instructors"])->group(function() {
        Route::get("/unused_certificates", [CertificateController::class, "getUnusedCertificates"]);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

Route::get('/get-permissions', function () {
    return auth('api')->check() ? auth('api')->user()->jsPermissions() : [];
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/logout', [AuthController::class, 'logout']);
Route::post('/setlocale', function (Request $request) { App::setLocale($request->getLocale()); });

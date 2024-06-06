<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CertificateController;
use App\Http\Controllers\API\CountryController;
use App\Http\Controllers\API\EventCategoryController;
use App\Http\Controllers\API\EventController;
use App\Http\Controllers\API\EventTypeController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\ImageController;
use App\Http\Controllers\API\InstructorAvailabilityController;
use App\Http\Controllers\API\InstructorController;
use App\Http\Controllers\API\LocationController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\RegionController;
use App\Http\Controllers\Api\ReservationController;
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

if (!function_exists('registerResourceRoutes')) {
    function registerResourceRoutes($base, $controller, $getAllMethod, $getMethod, $storeMethod, $updateMethod, $destroyMethod)
    {
        Route::group(['middleware' => ['auth:sanctum', 'can:manage ' . $base]], function () use ($base, $controller, $getAllMethod, $getMethod, $storeMethod, $updateMethod, $destroyMethod) {
            Route::get("/{$base}", [$controller, $getAllMethod]);
            Route::get("/{$base}/{id}", [$controller, $getMethod]);
            Route::middleware('can:create instances')->post("/{$base}", [$controller, $storeMethod]);
            Route::middleware('can:edit instances')->put("/{$base}/{id}", [$controller, $updateMethod]);
            Route::middleware('can:delete instances')->delete("/{$base}/{id}", [$controller, $destroyMethod]);
        });
    }
}

Route::get("/events", [EventController::class, 'getAllEvents']);
Route::get("/upcoming_events", [EventController::class, 'getUpcomingEvents']);
Route::get("/event_count_year", [EventController::class, 'getThisYearsEventCount']);
Route::get("/event_count_by_month", [EventController::class, 'getEventCountByMonth']);
Route::get("/event_percentage", [EventController::class, 'getEventPercentageThisYear']);

Route::middleware('auth:sanctum')->group(function () {
    registerResourceRoutes('users', UserController::class, 'getAllUsers', 'findUserById', 'storeUser', 'updateUser', 'destroyUser');
    registerResourceRoutes('permissions', PermissionController::class, 'getAllPermissions', 'findPermissionById', 'storePermission', 'updatePermission', 'destroyPermission');
    registerResourceRoutes('roles', RoleController::class, 'getAllRoles', 'findRoleById', 'storeRole', 'updateRole', 'destroyRole');
    registerResourceRoutes('countries', CountryController::class, 'getAllCountries', 'findCountryById', 'storeCountry', 'updateCountry', 'destroyCountry');
    registerResourceRoutes('regions', RegionController::class, 'getAllRegions', 'findRegionById', 'storeRegion', 'updateRegion', 'destroyRegion');
    registerResourceRoutes('instructors', InstructorController::class, 'getAllInstructors', 'findInstructorById', 'storeInstructor', 'updateInstructor', 'destroyInstructor');
    registerResourceRoutes('certificates', CertificateController::class, 'getAllCertificates', 'findCertificateById', 'storeCertificate', 'updateCertificate', 'destroyCertificate');
    registerResourceRoutes('categories', CategoryController::class, 'getAllCategories', 'findCategoryById', 'storeCategory', 'updateCategory', 'destroyCategory');
    registerResourceRoutes('locations', LocationController::class, 'getAllLocations', 'findLocationById', 'storeLocation', 'updateLocation', 'destroyLocation');
    registerResourceRoutes('event_types', EventTypeController::class, 'getAllEventTypes', 'findEventTypeById', 'storeEventType', 'updateEventType', 'destroyEventType');
    registerResourceRoutes('event_categories', EventCategoryController::class, 'getAllEventCategories', 'findEventCategoryById', 'storeEventCategory', 'updateEventCategory', 'destroyEventCategory');

    Route::group(['middleware' => ['permission:manage events']], function () {
        Route::post('/events', [EventController::class, 'storeEvent']);
        Route::get('/events/{id}', [EventController::class, 'findEventById']);
        Route::put('/events/{id}', [EventController::class, 'updateEvent']);
        Route::delete('/events/{id}', [EventController::class, 'destroyEvent']);

        Route::get('/event/categories', [EventCategoryController::class, 'getAllEventCategories']);
        Route::get('/event/types', [EventTypeController::class, 'getAllEventTypes']);
        Route::get('/event/locations', [LocationController::class, 'getAllLocations']);

    });

    Route::group(['middleware' => ['permission:manage instructors|manage certificates']], function () {
        Route::get("/unused_certificates/{instructorId}", [CertificateController::class, "getUnusedCertificates"]);
    });

    Route::group(['middleware' => ['permission:manage certificates']], function () {
        Route::get("/certificates_categories", [CategoryController::class, "getAllCategories"]);
    });

    Route::get('/availabilities/{instructor_id}/{date}', [InstructorAvailabilityController::class, 'getAvailabilitiesByDate']);
    Route::get('/availability-counts/{instructor_id}', [InstructorAvailabilityController::class, 'getAvailabilityCounts']);

    Route::get('/instructor/reservations', [InstructorController::class, 'getReservations']);
    Route::post('/instructor/reservation/{id}', [InstructorController::class, 'manageReservation']);

    Route::post('/reservations', [ReservationController::class, 'storeReservation']);

    Route::get('/getAllReservations', [HomeController::class, 'getAllReservations']);
    Route::delete('/personal_reservations/{id}', [ReservationController::class, 'destroyReservation']);
    Route::get('/homeData', [HomeController::class, 'index']);

    Route::post('/upload-image', [ImageController::class, 'uploadToImgur']);

    Route::put('/user/profile', [UserController::class, 'updateProfile']);

    // PDF routes
    Route::get('/reservation/pdf/{reservationId}', [ReservationController::class, 'generatePdf']);

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
Route::post('/setlocale', function (Request $request) {
    App::setLocale($request->getLocale());
});

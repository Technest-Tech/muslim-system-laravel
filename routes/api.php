<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Calendar API routes - using web middleware for session-based auth
// Note: These routes are in api.php for organization but use web middleware
// to ensure sessions are available for authentication
Route::middleware(['web', 'auth', 'admin'])->group(function () {
    Route::get('calendar/events', [\App\Http\Controllers\Admin\Calendar\CalendarController::class,'events'])->name('admin.calendar.events');
    Route::post('calendar/lessons', [\App\Http\Controllers\Admin\Calendar\CalendarController::class,'store'])->name('admin.calendar.store');
    Route::delete('calendar/lessons/delete', [\App\Http\Controllers\Admin\Calendar\CalendarController::class,'destroy'])->name('admin.calendar.destroy');
    Route::put('calendar/lessons/{id}', [\App\Http\Controllers\Admin\Calendar\CalendarController::class,'update'])->where('id', '.*')->name('admin.calendar.update');
    Route::get('calendar/lessons/{id}', [\App\Http\Controllers\Admin\Calendar\CalendarController::class,'show'])->where('id', '.*')->name('admin.calendar.show');
});

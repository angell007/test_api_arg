<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SpaceController;
use App\Http\Controllers\ReservationController;

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


Route::post("login", [AuthController::class, 'login'])->name('login');

Route::middleware(["jwt.auth"])->group(
    function () {
        Route::post("logout", [AuthController::class, "logout"]);
        Route::post("refresh", [AuthController::class, "refresh"]);
        Route::post("me", [AuthController::class, "me"]);
        Route::get("renew", [AuthController::class, "renew"]);
        Route::get("change-password", [
          AuthController::class,
          "changePassword",
        ]);

        Route::apiResource('spaces', SpaceController::class);
        Route::apiResource('reservations', ReservationController::class);
    }
);



Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

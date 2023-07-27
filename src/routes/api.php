<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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

Route::middleware("auth:sanctum")->get("/user", function (Request $request) {
    return $request->user();
});

Route::group(
    [
        "middleware" => ["api"],
        "prefix" => "auth",
    ],
    function ($router) {
        // TODO: remove this route?
        Route::post("/logout", [AuthController::class, "logout"]);

        Route::post("/login", [AuthController::class, "login"]);
        Route::post("/register", [AuthController::class, "register"]);

        Route::post("/confirm/{token}", [AuthController::class, "confirm"]);

        Route::post("/forgot-password", [
            AuthController::class,
            "forgotPassword",
        ]);

        Route::get("/reset-password/{code}", [
            AuthController::class,
            "getEmailByCode",
        ]);
        Route::post("/reset-password", [
            AuthController::class,
            "resetPassword",
        ]);

        // TODO: wip
        Route::post("/refresh", [AuthController::class, "refresh"]);
    }
);

Route::group(
    [
        "middleware" => ["api"],
        "prefix" => "/",
    ],
    function ($router) {
        Route::get("/auth/me", [UserController::class, "userProfile"]);
        Route::get("/users", [UserController::class, "getUsers"]);
        Route::get("/users/{id}", [UserController::class, "getUser"]);
        Route::patch("/users/{id}", [UserController::class, "updateUser"]);

        Route::post("/password/change", [
            UserController::class,
            "changePassword",
        ]);
    }
);

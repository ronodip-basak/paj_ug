<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix("/auth")->group(function(){
    Route::post("register", ["App\Http\Controllers\AuthController", "register"]);
    Route::post("login", ["App\Http\Controllers\AuthController", "login"]);
    Route::post("access_token", ["App\Http\Controllers\AuthController", "getAccessTokenFromRefreshToken"]);
});

Route::middleware("auth:sanctum")->group(function() {
    Route::post("/devices", ["App\Http\Controllers\DeviceController", "store"]);
    Route::get("/devices", ["App\Http\Controllers\DeviceController", "index"]);
    Route::get("/devices/{device}", ["App\Http\Controllers\DeviceController", "view"])->middleware("can:view,device");
});

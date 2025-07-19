<?php

use App\Http\Controllers\Api\ClassroomController;
use App\Http\Controllers\Api\CourController;
use Illuminate\Support\Facades\Route;


Route::apiResource('classes', ClassroomController::class);

Route::get('classes/{classroom}/cours', [ClassroomController::class, 'listCours']);

Route::post('classes/{classroom}/cours/attach', [ClassroomController::class, 'attachCour']);

Route::post('classes/{classroom}/cours/detach', [ClassroomController::class, 'detachCour']);


Route::apiResource('cours', CourController::class);
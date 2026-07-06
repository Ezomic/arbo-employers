<?php

use App\Http\Controllers\Api\CaseApiController;
use App\Http\Controllers\Api\ContactPersonApiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth.api-client', 'throttle:api-internal'])->group(function () {
    Route::put('cases/{id}', [CaseApiController::class, 'sync']);
    Route::put('employers/{employer}/contact-persons', [ContactPersonApiController::class, 'sync']);
});

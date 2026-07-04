<?php

use App\Http\Controllers\Api\CaseApiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth.api-client', 'throttle:60,1'])->group(function () {
    Route::put('cases/{id}', [CaseApiController::class, 'sync']);
});

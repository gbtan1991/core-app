<?php

use App\Http\Controllers\Api\FunnelCaptureController;
use Illuminate\Support\Facades\Route;

Route::post('/funnel/capture', [FunnelCaptureController::class, 'store'])->name('funnel.capture');

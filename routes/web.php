<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HookController;

Route::post('/latency/store', [HookController::class, 'store']);

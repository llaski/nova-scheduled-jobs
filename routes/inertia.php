<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/scheduled-jobs', function (Request $request) {
    return inertia('NovaScheduledJobs');
});
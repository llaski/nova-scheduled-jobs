<?php

use Illuminate\Support\Facades\Route;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;
use Llaski\NovaScheduledJobs\Columns;
use Llaski\NovaScheduledJobs\Tool as NovaScheduledJobsTool;

/*
|--------------------------------------------------------------------------
| Tool Routes
|--------------------------------------------------------------------------
|
| Here is where you may register Inertia routes for your tool. These are
| loaded by the ServiceProvider of the tool. The routes are protected
| by your tool's "Authorize" middleware by default. Now - go build!
|
*/

Route::get('/', function (NovaRequest $request) {
    $tool = collect(Nova::registeredTools())->first(
        fn ($registered) => $registered instanceof NovaScheduledJobsTool
    );

    return inertia('NovaScheduledJobs', [
        'columns' => $tool instanceof NovaScheduledJobsTool
            ? $tool->columns()
            : Columns::toolDefaults(),
    ]);
})->name('nova-scheduled-jobs.index');

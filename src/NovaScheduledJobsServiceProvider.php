<?php

namespace Llaski\NovaScheduledJobs;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Llaski\NovaScheduledJobs\Http\Middleware\Authorize;

class NovaScheduledJobsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'nova-scheduled-jobs');

        $this->app->booted(function () {
            $this->routes();
        });
    }

    /**
     * Register the card's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authorize::class])
            ->prefix('nova-vendor/llaski/nova-scheduled-jobs')
            ->group(__DIR__ . '/../routes/api.php');
    }

}

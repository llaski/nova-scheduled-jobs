<?php

namespace Llaski\NovaScheduledJobs;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Http\Middleware\Authenticate;
use Laravel\Nova\Nova;
use Llaski\NovaScheduledJobs\Http\Middleware\Authorize;

class ServiceProvider extends IlluminateServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/nova-scheduled-jobs.php' => config_path('nova-scheduled-jobs.php'),
            ], 'nova-scheduled-jobs-config');
        }

        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-scheduled-jobs', __DIR__.'/../dist/js/app.js');
            Nova::style('nova-scheduled-jobs', __DIR__.'/../dist/css/app.css');
        });
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        $app = $this->app;
        if ($app instanceof Application && $app->routesAreCached()) {
            return;
        }

        Nova::router(['nova', Authenticate::class, Authorize::class], 'nova-scheduled-jobs')
            ->group(__DIR__.'/../routes/inertia.php');

        Route::middleware(['nova', Authenticate::class, Authorize::class])
            ->prefix('nova-vendor/nova-scheduled-jobs')
            ->group(__DIR__.'/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/nova-scheduled-jobs.php',
            'nova-scheduled-jobs'
        );
    }
}

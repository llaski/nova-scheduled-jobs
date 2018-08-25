<?php

namespace Llaski\NovaScheduledJobs;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use Llaski\NovaScheduledJobs\Console\Commands\ScheduleList;

class NovaScheduledJobsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'nova-scheduled-jobs');

        $this->app->booted(function () {
            $this->routes();
        });

        // Nova::serving(function (ServingNova $event) {
        //     Nova::script('nova-scheduled-jobs', __DIR__ . '/../dist/js/card.js');
        //     Nova::style('nova-scheduled-jobs', __DIR__ . '/../dist/css/card.css');
        // });
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

        Route::middleware(['nova'])
            ->prefix('nova-vendor/llaski/nova-scheduled-jobs')
            ->group(__DIR__ . '/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            ScheduleList::class,
        ]);
    }
}

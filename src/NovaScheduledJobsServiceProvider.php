<?php

namespace Llaski\NovaScheduledJobs;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class NovaScheduledJobsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../resources/lang/' => resource_path('lang/vendor/nova-scheduled-jobs'),
        ]);

        $this->loadTranslations();

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'nova-scheduled-jobs');

        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-scheduled-jobs', __DIR__ . '/../dist/js/app.js');

            $this->bootTranslations();
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

        Route::middleware(['nova'])
            ->prefix('nova-vendor/llaski/nova-scheduled-jobs')
            ->group(__DIR__ . '/../routes/api.php');
    }

    /**
     * Load package translation resources.
     *
     * @return void
     */
    protected function loadTranslations()
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'NovaScheduledJobs');
        $this->loadJSONTranslationsFrom(__DIR__.'/../resources/lang');
        $this->loadJSONTranslationsFrom(resource_path('lang/vendor/nova-scheduled-jobs'));
    }

    /**
     * Bootstraps current application locale translations to Nova.
     *
     * @return void
     */
    protected function bootTranslations()
    {
        $currentLocale = $this->app->getLocale();

        Nova::translations(__DIR__.'/../resources/lang/'.$currentLocale.'.json');
        Nova::translations(resource_path('lang/vendor/nova-scheduled-jobs/'.$currentLocale.'.json'));
    }
}

<?php

namespace Llaski\NovaScheduledJobs\Tests\Fixtures;

use Laravel\Nova\Nova;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Llaski\NovaScheduledJobs\Tool as NovaScheduledJobsTool;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    // /**
    //  * Register the Nova routes.
    //  *
    //  * @return void
    //  */
    // protected function routes()
    // {
    //     Nova::routes()
    //         ->withAuthenticationRoutes()
    //         ->withPasswordResetRoutes()
    //         ->register();
    // }

    // /**
    //  * Register the Nova gate.
    //  *
    //  * This gate determines who can access Nova in non-local environments.
    //  *
    //  * @return void
    //  */
    // protected function gate()
    // {
    //     Gate::define('viewNova', function ($user) {
    //         # Would normally want to check the role of the user here
    //         return in_array($user->email, [
    //             'larry.laski@gmail.com'
    //         ]);
    //     });
    // }

    // /**
    //  * Get the dashboards that should be listed in the Nova sidebar.
    //  *
    //  * @return array
    //  */
    // protected function dashboards()
    // {
    //     return [
    //         new \App\Nova\Dashboards\Main,
    //     ];
    // }

    protected function resources()
    {
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            new NovaScheduledJobsTool
        ];
    }

    // /**
    //  * Register any application services.
    //  *
    //  * @return void
    //  */
    // public function register()
    // {
    //     //
    // }
}

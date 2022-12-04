<?php

namespace Llaski\NovaScheduledJobs\Tests;

use Illuminate\Support\Facades\Route;
use Laravel\Nova\NovaCoreServiceProvider;
use Laravel\Nova\Http\Middleware\Authorize;
use Laravel\Nova\Http\Middleware\BootTools;
use Laravel\Nova\Http\Middleware\ServeNova;
use Laravel\Nova\Http\Requests\NovaRequest;
use Llaski\NovaScheduledJobs\ServiceProvider;
use Laravel\Nova\Http\Middleware\Authenticate;
use Illuminate\Contracts\Http\Kernel as HttpKernel;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Laravel\Nova\Http\Middleware\HandleInertiaRequests;
use Laravel\Nova\Http\Middleware\DispatchServingNovaEvent;
use Llaski\NovaScheduledJobs\Tests\Fixtures\NovaServiceProvider;
use Inertia\ServiceProvider as InertiaServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Route::middlewareGroup('nova', [
            'web',
            HandleInertiaRequests::class,
            DispatchServingNovaEvent::class,
            BootTools::class,
        ]);

        $this->withoutExceptionHandling();
    }

    protected function getPackageProviders($app)
    {
        return [
            NovaServiceProvider::class,
            ServiceProvider::class,
        ];
    }
}

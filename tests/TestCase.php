<?php

namespace Llaski\NovaScheduledJobs\Tests;

use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Laravel\Nova\Http\Middleware\BootTools;
use Llaski\NovaScheduledJobs\ServiceProvider;
use Laravel\Nova\Http\Middleware\HandleInertiaRequests;
use Laravel\Nova\Http\Middleware\DispatchServingNovaEvent;
use Llaski\NovaScheduledJobs\Tests\Fixtures\NovaServiceProvider;

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

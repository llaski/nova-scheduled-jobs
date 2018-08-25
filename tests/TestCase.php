<?php

namespace Llaski\NovaScheduledJobs\Tests;

use Illuminate\Support\Facades\Route;
use Llaski\NovaScheduledJobs\NovaScheduledJobsServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    public function setUp()
    {
        parent::setUp();

        Route::middlewareGroup('nova', []);
    }

    protected function getPackageProviders($app)
    {
        return [
            NovaScheduledJobsServiceProvider::class,
        ];
    }

}

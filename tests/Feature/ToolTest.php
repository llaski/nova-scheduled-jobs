<?php

namespace Llaski\NovaScheduledJobs\Tests;

use Llaski\NovaScheduledJobs\Tests\TestCase;
use Laravel\Nova\Http\Middleware\Authenticate;
use Llaski\NovaScheduledJobs\Http\Middleware\Authorize;

class ToolTest extends TestCase
{
    /** @test */
    public function hasCorrectMiddleware()
    {
        $this->assertRouteUsesMiddleware('nova-scheduled-jobs.index', ['nova', Authenticate::class, Authorize::class], exact: true);
    }
}

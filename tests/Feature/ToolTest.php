<?php

namespace Llaski\NovaScheduledJobs\Tests;

use Laravel\Nova\Http\Middleware\Authenticate;
use Llaski\NovaScheduledJobs\Http\Middleware\Authorize;
use PHPUnit\Framework\Attributes\Test;

class ToolTest extends TestCase
{
    #[Test]
    public function hasCorrectMiddleware()
    {
        $this->assertRouteUsesMiddleware('nova-scheduled-jobs.index', ['nova', Authenticate::class, Authorize::class], exact: true);
    }
}

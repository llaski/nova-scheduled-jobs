<?php

namespace Llaski\NovaScheduledJobs\Tests;

use Illuminate\Support\Facades\Bus;
use Laravel\Nova\Http\Middleware\Authenticate;
use Llaski\NovaScheduledJobs\Http\Middleware\Authorize;
use Llaski\NovaScheduledJobs\Tests\Fixtures\Jobs\ProcessPodcast;
use Llaski\NovaScheduledJobs\Tests\Fixtures\Jobs\ProcessPodcastWithDependencies;
use PHPUnit\Framework\Attributes\Test;

class DispatchJobTest extends TestCase
{
    #[Test]
    public function hasCorrectMiddleware()
    {
        $this->assertRouteUsesMiddleware('nova-scheduled-jobs.dispatch', ['nova', Authenticate::class, Authorize::class], exact: true);
    }

    #[Test]
    public function canDispatchJob()
    {
        Bus::fake();

        $this->withoutMiddleware()->postJson('nova-vendor/nova-scheduled-jobs/dispatch', [
            'command' => ProcessPodcast::class,
        ])->assertStatus(200);

        Bus::assertDispatched(ProcessPodcast::class);
    }

    #[Test]
    public function canDispatchJobWithDependencies()
    {
        Bus::fake();

        $this->withoutMiddleware()->postJson('nova-vendor/nova-scheduled-jobs/dispatch', [
            'command' => ProcessPodcastWithDependencies::class,
        ])->assertStatus(200);

        Bus::assertDispatched(ProcessPodcastWithDependencies::class);
    }
}

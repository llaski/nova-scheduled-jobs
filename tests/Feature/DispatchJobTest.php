<?php

namespace Llaski\NovaScheduledJobs\Tests;

use Illuminate\Support\Facades\Bus;
use Llaski\NovaScheduledJobs\Tests\Fixtures\Jobs\ProcessPodcast;
use Llaski\NovaScheduledJobs\Tests\Fixtures\Jobs\ProcessPodcastWithDependencies;
use Llaski\NovaScheduledJobs\Tests\Fixtures\Jobs\UpdateOrders;
use Llaski\NovaScheduledJobs\Tests\Fixtures\Jobs\UpdateOrdersWithDependencies;

class DispatchJobTest extends TestCase
{
    /** @test */
    public function canDispatchJob()
    {
        Bus::fake();

        $this->postJson('nova-vendor/nova-scheduled-jobs/dispatch', [
            'command' => ProcessPodcast::class,
        ])->assertStatus(200);

        Bus::assertDispatched(ProcessPodcast::class);
    }

    /** @test */
    public function canDispatchJobWithDependencies()
    {
        Bus::fake();

        $this->postJson('nova-vendor/nova-scheduled-jobs/dispatch', [
            'command' => ProcessPodcastWithDependencies::class,
        ])->assertStatus(200);

        Bus::assertDispatched(ProcessPodcastWithDependencies::class);
    }
}

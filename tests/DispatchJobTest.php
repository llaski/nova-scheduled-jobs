<?php

namespace Llaski\NovaScheduledJobs\Tests;

use Illuminate\Support\Facades\Bus;
use Llaski\NovaScheduledJobs\Tests\Fixtures\Jobs\UpdateOrders;
use Llaski\NovaScheduledJobs\Tests\Fixtures\Jobs\UpdateOrdersWithDependencies;

class DispatchJobTest extends TestCase
{
    /** @test */
    public function canDispatchJob()
    {
        Bus::fake();

        $this->postJson('nova-vendor/llaski/nova-scheduled-jobs/dispatch-job', [
                'command' => UpdateOrders::class,
            ])->assertStatus(200);

        Bus::assertDispatched(UpdateOrders::class);
    }

    /** @test */
    public function canDispatchJobWithDependencies()
    {
        Bus::fake();

        $this->postJson('nova-vendor/llaski/nova-scheduled-jobs/dispatch-job', [
                'command' => UpdateOrdersWithDependencies::class,
            ])->assertStatus(200);

        Bus::assertDispatched(UpdateOrdersWithDependencies::class);
    }
}

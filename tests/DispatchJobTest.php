<?php

namespace Llaski\NovaScheduledJobs\Tests;

use Cron\CronExpression;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;
use Llaski\NovaScheduledJobs\Schedule\Cron;
use Llaski\NovaScheduledJobs\Vendor\CronSchedule;
use Llaski\NovaScheduledJobs\Tests\Fixtures\Jobs\UpdateOrders;

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
}

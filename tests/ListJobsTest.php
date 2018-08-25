<?php

namespace Llaski\NovaScheduledJobs\Tests;

use Cron\CronExpression;
use Illuminate\Support\Carbon;
use Llaski\NovaScheduledJobs\Tests\Fixtures\Jobs\UpdateOrders;
use Llaski\NovaScheduledJobs\Vendor\CronSchedule;

class ListJobsTest extends TestCase
{

    // /** @test */
    // public function itReturnsAnEmptyArrayIfThereAreNoJobsScheduled()
    // {
    //     $response = $this->getJson('nova-vendor/llaski/nova-scheduled-jobs/jobs');

    //     $response->assertStatus(200);
    //     $response->assertJson([]);
    // }

    /** @test */
    public function itReturnsAListOfScheduledJobs()
    {
        $kernel = app('Llaski\NovaScheduledJobs\Tests\Fakes\Kernel', [
            'scheduledJobs' => [
                [
                    'command' => 'horizon:snapshot',
                    'schedule' => 'everyFiveMinutes',
                    'additionalOptions' => []
                ],
                [
                    'command' => 'store-fake-metrics',
                    'schedule' => 'hourly',
                    'additionalOptions' => [
                        'withoutOverlapping',
                        'onOneServer',
                        'evenInMaintenanceMode'
                    ]
                ],
                [
                    'job' => UpdateOrders::class,
                    'schedule' => 'daily',
                    'additionalOptions' => [
                        'evenInMaintenanceMode'
                    ]
                ],
            ],
        ]);
        app()->instance('Illuminate\Contracts\Console\Kernel', $kernel);

        $response = $this->getJson('nova-vendor/llaski/nova-scheduled-jobs/jobs');

        $response->assertStatus(200);
        $response->assertJson([
            [
                'command' => 'horizon:snapshot',
                'description' => 'Store a snapshot of the queue metrics',
                'expression' => '*/5 * * * *',
                'humanReadableExpression' => 'Every consecutive 5 minutes past every hour on every day.',
                'nextRunAt' => $this->nextRunAt('*/5 * * * *'),
                'timezone' => 'UTC',
                'withoutOverlapping' => false,
                'onOneServer' => false,
                'evenInMaintenanceMode' => false,
            ],
            [
                'command' => 'store-fake-metrics',
                'description' => '',
                'expression' => '0 * * * *',
                'humanReadableExpression' => 'At the hour past every hour on every day.',
                'nextRunAt' => $this->nextRunAt('0 * * * *'),
                'timezone' => 'UTC',
                'withoutOverlapping' => true,
                'onOneServer' => true,
                'evenInMaintenanceMode' => true,
            ],
            [
                'command' => UpdateOrders::class,
                'description' => 'Fake job to update orders...',
                'expression' => '0 0 * * *',
                'humanReadableExpression' => 'At 00:00 on every day.',
                'nextRunAt' => $this->nextRunAt('0 0 * * *'),
                'timezone' => 'UTC',
                'withoutOverlapping' => false,
                'onOneServer' => false,
                'evenInMaintenanceMode' => true,
            ],
        ]);
    }

    private function nextRunAt($expression, $tz = null)
    {
        $cron = CronExpression::factory($expression);
        $nextRun = Carbon::instance($cron->getNextRunDate());

        if ($tz) {
            $nextRun->setTimezone($tz);
        }

        return $nextRun->toIso8601String();
    }
}

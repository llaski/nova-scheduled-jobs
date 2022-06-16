<?php

namespace Llaski\NovaScheduledJobs\Tests;

use Cron\CronExpression;
use Illuminate\Support\Carbon;
use Llaski\NovaScheduledJobs\Schedule\Cron;
use Llaski\NovaScheduledJobs\Tests\Fixtures\Jobs\UpdateOrders;
use Llaski\NovaScheduledJobs\Vendor\CronSchedule;

class ListJobsTest extends TestCase
{

    /** @test */
    public function itReturnsAnEmptyArrayIfThereAreNoJobsScheduled()
    {
        $response = $this->getJson('nova-vendor/llaski/nova-scheduled-jobs/jobs');

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    /** @test */
    public function itReturnsAListOfScheduledJobs()
    {
        $kernel = app('Llaski\NovaScheduledJobs\Tests\Fakes\Kernel', [
            'scheduledJobs' => [
                [
                    'command' => 'cache:clear',
                    'schedule' => 'everyFiveMinutes',
                    'additionalOptions' => []
                ],
                [
                    'command' => 'store-fake-metrics',
                    'schedule' => 'hourly',
                    'additionalOptions' => [
                        'withoutOverlapping' => null,
                        'onOneServer' => null,
                        'evenInMaintenanceMode' => null
                    ]
                ],
                [
                    'exec' => 'df',
                    'schedule' => 'hourly',
                    'additionalOptions' => [
                        'description' => 'Check disk usage',
                    ]
                ],
                [
                    'job' => UpdateOrders::class,
                    'schedule' => 'daily',
                    'additionalOptions' => [
                        'evenInMaintenanceMode' => null
                    ]
                ],
            ],
        ]);
        app()->instance('Illuminate\Contracts\Console\Kernel', $kernel);

        $response = $this->getJson('nova-vendor/llaski/nova-scheduled-jobs/jobs');

        $response->assertStatus(200);
        $response->assertJson([
            [
                'command' => 'cache:clear',
                'description' => 'Flush the application cache',
                'expression' => '*/5 * * * *',
                'humanReadableExpression' => 'Every consecutive 5 minutes past every hour on every day.',
                'nextRunAt' => Cron::nextRunAt('*/5 * * * *')->toIso8601String(),
                'timezone' => 'UTC',
                'withoutOverlapping' => false,
                'onOneServer' => false,
                'evenInMaintenanceMode' => false,
            ],
            [
                'command' => 'store-fake-metrics',
                'description' => null,
                'expression' => '0 * * * *',
                'humanReadableExpression' => 'At the hour past every hour on every day.',
                'nextRunAt' => Cron::nextRunAt('0 * * * *')->toIso8601String(),
                'timezone' => 'UTC',
                'withoutOverlapping' => true,
                'onOneServer' => true,
                'evenInMaintenanceMode' => true,
            ],
            [
                'command' => null,
                'description' => 'Check disk usage',
                'expression' => '0 * * * *',
                'humanReadableExpression' => 'At the hour past every hour on every day.',
                'nextRunAt' => Cron::nextRunAt('0 * * * *')->toIso8601String(),
                'timezone' => 'UTC',
                'withoutOverlapping' => false,
                'onOneServer' => false,
                'evenInMaintenanceMode' => false,
            ],
            [
                'command' => UpdateOrders::class,
                'description' => 'Fake job to update orders...',
                'expression' => '0 0 * * *',
                'humanReadableExpression' => 'At 00:00 on every day.',
                'nextRunAt' => Cron::nextRunAt('0 0 * * *')->toIso8601String(),
                'timezone' => 'UTC',
                'withoutOverlapping' => false,
                'onOneServer' => false,
                'evenInMaintenanceMode' => true,
            ],
        ]);
    }

}

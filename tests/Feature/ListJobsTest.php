<?php

namespace Llaski\NovaScheduledJobs\Tests;

use Illuminate\Support\Carbon;
use Llaski\NovaScheduledJobs\Tests\TestCase;
use Llaski\NovaScheduledJobs\Tests\Fixtures\Console\Kernel;

class ListJobsTest extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();

        Carbon::setTestNow();
    }

    /** @test */
    public function itReturnsAnEmptyArrayIfThereAreNoJobsScheduled()
    {
        $response = $this->getJson('nova-vendor/nova-scheduled-jobs/jobs');

        $response->assertStatus(200);
        $response->assertJson([]);
    }

    /** @test */
    public function itReturnsAListOfScheduledJobs()
    {
        Carbon::setTestNow(Carbon::parse('1/1/2022'));

        app()->instance('Illuminate\Contracts\Console\Kernel', app(Kernel::class));

        $response = $this->getJson('nova-vendor/nova-scheduled-jobs/jobs');

        $response->assertStatus(200);

        $response->assertJson([
            'data' => [
                [
                    "command" => "php artisan app:log-users",
                    "description" => "Log Users",
                    "expression" => '0   0 * * *',
                    "expressionHumanReadable" => "At 00:00 on every day.",
                    "timezone" => "UTC",
                    "nextDue" => "2022-01-02 00:00:00 +00:00",
                    "nextDueHumanReadable" => '1 day from now',
                    "withoutOverlapping" => false,
                    "onOneServer" => false,
                    "runInBackground" => false,
                    "evenInMaintenanceMode" => false,
                ],
                [
                    "command" => "php artisan app:log-users",
                    "description" => "Log Users",
                    "expression" => '0   0 * * *',
                    "expressionHumanReadable" => "At 00:00 on every day.",
                    "timezone" => "UTC",
                    "nextDue" => "2022-01-02 00:00:00 +00:00",
                    "nextDueHumanReadable" => '1 day from now',
                    "withoutOverlapping" => false,
                    "onOneServer" => false,
                    "runInBackground" => false,
                    "evenInMaintenanceMode" => false,
                ],
                [
                    "command" => "Closure at: " . str_replace('/tests/Feature', '', __DIR__) . "/tests/Fixtures/Console/Kernel.php:38",
                    "description" => "",
                    "expression" => '0   0 * * 0',
                    "expressionHumanReadable" => "At 00:00 on Sundays.",
                    "timezone" => "UTC",
                    "nextDue" => "2022-01-02 00:00:00 +00:00",
                    "nextDueHumanReadable" => '1 day from now',
                    "withoutOverlapping" => false,
                    "onOneServer" => false,
                    "runInBackground" => false,
                    "evenInMaintenanceMode" => true,
                ],
                [
                    "command" => "Closure at: Llaski\\NovaScheduledJobs\\Tests\\Fixtures\\Console\\Tasks\\DeleteRecentUsers::__invoke",
                    "description" => "",
                    "expression" => '0   0 * * *',
                    "expressionHumanReadable" => "At 00:00 on every day.",
                    "timezone" => "UTC",
                    "nextDue" => "2022-01-02 00:00:00 +00:00",
                    "nextDueHumanReadable" => "1 day from now",
                    "withoutOverlapping" => false,
                    "onOneServer" => false,
                    "runInBackground" => false,
                    "evenInMaintenanceMode" => false
                ],
                [
                    "command" => "Closure at: Llaski\\NovaScheduledJobs\\Tests\\Fixtures\\Console\\Tasks\\DeleteRecentUsers::__invoke",
                    "description" => "",
                    "expression" => '0   0 * * *',
                    "expressionHumanReadable" => "At 00:00 on every day.",
                    "timezone" => "America/New_York",
                    "nextDue" => "2022-01-01 00:00:00 -05:00",
                    "nextDueHumanReadable" => "5 hours from now",
                    "withoutOverlapping" => false,
                    "onOneServer" => false,
                    "runInBackground" => false,
                    "evenInMaintenanceMode" => false
                ],
                [
                    "command" => "php artisan app:log-users",
                    "description" => "Log Users",
                    "expression" =>  '0   0 * * 0',
                    "expressionHumanReadable" => "At 00:00 on Sundays.",
                    "timezone" => "UTC",
                    "nextDue" => "2022-01-02 00:00:00 +00:00",
                    "nextDueHumanReadable" => '1 day from now',
                    "withoutOverlapping" => true,
                    "onOneServer" => false,
                    "runInBackground" => false,
                    "evenInMaintenanceMode" => false,
                ],
                [
                    "command" => "php artisan app:log-users",
                    "description" => "Log Users",
                    "expression" =>  '0   0 * * 0',
                    "expressionHumanReadable" => "At 00:00 on Sundays.",
                    "timezone" => "UTC",
                    "nextDue" => "2022-01-02 00:00:00 +00:00",
                    "nextDueHumanReadable" => '1 day from now',
                    "withoutOverlapping" => true,
                    "onOneServer" => false,
                    "runInBackground" => false,
                    "evenInMaintenanceMode" => false,
                ],
                [
                    "command" => "php artisan inspire",
                    "description" => "",
                    "expression" => "0   * * * *",
                    "expressionHumanReadable" => "At the hour past every hour on every day.",
                    "timezone" => "UTC",
                    "nextDue" => "2022-01-01 01:00:00 +00:00",
                    "nextDueHumanReadable" => "1 hour from now",
                    "withoutOverlapping" => false,
                    "onOneServer" => false,
                    "runInBackground" => false,
                    "evenInMaintenanceMode" => true
                ],
                [
                    "command" => "php artisan inspire",
                    "description" => "",
                    "expression" => "0   * * * *",
                    "expressionHumanReadable" => "At the hour past every hour on every day.",
                    "timezone" => "UTC",
                    "nextDue" => "2022-01-01 01:00:00 +00:00",
                    "nextDueHumanReadable" => "1 hour from now",
                    "withoutOverlapping" => false,
                    "onOneServer" => false,
                    "runInBackground" => true,
                    "evenInMaintenanceMode" => false
                ],
                [
                    "command" => "Llaski\\NovaScheduledJobs\\Tests\\Fixtures\\Jobs\\ProcessPodcast",
                    "description" => "process the podcast",
                    "expression" => "*/5 * * * *",
                    "expressionHumanReadable" => "Every consecutive 5 minutes past every hour on every day.",
                    "timezone" => "UTC",
                    "nextDue" => "2022-01-01 00:05:00 +00:00",
                    "nextDueHumanReadable" => "5 minutes from now",
                    "withoutOverlapping" => false,
                    "onOneServer" => false,
                    "runInBackground" => false,
                    "evenInMaintenanceMode" => false
                ],
                [
                    "command" => "node /home/forge/script.js",
                    "description" => "",
                    "expression" => "0   0 1 * *",
                    "expressionHumanReadable" => "At 00:00 on the 1st of every month.",
                    "timezone" => "UTC",
                    "nextDue" => "2022-02-01 00:00:00 +00:00",
                    "nextDueHumanReadable" => "1 month from now",
                    "withoutOverlapping" => false,
                    "onOneServer" => false,
                    "runInBackground" => false,
                    "evenInMaintenanceMode" => false,
                ],
            ]
            // [
            //     'command' => 'cache:clear',
            //     'description' => 'Flush the application cache',
            //     'expression' => '*/5 * * * *',
            //     'humanReadableExpression' => 'Every consecutive 5 minutes past every hour on every day.',
            //     'nextRunAt' => Cron::nextRunAt('*/5 * * * *')->toIso8601String(),
            //     'timezone' => 'UTC',
            //     'withoutOverlapping' => false,
            //     'onOneServer' => false,
            //     'evenInMaintenanceMode' => false,
            // ],
            //     [
            //         'command' => 'store-fake-metrics',
            //         'description' => '',
            //         'expression' => '0 * * * *',
            //         'humanReadableExpression' => 'At the hour past every hour on every day.',
            //         'nextRunAt' => Cron::nextRunAt('0 * * * *')->toIso8601String(),
            //         'timezone' => 'UTC',
            //         'withoutOverlapping' => true,
            //         'onOneServer' => true,
            //         'evenInMaintenanceMode' => true,
            //     ],
            //     [
            //         'command' => UpdateOrders::class,
            //         'description' => 'Fake job to update orders...',
            //         'expression' => '0 0 * * *',
            //         'humanReadableExpression' => 'At 00:00 on every day.',
            //         'nextRunAt' => Cron::nextRunAt('0 0 * * *')->toIso8601String(),
            //         'timezone' => 'UTC',
            //         'withoutOverlapping' => false,
            //         'onOneServer' => false,
            //         'evenInMaintenanceMode' => true,
            //     ],
        ]);
    }
}

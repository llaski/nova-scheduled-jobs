<?php

namespace Llaski\NovaScheduledJobs\Tests\Fixtures\Console;

use Illuminate\Support\Facades\DB;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Llaski\NovaScheduledJobs\Tests\Fixtures\Jobs\ProcessPodcast;
use Llaski\NovaScheduledJobs\Tests\Fixtures\Console\Commands\LogUsers;
use Llaski\NovaScheduledJobs\Tests\Fixtures\Console\Tasks\DeleteRecentUsers;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        LogUsers::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /**
         * Potential Options - https://laravel.com/docs/9.x/scheduling
         * - Command via class
         * - Command via string
         * - Closure
         * - Invokeable object
         * - Job via class (job)
         * - Shell Commands (exec)
         */
        $schedule->command(LogUsers::class)->daily();
        $schedule->command('app:log-users')->daily();

        $schedule->call(function () {
            DB::table('recent_users')->delete();
        })->weekly()->evenInMaintenanceMode();

        $schedule->call(new DeleteRecentUsers)->daily();
        $schedule->call(new DeleteRecentUsers)->daily()->timezone('America/New_York');

        $schedule->command(LogUsers::class)->weekly()->withoutOverlapping();
        $schedule->command(LogUsers::class)->weekly()->withoutOverlapping(15);

        $schedule->command('inspire')->hourly()->evenInMaintenanceMode();
        $schedule->command('inspire')->hourly()->runInBackground();

        $schedule->job(new ProcessPodcast)->everyFiveMinutes();

        $schedule->exec('node /home/forge/script.js')->monthly();
    }
}

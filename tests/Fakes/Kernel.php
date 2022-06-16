<?php

namespace Llaski\NovaScheduledJobs\Tests\Fakes;

use Illuminate\Support\Arr;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * @var array
     */
    private $scheduledJobs;

    /**
     * Create a new console kernel instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @param  array $scheduledJobs
     * @return void
     */
    public function __construct(Application $app, Dispatcher $events, $scheduledJobs = [])
    {
        $this->scheduledJobs = collect($scheduledJobs);

        parent::__construct($app, $events);
    }

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        collect($this->scheduledJobs)->each(function ($job) use ($schedule) {

            if (Arr::has($job, 'job')) {
                $command = $schedule->job($job['job']);
            } else if (Arr::has($job, 'command')) {
                $command = $schedule->command($job['command']);
            } else if (Arr::has($job, 'exec')) {
                $command = $schedule->exec($job['exec']);
            }

            $command->{$job['schedule']}();

            collect(Arr::get($job, 'additionalOptions', []))->each(function ($parameter, $additionalOption) use ($command) {
                $command->{$additionalOption}($parameter);
            });
        });
    }

}

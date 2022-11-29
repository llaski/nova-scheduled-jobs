<?php

namespace Llaski\NovaScheduledJobs\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Console\Scheduling\Schedule;
use Llaski\NovaScheduledJobs\Schedule\EventFactory;
use Llaski\NovaScheduledJobs\Http\Resources\JobResource;
use Llaski\NovaScheduledJobs\Http\Resources\JobCollection;

class JobsController
{
    /**
     * Execute the console command.
     *
     * @param  \Illuminate\Contracts\Console\Kernel   $kernel (Not sure why we need to inject the kernel, but without it we don't get the schedueld jobs. Prob something to do with how the schedule method is called from the kernel)
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     *
     * @throws \Exception
     */
    public function __invoke(Kernel $kernel, Schedule $schedule)
    {
        $jobs = collect($schedule->events())->map(function ($event) {
            return EventFactory::make($event);
        });

        return new JobCollection($jobs);
    }
}

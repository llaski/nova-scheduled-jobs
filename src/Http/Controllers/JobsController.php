<?php

namespace Llaski\NovaScheduledJobs\Http\Controllers;

use Illuminate\Contracts\Console\Kernel;
use Llaski\NovaScheduledJobs\Http\Resources\JobCollection;
use Llaski\NovaScheduledJobs\Schedule\ScheduleReader;

class JobsController
{
    /**
     * Return the list of scheduled jobs.
     *
     * @param  \Illuminate\Contracts\Console\Kernel  $kernel
     * @return \Llaski\NovaScheduledJobs\Http\Resources\JobCollection
     *
     * @throws \Exception
     */
    public function __invoke(Kernel $kernel)
    {
        return new JobCollection(ScheduleReader::events($kernel));
    }
}

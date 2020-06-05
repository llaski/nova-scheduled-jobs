<?php

namespace Llaski\NovaScheduledJobs\Http\Controllers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Console\Kernel;
use Llaski\NovaScheduledJobs\Schedule\Factory as ScheduleFactory;

class JobsController
{

    /**
     * Return a list of all scheduled jobs
     *
     * @param  Kernel   $kernel (Not sure why we need to inject the kernel, but without it we don't get the schedueld jobs. Prob something to do with how the schedule method is called from the kernel)
     * @param  Schedule $schedule
     * @return array
     */
    public function index(Kernel $kernel, Schedule $schedule)
    {
        return collect($schedule->events())
            ->map(function ($event) {
                $scheduleEvent = ScheduleFactory::make($event);

                return [
                    'command' => $scheduleEvent->command(),
                    'description' => $scheduleEvent->description(),
                    'expression' => $scheduleEvent->expression,
                    'humanReadableExpression' => $scheduleEvent->humanReadableExpression(),
                    'nextRunAt' => $scheduleEvent->nextRunAt()->toIso8601String(),
                    'timezone' => $scheduleEvent->timezone(),
                    'withoutOverlapping' => $scheduleEvent->withoutOverlapping,
                    'onOneServer' => $scheduleEvent->onOneServer,
                    'evenInMaintenanceMode' => $scheduleEvent->evenInMaintenanceMode,
                    'dispatch' => $scheduleEvent->dispatchAs(),
                ];
            });
    }

}

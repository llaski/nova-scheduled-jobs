<?php

namespace Llaski\NovaScheduledJobs\Http\Controllers;

use Illuminate\Console\Scheduling\Schedule;
use Llaski\NovaScheduledJobs\Schedule\Factory as ScheduleFactory;

class JobsController
{

    public function index(Schedule $schedule)
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
                ];
            });
    }

}

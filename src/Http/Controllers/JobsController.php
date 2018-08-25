<?php

namespace Llaski\NovaScheduledJobs\Http\Controllers;

use Cron\CronExpression;
use Illuminate\Console\Parser;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Carbon;
use Llaski\NovaScheduledJobs\Vendor\CronSchedule;

class JobsController
{

    public function index(Kernel $kernel, Schedule $schedule)
    {
        $this->kernel = $kernel;

        return collect($schedule->events())
            ->map(function ($event) {
                if ($event instanceof \Illuminate\Console\Scheduling\CallbackEvent) {
                    $command = $event->description;
                    $description = $this->description($event);
                } else {
                    $command = $this->baseCommand($event->command);
                    $description = $this->description($event);
                }

                return [
                    'command' => $command,
                    'description' => $this->description($event),
                    'expression' => $event->expression,
                    'humanReadableExpression' => (CronSchedule::fromCronString($event->expression))->asNaturalLanguage(),
                    'nextRunAt' => $this->nextRunAt($event->expression, $event->timezone)->toIso8601String(),
                    'timezone' => $event->timezone ?: 'UTC',
                    'withoutOverlapping' => $event->withoutOverlapping,
                    'onOneServer' => $event->onOneServer,
                    'evenInMaintenanceMode' => $event->evenInMaintenanceMode,
                ];
            });
    }

    private function baseCommand($command)
    {
        preg_match("/artisan'\s(.*)/", $command, $matches);

        return $matches[1] ?? null;
    }

    private function description($event)
    {
        if (class_exists($event->description)) {
            $className = $event->description;
        } else {
            list($command) = Parser::parse($this->baseCommand($event->command));

            $commands = $this->kernel->all();

            dd(array_keys($commands));
            if (!isset($commands[$command])) {
                return '';
            }

            $className = get_class($commands[$command]);
        }

        try {
            $reflection = new \ReflectionClass($className);
            return (string) array_get($reflection->getDefaultProperties(), 'description', '');
        } catch (\ReflectionException $exception) {
            return '';
        }
    }

    private function nextRunAt($expression, $tz = null)
    {
        $cron = CronExpression::factory($expression);
        $nextRun = Carbon::instance($cron->getNextRunDate());

        if ($tz) {
            $nextRun->setTimezone($tz);
        }

        return $nextRun;
    }

}

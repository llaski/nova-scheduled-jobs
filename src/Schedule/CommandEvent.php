<?php

namespace Llaski\NovaScheduledJobs\Schedule;

use Illuminate\Console\Application;
use Illuminate\Console\Parser;
use Illuminate\Console\Scheduling\Event as ScheduleEvent;
use Illuminate\Contracts\Console\Kernel;

class CommandEvent extends Event
{
    public function command()
    {
        $command = $this->event->command;

        if (method_exists(ScheduleEvent::class, 'normalizeCommand')) {
            return ScheduleEvent::normalizeCommand($command);
        }

        return str_replace([Application::phpBinary(), Application::artisanBinary()], [
            'php',
            preg_replace("#['\"]#", '', Application::artisanBinary()),
        ], $command);
    }

    public function className()
    {
        [$command] = Parser::parse(str_replace('php artisan ', '', $this->command()));
        $commands = app(Kernel::class)->all();

        if (! isset($commands[$command])) {
            return '';
        }

        return get_class($commands[$command]);
    }
}

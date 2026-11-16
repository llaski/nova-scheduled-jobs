<?php

namespace Llaski\NovaScheduledJobs\Schedule;

use Illuminate\Console\Parser;
use Illuminate\Console\Scheduling\Event as ScheduleEvent;
use Illuminate\Contracts\Console\Kernel;

class CommandEvent extends Event
{
    public function command()
    {
        return ScheduleEvent::normalizeCommand($this->event->command);
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

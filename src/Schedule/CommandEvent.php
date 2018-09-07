<?php

namespace Llaski\NovaScheduledJobs\Schedule;

use Illuminate\Console\Parser;
use Illuminate\Contracts\Console\Kernel;

class CommandEvent extends Event
{
    public function command()
    {
        preg_match("/artisan.*\s(.*)/", $this->event->command, $matches);

        return $matches[1] ?? null;
    }

    public function className()
    {
        list($command) = Parser::parse($this->command());

        $commands = app(Kernel::class)->all();

        if (!isset($commands[$command])) {
            return '';
        }

        return get_class($commands[$command]);
    }

}

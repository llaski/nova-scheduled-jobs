<?php

namespace Llaski\NovaScheduledJobs\Schedule;

use Illuminate\Support\Arr;
use Illuminate\Console\Parser;
use Illuminate\Console\Application;
use Illuminate\Contracts\Console\Kernel;

class CommandEvent extends Event
{
    public function command()
    {
        $command = str_replace([Application::phpBinary(), Application::artisanBinary()], [
            'php',
            preg_replace("#['\"]#", '', Application::artisanBinary()),
        ], $this->event->command);

        return $command;
    }

    public function className()
    {
        [$command] = Parser::parse(str_replace('php artisan ', '', $this->command()));
        $commands = app(Kernel::class)->all();

        if (!isset($commands[$command])) {
            return '';
        }

        return get_class($commands[$command]);
    }
}

<?php

namespace Llaski\NovaScheduledJobs\Schedule;

use Illuminate\Console\Application;
use Illuminate\Console\Parser;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class CommandEvent extends Event
{
    public function command()
    {
        preg_match("/artisan.*?\s(.*)/", $this->event->command, $matches);

        return $matches[1] ?? null;
    }

    public function className()
    {
        [$command] = Parser::parse($this->command());

        $commands = app(Kernel::class)->all();

        if (!isset($commands[$command])) {
            return '';
        }

        return get_class($commands[$command]);
    }

    public function description()
    {
        $command = $this->command();
        $parsed = Parser::parse($this->command());

        if (is_subclass_of($command, SymfonyCommand::class)) {
            $callingClass = true;

            return $this->laravel->make($command)->getDescription();
        }

        if (!empty($command)) {
            $commands = Artisan::all();

            return isset($commands[$command]) ? $commands[$command]->getDescription() : '';
        }

        return '';
    }

    public function dispatchAs()
    {
        $class = Str::of($this->className());

        if ($class->contains('Job')) {
            return 'job';
        }
        if ($class->contains(['Console', 'Command'])) {

            $commands = Artisan::all();

            // Don't run commands that require arguments
            return  $commands[$this->command()]->getDefinition()->getArgumentCount() == 0 ? 'command' : false;
        }

        return false;
    }
}

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
        $command = $this->artisanCommand();

        if ($command === null) {
            return '';
        }

        return get_class($command);
    }

    public function description()
    {
        if (! empty($this->event->description)) {
            return $this->event->description;
        }

        $command = $this->artisanCommand();

        if ($command === null) {
            return parent::description();
        }

        return (string) $command->getDescription();
    }

    /**
     * @return \Symfony\Component\Console\Command\Command|null
     */
    protected function artisanCommand()
    {
        [$command] = Parser::parse(str_replace('php artisan ', '', $this->command()));
        $commands = app(Kernel::class)->all();

        return $commands[$command] ?? null;
    }
}

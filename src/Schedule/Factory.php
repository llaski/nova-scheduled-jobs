<?php

namespace Llaski\NovaScheduledJobs\Schedule;

class Factory
{
    public static function make(\Illuminate\Console\Scheduling\Event $event)
    {
        if ($event instanceof \Illuminate\Console\Scheduling\CallbackEvent) {
            return new JobEvent($event);
        }

        return new CommandEvent($event);
    }

}

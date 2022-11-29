<?php

namespace Llaski\NovaScheduledJobs\Schedule;

use \Illuminate\Console\Scheduling\Event;
use \Illuminate\Console\Scheduling\CallbackEvent;

class EventFactory
{
    public static function make(Event $event)
    {
        // Applies to closures as well as Jobs
        if ($event instanceof CallbackEvent) {
            return new ClosureEvent($event);
        }

        return new CommandEvent($event);
    }
}

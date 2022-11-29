<?php

namespace Llaski\NovaScheduledJobs\Schedule;

use Closure;
use ReflectionClass;
use ReflectionFunction;
use Illuminate\Console\Scheduling\CallbackEvent;

/*
* Can be a Job or full closure, Laravel treats both as a ClosureEvent
*/

class ClosureEvent extends Event
{

    public function command()
    {
        return $this->event->description ?? 'Closure at: ' . $this->getClosureLocation($this->event);
    }

    public function className()
    {
        return $this->event->description;
    }

    /**
     * Get the file and line number for the event closure.
     *
     * @param  \Illuminate\Console\Scheduling\CallbackEvent  $event
     * @return string
     */
    private function getClosureLocation(CallbackEvent $event)
    {
        $callback = tap((new ReflectionClass($event))->getProperty('callback'))
            ->setAccessible(true)
            ->getValue($event);

        if ($callback instanceof Closure) {
            $function = new ReflectionFunction($callback);

            return sprintf(
                '%s:%s',
                str_replace(base_path() . DIRECTORY_SEPARATOR, '', $function->getFileName() ?: ''),
                $function->getStartLine()
            );
        }

        if (is_array($callback)) {
            return sprintf('%s::%s', $callback[0]::class, $callback[1]);
        }

        return sprintf('%s::__invoke', $callback::class);
    }
}

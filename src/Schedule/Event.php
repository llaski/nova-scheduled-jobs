<?php

namespace Llaski\NovaScheduledJobs\Schedule;

use DateTimeZone;
use Cron\CronExpression;
use Illuminate\Support\Arr;
use Illuminate\Console\Parser;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Console\Application;
use Illuminate\Contracts\Console\Kernel;


abstract class Event
{
    protected $event;

    public function __construct($event)
    {
        $this->event = $event;
    }

    abstract public function command();

    abstract public function className();

    public function description()
    {
        try {
            $reflection = new \ReflectionClass($this->className());
            return (string) Arr::get($reflection->getDefaultProperties(), 'description', '');
        } catch (\ReflectionException $exception) {
            return '';
        }
    }

    public function formattedExpression(Collection $expressionSpacing)
    {
        $expressions = preg_split("/\s+/", $this->event->expression);

        return collect($expressionSpacing)
            ->map(fn ($length, $index) => str_pad($expressions[$index], $length))
            ->implode(' ');
    }

    public function humanReadableExpression()
    {
        return CronSchedule::fromCronString($this->event->expression)->asNaturalLanguage();
    }

    public function nextDue()
    {
        return Carbon::create((new CronExpression($this->event->expression))
                ->getNextRunDate(Carbon::now()->setTimezone($this->event->timezone))
                ->setTimezone($this->timezone())
        );
    }

    public function timezone()
    {
        return new DateTimeZone($this->event->timezone ?? config('app.timezone'));
    }

    public function __get($name)
    {
        return $this->event->{$name};
    }
}

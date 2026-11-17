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

/**
 * @property string $expression
 * @property string|null $timezone
 * @property bool $withoutOverlapping
 * @property bool $onOneServer
 * @property bool $runInBackground
 * @property bool $evenInMaintenanceMode
 * @method string mutexName()
 */
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
            $className = $this->className();

            if ($className === '' || $className === null) {
                return '';
            }

            $reflection = new \ReflectionClass($className);
            $description = (string) Arr::get($reflection->getDefaultProperties(), 'description', '');

            if ($description !== '') {
                return $description;
            }

            return $this->descriptionFromAsCommandAttribute($reflection);
        } catch (\ReflectionException $exception) {
            return '';
        }
    }

    /**
     * Resolve a description from the Symfony AsCommand attribute when present.
     *
     * @param  \ReflectionClass  $reflection
     * @return string
     */
    protected function descriptionFromAsCommandAttribute(\ReflectionClass $reflection)
    {
        $attributes = $reflection->getAttributes(\Symfony\Component\Console\Attribute\AsCommand::class);

        if ($attributes === []) {
            return '';
        }

        return (string) ($attributes[0]->newInstance()->description ?? '');
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

    /**
     * Resolve the mutex name Laravel uses for this event (including createMutexNameUsing).
     *
     * @return string
     */
    public function mutexName()
    {
        return $this->event->mutexName();
    }

    public function __get($name)
    {
        return $this->event->{$name};
    }
}

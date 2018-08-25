<?php

namespace Llaski\NovaScheduledJobs\Schedule;

use Carbon\Carbon;
use Cron\CronExpression;

class Cron
{

    public static function nextRunAt($expression, $tz = null)
    {
        $cron = CronExpression::factory($expression);
        $nextRun = Carbon::instance($cron->getNextRunDate());

        if ($tz) {
            $nextRun->setTimezone($tz);
        }

        return $nextRun;
    }

}

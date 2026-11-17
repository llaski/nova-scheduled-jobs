<?php

namespace Llaski\NovaScheduledJobs;

class Columns
{
    public const COMMAND = 'command';

    public const SCHEDULE = 'schedule';

    public const EXPRESSION = 'expression';

    public const TIMEZONE = 'timezone';

    public const NEXT_DUE = 'nextDue';

    public const WITHOUT_OVERLAPPING = 'withoutOverlapping';

    public const ON_ONE_SERVER = 'onOneServer';

    public const RUN_IN_BACKGROUND = 'runInBackground';

    public const EVEN_IN_MAINTENANCE_MODE = 'evenInMaintenanceMode';

    public const DISPATCH = 'dispatch';

    /**
     * Default columns shown on the Scheduled Jobs tool.
     *
     * @return array<int, string>
     */
    public static function toolDefaults(): array
    {
        return [
            self::COMMAND,
            self::SCHEDULE,
            self::EXPRESSION,
            self::TIMEZONE,
            self::NEXT_DUE,
            self::WITHOUT_OVERLAPPING,
            self::ON_ONE_SERVER,
            self::RUN_IN_BACKGROUND,
            self::EVEN_IN_MAINTENANCE_MODE,
            self::DISPATCH,
        ];
    }

    /**
     * Default columns shown on the Scheduled Jobs card.
     *
     * @return array<int, string>
     */
    public static function cardDefaults(): array
    {
        return [
            self::COMMAND,
            self::EXPRESSION,
            self::NEXT_DUE,
        ];
    }
}

<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Tool columns
    |--------------------------------------------------------------------------
    |
    | Optionally restrict which columns appear on the Scheduled Jobs tool.
    | Use either "only" (whitelist) or "except" (blacklist). Instance-level
    | Tool::only() / Tool::except() always take precedence over this file.
    |
    | Available columns: command, schedule, expression, timezone, nextDue,
    | withoutOverlapping, onOneServer, runInBackground,
    | evenInMaintenanceMode, dispatch
    |
    */

    'tool' => [
        // 'only' => ['command', 'schedule', 'expression', 'nextDue', 'dispatch'],
        // 'except' => ['timezone', 'runInBackground'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Card columns
    |--------------------------------------------------------------------------
    |
    | Optionally restrict which columns appear on the Scheduled Jobs card.
    | Use either "only" or "except". Card::only() / Card::except() take
    | precedence over this file.
    |
    | Available columns: command, expression, nextDue
    |
    */

    'card' => [
        // 'only' => ['command', 'nextDue'],
        // 'except' => ['expression'],
    ],

];

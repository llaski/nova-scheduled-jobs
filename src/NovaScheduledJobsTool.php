<?php

namespace Llaski\NovaScheduledJobs;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class NovaScheduledJobsTool extends Tool
{
    public function boot()
    {
        Nova::script('nova-scheduled-jobs', __DIR__ . '/../dist/js/scheduledJobs.js');
    }
}

<?php

namespace Llaski\NovaScheduledJobs;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class NovaScheduledJobsTool extends Tool
{
    public function boot()
    {
        Nova::script('nova-scheduled-jobs', __DIR__ . '/../dist/js/tool.js');
    }

    /**
     * Build the view that renders the navigation links for the tool.
     *
     * @return \Illuminate\View\View
     */
    public function renderNavigation()
    {
        return view('nova-scheduled-jobs::navigation');
    }
}

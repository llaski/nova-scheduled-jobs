<?php

namespace Llaski\NovaScheduledJobs;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class NovaScheduledJobsTool extends Tool
{
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

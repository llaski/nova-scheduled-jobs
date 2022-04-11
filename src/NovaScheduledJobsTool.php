<?php

namespace Llaski\NovaScheduledJobs;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool;
use Illuminate\Http\Request;
use Laravel\Nova\Menu\MenuSection;

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

    /**
     * Build the menu that renders the navigation links for the tool.
     *
     * @param  Request  $request
     * @return mixed
     */
    public function menu(Request $request)
    {
        return MenuSection::make('Scheduled Jobs')
                          ->path('/scheduled-jobs')
                          ->icon('calendar');
    }
}
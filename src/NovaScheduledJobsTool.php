<?php

namespace Llaski\NovaScheduledJobs;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class NovaScheduledJobsTool extends Tool
{
    protected $hide = false;

    /**
     * Hides the tool from navigation.
     *
     * @param bool $hide
     *
     * @return $this
     */
    public function hide($hide = true)
    {
        $this->hide = $hide;

        return $this;
    }

    /**
     * Build the view that renders the navigation links for the tool.
     *
     * @return \Illuminate\View\View
     */
    public function renderNavigation()
    {
        return $this->hide ? '' : view('nova-scheduled-jobs::navigation');
    }

    /**
     * Default authorisation
     *
     * if the tool isn't registered, all routes and card can be accessed,
     * otherwise the canSee callback is used.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    public static function authorize($request)
    {
        $tool = collect(Nova::registeredTools())
            ->first(function ($tool) {
                return $tool instanceof static;
            });

        return $tool == null ? true : $tool->authorizedToSee($request);
    }
}

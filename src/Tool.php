<?php

namespace Llaski\NovaScheduledJobs;

use Illuminate\Http\Request;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Tool as NovaTool;

class Tool extends NovaTool
{
    use HasConfigurableColumns;

    /**
     * Build the menu that renders the navigation links for the tool.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function menu(Request $request)
    {
        return MenuSection::make('Scheduled Jobs')
            ->path('/nova-scheduled-jobs')
            ->icon('calendar');
    }

    /**
     * @return array<int, string>
     */
    protected function defaultColumns(): array
    {
        return Columns::toolDefaults();
    }

    protected function columnsConfigKey(): string
    {
        return 'tool';
    }
}

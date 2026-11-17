<?php

namespace Llaski\NovaScheduledJobs;

use Laravel\Nova\Card as NovaCard;

class Card extends NovaCard
{
    use HasConfigurableColumns;

    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = '2/3';

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component()
    {
        return 'NovaScheduledJobsCard';
    }

    /**
     * Prepare the element for JSON serialization.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'columns' => $this->columns(),
        ]);
    }

    /**
     * @return array<int, string>
     */
    protected function defaultColumns(): array
    {
        return Columns::cardDefaults();
    }

    protected function columnsConfigKey(): string
    {
        return 'card';
    }
}

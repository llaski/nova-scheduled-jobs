<?php

namespace Llaski\NovaScheduledJobs\Http\Resources;

use Illuminate\Support\Collection;
use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
{
    protected Collection $expressionSpacing;

    /**
     * @param  \Illuminate\Support\Collection  $expressionSpacing
     * @return JobResource
     */
    public function setExpressionSpacing(Collection $expressionSpacing)
    {
        $this->expressionSpacing = $expressionSpacing;

        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'command' => $this->command(),
            'description' => $this->description(),
            'expression' => $this->formattedExpression($this->expressionSpacing),
            'expressionHumanReadable' => $this->humanReadableExpression(),
            'timezone' => $this->timezone,
            'nextDue' => $this->nextDue()->format('Y-m-d H:i:s P'),
            'nextDueHumanReadable' => $this->nextDue()->diffForHumans(),
            'withoutOverlapping' => $this->withoutOverlapping,
            'onOneServer' => $this->onOneServer,
            'runInBackground' => $this->runInBackground,
            'evenInMaintenanceMode' => $this->evenInMaintenanceMode,
        ];
    }
}

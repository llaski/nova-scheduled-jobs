<?php

namespace Llaski\NovaScheduledJobs\Tests\Fixtures\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Llaski\NovaScheduledJobs\Tests\Fixtures\Processors\PodcastProcessor;

class ProcessPodcastWithDependencies implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $description = 'process the podcast';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(PodcastProcessor $processor)
    {
        $this->processor = $processor;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
    }
}

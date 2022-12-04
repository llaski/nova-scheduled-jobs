<?php

namespace Llaski\NovaScheduledJobs\Tests\Fixtures\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class LogUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:log-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Log Users';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info('Would log the users here...');
    }
}

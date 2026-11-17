<?php

namespace Llaski\NovaScheduledJobs\Tests\Fixtures\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:send-invoices', description: 'Send pending invoices')]
class SendInvoices extends Command
{
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return self::SUCCESS;
    }
}

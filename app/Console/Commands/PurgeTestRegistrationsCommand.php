<?php

namespace App\Console\Commands;

use App\Services\TestRegistrationPurgeService;
use Illuminate\Console\Command;

class PurgeTestRegistrationsCommand extends Command
{
    protected $signature = 'inquiries:purge-test';

    protected $description = 'Delete Site Settings auto-purge mobile registrations after 5 minutes';

    public function handle(TestRegistrationPurgeService $purge): int
    {
        $count = $purge->purgeDue();
        $this->info("Purged {$count} test registration(s).");

        return self::SUCCESS;
    }
}

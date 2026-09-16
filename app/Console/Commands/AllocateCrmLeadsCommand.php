<?php

namespace App\Console\Commands;

use App\Services\CrmAllocationService;
use Illuminate\Console\Command;

class AllocateCrmLeadsCommand extends Command
{
    protected $signature = 'crm:allocate';

    protected $description = 'Auto-assign unassigned registered members evenly to active CRM employees';

    public function handle(CrmAllocationService $allocation): int
    {
        $count = $allocation->allocateUnassigned();
        $this->info("Allocated {$count} member(s).");

        return self::SUCCESS;
    }
}

<?php

namespace App\Console\Commands;

use App\Models\MembershipUpload;
use Illuminate\Console\Command;

class FixMembershipUploadPhotos extends Command
{
    protected $signature = 'membership:fix-photos';

    protected $description = 'Copy legacy membership proof files from storage into public/uploads';

    public function handle(): int
    {
        $fixed = 0;
        $missing = 0;

        MembershipUpload::query()->whereNotNull('photo_path')->orderBy('id')->each(function (MembershipUpload $upload) use (&$fixed, &$missing) {
            if ($upload->ensurePublicPhoto()) {
                $fixed++;
                $this->line("Fixed #{$upload->id}: {$upload->photo_path}");

                return;
            }

            if ($upload->photoUrl()) {
                $this->line("OK #{$upload->id}: {$upload->photo_path}");

                return;
            }

            $missing++;
            $this->warn("Missing #{$upload->id}: {$upload->getRawOriginal('photo_path')}");
        });

        $this->info("Done. Fixed: {$fixed}. Still missing: {$missing}.");

        return self::SUCCESS;
    }
}

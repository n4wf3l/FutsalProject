<?php

namespace App\Console\Commands;

use App\Models\PlayerApplication;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PurgeExpiredApplications extends Command
{
    protected $signature = 'applications:purge-expired
        {--dry-run : Only report what would be deleted, do not actually delete}
        {--months=6 : Retention duration in months after reviewed_at}';

    protected $description = 'Delete rejected player applications older than the retention window (Loi 09-08 compliance)';

    public function handle(): int
    {
        $months = (int) $this->option('months');
        $dryRun = (bool) $this->option('dry-run');
        $threshold = now()->subMonths($months);

        $expired = PlayerApplication::where('status', PlayerApplication::STATUS_REJECTED)
            ->whereNotNull('reviewed_at')
            ->where('reviewed_at', '<', $threshold)
            ->get();

        $count = $expired->count();
        $this->info(sprintf(
            '%d rejected application(s) older than %d months (reviewed before %s)',
            $count,
            $months,
            $threshold->toDateString(),
        ));

        if ($count === 0) {
            return self::SUCCESS;
        }

        $cvDeleted = 0;
        foreach ($expired as $application) {
            if ($dryRun) {
                $this->line(" [dry-run] would delete #{$application->id} reviewed_at={$application->reviewed_at}");
                continue;
            }

            if ($application->cv_path && Storage::disk('local')->exists($application->cv_path)) {
                Storage::disk('local')->delete($application->cv_path);
                $cvDeleted++;
            }
            $application->delete();
        }

        if (! $dryRun) {
            Log::info('Purged expired player applications', [
                'count' => $count,
                'cv_files_deleted' => $cvDeleted,
                'retention_months' => $months,
            ]);
            $this->info("Deleted {$count} application(s) and {$cvDeleted} CV file(s).");
        }

        return self::SUCCESS;
    }
}

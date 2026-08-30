<?php

namespace App\Console\Commands;

use App\Models\AboutSection;
use App\Models\Article;
use App\Models\Coach;
use App\Models\Interview;
use App\Models\PressRelease;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class CleanContent extends Command
{
    protected $signature = 'content:clean
        {--dry-run : Preview changes without saving them}
        {--only= : Comma-separated list of model short names to restrict cleanup (e.g. Article,Interview)}';

    protected $description = 'Strip emojis and normalize long dashes across content-bearing models. Saves a JSON backup of the original rows per model before mutating.';

    /**
     * Map of model class to the list of text fields that may contain user-edited content
     * with emojis or long dashes. Add new entries here when new content tables appear.
     *
     * @var array<class-string<Model>, string[]>
     */
    protected array $targets = [
        AboutSection::class => ['title', 'content'],
        Article::class => ['title', 'description'],
        Interview::class => ['title', 'interviewee_role', 'interviewee_affiliation', 'excerpt', 'quote_highlight', 'content'],
        Coach::class => ['description'],
        PressRelease::class => ['title', 'content'],
    ];

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $onlyOption = $this->option('only');
        $onlyFilter = $onlyOption ? array_map('trim', explode(',', $onlyOption)) : null;

        $totalChanged = 0;
        $totalScanned = 0;
        $prefix = $dryRun ? '[DRY RUN] ' : '';

        foreach ($this->targets as $modelClass => $fields) {
            $shortName = class_basename($modelClass);

            if ($onlyFilter !== null && ! in_array($shortName, $onlyFilter, true)) {
                continue;
            }

            $this->line("");
            $this->line("<comment>Model {$shortName}</comment>");

            $rows = $modelClass::all();
            $totalScanned += $rows->count();

            if ($rows->isEmpty()) {
                $this->line("  no rows, skipped");
                continue;
            }

            $backup = $rows->map(function (Model $row) use ($fields) {
                $snap = ['id' => $row->getKey()];
                foreach ($fields as $f) {
                    $snap[$f] = $row->getAttribute($f);
                }
                return $snap;
            })->toArray();

            $modelChanged = 0;
            $changedIds = [];

            foreach ($rows as $row) {
                $rowChanged = false;
                foreach ($fields as $field) {
                    $original = $row->getAttribute($field);
                    $cleaned = $this->cleanText($original);
                    if ($cleaned !== $original) {
                        $row->setAttribute($field, $cleaned);
                        $rowChanged = true;
                    }
                }
                if ($rowChanged) {
                    $modelChanged++;
                    $changedIds[] = $row->getKey();
                    if (! $dryRun) {
                        $row->save();
                    }
                }
            }

            $totalChanged += $modelChanged;

            if ($modelChanged === 0) {
                $this->info("  {$prefix}nothing to clean out of {$rows->count()} row(s)");
                continue;
            }

            $this->info("  {$prefix}{$modelChanged}/{$rows->count()} row(s) modified: [".implode(', ', $changedIds).']');

            if (! $dryRun) {
                $backupPath = storage_path("app/backups/{$shortName}_".date('Y-m-d_His').'.json');
                File::ensureDirectoryExists(dirname($backupPath));
                File::put($backupPath, json_encode($backup, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
                $this->line("  backup: {$backupPath}");
            }
        }

        $this->line("");
        $this->info("{$prefix}Summary: {$totalChanged} row(s) modified across {$totalScanned} scanned");

        return self::SUCCESS;
    }

    protected function cleanText(?string $text): ?string
    {
        if ($text === null || $text === '') {
            return $text;
        }

        // Strip common emoji Unicode ranges: Emoticons, Symbols and Pictographs, Transport,
        // Supplemental Symbols, Misc Symbols, Dingbats, Misc Technical, Misc Symbols and Arrows,
        // Variation Selectors, and Zero-Width Joiner used in emoji sequences.
        $emojiPattern = '/['
            .'\x{1F000}-\x{1FFFF}'
            .'\x{2600}-\x{27BF}'
            .'\x{2300}-\x{23FF}'
            .'\x{2B00}-\x{2BFF}'
            .'\x{FE00}-\x{FE0F}'
            .'\x{200D}'
            .']/u';
        $text = preg_replace($emojiPattern, '', $text);

        // Replace em dash and en dash with a comma plus space.
        $text = str_replace(['—', '–'], ', ', $text);

        // Collapse leftover multi-spaces and multi-tabs to a single space.
        $text = preg_replace('/[ \t]+/u', ' ', $text);

        // Remove space introduced before punctuation.
        $text = preg_replace('/\s+([,.:;!?])/u', '$1', $text);

        // Remove whitespace right after an opening HTML tag.
        $text = preg_replace('/(<[^\/][^>]*>)\s+/u', '$1', $text);

        // Remove whitespace right before a closing HTML tag.
        $text = preg_replace('/\s+(<\/[^>]+>)/u', '$1', $text);

        return $text;
    }
}

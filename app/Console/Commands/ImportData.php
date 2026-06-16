<?php

namespace App\Console\Commands;

use App\Models\Period;
use App\Models\Play;
use App\Models\Playwright;
use App\Models\Season;
use App\Models\Venue;
use DirectoryIterator;
use FilesystemIterator;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use Spatie\YamlFrontMatter\YamlFrontMatter;

#[Signature('app:import-data')]
#[Description('Command description')]
class ImportData extends Command
{
    const string UNKNOWN = '___UNKNOWN___';
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        //
        $errors = 0;
        $successes = 0;
        $keys = [];
        $this->info('Importing data...');
        $storage = storage_path('history-project/_shows');
        $dirIterator = new RecursiveDirectoryIterator($storage);
        $dirIterator->setFlags(FilesystemIterator::SKIP_DOTS);
        foreach ($dirIterator as $file) {
            $this->info("Importing from $file");
            if ($file->getFilename() === '_skeleton.md') {
                continue;
            }
            $subDir = new RecursiveDirectoryIterator($file->getPathname());
            $subDir->setFlags(FilesystemIterator::SKIP_DOTS);
            foreach ($subDir as $subFile) {
                $this->info("Importing from $subFile");
                try {
                    $parsed = YamlFrontMatter::parseFile($subFile->getPathname());
                    Play::query()->create([
                        'title' => $parsed->matter('title'),
                        'season_id' => Season::query()->firstOrCreate([
                            'name' => $parsed->matter('season') ?? self::UNKNOWN,
                        ])->id,
                        'season_sort' => $parsed->matter('season_sort'),
                        'period_id' => Period::query()->firstOrCreate([
                            'name' => $parsed->matter('period') ?? self::UNKNOWN,
                        ])->id,
                        'venue_id' => Venue::query()->firstOrCreate([
                            'name' => $parsed->matter('venue') ?? self::UNKNOWN,
                        ])->id,
                        'playwright_id' => Playwright::query()->firstOrCreate([
                            'name' => $parsed->matter('playwright') ?? self::UNKNOWN,
                        ])->id,
                        'date_start' => $parsed->matter('date_start'),
                        'date_end' => $parsed->matter('date_end'),
                        'summary' => trim($parsed->body()),
                    ]);
                    foreach ($parsed->matter() as $key => $_) {
                        $keys[$key] ??= 0;
                        $keys[$key]++;
                    }
                    $successes++;
                } catch (\Throwable $e) {
                    $this->error("Error importing $subFile: {$e->getMessage()}");
                    $errors++;
                }
            }
        }
        arsort($keys);
        $tableKeys = array_map(fn ($key) => [$key, $keys[$key]], array_keys($keys));
        $this->table(['Key', 'Count'], $tableKeys);
        $this->info("Imported $successes files, $errors errors");
        return self::SUCCESS;
    }
}

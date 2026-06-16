<?php

namespace App\Console\Commands;

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
            if ($file->getFilename() === '_skeleton.md') {
                continue;
            }
            $subDir = new RecursiveDirectoryIterator($file->getPathname());
            $subDir->setFlags(FilesystemIterator::SKIP_DOTS);
            foreach ($subDir as $subFile) {
                try {
                    $parsed = YamlFrontMatter::parseFile($subFile->getPathname());
                    foreach ($parsed->matter() as $key => $_) {
                        $keys[$key] ??= 0;
                        $keys[$key]++;
                    }
                    $successes++;
                } catch (\Throwable $e) {
                    \report($e);
                    $errors++;
                }
            }
        }
        $tableKeys = array_map(fn ($key) => [$key, $keys[$key]], array_keys($keys));
        $this->table(['Key', 'Count'], $tableKeys);
        $this->info("Imported $successes files, $errors errors");
        return self::SUCCESS;
    }
}

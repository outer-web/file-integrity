<?php

namespace Outerweb\FileIntegrity\Commands;

use Illuminate\Console\Command;
use Outerweb\FileIntegrity\Facades\FileIntegrity;

class FileIntegrityCheckCommand extends Command
{
    public $signature = 'file-integrity:check
        {--truth= : JSON contents of the truth checksum }
        {--truth-path= : Path to the truth checksum file }
        {--source= : JSON contents of the source checksum }
        {--source-path= : Path to the source checksum file }
    ';

    public $description = 'Verify the file integrity between two checksums';

    public function handle(): int
    {
        $truth = $this->option('truth')
            ? collect(json_decode($this->option('truth'), true))
            : (
                $this->option('truth-path')
                    ? collect(json_decode(file_get_contents($this->option('truth-path')), true))
                    : collect(json_decode(file_get_contents(FileIntegrity::defaultOutputPath()), true))
            );

        if ($truth->isEmpty()) {
            $this->error('Truth checksum is empty or could not be loaded.');

            return self::FAILURE;
        }

        $source = $this->option('source')
            ? collect(json_decode($this->option('source'), true))
            : (
                $this->option('source-path')
                    ? collect(json_decode(file_get_contents($this->option('source-path')), true))
                    : FileIntegrity::generateChecksumCollection(FileIntegrity::collectFiles())
            );

        $modifiedFiles = FileIntegrity::detectModifiedFiles($truth, $source);
        $missingFiles = FileIntegrity::detectMissingFiles($truth, $source);
        $extraFiles = FileIntegrity::detectExtraFiles($truth, $source);

        if ($modifiedFiles->isEmpty() && $missingFiles->isEmpty() && $extraFiles->isEmpty()) {
            $this->info('File integrity check passed. No issues found.');

            return self::SUCCESS;
        }

        $this->error('File integrity check failed.');
        $this->line(json_encode([
            'modified' => $modifiedFiles->values()->all(),
            'missing' => $missingFiles->values()->all(),
            'extra' => $extraFiles->values()->all(),
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return self::FAILURE;
    }
}

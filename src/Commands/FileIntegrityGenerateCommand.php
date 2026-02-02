<?php

namespace Outerweb\FileIntegrity\Commands;

use Illuminate\Console\Command;
use Outerweb\FileIntegrity\Facades\FileIntegrity;

class FileIntegrityGenerateCommand extends Command
{
    public $signature = 'file-integrity:generate
        {--output=file : the way the checksum should be outputted. Possible values: file (store as a JSON file on disk), console (output as JSON in the console)}
        {--output-path= : the path to generate the checksum for. If not set, the default configured paths will be used}
    ';

    public $description = 'Generate a checksum for later file integrity verification';

    public function handle(): int
    {
        $checksum = FileIntegrity::generateChecksumCollection(FileIntegrity::collectFiles());

        if ($this->option('output') === 'file') {
            FileIntegrity::storeChecksumCollection($checksum, $this->option('output-path'));

            $this->info('Checksum file generated successfully at '.($this->option('output-path') ?? FileIntegrity::defaultOutputPath()).'.');

            return self::SUCCESS;
        }

        $this->line($checksum->toJson());

        return self::SUCCESS;
    }
}

<?php

namespace Outerweb\FileIntegrity;

use Outerweb\FileIntegrity\Commands\FileIntegrityCheckCommand;
use Outerweb\FileIntegrity\Commands\FileIntegrityGenerateCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FileIntegrityServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('file-integrity')
            ->hasConfigFile()
            ->hasCommands([
                FileIntegrityGenerateCommand::class,
                FileIntegrityCheckCommand::class,
            ]);
    }
}

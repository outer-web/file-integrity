<?php

namespace Outerweb\FileIntegrity\Facades;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use SplFileInfo;

/**
 * @method Collection<string, string> generateChecksumCollection(Collection<SplFileInfo> $files)
 * @method string generateChecksum(SplFileInfo $file)
 * @method void storeChecksumCollection(Collection<string, string> $checksumCollection, ?string $outputPath = null)
 * @method Collection<SplFileInfo> collectFiles()
 * @method string defaultOutputPath()
 * @method Collection<string, string> detectModifiedFiles(Collection<string, string> $truth, Collection<string, string> $source)
 * @method Collection<string, string> detectMissingFiles(Collection<string, string> $truth, Collection<string, string> $source)
 * @method Collection<string, string> detectExtraFiles(Collection<string, string> $truth, Collection<string, string> $source)
 *
 * @see \Outerweb\FileIntegrity\FileIntegrity
 */
class FileIntegrity extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Outerweb\FileIntegrity\FileIntegrity::class;
    }
}

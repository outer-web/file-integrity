<?php

namespace Outerweb\FileIntegrity;

use Illuminate\Container\Attributes\Singleton;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

#[Singleton]
class FileIntegrity
{
    /** @return Collection<string, string> */
    public function generateChecksumCollection(Collection $files): Collection
    {
        return $files->map(function (SplFileInfo $file): string {
            return $this->generateChecksum($file);
        });
    }

    public function generateChecksum(SplFileInfo $file): string
    {
        return hash_file('sha256', $file->getRealPath());
    }

    public function storeChecksumCollection(Collection $checksumCollection, ?string $outputPath = null): void
    {
        file_put_contents(
            $outputPath
                ?? $this->defaultOutputPath(),
            $checksumCollection->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

    /** @return Collection<string, SplFileInfo> */
    public function collectFiles(): Collection
    {
        return collect(
            Finder::create()
                ->files()
                ->ignoreDotFiles(false)
                ->in(Config::string('file-integrity.base_path', base_path()))
                ->notPath(Config::array('file-integrity.exclude_paths', []))
        );
    }

    public function defaultOutputPath(): string
    {
        return Config::string(
            'file-integrity.output_path',
            storage_path('file-integrity-checksum.json')
        );
    }

    /** @return Collection<int, string> */
    public function detectModifiedFiles(Collection $truth, Collection $source): Collection
    {
        return $source->filter(function (string $checksum, string $filePath) use ($truth): bool {
            return $truth->has($filePath) && $truth->get($filePath) !== $checksum;
        })->keys();
    }

    /** @return Collection<int, string> */
    public function detectMissingFiles(Collection $truth, Collection $source): Collection
    {
        return $truth->filter(function (string $checksum, string $filePath) use ($source): bool {
            return ! $source->has($filePath);
        })->keys();
    }

    /** @return Collection<int, string> */
    public function detectExtraFiles(Collection $truth, Collection $source): Collection
    {
        return $source->filter(function (string $checksum, string $filePath) use ($truth): bool {
            return ! $truth->has($filePath);
        })->keys();
    }
}

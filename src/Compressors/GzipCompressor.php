<?php

declare(strict_types=1);

namespace BackupManager\Compressors;

final class GzipCompressor implements Compressor
{
    #[\Override]
    public function handles(?string $type): bool
    {
        return strtolower($type ?? '') === 'gzip';
    }

    #[\Override]
    public function getCompressCommandLine(string $inputPath): string
    {
        return 'gzip ' . escapeshellarg($inputPath);
    }

    #[\Override]
    public function getDecompressCommandLine(string $inputPath): string
    {
        return 'gzip -d ' . escapeshellarg($inputPath);
    }

    #[\Override]
    public function getCompressedPath(string $inputPath): string
    {
        return $inputPath . '.gz';
    }

    #[\Override]
    public function getDecompressedPath(string $inputPath): string
    {
        return (string) preg_replace('/\.gz$/', '', $inputPath);
    }
}

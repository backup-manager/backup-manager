<?php

declare(strict_types=1);

namespace BackupManager\Compressors;

final class NullCompressor implements Compressor
{
    #[\Override]
    public function handles(?string $type): bool
    {
        return strtolower($type ?? '') === 'null';
    }

    #[\Override]
    public function getCompressCommandLine(string $inputPath): string
    {
        return '';
    }

    #[\Override]
    public function getDecompressCommandLine(string $inputPath): string
    {
        return '';
    }

    #[\Override]
    public function getCompressedPath(string $inputPath): string
    {
        return $inputPath;
    }

    #[\Override]
    public function getDecompressedPath(string $inputPath): string
    {
        return $inputPath;
    }
}

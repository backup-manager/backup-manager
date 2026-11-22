<?php

declare(strict_types=1);

namespace BackupManager\Compressors;

interface Compressor
{
    public function handles(?string $type): bool;

    public function getCompressCommandLine(string $inputPath): string;

    public function getDecompressCommandLine(string $inputPath): string;

    public function getCompressedPath(string $inputPath): string;

    public function getDecompressedPath(string $inputPath): string;
}

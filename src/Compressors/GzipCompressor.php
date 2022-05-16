<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Compressors;

use Fezfez\BackupManager\ShellProcessing\ShellProcessor;
use Symfony\Component\Process\Process;

use function escapeshellarg;
use function preg_replace;

class GzipCompressor implements Compressor
{
    private ShellProcessor $shellProcessor;

    public function __construct(?ShellProcessor $shellProcessor = null)
    {
        $this->shellProcessor = $shellProcessor ?? new ShellProcessor();
    }

    public function compress(string $path): string
    {
        $this->shellProcessor->__invoke(Process::fromShellCommandline('gzip ' . escapeshellarg($path)));

        return $this->getCompressedPath($path);
    }

    public function decompress(string $path): string
    {
        $this->shellProcessor->__invoke(Process::fromShellCommandline('gzip -d ' . escapeshellarg($path)));

        return $this->getDecompressedPath($path);
    }

    private function getCompressedPath(string $path): string
    {
        return $path . '.gz';
    }

    private function getDecompressedPath(string $path): string
    {
        return preg_replace('/\.gz$/', '', $path);
    }
}

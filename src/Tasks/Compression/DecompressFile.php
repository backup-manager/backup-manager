<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Tasks\Compression;

use Fezfez\BackupManager\Compressors\Compressor;

class DecompressFile
{
    public function __invoke(string $sourcePath, Compressor ...$compressorList): string
    {
        foreach ($compressorList as $compressor) {
            $sourcePath = $compressor->decompress($sourcePath);
        }

        return $sourcePath;
    }
}

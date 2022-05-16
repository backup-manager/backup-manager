<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Tasks\Compression;

use Fezfez\BackupManager\Compressors\Compressor;

class CompressFile
{
    public function __invoke(string $sourcePath, Compressor ...$compressorList): string
    {
        foreach ($compressorList as $compressor) {
            $sourcePath = $compressor->compress($sourcePath);
        }

        return $sourcePath;
    }
}

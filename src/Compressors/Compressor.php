<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Compressors;

interface Compressor
{
    public function compress(string $path): string;

    public function decompress(string $path): string;
}

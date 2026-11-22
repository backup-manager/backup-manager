<?php

declare(strict_types=1);

namespace BackupManager\Compressors;

class CompressorProvider
{
    /** @var list<Compressor> */
    private array $compressors = [];

    public function add(Compressor $compressor): void
    {
        $this->compressors[] = $compressor;
    }

    /**
     * @throws CompressorTypeNotSupported
     */
    public function get(?string $name): Compressor
    {
        foreach ($this->compressors as $compressor) {
            if ($compressor->handles($name)) {
                return $compressor;
            }
        }

        throw new CompressorTypeNotSupported("The requested compressor type {$name} is not currently supported.");
    }
}

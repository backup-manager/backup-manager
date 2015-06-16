<?php namespace BackupManager\Compressors;

/**
 * Class CompressorProvider
 * @package BackupManager\Compressors
 */
class CompressorProvider {

    /** @var array|Compressor[] */
    private $compressors = [];

    /**
     * @param Compressor $compressor
     */
    public function add(Compressor $compressor) {
        $this->compressors[] = $compressor;
    }

    /**
     * @param $name
     * @return Compressor
     * @throws CompressorTypeNotSupported
     */
    public function get($name) {
        foreach ($this->compressors as $compressor) {
            if ($compressor->handles($name)) {
                return $compressor;
            }
        }
        throw new CompressorTypeNotSupported("The requested compressor type {$name} is not currently supported.");
    }
}

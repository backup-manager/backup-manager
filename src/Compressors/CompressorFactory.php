<?php namespace BackupManager\Compressors;

use BackupManager\Shell\ShellProcessor;

class CompressorFactory {

    /** @var ShellProcessor */
    private $shell;

    public function __construct(ShellProcessor $shell) {
        $this->shell = $shell;
    }

    public function make($type) {
        switch (strtolower($type)) {
            case 'gzip':
                return $this->makeGzipCompressor();
            case 'null':
                return $this->makeNullCompressor();
        }
    }

    private function makeGzipCompressor() {
        return new GzipCompressor($this->shell);
    }

    private function makeNullCompressor() {
        return new NullCompressor;
    }
}

<?php namespace BackupManager\Compressors;

use BackupManager\File;

class NullCompressor implements Compressor {

    /**
     * @param File $file
     * @return void
     */
    public function compress(File $file) {
        return null;
    }

    /**
     * @param File $file
     * @return void
     */
    public function decompress(File $file) {
        return null;
    }

    /**
     * @param File $file
     * @return File
     */
    public function compressedFile(File $file) {
        return $file;
    }

    /**
     * @param File $file
     * @return File
     */
    public function decompressedFile(File $file) {
        return $file;
    }
}
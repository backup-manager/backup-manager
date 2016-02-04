<?php namespace BackupManager\Compressors;

use BackupManager\File;

interface Compressor {

    /**
     * @param File $file
     * @return void
     */
    public function compress(File $file);

    /**
     * @param File $file
     * @return void
     */
    public function decompress(File $file);

    /**
     * @param File $file
     * @return File
     */
    public function compressedFile(File $file);

    /**
     * @param File $file
     * @return File
     */
    public function decompressedFile(File $file);
}
<?php namespace BackupManager\Compressors;

use BackupManager\Shell\ShellCommand;
use BackupManager\Shell\ShellProcessFailed;
use BackupManager\Shell\ShellProcessor;
use BackupManager\File;

class GzipCompressor implements Compressor {

    /** @var ShellProcessor */
    private $shell;

    public function __construct(ShellProcessor $shell) {
        $this->shell = $shell;
    }

    /**
     * @param File $file
     * @return string
     * @throws ShellProcessFailed
     */
    public function compress(File $file) {
        $command = new ShellCommand('gzip ' . escapeshellarg($file->path()));
        $this->shell->process($command);
    }

    /**
     * @param File $file
     * @return string
     * @throws ShellProcessFailed
     */
    public function decompress(File $file) {
        $command = new ShellCommand('gunzip ' . escapeshellarg($file->path()));
        $this->shell->process($command);
    }

    /**
     * @param File $file
     * @return File
     */
    public function compressedFile(File $file) {
        return new File("{$file->path()}.gz");
    }

    /**
     * @param File $file
     * @return File
     */
    public function decompressedFile(File $file) {
        return new File(preg_replace('/.gz$/', '', $file->path()));
    }
}
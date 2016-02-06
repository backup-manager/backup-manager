<?php namespace BackupManager\Config;

use BackupManager\File;
use League\Flysystem\FilesystemInterface;
use Symfony\Component\Yaml\Parser;

class YamlConfigReader implements ConfigReader {

    /** @var FilesystemInterface */
    private $filesystem;
    /** @var Parser */
    private $parser;

    public function __construct(FilesystemInterface $filesystem, Parser $parser) {
        $this->filesystem = $filesystem;
        $this->parser = $parser;
    }
    
    /**
     * @param File $file
     * @return Config
     */
    public function read(File $file) {
        $yaml = $this->filesystem->read($file->filePath());
        return new Config($this->parser->parse($yaml));
    }
}
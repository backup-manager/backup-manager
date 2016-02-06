<?php namespace BackupManager\Config;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Symfony\Component\Yaml\Parser;

class ConfigReaderFactory {

    public function make($type) {
        switch (strtolower($type)) {
            case 'yml':
            case 'yaml':
                return $this->makeYamlReader();
            default:
                throw new ConfigReaderTypeDoesNotExist($type);
                break;
        }
    }

    /**
     * @return YamlConfigReader
     */
    private function makeYamlReader() {
        $filesystem = new Filesystem(new Local(getcwd()));
        $parser = new Parser;
        return new YamlConfigReader($filesystem, $parser);
    }
}

<?php

namespace BackupManager\Filesystems;

final class Destination
{
    /** @var string */
    private $destination_filesystem;

    /** @var string */
    private $destination_path;

    /**
     * @param string $a_destination_filesystem
     * @param string $a_destination_path
     */
    public function __construct($a_destination_filesystem, $a_destination_path) {
        $this->destination_filesystem = $a_destination_filesystem;
        $this->destination_path = $a_destination_path;
    }

    /**
     * @return string
     */
    public function destinationFilesystem() {
        return $this->destination_filesystem;
    }

    /**
     * @return string
     */
    public function destinationPath() {
        return $this->destination_path;
    }
}

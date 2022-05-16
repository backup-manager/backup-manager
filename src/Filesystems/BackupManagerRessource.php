<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Filesystems;

use function is_resource;

class BackupManagerRessource
{
    /** @var resource */
    private $resource;

    public function __construct(mixed $resource)
    {
        if (! is_resource($resource)) {
            throw new NotARessource();
        }

        $this->resource = $resource;
    }

    /**
     * @return resource
     */
    public function getResource()
    {
        return $this->resource;
    }
}

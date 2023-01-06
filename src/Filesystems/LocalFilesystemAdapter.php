<?php

declare(strict_types=1);

namespace Fezfez\BackupManager\Filesystems;

interface LocalFilesystemAdapter extends BackupManagerFilesystemAdapter
{
    public function getRootPath(): string;
}

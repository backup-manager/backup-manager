<?php namespace McCool\DatabaseBackup\Procedures;

use McCool\DatabaseBackup\Archivers\Archiver;
use McCool\DatabaseBackup\Downloaders\Downloader;
use McCool\DatabaseBackup\NoValidArchiverFoundException;

class RestoreProcedure
{
    /**
     * @var RestorerInterface
     */
    private $restorer;
    /**
     * @var \McCool\DatabaseBackup\Downloaders\Downloader
     */
    private $downloader;
    /**
     * @var string
     */
    private $storagePath;
    /**
     * @var Archiver[]
     */
    private $archivers = [];

    /**
     * @param RestorerInterface $restorer
     * @param Downloader $downloader
     * @param $storagePath
     */
    public function __construct(RestorerInterface $restorer, Downloader $downloader, $storagePath)
    {
        $this->restorer = $restorer;
        $this->downloader = $downloader;
        $this->storagePath = $storagePath;
    }

    /**
     * @param Archiver $archiver
     */
    public function addArchiver(Archiver $archiver)
    {
        $this->archivers[] = $archiver;
    }

    /**
     * @param $database
     * @param $filePath
     */
    public function restore($database, $filePath)
    {
        $this->downloader->download($filePath, $this->storagePath);
        $localPath = $this->getLocalPath($filePath);
        $extractedFile = $this->extractArchive($localPath);
        $this->restorer->restore($database, $extractedFile);
    }

    /**
     * @param $filePath
     * @return string
     */
    private function getLocalPath($filePath)
    {
        $path = pathinfo($filePath);
        return $this->storagePath . '/' . $path['filename'];
    }

    /**
     * @param $filePath
     * @return mixed
     * @throws \McCool\DatabaseBackup\NoValidArchiverFoundException
     */
    private function extractArchive($filePath)
    {
        foreach ($this->archivers as $archiver) {
            if ($archiver->canHandleFile($filePath)) {
                return $archiver->extract($filePath);
            }
        }
    }
}

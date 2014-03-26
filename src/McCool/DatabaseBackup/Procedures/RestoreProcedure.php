<?php namespace McCool\DatabaseBackup\Procedures;

use McCool\DatabaseBackup\Archivers\ArchiverInterface;
use McCool\DatabaseBackup\Downloaders\DownloaderInterface;
use McCool\DatabaseBackup\NoValidArchiverFoundException;

class RestoreProcedure
{
    /**
     * @var RestorerInterface
     */
    private $restorer;
    /**
     * @var \McCool\DatabaseBackup\Downloaders\DownloaderInterface
     */
    private $downloader;
    /**
     * @var string
     */
    private $storagePath;
    /**
     * @var ArchiverInterface[]
     */
    private $archivers = [];

    /**
     * @param RestorerInterface $restorer
     * @param DownloaderInterface $downloader
     * @param $storagePath
     */
    public function __construct(RestorerInterface $restorer, DownloaderInterface $downloader, $storagePath)
    {
        $this->restorer = $restorer;
        $this->downloader = $downloader;
        $this->storagePath = $storagePath;
    }

    /**
     * @param ArchiverInterface $archiver
     */
    public function addArchiver(ArchiverInterface $archiver)
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

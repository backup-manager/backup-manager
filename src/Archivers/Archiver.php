<?php namespace McCool\DatabaseBackup\Archivers;

interface Archiver
{
    /**
     * @return array of file extensions
     */
    public function handlesFileExtensions();

    /**
     * @param $filePath
     * @return mixed
     */
    public function canHandleFile($filePath);

    /**
     * Executes the backup command.
     * @params string $filePath
     * @throws \McCool\DatabaseBackup\Exceptions\FailingShellProcess
     */
    public function archive($filePath);

    /**
     * @param $filePath to extract
     * @return string filename of the extracted file
     * @throws \McCool\DatabaseBackup\Exceptions\FailingShellProcess
     */
    public function extract($filePath);
}

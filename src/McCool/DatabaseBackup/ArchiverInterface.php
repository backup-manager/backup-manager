<?php namespace McCool\DatabaseBackup\Archivers;

interface ArchiverInterface
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
     * @throws \McCool\DatabaseBackup\Shell\ShellProcessorException
     */
    public function archive($filePath);

    /**
     * @param $filePath to extract
     * @return string filename of the extracted file
     * @throws \McCool\DatabaseBackup\Shell\ShellProcessorException
     */
    public function extract($filePath);
}

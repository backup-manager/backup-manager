<?php namespace BackupManager\Databases;

/**
 * Class MongoDatabase
 * @package BackupManager\Databases
 */
class MongoDatabase implements Database {

    /** @var array */
    private $config;

    /**
     * @param $type
     * @return bool
     */
    public function handles($type) {
        return strtolower($type) == 'mongodb';
    }

    /**
     * @param array $config
     * @return null
     */
    public function setConfig(array $config) {
        $this->config = $config;
    }

    /**
     * @param $outputPath
     * @return string
     */
    public function getDumpCommandLine($outputPath) {
    	$archive_name = 'mongodump.tar.gz';
        if(!array_key_exists('auth_db', $this->config)) {
            //set default authentication database
            $this->config['auth_db'] = 'admin';
        }

        //mongodump 
    	//dump and create archive (=> creates single file) - then remove the seperate files
        return sprintf('mongodump --quiet -h %s:%s -u %s -p %s -d %s -o %s --authenticationDatabase %s && tar -zcf %s %s && find %s ! -name %s -type d -exec rm -f -r {} +',
            escapeshellarg($this->config['host']),
            escapeshellarg($this->config['port']),
            escapeshellarg($this->config['user']),
            escapeshellarg($this->config['pass']),
            escapeshellarg($this->config['database']),
            escapeshellarg($outputPath),
            escapeshellarg($this->config['auth_db']),
            //values for archiving:
            escapeshellarg($archive_name),
            escapeshellarg($outputPath),
            escapeshellarg($outputPath.'/.'),
            escapeshellarg($archive_name)
        );
    }

    /**
     * @param $inputPath
     * @return string
     */
    public function getRestoreCommandLine($inputPath) {
        throw new Exception("This method is not implemented", 500);
    }
}

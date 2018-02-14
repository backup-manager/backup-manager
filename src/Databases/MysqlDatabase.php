<?php namespace BackupManager\Databases;

/**
 * Class MysqlDatabase
 * @package BackupManager\Databases
 */
class MysqlDatabase implements Database {

    /** @var array */
    private $config;

    /**
     * @param $type
     * @return bool
     */
    public function handles($type) {
        return strtolower($type) == 'mysql';
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
    	$extras = [];
    	if (array_key_exists('singleTransaction', $this->config) && $this->config['singleTransaction'] === true) {
    		$extras[] = '--single-transaction';
    	}
        if (array_key_exists('ignoreTables', $this->config)) {
            $extras[] = $this->getIgnoreTableParameter();
        }
        if (array_key_exists('ssl', $this->config) && $this->config['ssl'] === true) {
    		$extras[] = '--ssl';
    	}
        if (array_key_exists('extraParams', $this->config) && $this->config['extraParams']) {
            $extras[] = $this->config['extraParams'];
        }

    	// Prepare a "params" string from our config
    	$params = '';
    	$keys = ['host'=>'host', 'port'=>'port', 'user'=>'user', 'pass'=>'password'];
    	foreach ($keys as $key => $mysqlParam) {
    	    if (!empty($this->config[$key])) {
    	        $params.=sprintf(' --%s=%s', $mysqlParam, escapeshellarg($this->config[$key]));
            }
        }

    	$command = 'mysqldump --routines '.implode(' ', $extras).'%s %s > %s';
        return sprintf($command,
            $params,
            escapeshellarg($this->config['database']),
            escapeshellarg($outputPath)
        );
    }

    /**
     * @param $inputPath
     * @return string
     */
    public function getRestoreCommandLine($inputPath) {
        $extras = [];
        if (array_key_exists('ssl', $this->config) && $this->config['ssl'] === true) {
    		$extras[] = '--ssl';
    	}

        // Prepare a "params" string from our config
        $params = '';
        $keys = ['host'=>'host', 'port'=>'port', 'user'=>'user', 'pass'=>'password'];
        foreach ($keys as $key => $mysqlParam) {
            if (!empty($this->config[$key])) {
                $params.=sprintf(' --%s=%s', $mysqlParam, escapeshellarg($this->config[$key]));
            }
        }

        return sprintf('mysql%s '.implode(' ', $extras).' %s -e "source %s"',
            $params,
            escapeshellarg($this->config['database']),
            $inputPath
        );
    }

    /**
     * @return string
     */
    public function getIgnoreTableParameter() {

        if (!is_array($this->config['ignoreTables']) || count($this->config['ignoreTables']) === 0) {
            return '';
        }

        $db = $this->config['database'];
        $ignoreTables = array_map(function($table) use ($db) {
            return $db.'.'.$table;
        }, $this->config['ignoreTables']);

        $commands=[];
        foreach($ignoreTables AS $ignoreTable) {
            $commands[]=sprintf('--ignore-table=%s',
                escapeshellarg($ignoreTable)
            );
        }
        
        return implode(' ',$commands);
    }
}

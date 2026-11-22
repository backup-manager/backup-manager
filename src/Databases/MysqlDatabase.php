<?php namespace BackupManager\Databases;

/**
 * Class MysqlDatabase
 * @package BackupManager\Databases
 */
class MysqlDatabase implements Database
{
    /** @var array */
    private $config;

    /**
     * @param $type
     * @return bool
     */
    public function handles($type)
    {
        return in_array(strtolower($type ?? ''), ['mysql', 'pdo_mysql'], true);
    }

    /**
     * @param array $config
     * @return null
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param $outputPath
     * @return string
     */
    public function getDumpCommandLine($outputPath)
    {
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
        if (array_key_exists('columnStatistics', $this->config) && $this->config['columnStatistics'] === false) {
            // MySQL 8 mysqldump queries column statistics that older servers and MariaDB lack.
            $extras[] = '--column-statistics=0';
        }
        if (array_key_exists('extraParams', $this->config) && $this->config['extraParams']) {
            $extras[] = $this->config['extraParams'];
        }

        $params = $this->getConnectionParameters();

        $command = 'mysqldump --routines ' . implode(' ', $extras) . '%s %s > %s';
        return sprintf(
            $command,
            $params,
            escapeshellarg($this->config['database']) . $this->getOnlyTablesArguments(),
            escapeshellarg($outputPath)
        );
    }

    /**
     * @param $inputPath
     * @return string
     */
    public function getRestoreCommandLine($inputPath)
    {
        $extras = [];
        if (array_key_exists('ssl', $this->config) && $this->config['ssl'] === true) {
            $extras[] = '--ssl';
        }

        $params = $this->getConnectionParameters();

        return sprintf(
            'mysql%s ' . implode(' ', $extras) . ' %s -e "source %s"',
            $params,
            escapeshellarg($this->config['database']),
            $inputPath
        );
    }

    /**
     * Connection options for mysqldump and mysql. A configured socket replaces
     * host and port, as with Laravel's unix_socket.
     *
     * @return string
     */
    private function getConnectionParameters()
    {
        $keys = ['host' => 'host', 'port' => 'port', 'user' => 'user', 'pass' => 'password'];
        if (!empty($this->config['socket'])) {
            $keys = ['socket' => 'socket', 'user' => 'user', 'pass' => 'password'];
        }

        $params = '';
        foreach ($keys as $key => $mysqlParam) {
            if (!empty($this->config[$key])) {
                $params .= sprintf(' --%s=%s', $mysqlParam, escapeshellarg($this->config[$key]));
            }
        }

        return $params;
    }

    /**
     * Table names after the database name restrict the dump to those tables.
     *
     * @return string
     */
    private function getOnlyTablesArguments()
    {
        if (empty($this->config['onlyTables']) || !is_array($this->config['onlyTables'])) {
            return '';
        }

        return ' ' . implode(' ', array_map('escapeshellarg', $this->config['onlyTables']));
    }

    /**
     * @return string
     */
    public function getIgnoreTableParameter()
    {
        if (!is_array($this->config['ignoreTables']) || count($this->config['ignoreTables']) === 0) {
            return '';
        }

        $db = $this->config['database'];
        $ignoreTables = array_map(function ($table) use ($db) {
            return $db . '.' . $table;
        }, $this->config['ignoreTables']);

        $commands = [];
        foreach ($ignoreTables as $ignoreTable) {
            $commands[] = sprintf(
                '--ignore-table=%s',
                escapeshellarg($ignoreTable)
            );
        }

        return implode(' ', $commands);
    }
}

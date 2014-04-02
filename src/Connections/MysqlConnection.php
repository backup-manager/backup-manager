<?php namespace BigName\DatabaseBackup\Connections;

class MysqlConnection
{
    /**
     * @var string
     */
    public $host;
    /**
     * @var string
     */
    public $port;
    /**
     * @var string
     */
    public $username;
    /**
     * @var string
     */
    public $password;
    /**
     * @var string
     */
    public $database;

    /**
     * @param $host
     * @param $port
     * @param $username
     * @param $password
     * @param $database
     */
    public function __construct($host, $port, $username, $password, $database)
    {
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
    }
} 

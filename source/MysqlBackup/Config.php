<?php
namespace MysqlBackup;

class Config
{

    /** @var string */
    private $dbHost;

    /** @var string */
    private $dbPort;

    /** @var string */
    private $dbUser;

    /** @var string */
    private $dbPassword;

    /** @var string */
    private $dbName;

    /**
     * Directory with configuration file
     * @var string
     */
    private $workDir;

    /** @var string */
    private $targetBackupDir;

    public function __construct()
    {
        
    }

    private function read()
    {
        
    }
}

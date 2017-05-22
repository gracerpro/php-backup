<?php
namespace MysqlBackup;

class Config
{

    /** @var string */
    private $dbHost;

    /** @var string */
    private $dbPort;

    /** @var string */
    private $dbCharset;

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

    /** @var bool */
    private $moveArchiveToStorage;

    /** @var string */
    private $storageDiskDir;

    /** @var string */
    private $storageType;

    /** @var Config */
    private static $instance;

    private function __construct()
    {
        $this->targetBackupDir = "~";
        $this->workDir = '.';
    }

    /**
     * @return Config
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Config();
        }
        return self::$instance;
    }

    public function read($config)
    {
        $output = ConsoleOutput::getInstance();
        $output->printMessage("Read config parameters...");

        if (!is_array($config)) {
            throw new BackupException("The configuration must be an array.");
        }

        foreach ($config as $name => $value) {
            if (property_exists($this, $name)) {
                $this->{$name} = $value;
            }
        }
    }

    function getDbHost()
    {
        return $this->dbHost;
    }

    function getDbPort()
    {
        return $this->dbPort;
    }

    function getDbUser()
    {
        return $this->dbUser;
    }

    function getDbPassword()
    {
        return $this->dbPassword;
    }

    function getDbName()
    {
        return $this->dbName;
    }

    function getWorkDir()
    {
        return $this->workDir;
    }

    function getTargetBackupDir()
    {
        return $this->targetBackupDir;
    }

    function getDbCharset()
    {
        return $this->dbCharset;
    }

    public function getStorageDiskDir()
    {
        return $this->storageDiskDir;
    }

    public function setStorageDiskDir($dir)
    {
        $this->storageDiskDir = $dir;
        return $this;
    }

    public function getStorageType()
    {
        return $this->storageType;
    }

    public function setStorageType($storageType)
    {
        $this->storageType = $storageType;
        return $this;
    }

    public function getMoveArchiveToStorage()
    {
        return $this->moveArchiveToStorage;
    }

    public function setMoveArchiveToStorage($moveArchiveToStorage)
    {
        $this->moveArchiveToStorage = $moveArchiveToStorage;
        return $this;
    }
}

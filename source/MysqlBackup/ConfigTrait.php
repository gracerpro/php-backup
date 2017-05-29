<?php
namespace MysqlBackup;

trait ConfigTrait
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
    private $mysqlDumpOptions;

    /** @var string */
    private $storageType;

    /** @var string */
    private $storageDiskDir;

    /** @var string */
    private $storageYandexDiskToken;

    /** @var string */
    private $storageYandexDiskDir;

    /** @var bool */
    private $removeArchiveAfterSync;

    /** @var string */
    private $targetBackupDir;

    /** @var bool */
    private $debug;

    public function getMysqlDumpOptions()
    {
        return $this->mysqlDumpOptions;
    }

    public function getRemoveArchiveAfterSync()
    {
        return $this->removeArchiveAfterSync;
    }

    public function setMysqlDumpOptions($mysqlDumpOptions)
    {
        $this->mysqlDumpOptions = $mysqlDumpOptions;
        return $this;
    }

    public function setRemoveArchiveAfterSync($removeArchiveAfterSync)
    {
        $this->removeArchiveAfterSync = $removeArchiveAfterSync;
        return $this;
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
        return $this->removeArchiveAfterSync;
    }

    public function setMoveArchiveToStorage($removeArchiveAfterSync)
    {
        $this->removeArchiveAfterSync = $removeArchiveAfterSync;
        return $this;
    }

    public function getStorageYandexDiskToken()
    {
        return $this->storageYandexDiskToken;
    }

    public function setStorageYandexDiskToken($storageYandexDiskToken)
    {
        $this->storageYandexDiskToken = $storageYandexDiskToken;
        return $this;
    }

    public function getDebug()
    {
        return $this->debug;
    }

    public function setDebug($debug)
    {
        $this->debug = $debug;
        return $this;
    }

    public function getStorageYandexDiskDir()
    {
        return $this->storageYandexDiskDir;
    }

    public function setStorageYandexDiskDir($storageYandexDiskDir)
    {
        $this->storageYandexDiskDir = $storageYandexDiskDir;
        return $this;
    }
}

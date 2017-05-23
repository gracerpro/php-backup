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
    public $mysqlDumpOptions;

    /** @var string */
    public $storageType;

    /** @var string */
    public $storageDiskDir;

    /** @var bool */
    public $removeArchiveAfterSync;

    /** @var string */
    private $targetBackupDir;

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
}

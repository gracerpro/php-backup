<?php
namespace PhpBackup;

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

    /** @var string */
    private $storageGoogleDriveFolderId;

    /** @var string */
    private $storageGoogleDriveKeyFile;

    /** @var bool */
    private $removeArchiveAfterSync;

    /** @var string */
    private $tempBackupDir;

    /** @var bool */
    private $debug;

    /** @var string */
    private $backupTargetProjectDir;

    /** @var string */
    private $backupTargetDirectories;

    /** @var string */
    private $backupTargetDirName;

    /** @var int Time in days, if today - file date >= this field then backup will be removed. */
    private $cleanActiveDay;

    /** @var bool */
    private $runBackupFilesAction = false;

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

    function getTempBackupDir()
    {
        return $this->tempBackupDir;
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

    public function getCleanActiveDay()
    {
        return $this->cleanActiveDay;
    }

    public function setCleanActiveDay($cleanActiveDay)
    {
        $this->cleanActiveDay = $cleanActiveDay;
        return $this;
    }

    public function getStorageGoogleDriveFolderId()
    {
        return $this->storageGoogleDriveFolderId;
    }

    public function setStorageGoogleDriveFolderId($folderId)
    {
        $this->storageGoogleDriveFolderId = $folderId;
        return $this;
    }

    /**
     * @return string File path to key file
     */
    public function getStorageGoogleDriveKeyFile()
    {
        if (is_file($this->storageGoogleDriveKeyFile)) {
            return $this->storageGoogleDriveKeyFile;
        }
        $name = basename($this->storageGoogleDriveKeyFile);
        $filePath = \PhpBackup\PhpBackup::getRuningDir() . '/' . $name;
        if (is_file($filePath)) {
            return $filePath;
        }
        return '';
    }

    public function setStorageGoogleDriveKeyFile($file)
    {
        $this->storageGoogleDriveKeyFile = $file;
        return $this;
    }

    public function getBackupTargetDirName()
    {
        return $this->backupTargetDirName;
    }

    public function setBackupTargetDirName($value)
    {
        $this->backupTargetDirName = $value;
        return $this;
    }

    public function getBackupTargetDirectories()
    {
        return $this->backupTargetDirectories;
    }

    public function setBackupTargetDirectories($value)
    {
        if (is_array($value)) {
            $this->backupTargetDirectories = $value;
        } else {
            $this->backupTargetDirectories = [$value];
        }

        return $this;
    }

    public function getBackupTargetProjectDir()
    {
        return $this->backupTargetProjectDir;
    }

    public function setBackupTargetProjectDir($backupTargetProjectDir)
    {
        $this->backupTargetProjectDir = $backupTargetProjectDir;
        return $this;
    }
    
    function getRunBackupFilesAction(): bool
    {
        return $this->runBackupFilesAction;
    }

    function setRunBackupFilesAction($value = true)
    {
        $this->runBackupFilesAction = (bool)$value;
        return $this;
    }
}

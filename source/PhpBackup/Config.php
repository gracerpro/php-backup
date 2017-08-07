<?php
namespace PhpBackup;

class Config
{

    use ConfigTrait;

    /** @var Config */
    private static $instance;

    private function __construct()
    {
        $this->tempBackupDir = "~";
        $this->workDir = '.';

        $this->dbHost = '127.0.0.1';
        $this->dbPort = 3306;
        $this->dbUser = '';
        $this->dbPassword = '';
        $this->dbName = '';
        $this->dbCharset = 'utf8';

        $this->removeArchiveAfterSync = false;
        $this->storageType = BackupStorageFactory::STORAGE_DISK;

        $this->backupTargetDirName = '';
        $this->backupTargetDirectories = [];
        $this->backupTargetProjectDir = '';

        // disk
        $this->storageDiskDir = '';

        // yandex disk
        // https://oauth.yandex.ru/authorize?response_type=token&client_id=xxx
        $this->storageYandexDiskToken = '';
        $this->storageYandexDiskDir = '';

        // google drive
        $this->storageGoogleDriveKeyFile = 'google-drive-key.json';
        $this->storageGoogleDriveFolderId = '';
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

        if (isset($config['backupTargetDir'])) {
            if (is_array($config['backupTargetDir'])) {
                $this->backupTargetDirectories = $config['backupTargetDir'];
            } else {
                $this->backupTargetDirectories = [$config['backupTargetDir']];
            }
        }
    }
}

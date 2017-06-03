<?php
namespace MysqlBackup\Storages;

use MysqlBackup\Config;
use MysqlBackup\BackupCreator;
use Yandex\Disk\DiskClient;

class YandexDiskStorage implements StorageInterface
{

    /**
     * @param BackupCreator $creator
     * @throws \Yandex\Disk\Exception\DiskRequestException
     * @return boolean
     */
    public function save(BackupCreator $creator)
    {
        $consoleOut = \MysqlBackup\ConsoleOutput::getInstance();
        $config = Config::getInstance();

        if (!$config->getStorageYandexDiskDir()) {
            throw new \MysqlBackup\BackupException("Empty yandex disk target directory.");
        }
        $targetDir = $this->validateDir($config->getStorageYandexDiskDir());
        $disk = new DiskClient();
        if ($config->getDebug()) {
            $disk->setDebug(true);
        }
        $disk->setAccessToken($config->getStorageYandexDiskToken());
        $filePath = $creator->getBackupZippedFilePath();
        if (!file_exists($filePath)) {
            $consoleOut->printMessage("Archive not exists");
            return false;
        }
        $disk->setServiceScheme(DiskClient::HTTPS_SCHEME);
        $uploadParams = [
            'path' => $filePath,
            'size' => filesize($filePath),
            'name' => basename($filePath)
        ];
        $consoleOut->printMessage("Upload to yandex disk...");
        $consoleOut->printMessage("to directory: " . $targetDir);
        $disk->uploadFile($targetDir, $uploadParams);
        $consoleOut->printMessage("Ok");

        return true;
    }

    public function removeOldBackups(BackupCreator $creator)
    {
        throw new \MysqlBackup\BackupException(__METHOD__ . " TODO");
    }

    private function validateDir($dir)
    {
        return "/" . trim($dir, " \t\n\t/\\") . "/";
    }
}

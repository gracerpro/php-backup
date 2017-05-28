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
        $disk->uploadFile('/wowtransfer.com-db/', $uploadParams);
        $consoleOut->printMessage("Ok");

        return true;
    }
}

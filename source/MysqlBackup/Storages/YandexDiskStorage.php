<?php

namespace MysqlBackup\Storages;

use MysqlBackup\Config;
use MysqlBackup\BackupCreator;
use Yandex\Disk\DiskClient;

class YandexDiskStorage implements StorageInterface
{
    
    public function save(BackupCreator $creator)
    {
        $config = Config::getInstance();
        $disk = new DiskClient();
        $disk->setAccessToken($config->getStorageYandexDiskToken());
        $filePath = $creator->getBackupZippedFilePath();
        $disk->setServiceScheme(DiskClient::HTTPS_SCHEME);
        $uploadParams = [
            'path' => $filePath,
            'size' => filesize($filePath),
            'name' => '1.zip'
        ];
        var_dump($uploadParams);
        $r = $disk->uploadFile('/backup', $uploadParams);
        var_dump($r);
    }
}

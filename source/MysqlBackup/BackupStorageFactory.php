<?php
namespace MysqlBackup;

use MysqlBackup\Storages\DiskStorage;
use MysqlBackup\Storages\YandexDiskStorage;
use MysqlBackup\Storages\StorageInterface;

class BackupStorageFactory
{

    const STORAGE_DISK = 'disk';
    const STORAGE_FTP = 'ftp';
    const STORAGE_YANDEX_DISK = 'yandex_disk';
    const STORAGE_MAIL_CLOUD = 'mail_cloud';
    const STORAGE_DEFAULT = self::STORAGE_DISK;

    /**
     * @return DiskStorage
     */
    public function createDiskStorage()
    {
        return $this->create(self::STORAGE_DISK);
    }

    /**
     * @param string $storageType
     * @return StorageInterface
     * @throws Exception
     */
    public function create($storageType)
    {
        switch ($storageType) {
            case self::STORAGE_DISK:
                return new DiskStorage();
            case self::STORAGE_YANDEX_DISK:
                return new YandexDiskStorage();
            default:
                throw new \MysqlBackup\BackupException("Bad storage type");
        }
    }
    
}

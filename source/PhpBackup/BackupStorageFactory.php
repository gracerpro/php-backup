<?php
namespace PhpBackup;

use PhpBackup\Storages\DiskStorage;
use PhpBackup\Storages\YandexDiskStorage;
use PhpBackup\Storages\StorageInterface;
use PhpBackup\Storages\GoogleDriveStorage;

class BackupStorageFactory
{

    const STORAGE_DISK = 'disk';
    const STORAGE_FTP = 'ftp';
    const STORAGE_YANDEX_DISK = 'yandex_disk';
    const STORAGE_GOOGLE_DRIVE = 'google_disk';
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
     * @return StorageInterface
     * @param string $storageType
     * @throws BackupException
     */
    public function create($storageType)
    {
        switch ($storageType) {
            case self::STORAGE_DISK:
                return new DiskStorage();
            case self::STORAGE_YANDEX_DISK:
                return new YandexDiskStorage();
            case self::STORAGE_GOOGLE_DRIVE:
                return new GoogleDriveStorage();
            default:
                throw new \PhpBackup\BackupException("Bad storage type");
        }
    }
    
}

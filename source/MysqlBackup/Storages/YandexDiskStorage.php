<?php
namespace MysqlBackup\Storages;

use MysqlBackup\Config;
use MysqlBackup\CreatorInterface;
use Yandex\Disk\DiskClient;
use MysqlBackup\BackupException;

class YandexDiskStorage implements StorageInterface
{

    /**
     * @param CreatorInterface $creator
     * @throws \Yandex\Disk\Exception\DiskRequestException
     * @return boolean
     */
    public function save(CreatorInterface $creator)
    {
        $consoleOut = \MysqlBackup\ConsoleOutput::getInstance();
        $config = Config::getInstance();

        if (!$config->getStorageYandexDiskDir()) {
            throw new BackupException("Empty yandex disk target directory.");
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

    public function removeOldBackups(CreatorInterface $creator)
    {
        throw new BackupException(__METHOD__ . " TODO");
    }

    private function validateDir($dir)
    {
        return "/" . trim($dir, " \t\n\t/\\") . "/";
    }
}

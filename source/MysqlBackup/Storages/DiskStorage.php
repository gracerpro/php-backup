<?php
namespace MysqlBackup\Storages;

use MysqlBackup\Config;
use MysqlBackup\BackupCreator;
use MysqlBackup\Storages\StorageInterface;

class DiskStorage implements StorageInterface
{

    public function save(BackupCreator $creator)
    {
        $config = Config::getInstance();
        $dir = $config->getStorageDiskDir();
        if (!$dir) {
            throw new \MysqlBackup\BackupException("The storage disk directory musb sets.");
        }
        if (!is_dir($dir)) {
            throw new \MysqlBackup\BackupException("Unknown storage disk directory.");
        }
        $sourceFilePath = $creator->getBackupZippedFilePath();
        $destFilePath = $dir . '/' . basename($sourceFilePath);
        
        $consoleOut = \MysqlBackup\ConsoleOutput::getInstance();
        //var_dump($config->getMoveArchiveToStorage()); die;
        if ($config->getMoveArchiveToStorage()) {
            $consoleOut->printMessage("Rename file");
            $result = rename($sourceFilePath, $destFilePath);
        } else {
            $consoleOut->printMessage("Copy file");
            $result = copy($sourceFilePath, $destFilePath);
        }
        
        return $result;
    }
}

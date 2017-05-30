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
        $this->checkTargetDir($dir);
        $sourceFilePath = $creator->getBackupZippedFilePath();
        $destFilePath = $dir . '/' . basename($sourceFilePath);

        $consoleOut = \MysqlBackup\ConsoleOutput::getInstance();
        //var_dump($config->getMoveArchiveToStorage()); die;
        if ($config->getMoveArchiveToStorage()) {
            $consoleOut->printMessage("Rename file");
            $consoleOut->printMessage("from: {$sourceFilePath}");
            $consoleOut->printMessage("to: {$destFilePath}");
            $result = rename($sourceFilePath, $destFilePath);
        } else {
            $consoleOut->printMessage("Copy file");
            $consoleOut->printMessage("from: {$sourceFilePath}");
            $consoleOut->printMessage("to: {$destFilePath}");
            $result = copy($sourceFilePath, $destFilePath);
        }

        return $result;
    }

    private function checkTargetDir($dir)
    {
        if (!$dir) {
            throw new \MysqlBackup\BackupException("The storage disk directory musb sets.");
        }
        if (!is_dir($dir)) {
            throw new \MysqlBackup\BackupException("Unknown storage disk directory.");
        }
    }

    public function removeOldBackups(BackupCreator $creator)
    {
        $fileHelper = new \MysqlBackup\FileHelper();
        $consoleOut = \MysqlBackup\ConsoleOutput::getInstance();
        $config = Config::getInstance();
        $dir = $fileHelper->trimDirName($config->getStorageDiskDir());
        $this->checkTargetDir($dir);

        $handle = opendir($dir);
        if ($handle === false) {
            throw new \MysqlBackup\BackupException("Could not open directory.");
        }

        // ignore subdirectories
        while (($file = readdir($handle)) != false) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            $filePath = $dir . '/' . $file;
            $consoleOut->printMessage($file);

            if ($this->isArchiveFile($filePath, $creator) && $this->isReadyForClean($filePath)) {
                $consoleOut->printMessage("Remove file... {$file}");
                //if (!unlink($file)) {
                //    throw new \MysqlBackup\BackupException("Could not remove file");
                //}
            }
        }

        closedir($handle);
    }

    private function isArchiveFile($filePath, BackupCreator $creator)
    {
        // by mask
        //...
        // by extension
        $fileExtension = \MysqlBackup\FileHelper::getFileExtension($filePath);
        if ($fileExtension === $creator->getBackupArchiveExtension()) {
            return true;
        }

        return false;
    }

    private function isReadyForClean($filePath)
    {
        $config = Config::getInstance();
        $modifyTimestamp = filemtime($filePath);
        $daysInSeconds = $config->getCleanActiveDay() * 3600 * 24;
        if (time() - $modifyTimestamp > $daysInSeconds) {
            return true;
        }
        return false;
    }
}

<?php
namespace PhpBackup\Storages;

use PhpBackup\Config;
use PhpBackup\CreatorInterface;
use PhpBackup\Storages\StorageInterface;

class DiskStorage implements StorageInterface
{

    public function save(CreatorInterface $creator)
    {
        $consoleOut = \PhpBackup\ConsoleOutput::getInstance();
        $config = Config::getInstance();

        $dir = $config->getStorageDiskDir();
        $this->checkTargetDir($dir);
        $sourceFilePath = $creator->getBackupZippedFilePath();
        $destFilePath = $dir . '/' . basename($sourceFilePath);

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
            throw new \PhpBackup\BackupException("The storage disk directory musb sets.");
        }
        if (!is_dir($dir)) {
            throw new \PhpBackup\BackupException("Unknown storage disk directory.");
        }
    }

    public function removeOldBackups(CreatorInterface $creator)
    {
        $fileHelper = new \PhpBackup\FileHelper();
        $consoleOut = \PhpBackup\ConsoleOutput::getInstance();
        $config = Config::getInstance();
        $dir = $fileHelper->trimDirName($config->getStorageDiskDir());
        $this->checkTargetDir($dir);

        $handle = opendir($dir);
        if ($handle === false) {
            throw new \PhpBackup\BackupException("Could not open directory.");
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
                //    throw new \PhpBackup\BackupException("Could not remove file");
                //}
            }
        }

        closedir($handle);
    }

    private function isArchiveFile($filePath, CreatorInterface $creator): bool
    {
        // by mask
        //...
        // by extension
        $fileExtension = \PhpBackup\FileHelper::getFileExtension($filePath);
        if ($fileExtension === $creator->getBackupArchiveExtension()) {
            return true;
        }

        return false;
    }

    private function isReadyForClean($filePath): bool
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

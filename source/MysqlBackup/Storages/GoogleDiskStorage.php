<?php
namespace MysqlBackup\Storages;

class GoogleDiskStorage implements StorageInterface
{

    public function removeOldBackups(\MysqlBackup\BackupCreator $creator)
    {
        $consoleOut = \MysqlBackup\ConsoleOutput::getInstance();
        $consoleOut->printMessage("TODO");
    }

    /**
     * @param \MysqlBackup\BackupCreator $creator
     * @return boolean
     */
    public function save(\MysqlBackup\BackupCreator $creator)
    {
        $consoleOut = \MysqlBackup\ConsoleOutput::getInstance();
        $config = \MysqlBackup\Config::getInstance();

        if (!$config->getStorageGoogleDriveFolderId()) {
            throw new \MysqlBackup\BackupException("Empty folder ID.");
        }

        $client = new \Google_Client();
        $oauthConfigPath = \MysqlBackup\MysqlBackup::getProjectDir() . '/f597bcc83c2d.json';
        $client->setAuthConfig($oauthConfigPath);
        $client->addScope(\Google_Service_Drive::DRIVE);

        $folderId = $config->getStorageGoogleDriveFolderId();

        $driveFile = new \Google_Service_Drive_DriveFile();
        $driveFile->setName(basename($creator->getBackupZippedFilePath()));
        $driveFile->setParents([$folderId]);

        $consoleOut->printMessage("Write to google drive...");
        $consoleOut->printMessage('To folder ID: "' . $folderId . '"');
        $googleDrive = new \Google_Service_Drive($client);
        /* @var $googleFiles \Google_Service_Drive_Resource_Files */
        $googleFiles = $googleDrive->files;

        $data = file_get_contents($creator->getBackupZippedFilePath());

        $newFile = $googleFiles->create($driveFile, [
            'data' => $data,
            'mimeType' => 'application/octet-stream',
            'uploadType' => 'media',
        ]);

        $consoleOut->printMessage('Ok. File ID: "' . $newFile->getId() . '"');

        return true;
    }
}

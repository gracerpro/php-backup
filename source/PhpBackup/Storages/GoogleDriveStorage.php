<?php
namespace PhpBackup\Storages;

use PhpBackup\CreatorInterface;

class GoogleDriveStorage implements StorageInterface
{

    public function removeOldBackups(CreatorInterface $creator)
    {
        throw new \PhpBackup\BackupException("TODO: removeOldBackups");
    }

    /**
     * @param CreatorInterface $creator
     * @return boolean
     */
    public function save(CreatorInterface $creator)
    {
        $consoleOut = \PhpBackup\ConsoleOutput::getInstance();
        $config = \PhpBackup\Config::getInstance();

        if (!$config->getStorageGoogleDriveFolderId()) {
            throw new \PhpBackup\BackupException("Empty folder ID.");
        }

        $oauthConfigPath = $config->getStorageGoogleDriveKeyFile();
        if (!is_file($oauthConfigPath)) {
            $message = 'Google drive key file not found, path: "' . $oauthConfigPath. '"';
            throw new \PhpBackup\BackupException($message);
        }

        $client = new \Google_Client();
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

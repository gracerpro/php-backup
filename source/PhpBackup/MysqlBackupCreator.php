<?php
namespace PhpBackup;

class MysqlBackupCreator extends BackupCreatorBase implements CreatorInterface
{

    public function getBackupZippedFilePath(): string
    {
        return $this->getBackupFilePath() . '.' . $this->getBackupArchiveExtension();
    }

    /**
     * @return string
     * @throws BackupException
     */
    public function getBackupFilePath(): string
    {
        $config = Config::getInstance();
        if ($this->frequencyCreation === self::FRECUENCY_DAY) {
            return $config->getTempBackupDir() . '/' . date('Y-m-d') . '_' . $config->getDbName() . '.sql';
        }
        throw new BackupException("Unknown frequency of a creation.");
    }

    /**
     * Take the database settings and create dump
     */
    public function create()
    {
        $consoleOut = ConsoleOutput::getInstance();

        try {
            $this->checkDatabaseOptions();
            $this->createBackup();
            $this->compress();
        } catch (\Exception $ex) {
            $consoleOut->printMessage("Error: {$ex->getMessage()}");
            throw $ex;
        }
    }

    private function createBackup()
    {
        $config = Config::getInstance();
        $consoleOut = ConsoleOutput::getInstance();
        $targetFilePath = $this->getBackupFilePath();

        $consoleOut->printMessage("Run mysqldump...");
        $command = "mysqldump -u {$config->getDbUser()} -p{$config->getDbPassword()} {$config->getDbName()} > \"{$targetFilePath}\"";
        $safeCommand = "mysqldump -u {$config->getDbUser()} -p***** {$config->getDbName()} > 'xxx.sql'";
        $consoleOut->printMessage("write to: {$targetFilePath}");
        $consoleOut->printMessage($safeCommand);

        $output = null;
        $returnCode = 0;
        exec($command, $output, $returnCode);
        $consoleOut->printMessage("exec() return code: {$returnCode}");
        if ($returnCode) {
            throw new BackupException("mysqldump errors!");
        }
        if (!$targetFilePath || !is_file($targetFilePath)) {
            throw new BackupException("Could not create a file.");
        }
        $targetFileSizeInMb = (int) round(filesize($targetFilePath) / 1024 / 1024, 3);
        $consoleOut->printMessage("Writed {$targetFileSizeInMb} Mb.");
    }

    private function compress()
    {
        $consoleOut = ConsoleOutput::getInstance();

        $consoleOut->printMessage("Gzip...");
        $compress = new Compress();
        $compress->zipFile($this->getBackupFilePath(), $this->getBackupArchiveExtension());
 
        $archiveFilePath = $this->getBackupZippedFilePath();
        $archiveFileSizeInMb = (int) round(filesize($archiveFilePath) / 1024 / 1024, 3);
        $consoleOut->printMessage("Writed {$archiveFileSizeInMb} Mb.");
    }

    private function checkDatabaseOptions()
    {
        $config = Config::getInstance();

        if (!$config->getDbHost()) {
            throw new BackupException("Empty database host.");
        }
        if (!$config->getDbName()) {
            throw new BackupException("Empty database name.");
        }
        if (!$config->getDbUser()) {
            throw new BackupException("Empty database user.");
        }
        if (!$config->getDbPort()) {
            throw new BackupException("Empty database port.");
        }
    }
}

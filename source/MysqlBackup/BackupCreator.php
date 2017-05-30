<?php
namespace MysqlBackup;

class BackupCreator
{
    // TODO: move to config
    const FRECUENCY_DAY = 'day';

    /** @var string */
    private $frequencyCreation;

    public function __construct()
    {
        $this->frequencyCreation = self::FRECUENCY_DAY;
    }

    /**
     * @return string
     * @throws BackupException
     */
    public function getBackupFilePath()
    {
        $config = Config::getInstance();
        if ($this->frequencyCreation === self::FRECUENCY_DAY) {
            return $config->getTargetBackupDir() . '/' . date('Y-m-d') . '_' . $config->getDbName() . '.sql';
        }
        throw new BackupException("Unknown frequency of a creation.");
    }

    /**
     * @return string
     */
    public function getBackupZippedFilePath()
    {
        return $this->getBackupFilePath() . '.' . $this->getBackupArchiveExtension();
    }
    
    public function getBackupArchiveExtension()
    {
        return 'gz';
    }

    /**
     * Take the database settings and create dump
     */
    public function create()
    {
        $config = Config::getInstance();
        $consoleOut = ConsoleOutput::getInstance();

        try {
            $consoleOut->printMessage("Run mysqldump...");
            $targetFilePath = $this->getBackupFilePath();
            $command = "mysqldump -u {$config->getDbUser()} -p{$config->getDbPassword()} {$config->getDbName()} > \"{$targetFilePath}\"";
            $safeCommand = "mysqldump -u {$config->getDbUser()} -p***** {$config->getDbName()} > 'xxx.sql'";
            $consoleOut->printMessage("write to: {$targetFilePath}");
            $consoleOut->printMessage($safeCommand);

            $output = null;
            $returnCode = 0;
            exec($command, $output, $returnCode);
            $consoleOut->printMessage("exec() return code: {$returnCode}");
            if (!file_exists($targetFilePath)) {
                throw new BackupException("Could not create a file.");
            }
            $targetFileSizeInMb = round(filesize($targetFilePath) / 1024 / 1024, 3);
            $consoleOut->printMessage("Writed {$targetFileSizeInMb} Mb.");

            $consoleOut->printMessage("Gzip...");
            $command = "gzip -f -S \".{$this->getBackupArchiveExtension()}\" \"{$targetFilePath}\"";
            exec($command, $output, $returnCode);
            $consoleOut->printMessage("exec() return code: {$returnCode}");
            $archiveFilePath = $this->getBackupZippedFilePath();
            if (!file_exists($archiveFilePath)) {
                throw new BackupException("Could not create a file");
            }
            $archiveFileSizeInMb = round(filesize($archiveFilePath) / 1024 / 1024, 3);
            $compressPercent = round($archiveFileSizeInMb / $targetFileSizeInMb, 2);
            $consoleOut->printMessage("Writed {$archiveFileSizeInMb} Mb, compressed {$compressPercent}%.");
            
        } catch (\Exception $ex) {
            $consoleOut->printMessage("Error: {$ex->getMessage}");
            throw $ex;
        }
    }
}

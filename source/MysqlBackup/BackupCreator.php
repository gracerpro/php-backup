<?php
namespace MysqlBackup;

class BackupCreator
{

    const FRECUENCY_DAY = 'day';

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
        throw new BackupException("Unknown frequency creation.");
    }

    /**
     * @return string
     */
    public function getBackupZippedFilePath()
    {
        return $this->getBackupFilePath() . '.gz';
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

            $consoleOut->printMessage("Gzip...");
            $command = "gzip -f -S \".gz\" {$targetFilePath}";
            exec($command, $output, $returnCode);
            $consoleOut->printMessage("exec() return code: {$returnCode}");
        } catch (\Exception $ex) {
            $consoleOut->printMessage("Error: " . $ex->getMessage);
            throw $ex;
        }
    }
}

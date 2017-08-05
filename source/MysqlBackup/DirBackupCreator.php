<?php

namespace MysqlBackup;

class DirBackupCreator extends BackupCreatorBase
{
    /** @var string */
    private $targetDirectory;
    
    public function __construct($direcrory)
    {
        $this->targetDirectory = $direcrory;
    }

    public function create()
    {
        if (!is_dir($this->targetDirectory)) {
            throw new BackupException("The target directory not exists.");
        }
        $consoleOut = ConsoleOutput::getInstance();
        $targetFilePath = $this->getBackupZippedFilePath();

        $consoleOut->printMessage("Create directory archive...");
        
        $consoleOut->printMessage("Compressed dir: {$this->targetDirectory}");
        $consoleOut->printMessage("Target file path: {$targetFilePath}");
        
    }

    public function getBackupZippedFilePath(): string
    {
        $config = Config::getInstance();
        $path = str_replace(['/', '\\'], ['-', '-'], $this->targetDirectory);
        return $config->getTempBackupDir() . '/' . '';
    }
    
    
}

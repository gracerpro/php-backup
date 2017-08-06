<?php
namespace MysqlBackup;

class DirBackupCreator extends BackupCreatorBase
{

    /** @var string */
    private $projectDirectory;

    /** @var string */
    private $targetDirectories;

    /** @var string */
    private $targetDirectoryName;

    public function __construct()
    {
        $this->targetDirectories = [];
        $this->targetDirectoryName = "";
        $this->projectDirectory = "";
    }

    public function create()
    {
        $this->validateForCreation();

        $consoleOut = ConsoleOutput::getInstance();
        $targetFilePath = $this->getBackupZippedFilePath();

        $consoleOut->printMessage("Create directory archive...");

        $consoleOut->printMessage("Compressed dir: {$this->targetDirectories}");
        $consoleOut->printMessage("Target file path: {$targetFilePath}");

        try {
            $zip = new ZipArchive();
        } catch (\Exception $ex) {

            throw new BackupException("Create directory backup fail.");
        }
    }

    private function getRealDirectories()
    {
        $directories = [];

        foreach ($this->targetDirectories as $dir) {
            
        }

        return $directories;
    }

    public function getBackupZippedFilePath(): string
    {
        $config = Config::getInstance();
        return $config->getTempBackupDir() . '/' . $this->targetDirectoryName . '.' . $this->getBackupArchiveExtension();
    }

    public function setTargetDirectoryName($value)
    {
        $this->targetDirectoryName = $value;
        return $this;
    }

    private function validateForCreation()
    {
        if (!is_dir($this->targetDirectories)) {
            throw new BackupException("The target directory not exists.");
        }
        if (!$this->targetDirectoryName) {
            throw new BackupException("Empty target directory name.");
        }
    }

    public function setProjectDirectory($projectDirectory)
    {
        $this->projectDirectory = $projectDirectory;
        return $this;
    }
    
    public function setTargetDirectories($targetDirectories)
    {
        $this->targetDirectories = $targetDirectories;
        return $this;
    }

}

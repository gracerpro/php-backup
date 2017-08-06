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

        $directories = $this->getRealDirectories();
        $consoleOut->printMessage("Project dir: {$this->projectDirectory}");
        foreach ($this->targetDirectories as $directory) {
            $consoleOut->printMessage("  compress dir: {$directory}");
        }
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
        $config = Config::getInstance();

        foreach ($this->targetDirectories as $dir) {
            $directories[] = $this->projectDirectory . '/' . $dir;
        }
       // var_dump($directories); die;

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
        if (!$this->projectDirectory) {
            throw new BackupException("The project directory must be sets.");
        }
        if (!is_dir($this->projectDirectory)) {
            throw new BackupException("The project directory not exists.");
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

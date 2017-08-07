<?php
namespace PhpBackup;

class DirBackupCreator extends BackupCreatorBase implements CreatorInterface
{

    /** @var string */
    private $projectDirectory;

    /** @var string */
    private $targetDirectories;

    /** @var string */
    private $targetDirectoryName;

    public function __construct()
    {
        parent::__construct();

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
            $consoleOut->printMessage("  subdir: {$directory}");
        }
        $consoleOut->printMessage("Target file path: {$targetFilePath}");

        try {
            $compress = new Compress();
            $compress->zipDirectories($this->projectDirectory, $directories, $targetFilePath);
        } catch (BackupException $ex) {
            throw $ex;
        } catch (\Exception $ex) {
            throw new BackupException("Create directory backup fail.");
        }

        $sizeInMb = round(filesize($targetFilePath) / 1024 / 1024, 3);
        $consoleOut->printMessage("Archive file size: {$sizeInMb} Mb.");
    }

    private function getRealDirectories(): array
    {
        $directories = [];

        foreach ($this->targetDirectories as $dir) {
            $directories[] = $this->projectDirectory . '/' . $dir;
        }

        return $directories;
    }

    public function getBackupFilePath(): string
    {
        return '';
    }

    public function getBackupZippedFilePath(): string
    {
        $config = Config::getInstance();

        $filePath = '';
        if ($this->frequencyCreation === self::FRECUENCY_DAY) {
            $filePath = $config->getTempBackupDir() . '/' . date('Y-m-d'). '_' . $this->targetDirectoryName . '.' . $this->getBackupArchiveExtension();
        }
        
        return $filePath;
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
            throw new BackupException("The project directory not exists. \"{$this->projectDirectory}\"");
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

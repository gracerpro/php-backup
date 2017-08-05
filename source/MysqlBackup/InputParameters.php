<?php
namespace MysqlBackup;

class InputParameters
{

    use ConfigTrait;

    /** @var bool */
    private $help;

    /** @var bool */
    private $empty;

    /** @var bool */
    private $runBuckup;

    /** @var bool */
    private $runClean;

    /** @var bool */
    private $runBackupDirAction;

    /** @var string */
    private $backupTargerDir;

    /** @var string */
    private $backupTargerDirName;
    // TODO: move to trait
    /** @var string */
    private $configFileName;

    public function getHelp()
    {
        return $this->help;
    }

    public function getConfigFileName()
    {
        return $this->configFileName;
    }

    public function setHelp($help)
    {
        $this->help = $help;
        return $this;
    }

    public function setConfigFileName($configFileName)
    {
        $this->configFileName = $configFileName;
        return $this;
    }

    public function isEmpty()
    {
        return $this->empty;
    }

    public function setEmpty($empty)
    {
        $this->empty = $empty;
        return $this;
    }

    public function getRunBuckup()
    {
        return $this->runBuckup;
    }

    public function setRunBuckup($runBuckup)
    {
        $this->runBuckup = $runBuckup;
        return $this;
    }

    public function getRunClean()
    {
        return $this->runClean;
    }

    public function setRunClean($runClean)
    {
        $this->runClean = $runClean;
        return $this;
    }

    public function isRunBackupDirAction()
    {
        return $this->runBackupDirAction;
    }

    public function setIsRunBackupDirAction($value = true)
    {
        $this->runBackupDirAction = $value;
        return $this;
    }

    public function getBackupTargerDirName()
    {
        return $this->backupTargerDirName;
    }

    public function setBackupTargerDirName($value)
    {
        $this->backupTargerDirName = $value;
        return $this;
    }

    public function getBackupTargerDir()
    {
        return $this->backupTargerDir;
    }

    public function setBackupTargerDir($value)
    {
        $this->backupTargerDir = $value;
        return $this;
    }
}

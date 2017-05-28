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
}

<?php

namespace MysqlBackup;

class InputParameters
{

    use ConfigTrait;

    /** @var bool */
    private $help;

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
}

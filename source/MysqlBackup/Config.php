<?php
namespace MysqlBackup;

class Config
{

    use ConfigTrait;

    /** @var Config */
    private static $instance;

    private function __construct()
    {
        $this->targetBackupDir = "~";
        $this->workDir = '.';
    }

    /**
     * @return Config
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Config();
        }
        return self::$instance;
    }

    public function read($config)
    {
        $output = ConsoleOutput::getInstance();
        $output->printMessage("Read config parameters...");

        if (!is_array($config)) {
            throw new BackupException("The configuration must be an array.");
        }

        foreach ($config as $name => $value) {
            if (property_exists($this, $name)) {
                $this->{$name} = $value;
            }
        }
    }

}

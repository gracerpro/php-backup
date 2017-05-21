<?php
namespace MysqlBackup;

//use MysqlBackup\Config;

class MysqlBackup
{

    /** @var InputParameters */
    private $inputParameters;

    const VERSION = "1.0";

    public function __construct()
    {
        $this->inputParameters = new InputParameters();
    }

    public function run()
    {
        echo "MySQL backup version ", MysqlBackup::VERSION, "\n";

        try {
            $this->readInputParameters();
            $this->init();
            $this->runActions();
            echo "Bay\n";
        } catch (\Exception $ex) {
            echo "Error: ", $ex->getMessage(), "\n";
        }
    }

    private function init()
    {
        $configFileName = 'config-local.php';
        if ($this->inputParameters->configFileName) {
            $configFileName = $this->inputParameters->configFileName;
        }
        $configMain = require 'config.php';
        $configLocal = require $configFileName;
        $configCommon = array_merge($configMain, $configLocal);
        $config = Config::getInstance();
        $config->read($configCommon);
    }

    private function readInputParameters()
    {
        $shortOptions = 'f:';
        $longOptions = [
            'configFile:',
            'help::',
            'mysqlDumpOptions::'
        ];
        $options = getopt($shortOptions, $longOptions);

        if (isset($options['f'])) {
            $this->inputParameters->configFileName = $options['f'];
        }
        if (isset($options['configFile'])) {
            $this->inputParameters->configFileName = $options['configFile'];
        }
        if (isset($options['help'])) {
            $this->inputParameters->help = true;
        }
        if (isset($options['mysqlDumpOptions'])) {
            $this->inputParameters->mysqlDumpOptions = $options['mysqlDumpOptions'];
        }
    }

    private function runActions()
    {
        if ($this->inputParameters->help) {
            $help = new Help();
            $help->printHelp();
            exit;
        }

        $this->createBackupFile();

        // TODO: if nothing in command line and config then print it
        echo "Use --help for additional inforation.\n";
    }

    private function createBackupFile()
    {
        $creator = new BackupCreator();
        $creator->create();
    }
}

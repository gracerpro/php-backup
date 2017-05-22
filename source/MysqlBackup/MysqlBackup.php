<?php
namespace MysqlBackup;

use MysqlBackup\BackupStorageFactory;

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
            $this->readConfig();
            $this->writeInputParametersToConfig();
            $this->init();
            $this->runActions();
            echo "Bay\n";
        } catch (\Exception $ex) {
            echo "Error: ", $ex->getMessage(), "\n";
        }
    }

    private function readConfig()
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

    private function init()
    {
        
    }

    private function writeInputParametersToConfig()
    {
        $config = Config::getInstance();
        $inputParams = $this->inputParameters;
        if ($inputParams->storageType) {
            $config->setStorageType($inputParams->storageType);
        }
        if ($inputParams->storageDiskDir) {
            $config->setStorageDiskDir($inputParams->storageDiskDir);
        }
        if ($inputParams->moveArchiveToStorage) {
            $config->setMoveArchiveToStorage($inputParams->moveArchiveToStorage);
        }
    }

    private function readInputParameters()
    {
        $shortOptions = 'f:h::';
        $longOptions = [
            'configFile:',
            'help::',
            'mysqlDumpOptions::',
            'moveArchiveToStorage::'
        ];
        $options = getopt($shortOptions, $longOptions);

        if (isset($options['f'])) {
            $this->inputParameters->configFileName = $options['f'];
        }
        if (isset($options['configFile'])) {
            $this->inputParameters->configFileName = $options['configFile'];
        }
        if (isset($options['help']) || isset($options['h'])) {
            $this->inputParameters->help = true;
        }
        if (isset($options['mysqlDumpOptions'])) {
            $this->inputParameters->mysqlDumpOptions = $options['mysqlDumpOptions'];
        }

        if (isset($options['storageType'])) {
            $this->inputParameters->storageType = $options['storageType'];
        }
        if (isset($options['storageDiskDir'])) {
            $this->inputParameters->storageDiskDir = $options['storageDiskDir'];
        }
        if (isset($options['moveArchiveToStorage'])) {
            $this->inputParameters->moveArchiveToStorage = true;
        }
    }

    private function runActions()
    {
        if ($this->inputParameters->help) {
            $help = new Help();
            $help->printHelp();
            exit;
        }

        $consoleOut = ConsoleOutput::getInstance();

        try {
            $creator = $this->createBackupFile();
            $this->sendBackupToStorage($creator);
        } catch (\MysqlBackup\BackupException $ex) {
            $consoleOut->printMessage("Global error: " . $ex->getMessage());
        } catch (\Exception $ex) {
            $consoleOut->printMessage("Exception: " . $ex->getMessage());
        }

        // TODO: if nothing in command line and config then print it
        echo "Use --help for additional inforation.\n";
    }

    private function createBackupFile()
    {
        $creator = new BackupCreator();
        $creator->create();

        return $creator;
    }

    private function sendBackupToStorage(BackupCreator $creator)
    {
        $config = Config::getInstance();
        $storageFactory = new BackupStorageFactory();
        $storageType = $config->getStorageType();
        $storage = $storageFactory->create($storageType);
        if (!$storage->save($creator)) {
            throw new \MysqlBackup\BackupException("Could not save to storage");
        }
    }
}

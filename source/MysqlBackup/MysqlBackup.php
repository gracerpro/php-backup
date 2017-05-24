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
        if ($this->inputParameters->getConfigFileName()) {
            $configFileName = $this->inputParameters->getConfigFileName();
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
        //var_dump($inputParams); die;
        if ($inputParams->getStorageType()) {
            $config->setStorageType($inputParams->getStorageType());
        }
        if ($inputParams->getStorageDiskDir()) {
            $config->setStorageDiskDir($inputParams->getStorageDiskDir());
        }
        if ($inputParams->getRemoveArchiveAfterSync()) {
            $config->setMoveArchiveToStorage($inputParams->getRemoveArchiveAfterSync());
        }
        if ($inputParams->getStorageYandexDiskToken()) {
            $config->setStorageYandexDiskToken($inputParams->getStorageYandexDiskToken());
        }
    }

    private function readInputParameters()
    {
        $shortOptions = 'f:h::';
        $longOptions = [
            'configFile:',
            'help::',
            'mysqlDumpOptions::',
            'removeArchiveAfterSync::',
            'storageType:',
            'storageDiskDir:',
        ];
        $options = getopt($shortOptions, $longOptions);

        if (isset($options['f'])) {
            $this->inputParameters->setConfigFileName($options['f']);
        }
        if (isset($options['configFile'])) {
            $this->inputParameters->setConfigFileName($options['configFile']);
        }
        if (isset($options['help']) || isset($options['h'])) {
            $this->inputParameters->setHelp(true);
        }
        if (isset($options['mysqlDumpOptions'])) {
            $this->inputParameters->setMysqlDumpOptions($options['mysqlDumpOptions']);
        }

        if (isset($options['storageType'])) {
            $this->inputParameters->setStorageType($options['storageType']);
        }
        if (isset($options['storageDiskDir'])) {
            $this->inputParameters->setStorageDiskDir($options['storageDiskDir']);
        }
        if (isset($options['storageYandexDiskToken'])) {
            $this->inputParameters->setStorageYandexDiskToken($options['storageYandexDiskToken']);
        }
        if (isset($options['removeArchiveAfterSync'])) {
            $this->inputParameters->setRemoveArchiveAfterSync(true);
        }
    }

    private function runActions()
    {
        if ($this->inputParameters->getHelp()) {
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

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

    public static function getProjectDir()
    {
        return __DIR__ . '/../..';
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
        if ($inputParams->getStorageGoogleDriveFolderId()) {
            $config->setStorageGoogleDriveFolderId($inputParams->getStorageGoogleDriveFolderId());
        }
        if ($inputParams->getStorageYandexDiskToken()) {
            $config->setStorageYandexDiskToken($inputParams->getStorageYandexDiskToken());
        }
        if ($inputParams->getStorageYandexDiskDir()) {
            $config->setStorageYandexDiskDir($inputParams->getStorageYandexDiskDir());
        }
        if ($inputParams->getDebug()) {
            $config->setDebug(true);
        }
        if ($inputParams->getCleanActiveDay()) {
            $config->setCleanActiveDay($inputParams->getCleanActiveDay());
        }
    }

    private function readInputParameters()
    {
        $shortOptions = 'b::d::f:h::';
        $longOptions = [
            'backup::',
            'clean::',
            'configFile:',
            'debug::',
            'help::',
            'mysqlDumpOptions::',
            'removeArchiveAfterSync::',
            'storageType:',
            'storageDiskDir:',
            'storageGoogleDriveFolderId:',
            'storageYandexDiskDir:',
            'storageYandexDiskToken:',
        ];
        $options = getopt($shortOptions, $longOptions);

        $this->inputParameters->setEmpty(empty($options));

        if (isset($options['b']) || isset($options['backup'])) {
            $this->inputParameters->setRunBuckup(true);
        }
        if (isset($options['clean'])) {
            $this->inputParameters->setRunClean(true);
        }
        if (isset($options['configFile'])) {
            $this->inputParameters->setConfigFileName($options['configFile']);
        }
        if (isset($options['d']) || isset($options['debug'])) {
            $this->inputParameters->setDebug(true);
        }
        if (isset($options['h']) || isset($options['help'])) {
            $this->inputParameters->setHelp(true);
        }
        if (isset($options['f'])) {
            $this->inputParameters->setConfigFileName($options['f']);
        }
        if (isset($options['mysqlDumpOptions'])) {
            $this->inputParameters->setMysqlDumpOptions($options['mysqlDumpOptions']);
        }
        if (isset($options['removeArchiveAfterSync'])) {
            $this->inputParameters->setRemoveArchiveAfterSync(true);
        }
        if (isset($options['storageType'])) {
            $this->inputParameters->setStorageType($options['storageType']);
        }
        if (isset($options['storageDiskDir'])) {
            $this->inputParameters->setStorageDiskDir($options['storageDiskDir']);
        }
        if (isset($options['storageGoogleDriveFolderId'])) {
            $this->inputParameters->setStorageGoogleDriveFolderId($options['storageGoogleDriveFolderId']);
        }
        if (isset($options['storageYandexDiskToken'])) {
            $this->inputParameters->setStorageYandexDiskToken($options['storageYandexDiskToken']);
        }
        if (isset($options['storageYandexDiskDir'])) {
            $this->inputParameters->setStorageYandexDiskDir($options['storageYandexDiskDir']);
        }
        //var_dump($options); die;
    }

    private function runActions()
    {
        if ($this->inputParameters->getHelp()) {
            $help = new Help();
            $help->printHelp();
            exit;
        }

        $consoleOut = ConsoleOutput::getInstance();
        $printDefaultMessage = $this->inputParameters->isEmpty();

        try {

            if ($this->inputParameters->getRunBuckup()) {
                $creator = $this->createBackupFile();
                $this->sendBackupToStorage($creator);
            } elseif ($this->inputParameters->getRunClean()) {
                $creator = new BackupCreator();
                $this->removeOldBackups($creator);
            } else {
                $printDefaultMessage = true;
            }

            if ($printDefaultMessage) {
                $consoleOut->printMessage("Use --help parameter for view help.");
            }
        } catch (\MysqlBackup\BackupException $ex) {
            $consoleOut->printMessage("Global error: " . $ex->getMessage());
        } catch (\Exception $ex) {
            $consoleOut->printMessage("Exception: " . $ex->getMessage());
        }
    }

    private function createBackupFile()
    {
        $creator = new BackupCreator();
        $creator->create();

        return $creator;
    }
    
    private function removeOldBackups(BackupCreator $creator)
    {
        $config = Config::getInstance();
        $consoleOut = ConsoleOutput::getInstance();
        $storageFactory = new BackupStorageFactory();
        
        $storageType = $config->getStorageType();
        $consoleOut->printMessage("Use sorage type: " . $storageType);
        $storage = $storageFactory->create($storageType);
        $storage->removeOldBackups($creator);
    }

    private function sendBackupToStorage(BackupCreator $creator)
    {
        $consoleOut = ConsoleOutput::getInstance();
        $config = Config::getInstance();
        $storageFactory = new BackupStorageFactory();
        
        $storageType = $config->getStorageType();
        $consoleOut->printMessage("Use sorage type: " . $storageType);
        $storage = $storageFactory->create($storageType);
        if (!$storage->save($creator)) {
            throw new \MysqlBackup\BackupException("Could not save to storage");
        }
    }
}

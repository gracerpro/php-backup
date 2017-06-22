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

    /**
     * @return string
     */
    public static function getRuningDir()
    {
        $isPharArchive = strtolower(substr(__FILE__, 0, 5)) == 'phar:';
        if ($isPharArchive) {
            $dir = dirname(\Phar::running(false));
        } else {
            $dir = dirname(__FILE__) . '/../..';
        }

        return $dir;
    }

    public function run($config = [])
    {
        $consoleOut = ConsoleOutput::getInstance();
        $consoleOut->printMessage('MySQL backup version "' . MysqlBackup::VERSION . '"');

        try {
            $this->readConfig($config);
            $this->readInputParameters();
            if ($this->inputParameters->getConfigFileName()) {
                $this->reopenConfig($this->inputParameters->getConfigFileName());
            }
            $this->writeInputParametersToConfig();
            $this->init();
            $this->runActions();
            $consoleOut->printMessage("Bay.");
        } catch (\Exception $ex) {
            $consoleOut->printMessage("Error: " . $ex->getMessage());
        }
    }

    private function reopenConfig($fileName)
    {
        if (is_file($fileName)) {
            $configArr = file_get_contents($fileName);
            return $this->readConfig($configArr);
        }
        throw new BackupException('Could not open the configuration file "' . $fileName . '"');
    }

    private function readConfig($configArr)
    {
        $config = Config::getInstance();
        $config->read($configArr);
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
        if ($inputParams->getStorageGoogleDriveKeyFile()) {
            $config->setStorageGoogleDriveKeyFile($inputParams->getStorageGoogleDriveKeyFile());
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
            'storageGoogleDriveKeyFile:',
            'storageYandexDiskDir:',
            'storageYandexDiskToken:',
        ];
        $options = getopt($shortOptions, $longOptions);

        $this->inputParameters->setEmpty(empty($options));

        // TODO: optimize this block
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
        if (isset($options['storageGoogleDriveKeyFile'])) {
            $this->inputParameters->setStorageGoogleDriveKeyFile($options['storageGoogleDriveKeyFile']);
        }
        if (isset($options['storageYandexDiskToken'])) {
            $this->inputParameters->setStorageYandexDiskToken($options['storageYandexDiskToken']);
        }
        if (isset($options['storageYandexDiskDir'])) {
            $this->inputParameters->setStorageYandexDiskDir($options['storageYandexDiskDir']);
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
        $printDefaultMessage = $this->inputParameters->isEmpty();

        try {

            if ($this->inputParameters->getRunBuckup()) {
                $creator = $this->createBackupFile();
                $this->sendBackupToStorage($creator);
                $this->removeBackupFile($creator);
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

    private function removeBackupFile(BackupCreator $creator)
    {
        $consoleOut = ConsoleOutput::getInstance();

        $consoleOut->printMessage("Remove backup files from disk.");

        if (is_file($creator->getBackupFilePath())) {
            $consoleOut->printMessage("Remove backup file...");
            $result = unlink($creator->getBackupFilePath());
            $consoleOut->printMessage("status: " . ($result ? 'succes' : 'failed'));
        }
        if (is_file($creator->getBackupZippedFilePath())) {
            $consoleOut->printMessage("Remove backup archive file...");
            $result = unlink($creator->getBackupZippedFilePath());
            $consoleOut->printMessage("status: " . ($result ? 'succes' : 'failed'));
        }
        $consoleOut->printMessage("Ok.");
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
        $consoleOut->printMessage("Use ssorage type: " . $storageType);
        $storage = $storageFactory->create($storageType);
        $storage->removeOldBackups($creator);
    }

    private function sendBackupToStorage(BackupCreator $creator)
    {
        $consoleOut = ConsoleOutput::getInstance();
        $config = Config::getInstance();
        $storageFactory = new BackupStorageFactory();

        $storageType = $config->getStorageType();
        $consoleOut->printMessage("Use storage type: " . $storageType);
        $storage = $storageFactory->create($storageType);
        if (!$storage->save($creator)) {
            throw new \MysqlBackup\BackupException("Could not save to storage");
        }
    }
}

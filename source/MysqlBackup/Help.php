<?php
namespace MysqlBackup;

use MysqlBackup\BackupStorageFactory;

class Help
{

    public function printHelp()
    {
        $console = ConsoleOutput::getInstance();
        
        $console->printMessage("Parameters:");
        $console->printMessage("-h, --help           Show help.");
        $console->printMessage("-f, --configFile     Configuration file.");
        $console->printMessage("--mysqlDumpOptions   Options for mysqldump.");
        $console->printMessage("--moveArchiveToStorage Move (delete source) file to storage.");
        $console->printMessage("--storageType        Storage type, default '" . BackupStorageFactory::STORAGE_DEFAULT . "'.");
        $console->printMessage("  type '" . BackupStorageFactory::STORAGE_DISK . "'");
        $console->printMessage("    --storageDiskDir   Directory for archives, string.");
        $console->printMessage("  type '" . BackupStorageFactory::STORAGE_YANDEX_DISK . "'");
        $console->printMessage("    null");
        $console->printMessage("  type '" . BackupStorageFactory::STORAGE_MAIL_CLOUD . "'");
        $console->printMessage("    null");
    }
}

<?php
namespace MysqlBackup;

use MysqlBackup\BackupStorageFactory;

class Help
{

    public function printHelp()
    {
        $console = ConsoleOutput::getInstance();
        
        $console->printMessage("This script creates the backups, clear it, syncronize it to storages.");
        
        $console->printMessage("Parameters:");
        $console->printMessage("-h, --help           Show help.");
        $console->printMessage("-f, --configFile     Configuration file.");
        $console->printMessage("--mysqlDumpOptions   Options for mysqldump.");
        $console->printMessage("--removeArchiveAfterSync Remove archive file after syncronize to storage.");
        $console->printMessage("--storageType        Storage type, default '" . BackupStorageFactory::STORAGE_DEFAULT . "'.");
        $console->printMessage("  type '" . BackupStorageFactory::STORAGE_DISK . "'");
        $console->printMessage("    --storageDiskDir   Directory for archives, string.");
        $console->printMessage("  type '" . BackupStorageFactory::STORAGE_YANDEX_DISK . "'");
        $console->printMessage("    null");
        $console->printMessage("  type '" . BackupStorageFactory::STORAGE_MAIL_CLOUD . "'");
        $console->printMessage("    null");
    }
}

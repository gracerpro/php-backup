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
        $console->printMessage("-b, --backup           Run backup.");
        $console->printMessage("-f, --configFile     Configuration file.");
        $console->printMessage("-d, --debug          Debug mode.");
        $console->printMessage("--mysqlDumpOptions   Options for mysqldump.");
        $console->printMessage("--removeArchiveAfterSync Remove archive file after syncronize to storage.");
        $console->printMessage("--storageType        Storage type, default '" . BackupStorageFactory::STORAGE_DEFAULT . "'.");
        $console->printMessage("  type '" . BackupStorageFactory::STORAGE_DISK . "' (default)");
        $console->printMessage("    --storageDiskDir   Directory for archives, string.");
      /*  $console->printMessage("  type '" . BackupStorageFactory::STORAGE_FTP . "'");
        $console->printMessage("    --storageFtpHost   Host, string.");
        $console->printMessage("    --storageFtpUser   User, string.");
        $console->printMessage("    --storageFtpPassword   Password, string.");
        $console->printMessage("    --storageFtpRemoteDir  Remote directory, string."); */
        $console->printMessage("  type '" . BackupStorageFactory::STORAGE_YANDEX_DISK . "'");
        $console->printMessage("    --storageYandexDiskToken");
        $console->printMessage("    --storageYandexDiskDir");
      /*  $console->printMessage("  type '" . BackupStorageFactory::STORAGE_MAIL_CLOUD . "'");
        $console->printMessage("    null"); */
    }
}

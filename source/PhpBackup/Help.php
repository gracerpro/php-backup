<?php
namespace PhpBackup;

use PhpBackup\BackupStorageFactory;

class Help
{

    public function printHelp()
    {
        $console = ConsoleOutput::getInstance();

        $console->printMessage("This script creates the backups, clear it, syncronize it to storages.");

        $console->printMessage("Parameters:");
        $console->printMessage("-h, --help           Show help.");
        $console->printMessage("-b, --backup         Run backup action.");
        $console->printMessage("--backupDir          Run backup of the directory action.");
        $console->printMessage("--backupTargetProjectDir Backup project directory.");
        $console->printMessage("--backupTargetDir    Backup directories. If --backupTargetProjectDir sets then contains from relative names.");
        $console->printMessage("--backupTargetDirName Target directory name for backup.");
        $console->printMessage("--clean              Run clean action.");
        $console->printMessage("-f, --configFile     Configuration file, default 'config-local.php'.");
        $console->printMessage("-d, --debug          Debug mode.");
        $console->printMessage("--mysqlDumpOptions   Options for mysqldump.");
        $console->printMessage("--removeArchiveAfterSync Remove archive file after syncronize to storage.");
        
        $console->printMessage("--storageType        Storage type, default '" . BackupStorageFactory::STORAGE_DEFAULT . "\".");

        $console->printMessage("  type \"" . BackupStorageFactory::STORAGE_DISK . "\" (default)");
        $console->printMessage("    --storageDiskDir   Directory for archives, string.");

        $console->printMessage("  type \"" . BackupStorageFactory::STORAGE_GOOGLE_DRIVE . "\"");
        $console->printMessage("    --storageGoogleDriveKeyFile   File name or path to kye file.");
        $console->printMessage("    --storageGoogleDriveFolderId  Folder ID, see in the web url.");

        $console->printMessage("  type \"" . BackupStorageFactory::STORAGE_YANDEX_DISK . "'");
        $console->printMessage("    --storageYandexDiskToken");
        $console->printMessage("    --storageYandexDiskDir");
        /*  $console->printMessage("  type '" . BackupStorageFactory::STORAGE_MAIL_CLOUD . "'");
          $console->printMessage("    null"); */
    }
}

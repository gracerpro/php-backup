<?php
namespace MysqlBackup;

class Help
{

    public function printHelp()
    {
        $console = ConsoleOutput::getInstance();
        
        $console->printMessage("Parameters:");
        $console->printMessage("--help               Show help.");
        $console->printMessage("-f, --configFile     Configuration file.");
        $console->printMessage("--mysqlDumpOptions   Options for mysqldump.");
        $console->printMessage("--sendBackupFileTo   type, default '" . BackupPlacement::PLACEMENT_DEFAULT . "'.");
        $console->printMessage("  type '" . BackupPlacement::PLACEMENT_DISK . "'");
        $console->printMessage("    placementDiskDir   Directory, string.");
        $console->printMessage("  type '" . BackupPlacement::PLACEMENT_YANDEX_DISK . "'");
        $console->printMessage("    null");
        $console->printMessage("  type '" . BackupPlacement::PLACEMENT_MAIL_CLOUD . "'");
        $console->printMessage("    null");
        $console->printMessage("--deleteAfterSendToPlacement  Delete backup file after sending to placement.");
    }
}

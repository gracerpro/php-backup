<?php
namespace MysqlBackup;

class Help
{

    public function printHelp()
    {
        echo "Parameters:\n";
        echo "--help               Show help.\n";
        echo "-f, --configFile     Configuration file.\n";
        echo "--mysqlDumpOptions   Options for mysqldump.\n";
    }
}

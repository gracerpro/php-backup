<?php
namespace MysqlBackup;

class BackupCreator
{

    /**
     * Take the database settings and create dump
     */
    public function create()
    {
        $config = Config::getInstance();
        // connect to mysql
        $dsn = "mysql:host={$config->getDbHost()};dbname={$config->getDbName()};charset={$config->getDbCharset()}";

        try {
            $pdo = new \PDO($dsn, $config->getDbUser(), $config->getDbPassword());
        } catch (Exception $ex) {

        }
        
        // run command
        // wait
        // zip
    }

    public function getFilePath()
    {
        
    }
}

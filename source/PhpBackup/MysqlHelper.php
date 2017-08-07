<?php
namespace PhpBackup;

class MysqlHelper
{

    /** @var \PDO|null */
    private $pdo;

    public function conncet()
    {
        $config = Config::getInstance();
        // connect to mysql
        $dsn = "mysql:host={$config->getDbHost()};dbname={$config->getDbName()};charset={$config->getDbCharset()}";
        $this->pdo = new \PDO($dsn, $config->getDbUser(), $config->getDbPassword());
    }
}

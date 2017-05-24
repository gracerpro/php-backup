<?php
$projectDir = realpath(__DIR__ . '/../..');
$sourceDir = __DIR__;
include_once $sourceDir . '/MysqlBackup.php';
include_once $projectDir . '/vendor/autoload.php';

spl_autoload_register(function($className) use ($projectDir) {
    $classNameWithSlashes = str_replace('\\', '/', $className);
    $filePath = $projectDir . '/source/' . $classNameWithSlashes . '.php';
    if (file_exists($filePath)) {
        require_once $filePath;
    }
});

$mysqlBackup = new \MysqlBackup\MysqlBackup();
$mysqlBackup->run();

<?php

$projectDir = realpath(__DIR__ . '/../..');
$sourceDir = __DIR__;
include_once $sourceDir . '/MysqlBackup.php';

spl_autoload_register(function($className) use ($projectDir) {
    $classNameWithSlashes = str_replace('\\', '/', $className);
    $filePath = $projectDir . '/source/' . $classNameWithSlashes . '.php';
    if (file_exists($filePath)) {
        echo $filePath, "\n";
        require_once $filePath;
    }
});

$mysqlBackup = new \MysqlBackup\MysqlBackup();
$mysqlBackup->run();

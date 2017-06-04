<?php
$projectDir = __DIR__;

include_once $projectDir . '/vendor/autoload.php';

spl_autoload_register(function($className) use ($projectDir) {
    $classNameWithSlashes = str_replace('\\', '/', $className);
    $filePath = $projectDir . '/source/' . $classNameWithSlashes . '.php';
    if (file_exists($filePath)) {
        require_once $filePath;
    }
});

$config = [];
$configFile = __DIR__ . '/config-local.php';
if (is_file($configFile)) {
    $config = require $configFile;
}

$mysqlBackup = new \MysqlBackup\MysqlBackup();
$mysqlBackup->run($config);

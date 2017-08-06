<?php

if (php_sapi_name() != 'cli') {
    echo "This script works only in console.\n";
    exit;
}

$projectDir = __DIR__;

include_once $projectDir . '/vendor/autoload.php';
$isPharArchive = strtolower(substr(__FILE__, 0, 5)) == 'phar:';
if ($isPharArchive) {
    spl_autoload_register(function ($className) {
        $classNameWithSlashes = str_replace('\\', '/', $className);
        $filePath = __DIR__ . '/' . $classNameWithSlashes . '.php';
        echo $filePath, "\n";
        echo __FILE__, "\n";
        if (file_exists($filePath)) {
            include_once $filePath;
        }
    });
} else {
    spl_autoload_register(function($className) {
        $classNameWithSlashes = str_replace('\\', '/', $className);
        $filePath = __DIR__ . '/source/' . $classNameWithSlashes . '.php';
        if (file_exists($filePath)) {
            require_once $filePath;
        }
    });
}

$config = [];
$configFile = \MysqlBackup\MysqlBackup::getRuningDir() . '/config-local.php';
if (is_file($configFile)) {
    $config = require $configFile;
}

$mysqlBackup = new \MysqlBackup\MysqlBackup();
$mysqlBackup->run($config);

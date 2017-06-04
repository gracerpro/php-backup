<?php

$buildDir = __DIR__ . '/build';
$fileName = 'php-backup.phar';
$buildFilePath = $buildDir . '/' . $fileName;

$phar = new Phar($buildFilePath, 0, $fileName);

$phar->buildFromDirectory(__DIR__, '/vendor/');
$phar->buildFromDirectory(__DIR__, '/source/');
$phar->addFile('config.dest.php');
$phar->addFile('README.md');

//$phar->setStub($buildFilePath);

if (Phar::canCompress(Phar::GZ)) {
    $phar->compress(Phar::GZ, 'phar.bz2');
}

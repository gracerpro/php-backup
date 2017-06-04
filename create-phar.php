<?php
echo "Create phar archive.\n";

$buildDir = __DIR__ . '/build';
$fileName = 'php-backup.phar';
$buildFilePath = $buildDir . '/' . $fileName;

$phar = new Phar($buildFilePath, 0, $fileName);

echo "Build...\n";
$phar->buildFromDirectory(__DIR__, '/vendor/');
$phar->buildFromDirectory(__DIR__, '/source/');
$phar->addFile('config.dest.php');
$phar->addFile('README.md');
$phar->addFile('index.php');

$phar->setStub($phar->createDefaultStub('index.php'));

echo "Compress...\n";
if (Phar::canCompress(Phar::GZ)) {
    $compressedFilePath = $buildDir . '/' . $fileName . '.gz';
    if (is_file($compressedFilePath)) {
        echo "Remove old archive file...\n";
        unlink($compressedFilePath);
    }
    $phar->compress(Phar::GZ, 'phar.gz');
}

echo "Ok.\n";

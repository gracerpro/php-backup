<?php
echo "Create phar archive.\n";

$projectDir = dirname(__FILE__);
$buildDir = __DIR__ . '/build';
$fileName = 'php-backup.phar';
$buildFilePath = $buildDir . '/' . $fileName;

$phar = new Phar($buildFilePath, FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME, $fileName);

echo "Build...\n";
$phar->buildFromDirectory($projectDir, '/vendor/');
$phar->buildFromDirectory($projectDir . '/source');
$phar->addFile($projectDir . '/config.dest.php');
$phar->addFile($projectDir . '/README.md');
$phar->addFile($projectDir . '/index.php');

//$phar->setStub($phar->createDefaultStub($projectDir . '/index.php'));

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

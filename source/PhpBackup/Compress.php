<?php
namespace PhpBackup;

class Compress
{

    /**
     * Compress file adding $extension to file path
     *
     * @param string $filePath
     * @param string $extension
     * @return boolean
     * @throws BackupException
     */
    public function zipFile($filePath, $extension = 'gz')
    {
        $returnCode = 0;
        $output = '';

        $command = "gzip -f -S \".{$extension}\" \"{$filePath}\"";
        exec($command, $output, $returnCode);

        if ($returnCode) {
            throw new BackupException("Could not create zip file.");
        }

        return true;
    }

    /**
     * @param string $projectDirectory
     * @param string[] $directories
     * @param string $targetFilePath
     * @throws BackupException
     */
    public function zipDirectories($projectDirectory, $directories, $targetFilePath)
    {
        $zip = new \ZipArchive();
        if (!$zip->open($targetFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE)) {
            throw new BackupException("Could not open zip archive {$targetFilePath}");
        }

        $rootIndex = strlen($projectDirectory) + 1;
        foreach ($directories as $directory) {
            $directoryIterator = new \RecursiveDirectoryIterator($directory);
            $files = new \RecursiveIteratorIterator($directoryIterator, \RecursiveIteratorIterator::LEAVES_ONLY);
            foreach ($files as $name => $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, $rootIndex);
                    $zip->addFile($filePath, $relativePath);
                }
            }
        }
        $zip->close();
    }
}

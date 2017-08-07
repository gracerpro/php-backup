<?php
namespace MysqlBackup;

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
}

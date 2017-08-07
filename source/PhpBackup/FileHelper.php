<?php
namespace PhpBackup;

class FileHelper
{

    /**
     * Remove end slashes and spaces from name
     *
     * @param string $dirName
     */
    public function trimDirName($dirName)
    {

        return rtrim($dirName, " \t\n\r/");
    }

    /**
     * @param string $fileName
     * @return string
     */
    public static function getFileExtension($fileName)
    {
        $dotPos = strrpos($fileName, '.');
        if ($dotPos !== false) {
            return substr($fileName, $dotPos + 1);
        }

        return '';
    }
}

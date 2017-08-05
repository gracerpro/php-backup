<?php

namespace MysqlBackup;

abstract class BackupCreatorBase
{
    
    abstract public function create();
    
    /**
     * @return string
     */
    public function getBackupArchiveExtension(): string
    {
        return 'gz';
    }

    /**
     * @return string
     */
    abstract public function getBackupZippedFilePath(): string;
}

<?php
namespace MysqlBackup\Storages;

use MysqlBackup\BackupCreator;

interface StorageInterface
{

    public function save(BackupCreator $creator);
    
    public function removeOldBackups(BackupCreator $creator);

}

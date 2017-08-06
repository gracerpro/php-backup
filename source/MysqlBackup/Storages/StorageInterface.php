<?php
namespace MysqlBackup\Storages;

use MysqlBackup\CreatorInterface;

interface StorageInterface
{

    public function save(CreatorInterface $creator);
    
    public function removeOldBackups(CreatorInterface $creator);

}

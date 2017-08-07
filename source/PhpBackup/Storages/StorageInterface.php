<?php
namespace PhpBackup\Storages;

use PhpBackup\CreatorInterface;

interface StorageInterface
{

    public function save(CreatorInterface $creator);
    
    public function removeOldBackups(CreatorInterface $creator);

}

<?php
namespace MysqlBackup;

interface TargetInterface
{

    public function save(BackupCreator $creator);
    
    

}

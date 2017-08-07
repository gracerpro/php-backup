<?php

namespace MysqlBackup;

abstract class BackupCreatorBase
{

    const FRECUENCY_DAY = 'day';

    /** @var string */
    protected $frequencyCreation;
    
    abstract public function create();
    
    public function __construct()
    {
        $this->frequencyCreation = self::FRECUENCY_DAY;
    }
    
    /**
     * @return string
     */
    public function getBackupArchiveExtension(): string
    {
        return 'gz';
    }

}

<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace PhpBackup;

interface CreatorInterface
{

    public function getBackupArchiveExtension(): string;

    public function getBackupZippedFilePath(): string;

    public function getBackupFilePath(): string;
}

<?php

return [
    'dbHost' => '',
    'dbPort' => 3306,
    'dbUser' => '',
    'dbPassword' => '',
    'dbName' => '',
    'dbCharset' => 'utf8',
    'removeArchiveAfterSync' => false,
    'storageType' => 'disk',
    //   disk
    'storageDiskDir' => '',
    //   yandex disk
    // https://oauth.yandex.ru/authorize?response_type=token&client_id=xxx
    'storageYandexDiskToken' => '',
    'storageYandexDiskDir' => '',
    //   google disk
    'storageGoogleDriveKeyFile' => 'google-drive-key.json',
    'storageGoogleDriveFolderId' => '',
];

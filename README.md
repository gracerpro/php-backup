# PHP MySQL backup

Create and clean MySQL backups. The backups stores on the different types.



# How to use

* copy this script to the server
* set up configuration file
* run

## MySQL, Site locates to one server



## MySQL, Site locates to different servers


### Disk


### Yandex disk

* [Create new email]
* Create new application https://oauth.yandex.ru/
* Add rules
 ** Yandex.Disk WebDAV API
 ** Yandex.Disk REST API
* Save, remember client_id
* Generage token https://oauth.yandex.ru/authorize?response_type=token&client_id=xxx
* Write token to local configuration file

### Google drive

* [Create new email account]
* Log in
* Go to https://console.developers.google.com
* Create a service account, https://support.google.com/cloud/answer/6158862?hl=en
* Create folder, give the edit permission to service account on this folder, save Folder ID
* Write folder ID to local configuration file

## Clean


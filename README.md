Tool to archive your administration with the following options:
-> Mt940 file import
-> Connect files for each expense on your bankaccount and connect it to files from
    -> Gmail
    -> Ftp server
-> Possible to create a .zip file from a whole year


To install:
-> clone repo
-> setup .env file
-> composer install
-> npm install
-> php artisan migrate
-> npm run dev
-> php artisan key:generate
-> Setup your laravel worker


To connect gmail:
-> Create google project and setup values in .env file
-> Auth your gmail by visiting url: https://app_url/oauth/gmail

To connect ftp server:
-> Setup ftp credentials in .env file
-> Create following folder structure on ftp server
    /archive/files
    /archive/mt940
    /archive/scans
    /archive/zip





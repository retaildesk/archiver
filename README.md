# Archiver

Tool to archive your administration with the following options:

- Mt940 file import
- Connect files for each expense on your bankaccount and connect it to files gmail or ftpserver.
- Possible to create a .zip file from a whole year

## Installation

1. Clone repo
2. Setup .env file (copy .env.example)
3. composer install
4. npm install
5. php artisan migrate
6. npm run dev
7. php artisan key:generate
8. Setup your laravel worker
9. Create user with php artisan user:make

## Connect Gmail
1. Create google project and setup values in .env file
2. Auth your gmail by visiting url: https://app_url/oauth/gmail

## Connect ftp server
1. Setup ftp credentials in .env file
2. Create following folder structure on ftp server
- /archive/files
- /archive/mt940
- /archive/scans
- /archive/zip


## Quick start
1. Start by uploading mt940 file
2. And simply start archiving

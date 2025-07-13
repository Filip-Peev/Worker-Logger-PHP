@echo off

Title Worker-Logger-Starter

php composer.phar require phpoffice/phpspreadsheet
echo .
echo .

start http://127.0.0.1/Worker-Logger-PHP/init_db.php

echo Creating The Database...
echo .
echo .
timeout /t 2
echo .
echo .
start http://127.0.0.1/Worker-Logger-PHP/
echo .
echo .
cmd /k
echo off
set ins_dir4_0=%~1
set VTIGER_HOME=%cd%
echo %VTIGER_HOME%
set version="4.0"

:getinstalldir
if NOT "X%ins_dir4_0%" == "X" goto checkdir

set /p ins_dir4_0="Enter the vtiger CRM 4.0 installation bin directory (For example: C:\Program Files\vtigerCRM3_0\bin: "
goto checkdir

:findstrdir
echo "4.0 install dir is %ins_dir4_0"
echo %WINDIR%
set FIND_STR="%WINDIR%\system32\findstr.exe"
set SLEEP_STR="%WINDIR%\system32\ping.exe"
goto readMySQLparams

:checkdir
if NOT EXIST "%ins_dir4_0%\startvTiger.bat" (
	echo "Invalid vtigerCRM 4.0 installation directory specified"
	set ins_dir4_0=
	goto getinstalldir	
)
goto findstrdir

:readMySQLparams

echo "Reading the vtiger CRM %version% MySQL Parameters"
if %version% == "4.0" (
	echo "Inside 4.0 loop"
	%FIND_STR% /C:"set mysql_" "%ins_dir4_0%\startvTiger.bat" > mysql_params.bat
)	
if %version% == "4.0.1" (
	echo "Inside 4.0.1 loop"
	%FIND_STR% /C:"set mysql_" startvTiger.bat > mysql_params.bat
)	
call mysql_params.bat
set mysql_dir_4_0_1=..\mysql\bin
echo %mysql_dir%
echo %mysql_username%
echo %mysql_password%
echo %mysql_port%
echo %mysql_bundled%
echo %mysql_dir_4_0_1%

if %version% == "4.0" (
	set /p mysql_host_name_4_0="Specify the host name of the vtiger CRM 4.0 mysql server:  "

	echo ^<?php > ..\apache\htdocs\vtigerCRM\migrator_connection.php
	echo $mysql_host_name_old = '%mysql_host_name_4_0%'; >> ..\apache\htdocs\vtigerCRM\migrator_connection.php
	echo $mysql_username_old = '%mysql_username%'; >> ..\apache\htdocs\vtigerCRM\migrator_connection.php
	echo $mysql_password_old = '%mysql_password%'; >> ..\apache\htdocs\vtigerCRM\migrator_connection.php
	echo $mysql_port_old = '%mysql_port%'; >> ..\apache\htdocs\vtigerCRM\migrator_connection.php
	echo ?^> >> ..\apache\htdocs\vtigerCRM\migrator_connection.php

)


goto isMySQLRunning


:isMySQLRunning
echo "Checking whether the vtiger CRM %version% MySQL server is already running"
"%mysql_dir%\bin\mysql" --port=%mysql_port% --user=%mysql_username% --password=%mysql_password% -e "show databases" > NUL
IF ERRORLEVEL 1 goto startmysql
ECHO  "vtiger CRM %version% MySQL Server is already started and running"
if %version% == "4.0" goto dump4_0mysql
if %version% == "4.0.1" goto dumpin4_0_1mysql

:startmysql
echo "Starting %version% vtiger MySQL on port specified by the user"
cd /d %mysql_dir%\bin
start mysqld -b .. --datadir=../data --port=%mysql_port%
%SLEEP_STR% -n 11 127.0.0.1>nul
"%mysql_dir%\bin\mysql" --port=%mysql_port% --user=%mysql_username% --password=%mysql_password% -e "show databases" > NUL
IF ERRORLEVEL 1 goto notstarted
echo "Started vtiger CRM %version% MySQL on port specified by the user"
cd /d %VTIGER_HOME%
if %version% == "4.0" goto dump4_0mysql
if %version% == "4.0.1" goto dumpin4_0_1mysql

:notstarted
cd /d %VTIGER_HOME%
echo ""
echo ""
ECHO "Unable to start the vtiger CRM %version% MySQL server at port %mysql_port%. Check if the port is free"
echo ""
echo ""
set /p pt=Free the port and Press Any Key to Continue...
goto startmysql

:dump4_0mysql
echo set FOREIGN_KEY_CHECKS=0; > vtiger_4_0_dump.txt
"%mysql_dir_4_0_1%\mysqldump" --user=%mysql_username% --password=%mysql_password% --port=%mysql_port% vtigercrm4 >> vtiger_4_0_dump.txt
IF ERRORLEVEL 1 (
	 echo "Unable to take the vtiger CRM %version% database backup. vtigercrm database may be corrupted"
	 goto exitmigration
) 
echo "Data dump taken successfully in vtiger_dump.txt"
"%mysql_dir%/bin/mysql" --user=%mysql_username% --password=%mysql_password% --port=%mysql_port% -e "create database vtigercrm_4_0_bkp"
"%mysql_dir%/bin/mysql" --user=%mysql_username% --password=%mysql_password% --port=%mysql_port% --force vtigercrm_4_0_bkp < vtiger_4_0_dump.txt
rem "%mysql_dir%/bin/mysql" --user=%mysql_username% --password=%mysql_password% --port=%mysql_port% vtigercrm_4_0_bkp < migrate_4_0to4_0_1.sql
..\php\php.exe -f ..\apache\htdocs\vtigerCRM\Migrate.php
echo set FOREIGN_KEY_CHECKS=0; > migrated_vtiger_4_0_1_dump.txt
"%mysql_dir_4_0_1%\mysqldump" --user=%mysql_username% --password=%mysql_password% --port=%mysql_port% vtigercrm_4_0_bkp >> migrated_vtiger_4_0_1_dump.txt
"%mysql_dir%/bin/mysql" --user=%mysql_username% --password=%mysql_password% --port=%mysql_port% -e "drop database vtigercrm_4_0_bkp"
goto stopMySQL

:stopMySQL
if %mysql_bundled% == true (
        cd /d %mysql_dir%\bin
	echo "Going to stop vtiger CRM %version% MySQL server"
        "%mysql_dir%\bin\mysqladmin" --port=%mysql_port% --user=%mysql_username% --password=%mysql_password% shutdown
        echo "vtiger CRM  MySQL Sever is shut down"
        cd /d %VTIGER_HOME%
	%SLEEP_STR% -n 11 127.0.0.1>nul
)
goto set4_0_1version

:set4_0_1version
set version="4.0.1"
goto readMySQLparams

:dumpin4_0_1mysql
"%mysql_dir%/bin/mysql" --user=%mysql_username% --password=%mysql_password% --port=%mysql_port% -e "drop database vtigercrm4_0_1"
"%mysql_dir%/bin/mysql" --user=%mysql_username% --password=%mysql_password% --port=%mysql_port% -e "create database if not exists vtigercrm4_0_1"
"%mysql_dir%/bin/mysql" --user=%mysql_username% --password=%mysql_password% --port=%mysql_port% vtigercrm4_0_1 --force < migrated_vtiger_4_0_1_dump.txt  2> migrate_log.txt
IF ERRORLEVEL 1 (
	 echo "Unable to dump data into the vtiger CRM %version% database vtigercrm4_0_1. Check the migrate_log.txt in the %VTIGER_HOME% directory"
	 goto exitmigration
)
echo "Data successfully migrated into vtiger CRM 4.0.1 database"
goto end 

:exitmigration
echo "Exiting Migration"

:end
del mysql_params.bat
echo ""

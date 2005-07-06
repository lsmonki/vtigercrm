echo off
set ins_dir4_0=%~1
set VTIGER_HOME=%cd%
echo %VTIGER_HOME%
set version="4.0.1"

:getinstalldir
if NOT "X%ins_dir4_0%" == "X" goto checkdir

echo "*******************"
echo "*******************"
echo "*******************"
set /p diffmac="Is the vtiger CRM 4.0.1 mysql db in the same machine as the vtigerCRM4_2GA mysql db installation? (Y/N): "
if NOT "%diffmac%"=="Y" (
 get the 4.0.1 machinename, the mysqluser name, mysql port, mysql password of the 4.0.1 mysql db containing machine
set /p diffmac_uname="Enter the vtiger CRM 4.0.1 mysql db username? "
set /p diffmac_password="Enter the vtiger CRM 4.0.1 mysql db password?: "
set /p diffmac_port="Enter the vtiger CRM 4.0.1 mysql db port?: "
set /p diffmac_hostname="Enter the hostname of  the machine hosting the vtiger CRM 4.0.1?: "
rem then store the data in the mysql_params.bat file
echo "%diffmac_username%"
"set mysql_username =" echo "%diffmac_uname%" > mysql_params.bat
"set mysql_password =" echo "%diffmac_password%" > mysql_params.bat
"set mysql_port =" echo "%diffmac_port%" > mysql_params.bat
"set mysql_hostname =" echo "%diffmac_hostname%" > mysql_params.bat

rem use the mysql script to get the dump and other details as normal
echo ' about to dump the migrated database to the vtiger_4_2_dump.txt file'
echo set FOREIGN_KEY_CHECKS=0; > vtiger_4_2_dump.txt
"%mysql_dir_4_2%\mysqldump" --user=%diffmac_uname% --password=%diffmac_password% --port=%diffmac_port% vtigercrm4_0_1 >> vtiger_4_2_dump.txt
IF ERRORLEVEL 1 (
	 echo "Unable to take the vtiger CRM %version% database backup. vtigercrm database may be corrupted"
	 goto exitmigration
) 
call mysql_params.bat
goto invokemysqlparams
	echo ^<?php > ..\apache\htdocs\vtigerCRM\migrator_connection.php
	echo $mysql_host_name_old = '%mysql_host_name_4_0%'; >> ..\apache\htdocs\vtigerCRM\migrator_connection.php
	echo $mysql_username_old = '%mysql_username%'; >> ..\apache\htdocs\vtigerCRM\migrator_connection.php
	echo $mysql_password_old = '%mysql_password%'; >> ..\apache\htdocs\vtigerCRM\migrator_connection.php
	echo $mysql_port_old = '%mysql_port%'; >> ..\apache\htdocs\vtigerCRM\migrator_connection.php
	echo ?^> >> ..\apache\htdocs\vtigerCRM\migrator_connection.php
)

rem the below line loops till the user gives the proper data. the prompt cannot be killed in any manner, so need to fix this asap.
set /p ins_dir4_0="Enter the vtiger CRM 4.0.1 installation bin directory (For example: C:\Program Files\vtigerCRM4_0_1\bin: "
goto checkdir

:findstrdir
echo "4.0.1 install dir is %ins_dir4_0%"
echo %WINDIR%
set FIND_STR="%WINDIR%\system32\findstr.exe"
set SLEEP_STR="%WINDIR%\system32\ping.exe"
goto readMySQLparams

:checkdir
if NOT EXIST "%ins_dir4_0%\startvTiger.bat" (
	echo "Kindly specify a valid vtigerCRM 4.0.1 installation directory please"
	set ins_dir4_0=
	goto getinstalldir	
)
goto findstrdir

:readMySQLparams

echo "Reading the vtiger CRM %version% MySQL Parameters"
if %version% == "4.0.1" (
	echo "Inside 4.0.1 loop"
echo 'about to parse the startvTiger.bat of the 4.0.1 server and populate to mysql_params file
	%FIND_STR% /C:"set mysql_" "%ins_dir4_0%\startvTiger.bat" > mysql_params.bat
)	
if %version% == "4.2" (
	echo "Inside 4.2 loop"
echo 'about to parse the startvTiger.bat of the 4.2 server and populate to mysql_params file
	%FIND_STR% /C:"set mysql_" startvTiger.bat > mysql_params.bat
)	
call mysql_params.bat
set mysql_dir_4_0_1=..\mysql\bin
echo %mysql_dir%
:invokemysqlparams
echo %mysql_username%
echo %mysql_password%
echo %mysql_port%
echo %mysql_bundled%
echo %mysql_dir_4_0_1%
goto end

if %version% == "4.0.1" (
	set /p mysql_host_name_4_0="Specify the host name of the vtiger CRM 4.0.1 mysql server:  "

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
if %version% == "4.0.1" goto dump4_0_1mysql
if %version% == "4.2" goto dumpin4_2mysql

:startmysql
echo "Starting %version% vtiger MySQL on port specified by the user"
cd /d %mysql_dir%\bin
start mysqld -b .. --datadir=../data --port=%mysql_port%
%SLEEP_STR% -n 11 127.0.0.1>nul
"%mysql_dir%\bin\mysql" --port=%mysql_port% --user=%mysql_username% --password=%mysql_password% -e "show databases" > NUL
IF ERRORLEVEL 1 goto notstarted
echo "Started vtiger CRM %version% MySQL on port specified by the user"
cd /d %VTIGER_HOME%
if %version% == "4.0.1" goto dump4_0_1mysql
if %version% == "4.2" goto dumpin4_2mysql

:notstarted
cd /d %VTIGER_HOME%
echo ""
echo ""
ECHO "Unable to start the vtiger CRM %version% MySQL server at port %mysql_port%. Check if the port is free"
echo ""
echo ""
set /p pt=Free the port and Press Any Key to Continue...
goto startmysql

:dump4_0_1mysql
echo ' about to dump the migrated database to the vtiger_4_2_dump.txt file'
echo set FOREIGN_KEY_CHECKS=0; > vtiger_4_2_dump.txt
"%mysql_dir_4_0_1%\mysqldump" --user=%mysql_username% --password=%mysql_password% --port=%mysql_port% vtigercrm4_0_1 >> vtiger_4_2_dump.txt
IF ERRORLEVEL 1 (
	 echo "Unable to take the vtiger CRM %version% database backup. vtigercrm database may be corrupted"
	 goto exitmigration
) 
echo "Data dump taken successfully in vtiger_dump.txt"
"%mysql_dir%/bin/mysql" --user=%mysql_username% --password=%mysql_password% --port=%mysql_port% -e "create database vtigercrm_4_0_1_bkp"
"%mysql_dir%/bin/mysql" --user=%mysql_username% --password=%mysql_password% --port=%mysql_port% --force vtigercrm_4_0_1_bkp < vtiger_4_2_dump.txt
rem "%mysql_dir%/bin/mysql" --user=%mysql_username% --password=%mysql_password% --port=%mysql_port% vtigercrm_4_0_1_bkp < migrate_4_0to4_0_1.sql

echo 'about to start the input from the DataMigration.php file '
..\php\php.exe -f ..\apache\htdocs\vtigerCRM\Migrate.php
echo 'exporting the migrated data to the dump file migrated_vtiger_4_0_1_dump file'

echo set FOREIGN_KEY_CHECKS=0; > migrated_vtiger_4_0_1_dump.txt
"%mysql_dir_4_0_1%\mysqldump" --user=%mysql_username% --password=%mysql_password% --port=%mysql_port% vtigercrm_4_0_1_bkp >> migrated_vtiger_4_0_1_dump.txt

echo ' about to drop the vtigercrm_4_0_1_bkp database '
"%mysql_dir%/bin/mysql" --user=%mysql_username% --password=%mysql_password% --port=%mysql_port% -e "drop database vtigercrm_4_0_1_bkp"
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
goto set4_2version

:set4_2version
set version="4.2"
echo '######################## version set as 4.2 vtiger CRM ######################## '
goto readMySQLparams

:dumpin4_2mysql
echo 'about to drop 4_2 db'
"%mysql_dir%/bin/mysql" --user=%mysql_username% --password=%mysql_password% --port=%mysql_port% -e "drop database vtigercrm4_2"
echo 'about create if not exists drop 4_2 db'
"%mysql_dir%/bin/mysql" --user=%mysql_username% --password=%mysql_password% --port=%mysql_port% -e "create database if not exists vtigercrm4_2"
echo 'about to force dump data to the 4_2 db from the migrated_vtiger_4_0_1_dump file'
"%mysql_dir%/bin/mysql" --user=%mysql_username% --password=%mysql_password% --port=%mysql_port% vtigercrm4_2 --force < migrated_vtiger_4_0_1_dump.txt  2> migrate_log.txt
IF ERRORLEVEL 1 (
	 echo "Unable to dump data into the vtiger CRM %version% database vtigercrm4_2. Check the migrate_log.txt in the %VTIGER_HOME% directory"
	 goto exitmigration
)
echo "Data successfully migrated into vtiger CRM 4.2 database"
goto end 

:exitmigration
echo "Exiting Migration"

:end
del mysql_params.bat
echo ""

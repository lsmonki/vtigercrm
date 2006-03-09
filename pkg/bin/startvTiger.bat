echo off
set mysql_dir=MYSQLINSTALLDIR
set mysql_username=MYSQLUSERNAME
set mysql_password=MYSQLPASSWORD
set mysql_port=MYSQLPORT
set mysql_bundled=MYSQLBUNDLEDSTATUS
set apache_dir=APACHEINSTALLDIR
set apache_bin=APACHEBIN
set apache_conf=APACHECONF
set apache_port=APACHEPORT
set apache_bundled=APACHEBUNDLED
set apache_service=APACHESERVICE


echo %WINDIR%
set FIND_STR="%WINDIR%\system32\findstr.exe"
set SLEEP_STR="%WINDIR%\system32\ping.exe"
goto initiate

:initiate
rem if true means , vtiger crm mysql is being used
if "test" == "%1test" goto start1
set VTIGER_HOME=%1
goto start2

:start1
cd ..
set VTIGER_HOME=%cd%
:start2

if %apache_bundled% == true goto checkBundledApache
if %apache_bundled% == false goto checkUserApache

:checkBundledApache
echo "APACHEBUNDLED"
cd /d %apache_dir%
if %apache_service% == true goto StartApacheService
start bin\Apache -f conf\httpd.conf
IF ERRORLEVEL 1 goto stopservice
goto checkmysql

:StartApacheService
echo ""
echo "making an attempt to kill any existing vtigercrm service"
echo ""
bin\apache -k stop -n vtigercrm4_2
bin\apache -k uninstall -n vtigercrm4_2
echo ""
echo ""
echo "installing vtigercrm4_2 apache service"
echo ""
echo ""
bin\apache -k install -n vtigercrm4_2 -f conf\httpd.conf
echo ""
echo "Starting  vtigercrm4_2 apache service"
echo ""
bin\apache -n vtigercrm4_2 -k start
IF ERRORLEVEL 1 goto stopservice
goto checkmysql

:checkUserApache
netstat -anp tcp >port.txt
%FIND_STR% "\<%apache_port%\>" port.txt
if ERRORLEVEL 1 goto apachenotrunning
%FIND_STR% "\<%apache_port%\>" port.txt >list.txt
%FIND_STR% "LISTEN.*" list.txt
if ERRORLEVEL 1 goto apachenotrunning
echo ""
echo "Apache is running"
echo ""
goto checkmysql

:apachenotrunning
echo ""
echo ""
echo "Apache in the location %apache_dir% is not running. Start Apache and then start vtiger crm"
echo ""
echo ""
set /p pt=Press Any Key to Continue...
goto end
                
:checkmysql
cd /d %mysql_dir%\bin
echo %cd%

echo ""
echo "Checking the whether the MySQL server is already running"
echo ""
mysql --port=%mysql_port% --user=%mysql_username% --password=%mysql_password% -e "show databases" > NUL
IF ERRORLEVEL 1 goto startmysql 
echo ""
echo ""
ECHO  "MySQL is already started and running"
echo ""
echo ""
goto checkdatabase


:startmysql
echo ""
echo "Starting MySQL on port specified by the user"
echo ""
start mysqld -b .. --datadir=../data --port=%mysql_port%
%SLEEP_STR% -n 11 127.0.0.1>nul
mysql --port=%mysql_port% --user=%mysql_username% --password=%mysql_password% -e "show databases" > NUL
IF ERRORLEVEL 1 goto notstarted
echo ""
echo "Started MySQL on port specified by the user"
echo ""
goto checkdatabase


:checkdatabase
echo ""
echo "check to see if vtigercrm4_2 database already exists"
echo ""
mysql --port=%mysql_port% --user=%mysql_username% --password=%mysql_password% -e "show databases like 'vtigercrm4_2'" | "%WINDIR%\system32\find.exe" "vtigercrm4_2" > NUL
IF ERRORLEVEL 1 goto dbnotexists
echo ""
ECHO  "vtigercrm4_2 database exists"
echo ""
goto end


:dbnotexists
echo ""
ECHO "vtigercrm4_2 database does not exist"
echo ""
echo %cd%
echo ""
echo "Proceeding to create database vtigercrm4_2 and populate the same"
echo ""
mysql --user=%mysql_username% --password=%mysql_password% --port=%mysql_port% -e "create database if not exists vtigercrm4_2"
echo ""
echo "vtigercrm4_2 database created"
echo ""
goto end

:notstarted
echo ""
echo ""
ECHO "Unable to start the MySQL server at port %mysql_port%. Check if the port is free"
echo ""
echo ""
set /p pt=Press Any Key to Continue...
goto end

:stopservice
echo ""
echo ""
echo ""
echo "********* Service not started as port # %apache_port% occupied ******* "
echo "********* Kindly free port %apache_port% and restart again ******* "
echo ""
echo ""
echo ""
set /p pt=Press Any Key to Continue...
goto end


:end
cd /d %VTIGER_HOME%\bin



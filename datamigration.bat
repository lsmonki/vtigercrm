set VTIGER_HOME=%cd%

set /p mysql_host_name_3_2="Specify the host name of the vtiger CRM 3.2 mysql server:  "
set /p mysql_user_name_3_2="Specify the user name of the vtiger CRM 3.2 mysql server:  "
set /p mysql_password_3_2="Specify the password of the vtiger CRM 3.2 mysql server:  "
set /p mysql_port_3_2="Specify the port of the vtiger CRM 3.2 mysql server: "


echo ^<?php > ..\apache\htdocs\vtigerCRM\migrator_connection.php
echo $mysql_host_name_old = '%mysql_host_name_3_2%'; >> ..\apache\htdocs\vtigerCRM\migrator_connection.php
echo $mysql_username_old = '%mysql_user_name_3_2%'; >> ..\apache\htdocs\vtigerCRM\migrator_connection.php
echo $mysql_password_old = '%mysql_password_3_2%'; >> ..\apache\htdocs\vtigerCRM\migrator_connection.php
echo $mysql_port_old = '%mysql_port_3_2%'; >> ..\apache\htdocs\vtigerCRM\migrator_connection.php
echo ?^> >> ..\apache\htdocs\vtigerCRM\migrator_connection.php

..\php\php.exe -f ..\apache\htdocs\vtigerCRM\Migrator.php


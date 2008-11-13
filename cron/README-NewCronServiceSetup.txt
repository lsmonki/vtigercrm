To setup a new cron service
===========================

1. Create <ServiceName>.service file, which has the following content at the beginning

<?php

require_once('config.inc.php');

/** Verify the script call is from trusted place. */
global $application_unique_key;
if($_REQUEST['app_key'] != $application_unique_key) {
	echo "Access denied!";
	exit;
}

// ... REST OF YOUR CODE ...

?>


2. Create <ServiceName>Cron.sh file which should have the following:

wget "http://localhost:APACHEPORT/vtigercron.php?service=<ServiceName>&app_key=YOUR_APP_KEY_HERE&<param>=<value>" -O /dev/null

3. Create <ServiceName>Cron.bat file which should have the following:

@echo off

set VTIGERCRM_ROOTDIR="C:\Program Files\vtigercrm5\apache\htdocs\vtigerCRM"
set PHP_EXE="C:\Program Files\vtigercrm5\php\php.exe"

cd /D %VTIGERCRM_ROOTDIR%

%PHP_EXE% -f vtigercron.php service="<ServiceName>" app_key="YOUR_APP_KEY_HERE" <param>="<value>"


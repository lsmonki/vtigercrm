<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/


global $Server_Path;
global $Portal_Path;

//This is the vtigerCRM Server Path where contactserialize.php file is located
$Server_Path = ""; //for eg. http://vtiger-server:90 

//This is the Customer Portal path where CustomerAuthenticate.php file is located 
$Authenticate_Path = ""; //for eg. http://your-server/vtiger_customerportal 

//Give a temporary directory path which is used when we upload attachment
$upload_dir = '/tmp';

//These are the Proxy Settings parameters
$proxy_host = ''; //Host Name of the Proxy
$proxy_port = ''; //Port Number of the Proxy
$proxy_username = ''; //User Name of the Proxy
$proxy_password = ''; //Password of the Proxy

?>

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

//These are the Proxy Settings parameters
$Proxy_Host = ''; //Host Name of the Proxy
$Proxy_Port = ''; //Port Number of the Proxy
$Proxy_Username = ''; //User Name of the Proxy
$Proxy_Password = ''; //Password of the Proxy

?>

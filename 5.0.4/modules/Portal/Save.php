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

require_once('modules/Portal/Portal.php');

global $default_charset;
$conv_pname = function_exists(iconv) ? @iconv("UTF-8",$default_charset, $_REQUEST['portalname']) : $_REQUEST['portalname'];
$conv_purl = function_exists(iconv) ? @iconv("UTF-8",$default_charset, $_REQUEST['portalurl']) : $_REQUEST['portalurl'];
$portlname =str_replace(array("'",'"'),'',$conv_pname);
$portlurl =str_replace(array("'",'"'),'',$conv_purl);

if($portlname != '' && $portlurl != '')
{
	if(isset($_REQUEST['record']) && $_REQUEST['record'] !='')
	{
		$result=UpdatePortal($portlname,"http://".str_replace("#$#$#","&",$portlurl),$_REQUEST['record']);
	}
	else
	{
		$result=SavePortal($portlname,"http://".str_replace("#$#$#","&",$portlurl));
	}
	header("Location: index.php?action=PortalAjax&module=Portal&file=ListView&mode=ajax&datamode=manage");
}else
{
	echo ":#:FAILURE";
}
?>

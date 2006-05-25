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

if(isset($_REQUEST['record']) && $_REQUEST['record'] !='')
{
	$result=UpdatePortal($_REQUEST['portalname'],$_REQUEST['portalurl'],$_REQUEST['record']);
}else
{
	$result=SavePortal($_REQUEST['portalname'],$_REQUEST['portalurl']);
}
header("Location: index.php?action=PortalAjax&module=Portal&file=ListView&mode=ajax&datamode=manage");
?>

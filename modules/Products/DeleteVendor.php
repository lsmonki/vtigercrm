<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the 
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /cvsroot/vtigercrm/vtiger_crm/modules/Products/DeleteVendor.php,v 1.2 2005/07/05 09:26:57 saraj Exp $
 * Description:  Deletes an Account record and then redirects the browser to the 
 * defined return URL.
 ********************************************************************************/

require_once('modules/Products/Vendor.php');
global $mod_strings;

require_once('include/logging.php');
$log = LoggerManager::getLogger('product_delete');

$focus = new Vendor();

if(!isset($_REQUEST['record']))
	die($mod_strings['ERR_DELETE_RECORD']);


if($_REQUEST['module'] == $_REQUEST['return_module'])
{
	$focus->mark_deleted($_REQUEST['record']);
	$smod ="smodule=VENDOR&";
}
header("Location: index.php?".$smod."module=".$_REQUEST['return_module']."&action=".$_REQUEST['return_action']."&record=".$_REQUEST['return_id']);
?>

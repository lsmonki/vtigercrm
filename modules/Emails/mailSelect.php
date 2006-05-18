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
require_once('Smarty_setup.php');
require_once('include/database/PearDatabase.php');


global $app_strings;
global $mod_strings;

global $theme;
$image_path = 'themes/'.$theme.'/images/';
$idlist = $_REQUEST['idlist'];
$pmodule=$_REQUEST['return_module'];
$ids=explode(';',$idlist);

$smarty = new vtigerCRM_Smarty;
if ($pmodule=='Accounts')
{
	$querystr="select fieldid,fieldlabel,columnname,tablename from field where tabid=6 and uitype=13;"; 
}
elseif ($pmodule=='Contacts')
{
	$querystr="select fieldid,fieldlabel,columnname from field where tabid=4 and uitype=13;";
}
elseif ($pmodule=='Leads')
{
	$querystr="select fieldid,fieldlabel,columnname from field where tabid=7 and uitype=13;";
}
$result=$adb->query($querystr);
$numrows = $adb->num_rows($result);
$returnvalue = Array();
for ($i=0;$i<$numrows;$i++)
{
	$value = Array();
	$temp=$adb->query_result($result,$i,'columnname');
	$fieldid=$adb->query_result($result,$i,'fieldid');
	$value[] =$adb->query_result($result,$i,'fieldlabel');
	$value[]= br2nl($myfocus->column_fields[$temp]); 
	$returnvalue [$fieldid]= $value;
}
$smarty->assign('MAILINFO',$returnvalue);
$smarty->assign("MOD", $mod_strings);
$smarty->assign("IDLIST", $idlist);
$smarty->assign("APP", $app_strings);
$smarty->assign("FROM_MODULE", $pmodule);
$smarty->assign("IMAGE_PATH",$image_path);

$smarty->display("SelectEmail.tpl");
?>

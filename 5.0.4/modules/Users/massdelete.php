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


require_once('include/database/PearDatabase.php');
require_once('include/utils/UserInfoUtil.php');
require_once('include/utils/CommonUtils.php');
$idlist = $_REQUEST['idlist'];
$viewid = $_REQUEST['viewname'];
$returnmodule=$_REQUEST['return_module'];
$return_action = $_REQUEST['return_action'];
$rstart='';
//split the string and store in an array
$storearray = explode(";",$idlist);
array_filter($storearray);
$ids_list = array();
foreach($storearray as $id)
{
        if(isPermitted($returnmodule,'Delete',$id) == 'yes')
        {
		global $current_user;
                require_once('include/freetag/freetag.class.php');
                $freetag=new freetag();
                $freetag->delete_all_object_tags_for_user($current_user->id,$id);
                $sql="update vtiger_crmentity set deleted=1 where crmid='" .$id ."'";
                $result = $adb->query($sql);
		if($returnmodule == 'Accounts')
			delAccRelRecords($id);
        }
        else
        {
                $ids_list[] = $id;
        }
}
$ret = getEntityName($returnmodule,$ids_list);
if(count($ret) > 0)
{
       $errormsg = implode(',',$ret);
}else
{
       $errormsg = '';
}

if(isset($_REQUEST['smodule']) && ($_REQUEST['smodule']!=''))
{
	$smod = "&smodule=".$_REQUEST['smodule'];
}
if(isset($_REQUEST['start']) && ($_REQUEST['start']!=''))
{
	$rstart = "&start=".$_REQUEST['start'];
}
if($returnmodule == 'Emails')
{
	if(isset($_REQUEST['folderid']) && $_REQUEST['folderid'] != '')
	{
		$folderid = $_REQUEST['folderid'];
	}else
	{
		$folderid = 1;
	}
	header("Location: index.php?module=".$returnmodule."&action=".$returnmodule."Ajax&folderid=".$folderid."&ajax=delete".$rstart."&file=ListView&errormsg=".$errormsg);
}
elseif($return_action == 'ActivityAjax')
{
	$subtab = $_REQUEST['subtab'];
	header("Location: index.php?module=".$returnmodule."&action=".$return_action."".$rstart."&view=".$_REQUEST['view']."&hour=".$_REQUEST['hour']."&day=".$_REQUEST['day']."&month=".$_REQUEST['month']."&year=".$_REQUEST['year']."&type=".$_REQUEST['type']."&viewOption=".$_REQUEST['viewOption']."&subtab=".$subtab);
}
			
elseif($returnmodule!='Faq')
{
	header("Location: index.php?module=".$returnmodule."&action=".$returnmodule."Ajax&ajax=delete".$rstart."&file=ListView&viewname=".$viewid."&errormsg=".$errormsg);
}
else
{
	header("Location: index.php?module=".$returnmodule."&action=".$returnmodule."Ajax&ajax=delete".$rstart."&file=ListView&errormsg=".$errormsg);
}
?>


<?
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 ********************************************************************************/

global $current_user;
global $adb;
if(isset($_REQUEST['announce_rss']) && ($_REQUEST['announce_rss'] != ''))
{
	$sql="select * from announcement order by time";
	$result=$adb->query($sql);
	$announcement=$adb->query_result($result,$adb->num_rows($result)-1,'announcement');
	echo $announcement; 
		
}elseif(isset($_REQUEST['announce_save']) && ($_REQUEST['announce_save'] != ''))
{
	$date_var = date('YmdHis');
	$announcement = $_REQUEST['announcement'];
	$title = $_REQUEST['title_announcement'];
	$sql="select * from announcement where creatorid=".$current_user->id;
	$is_announce=$adb->query($sql);
	if($adb->num_rows($is_announce) > 0)
	{
			$query="update announcement set announcement='".$announcement."',time='".$adb->formatString("announcement","time",$date_var)."',title='".$title."' where creatorid=".$current_user->id;
	}
	else
		$query="insert into announcement values ('".$announcement."',".$current_user->id.",'".$date_var."','".$title."')";
	$result=$adb->query($query);	
	echo $announcement; 
}

?>

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

global $current_user;
if($current_user->is_admin != 'on')
{
	die("<br><br><center>".$app_strings['LBL_PERMISSION']." <a href='javascript:window.history.back()'>".$app_strings['LBL_GO_BACK'].".</a></center>");
}

// Remove the Migration.tpl file from Smarty cache
$migration_tpl_file = get_smarty_compiled_file('Migration.tpl');
if ($migration_tpl_file != null) unlink($migration_tpl_file);

include("modules/Migration/versions.php");
require_once('Smarty_setup.php');
global $app_strings,$app_list_strings,$mod_strings,$theme,$currentModule;
include("vtigerversion.php");
//Check the current version before starting migration. If the current versin is latest, then we wont allow to do 5.x migration. But here we must allow for 4.x migration. Because 4.x migration can be done with out changing the current database. -Shahul
$status=true;
$exists=$adb->query("show create table vtiger_version");
if($exists)
{
	$result = $adb->query("select * from vtiger_version");
	$dbversion = $adb->query_result($result, 0, 'current_version');
	if($dbversion == $vtiger_current_version)
	{
		$status=false;
	}

}
if(isset($_REQUEST['dbconversionutf8']))
{
	if($_REQUEST['dbconversionutf8'] == 'yes')
	{
	$query = " ALTER DATABASE ".$dbconfig['db_name']." DEFAULT CHARACTER SET utf8";
	$adb->query($query);
	}
}	
//Added to check database charset and $default_charset are set to UTF8.
//If both are not set to be UTF-8, Then we will show an alert message.
function check_db_utf8_support($conn) 
{ 
	$dbvarRS = &$conn->query("show variables like '%_database' "); 
	$db_character_set = null; 
	$db_collation_type = null; 
	while(!$dbvarRS->EOF) { 
		$arr = $dbvarRS->FetchRow(); 
		$arr = array_change_key_case($arr); 
		switch($arr['variable_name']) { 
		case 'character_set_database' : $db_character_set = $arr['value']; break; 
		case 'collation_database'     : $db_collation_type = $arr['value']; break; 
		}
		// If we have all the required information break the loop. 
		if($db_character_set != null && $db_collation_type != null) break; 
	} 
	return (stristr($db_character_set, 'utf8') && stristr($db_collation_type, 'utf8')); 
}

	global $adb,$default_charset;
	$db_status=check_db_utf8_support($adb);
	if(strtolower($default_charset) == 'utf-8')	$config_status=1;
	else						$config_status=0;

	$db_migration_status =true;

	if(!$db_status && !$config_status)
	{
		$msg='<font color="red"><b>Your database charset and $default_charset variable in config.inc.php are not set to UTF-8. Due to that you may not use UTF-8 characters in vtigerCRM. Please set the above to UTF-8</b></font>';
	}
	else if($db_status && !$config_status)
	{
	       	$db_migration_status = false;
		$msg='<font color="red"><b>&nbsp;&nbsp; Your database charset is set as UTF-8. But $default_charset variable in config.inc.php is not set to UTF-8. Due to that you may not use UTF-8 characters in vtigerCRM.<br>If you want use UTF-8 charset , please set the $default_charset variable to UTF-8 in config.inc.php file. </b></font>';

	}
	else if(!$db_status && $config_status)
	{
	       	$db_migration_status = false;
		$msg='<font color="red"><b> &nbsp;&nbsp; Your $default_charset variable in config.inc.php is set as UTF-8. But your database charset is not set as UTF-8. Due to that you may not use UTF-8 characters in vtigerCRM. <br/> If you want use UTF-8 charset , please click convert database button.<br/> Otherwise continue your migration , both $default_charset variable in config.inc.php and database charset are must same.</b></font>';

	}
	
$smarty = new vtigerCRM_Smarty();

$smarty->assign("CHARSET_CHECK", $msg);
$smarty->assign("MIG_CHECK", $status);
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("MODULE","Migration");

$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);

$source_versions = "<select id='source_version' name='source_version'>";
foreach($versions as $ver => $label)
{
	$source_versions .= "<option value='".$ver."'> $label </option>";
}
$source_versions .= "</select>";

$smarty->assign("SOURCE_VERSION", $source_versions);
global $vtiger_current_version;
$smarty->assign("CURRENT_VERSION", $vtiger_current_version);

if($db_migration_status == false)
{
	echo '<br><br>
	<font color ="red"> <ul><li>Changes made to database during migration cannot be reverted back. So we highly recommend to take database dump of the current working database before migration.</ul></font><br/> <br/></table><table border="1" cellpadding="3" cellspacing="0" height="100%" width="80%" align="center">
		<tr>
		<td colspan="2" align="center"><br>';
	echo $msg;
	echo '<br><br><form name="html_to_utf" method="post" action="index.php">
					<input type="hidden" name="module" value="Migration">
					<input type="hidden" name="action" value="index">
				      	<input type="hidden" name="dbconversionutf8" value = "">
					<input type="hidden" name="parenttab" value="Settings">';
				if(!$db_status && $config_status){	
			echo	'<input type="button" name="close" value=" &nbsp;Convert Database &nbsp; " onclick ="dbcovert();" class="crmbutton small save" />';
}
		echo  '	<input type="submit" name="close" value=" &nbsp;Continue &nbsp; " class="crmbutton small save" />
			</form>
<script>function dbcovert(){getObj("dbconversionutf8").value="yes";document.html_to_utf.submit();}</script>
			<br>
			</td>
		</tr>
	</table><br><br>';

	//echo "<a href='index.php?action=index&module=".$_REQUEST['module']."&parenttab=".$_REQUEST['parenttab']."'>Continue</a>";
	exit;	
}
else
	$smarty->display("Migration.tpl");

//include("modules/Migration/DBChanges/501_to_502.php");

?>

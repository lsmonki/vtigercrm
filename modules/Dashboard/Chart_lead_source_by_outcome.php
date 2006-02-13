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
 * $Header: /cvsroot/vtigercrm/vtiger_crm/modules/Dashboard/Chart_lead_source_by_outcome.php,v 1.17 2005/05/03 13:18:53 saraj Exp $
 * Description:  returns HTML for client-side image map.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('include/utils.php');
require_once('include/logging.php');
require_once("modules/Potentials/Charts.php");
require_once('include/ComboUtil.php');
global $current_language, $tmp_dir, $currentModule, $action;
$current_module_strings = return_module_language($current_language, 'Dashboard');

// Get _dom arrays from Database
$comboFieldNames = Array('leadsource'=>'lead_source_dom');
$comboFieldArray = getComboArray($comboFieldNames);

$log = LoggerManager::getLogger('lead_source_by_outcome');

if (isset($_REQUEST['lsbo_refresh'])) { $refresh = $_REQUEST['lsbo_refresh']; }
else { $refresh = false; }

// added for auto refresh
$refresh = true;
//

$tempx = array();
$datax = array();
//get list of sales stage keys to display
if (isset($_SESSION['lsbo_lead_sources']) && count($_SESSION['lsbo_lead_sources']) > 0 && !isset($_REQUEST['lsbo_lead_sources'])) {
	$tempx = $_SESSION['lsbo_lead_sources'];
	$log->debug("_SESSION['lsbo_lead_sources'] is:");
	$log->debug($_SESSION['lsbo_lead_sources']);
}
elseif (isset($_REQUEST['lsbo_lead_sources']) && count($_REQUEST['lsbo_lead_sources']) > 0) {
	$tempx = $_REQUEST['lsbo_lead_sources'];
	$current_user->setPreference('lsbo_lead_sources', $_REQUEST['lsbo_lead_sources']);
	$log->debug("_REQUEST['lsbo_lead_sources'] is:");
	$log->debug($_REQUEST['lsbo_lead_sources']);
	$log->debug("_SESSION['lsbo_lead_sources'] is:");
	$log->debug($_SESSION['lsbo_lead_sources']);
}

//set $datax using selected sales stage keys 
if (count($tempx) > 0) {
	foreach ($tempx as $key) {
		$datax[$key] = $comboFieldArray['lead_source_dom'][$key];
	}
}
else {
	$datax = $comboFieldArray['lead_source_dom'];
}
$log->debug("datax is:");
$log->debug($datax);

$ids = array();
//get list of user ids for which to display data
if (isset($_SESSION['lsbo_ids']) && count($_SESSION['lsbo_ids']) != 0 && !isset($_REQUEST['lsbo_ids'])) {
	$ids = $_SESSION['lsbo_ids'];
	$log->debug("_SESSION['lsbo_ids'] is:");
	$log->debug($_SESSION['lsbo_ids']);
}
elseif (isset($_REQUEST['lsbo_ids']) && count($_REQUEST['lsbo_ids']) > 0) {
	$ids = $_REQUEST['lsbo_ids'];
	$current_user->setPreference('lsbo_ids', $_REQUEST['lsbo_ids']);
	$log->debug("_REQUEST['lsbo_ids'] is:");
	$log->debug($_REQUEST['lsbo_ids']);
	$log->debug("_SESSION['lsbo_ids'] is:");
	$log->debug($_SESSION['lsbo_ids']);
}
else {
	$ids = get_user_array(false);
	$ids = array_keys($ids);
}

//create unique prefix based on selected users for image files
$id_hash = '';
if (isset($ids)) {
	sort($ids);
	$id_hash = crc32(implode('',$ids));
}
$log->debug("ids is:");
$log->debug($ids);

$cache_file_name = $id_hash."_lead_source_by_outcome_".$current_language."_".crc32(implode('',$datax)).".png";
$log->debug("cache file name is: $cache_file_name");

if (substr(phpversion(), 0, 1) == "5") { // php5 }
	echo "<em>Charts not supported in PHP 5.</em>";
}
else {
$draw_this = new jpgraph();
echo $draw_this->lead_source_by_outcome($datax, $ids, $tmp_dir.$cache_file_name, $refresh);
echo "<P><font size='1'><em>".$current_module_strings['LBL_LEAD_SOURCE_BY_OUTCOME_DESC']."</em></font></P>";
if (isset($_REQUEST['lsbo_edit']) && $_REQUEST['lsbo_edit'] == 'true') {
?>
<form action="index.php" method="post" >
<input type="hidden" name="module" value="<?php echo $currentModule;?>">
<input type="hidden" name="action" value="<?php echo $action;?>">
<input type="hidden" name="lsbo_refresh" value="true">
<table cellpadding="2" border="0"><tbody>
<tr>
<td valign='top' nowrap><?php echo $current_module_strings['LBL_LEAD_SOURCES'];?></strong></td>
<td valign='top'><select name="lsbo_lead_sources[]" multiple size='3'><?php echo get_select_options_with_id($comboFieldArray['lead_source_dom'],$_SESSION['lsbo_lead_sources']); ?></select></td>
</tr><tr>
<td valign='top' nowrap><?php echo $current_module_strings['LBL_USERS'];?></td>
<td valign='top'><select name="lsbo_ids[]" multiple size='3'><?php echo get_select_options_with_id(get_user_array(false),$_SESSION['lsbo_ids']); ?></select></td>
</tr><tr>
<td align="right"><br /> <input class="button" type="submit" title="<?php echo $app_strings['LBL_SELECT_BUTTON_TITLE']; ?>" accessKey="<?php echo $app_strings['LBL_SELECT_BUTTON_KEY']; ?>" value="<?php echo $app_strings['LBL_SELECT_BUTTON_LABEL']?>" /></td>
</tr></table>
</form>
<?php } 
else {
	if (file_exists($tmp_dir.$cache_file_name)) {
		$file_date = getDisplayDate(date('Y-m-d H:i', filemtime($tmp_dir.$cache_file_name)));
	}
	else {
		$file_date = '';
	}
?>
<div align=right><FONT size='1'>
<em><?php  echo $current_module_strings['LBL_CREATED_ON'].' '.$file_date; ?> 
</em>[<a href="index.php?module=<?php echo $currentModule;?>&action=<?php echo $action;?>&lsbo_refresh=true"><?php echo $current_module_strings['LBL_REFRESH'];?></a>]
[<a href="index.php?module=<?php echo $currentModule;?>&action=<?php echo $action;?>&lsbo_edit=true"><?php echo $current_module_strings['LBL_EDIT'];?></a>]
</FONT></div>
<?php } 
}?>

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
 * $Header: /cvsroot/vtigercrm/vtiger_crm/modules/Dashboard/Chart_pipeline_by_lead_source.php,v 1.17 2005/05/03 13:18:54 saraj Exp $
 * Description:  returns HTML for client-side image map.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('include/utils.php');
require_once('include/logging.php');
require_once("modules/Potentials/Charts.php");
require_once('include/ComboUtil.php');
global $current_language, $ids, $tmp_dir;
$current_module_strings = return_module_language($current_language, 'Dashboard');

// Get _dom arrays from Database
$comboFieldNames = Array('leadsource'=>'lead_source_dom');
$comboFieldArray = getComboArray($comboFieldNames);

$log = LoggerManager::getLogger('CSIM_pipeline_by_lead_source');

if (isset($_REQUEST['pbls_refresh'])) { $refresh = $_REQUEST['pbls_refresh']; }
else { $refresh = false; }

// added for auto refresh
$refresh = true;
//

$tempx = array();
$datax = array();
//get list of sales stage keys to display
if (isset($_SESSION['pbls_lead_sources']) && count($_SESSION['pbls_lead_sources']) > 0 && !isset($_REQUEST['pbls_lead_sources'])) {
	$tempx = $_SESSION['pbls_lead_sources'];
	$log->debug("_SESSION['pbls_lead_sources'] is:");
	$log->debug($_SESSION['pbls_lead_sources']);
}
elseif (isset($_REQUEST['pbls_lead_sources']) && count($_REQUEST['pbls_lead_sources']) > 0) {
	$tempx = $_REQUEST['pbls_lead_sources'];
	$current_user->setPreference('pbls_lead_sources', $_REQUEST['pbls_lead_sources']);
	$log->debug("_REQUEST['pbls_lead_sources'] is:");
	$log->debug($_REQUEST['pbls_lead_sources']);
	$log->debug("_SESSION['pbls_lead_sources'] is:");
	$log->debug($_SESSION['pbls_lead_sources']);
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
if (isset($_SESSION['pbls_ids']) && count($_SESSION['pbls_ids']) != 0 && !isset($_REQUEST['pbls_ids'])) {
	$ids = $_SESSION['pbls_ids'];
	$log->debug("_SESSION['pbls_ids'] is:");
	$log->debug($_SESSION['pbls_ids']);
}
elseif (isset($_REQUEST['pbls_ids']) && count($_REQUEST['pbls_ids']) > 0) {
	$ids = $_REQUEST['pbls_ids'];
	$current_user->setPreference('pbls_ids', $ids);
	$log->debug("_REQUEST['pbls_ids'] is:");
	$log->debug($_REQUEST['pbls_ids']);
	$log->debug("_SESSION['pbls_ids'] is:");
	$log->debug($_SESSION['pbls_ids']);
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

$cache_file_name = $id_hash."_pipeline_by_lead_source_".$current_language."_".crc32(implode('',$datax)).".png";
$log->debug("cache file name is: $cache_file_name");

if (substr(phpversion(), 0, 1) == "5") { // php5 }
	echo "<em>Charts not supported in PHP 5.</em>";
}
else {
$draw_this = new jpgraph();
echo $draw_this->pipeline_by_lead_source($datax, $ids, $tmp_dir.$cache_file_name, $refresh);
echo "<P><font size='1'><em>".$current_module_strings['LBL_LEAD_SOURCE_FORM_DESC']."</em></font></P>";
if (isset($_REQUEST['pbls_edit']) && $_REQUEST['pbls_edit'] == 'true') {
?>
<form action="index.php" method="post" >
<input type="hidden" name="module" value="<?php echo $currentModule;?>">
<input type="hidden" name="action" value="<?php echo $action;?>">
<input type="hidden" name="pbls_refresh" value="true">
<table cellpadding="2" border="0"><tbody>
<tr>
<td valign='top' nowrap><?php echo $current_module_strings['LBL_LEAD_SOURCES'];?></td>
<td valign='top'><select name="pbls_lead_sources[]" multiple size='3'><?php echo get_select_options_with_id($comboFieldArray['lead_source_dom'],$_SESSION['pbls_lead_sources']); ?></select></td>
</tr><tr>
<td valign='top' nowrap><?php echo $current_module_strings['LBL_USERS'];?></td>
<td valign='top'><select name="pbls_ids[]" multiple size='3'><?php echo get_select_options_with_id(get_user_array(false),$_SESSION['pbls_ids']); ?></select></td>
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
</em>[<a href="index.php?module=<?php echo $currentModule;?>&action=<?php echo $action;?>&pbls_refresh=true"><?php echo $current_module_strings['LBL_REFRESH'];?></a>]
[<a href="index.php?module=<?php echo $currentModule;?>&action=<?php echo $action;?>&pbls_edit=true"><?php echo $current_module_strings['LBL_EDIT'];?></a>]
</FONT></div>
<?php } 
}?>

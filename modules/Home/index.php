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
 * $Header:  vtiger_crm/sugarcrm/modules/Home/index.php,v 1.2 2004/09/16 12:13:57 jack Exp $
 * Description:  Main file for the Home module.
 ********************************************************************************/

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');
require_once('modules/Opportunities/Opportunity.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;

$opp = new Opportunity();
$where = "assigned_user_id='$current_user->id'";
$opp_list = $opp->get_full_list("amount DESC, date_closed DESC", $where);

//build pipeline by sales stage data
$total = 0;
if (isset($opp_list)) {
	foreach ($opp_list as $record) {
		if (!isset($sum[$record->sales_stage])) $sum[$record->sales_stage] = 0;
		if (isset($record->amount))	{
			// Strip all non numbers from this string.
			$amount = ereg_replace('[^0-9]', '', $record->amount);
			$sum[$record->sales_stage] = $sum[$record->sales_stage] + $amount;  
			if (($record->sales_stage != 'Closed Won') && ($record->sales_stage != 'Closed Lost')) $total = $total + ($amount/1000);
		}
		$log->debug("record->amount is '$record->amount' and record->sales_stage is '$record->sales_stage' and sum[$record->sales_stage] is ".$sum[$record->sales_stage]); 
	}
}

$datax =& $app_list_strings['sales_stage_dom'];
$visible_sales_stage_legends = Array();
$datay = Array();
foreach ($datax as $stage_key=>$stage_translation) {
	if (isset($sum[$stage_key])) 
	{
		array_push($datay, $sum[$stage_key]/1000);
		array_push($visible_sales_stage_legends, $stage_translation);
	}
}

//pipeline($datax,$datay); 
$flat_array1 = urlencode(implode(",",$visible_sales_stage_legends));
$flat_array2 = urlencode(implode(",",$datay));

$title = urlencode($mod_strings['LBL_TOTAL_PIPELINE'].$app_strings['LBL_CURRENCY_SYMBOL'].$total.$app_strings['LBL_THOUSANDS_SYMBOL']);
$subtitle= urlencode($mod_strings['LBL_OPP_SIZE']);

$_REQUEST['search_form'] = 'false';
$_REQUEST['query'] = 'true';
$_REQUEST['status'] = 'In Progress--Not Started';
$_REQUEST['current_user_only'] = 'On';

$task_title = $mod_strings['LBL_OPEN_TASKS'];

?>
<table width=100% align="left" cellpadding="5" cellspacing="5" border="0">
<tr>
<td valign="top"><?php include("modules/Opportunities/ListViewTop.php"); ?></td>
<?php 
if (count($datay)>0) 
{ 
	echo '<td rowspan="3" width="320" valign="top">'; echo get_left_form_header($mod_strings['LBL_PIPELINE_FORM_TITLE']);
    echo "<img src='graph.php?module=Opportunities&action=Charts&graph=pipeline&flat_array1=".$flat_array1."&flat_array2=".$flat_array2."&title=".$title."&subtitle=".$subtitle."' border=0 align=top>\n";
	echo get_left_form_footer();echo "</td>";
}
?>
</tr><tr>		
<td valign="top"><?php include("modules/Activities/OpenListView.php") ;?></td>
</tr><tr>		
<td valign="top"><?php include("modules/Tasks/ListView.php") ;?></td>
</tr>
</table>

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
 * $Header:  vtiger_crm/sugarcrm/modules/Dashboard/index.php,v 1.1 2004/08/17 15:04:28 gjayakrishnan Exp $
 * Description:  Main file for the Home module.
 ********************************************************************************/

global $app_strings;
global $app_list_strings;
global $mod_strings;

global $theme;
global $currentModule;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');
require_once('modules/Opportunities/Opportunity.php');
require_once('data/SugarBean.php');
require_once('include/logging.php');

$log = LoggerManager::getLogger('date_list');

$opp = new Opportunity();
$where = "";
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
			$total = $total + ($amount/1000);
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

//build pipeline by lead source data
$total = 0;
if (isset($opp_list)) {
	foreach ($opp_list as $record) {
		if (!isset($sum[$record->lead_source])) $sum[$record->lead_source] = 0;
		if (isset($record->amount) && isset($record->lead_source))	{
			// Strip all non numbers from this string.
			$amount = ereg_replace('[^0-9]', '', $record->amount);
			
			$sum[$record->lead_source] = $sum[$record->lead_source] + ($amount/1000);  
			$total = $total + ($amount/1000);
		}
	}
}

$visible_legends = Array();
$data= Array();
foreach ($app_list_strings['lead_source_dom'] as $lead_source_key=>$lead_source_translation) {
	if (isset($sum[$lead_source_key])) 
	{
		array_push($data, $sum[$lead_source_key]);
		if($lead_source_key != '')
		{
			array_push($visible_legends, $lead_source_translation);
		}
		else
		{
			// put none in if the field is blank.
			array_push($visible_legends, $mod_strings['NTC_NO_LEGENDS']);
		}
	}
}					


//pipeline_by_lead_source($data,$legends, $title);
$data_string = urlencode(implode(",", $data));
$legends_string = urlencode(implode(",", $visible_legends));


$title = urlencode($mod_strings['LBL_TOTAL_PIPELINE'].$app_strings['LBL_CURRENCY_SYMBOL'].$total.$app_strings['LBL_THOUSANDS_SYMBOL']);
$subtitle= urlencode($mod_strings['LBL_OPP_SIZE']);

?>
<table width=100% align="left" cellpadding="5" cellspacing="5" border="0">
<tr>
<?php 
if (count($datay)>0) 
{ 
	echo '<td rowspan="3" width="320" valign="top">'; echo get_left_form_header($mod_strings['LBL_SALES_STAGE_FORM_TITLE']);
    echo "<img src='graph.php?module=Opportunities&current_language=$current_language&action=Charts&graph=pipeline&flat_array1=".$flat_array1."&flat_array2=".$flat_array2."&title=".$title."&subtitle=".$subtitle."' border=0 align=top>\n";
	echo get_left_form_footer();echo "</td>";
}
?>
<td valign="top">
<?php 
$flat_array1 = urlencode(implode(",",$visible_legends));
$flat_array2 = urlencode(implode(',',$data));
if (count($datay)>0) 
{ 
	echo get_left_form_header($mod_strings['LBL_LEAD_SOURCE_FORM_TITLE']);
    echo "<img src='graph.php?module=Opportunities&current_language=$current_language&action=Charts&graph=pipeline_by_lead_source&flat_array1=".$legends_string."&flat_array2=".$data_string."&title=$title&subtitle=".$subtitle."' border=0 align=top>\n";
	echo get_left_form_footer();echo "</td>";
}
else
{
	echo $mod_strings['ERR_NO_OPPS'];	
}
?>


</td></tr>
</table>

<?php 
?>


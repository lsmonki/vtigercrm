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
 * modules/Leads/ListViewTop.php,v 1.22 2005/04/19 17:00:30 ray Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

function getNewLeads()
{
	require_once('XTemplate/xtpl.php');
	require_once("data/Tracker.php");
	require_once("include/utils/utils.php");

	global $currentModule;

	global $theme;
	global $focus;
	global $action;
	global $adb;
	global $app_strings;
	global $current_language;
	global $current_user;
	$current_module_strings = return_module_language($current_language, 'Leads');

	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	require_once($theme_path.'layout_utils.php');
	if($_REQUEST['lead_view']=='')
	{	
		$query = "select lead_view from users where id ='$current_user->id'";
		$result=$adb->query($query);
		$lead_view=$adb->query_result($result,0,'lead_view');
	}
	else
		$lead_view=$_REQUEST['lead_view'];

	$today = date("Y-m-d", time());

	if($lead_view == 'Today')
	{	
		$start_date = date("Y-m-d",strtotime("$today"));
	}	
	else if($lead_view == 'Last 2 Days')
	{
		$start_date = date("Y-m-d", strtotime("-2  days"));
	}
	else if($lead_view == 'Last Week')
	{	
		$start_date = date("Y-m-d", strtotime("-1 week"));
	}	

	$list_query = 'select leaddetails.*,crmentity.createdtime,crmentity.description from leaddetails inner join crmentity on leaddetails.leadid = crmentity.crmid where crmentity.deleted =0 AND leaddetails.converted =0 AND crmentity.createdtime >='.$start_date.' AND crmentity.smownerid = '.$current_user->id; 
	
	$list_result = $adb->query($list_query);
	$noofrows = $adb->num_rows($list_result);
	$open_lead_list =array();
	if (count($list_result)>0)
		for($i=0;$i<$noofrows;$i++) 
		{
			$open_lead_list[] = Array('leadname' => $adb->query_result($list_result,$i,'firstname').' '.$adb->query_result($list_result,$i,'lastname'),
					'company' => $adb->query_result($list_result,$i,'company'),
					'annualrevenue' => $adb->query_result($list_result,$i,'annualrevenue'),
					'leadstatus' => $adb->query_result($list_result,$i,'leadstatus'),
					'leadsource' => $adb->query_result($list_result,$i,'leadsource'),
					'id' => $adb->query_result($list_result,$i,'leadid'),
					);
		}
	
	$title=array();
	$title[]='Leads.gif';
	$title[]=$current_module_strings["LBL_NEW_LEADS"];
	$title[]='home_mynewlead';
	$title[]=getLeadView($lead_view);
	$title[]='showLeadView';		
	$title[]='MyNewLeadFrm';
	$title[]='lead_view';

	$header=array();
	$header[] =$current_module_strings['LBL_LIST_LEAD_NAME'];
	$header[] =$current_module_strings['Company'];
	$header[] =$current_module_strings['Annual Revenue'];
	$header[] =$current_module_strings['Lead Status'];
	$header[] =$current_module_strings['Lead Source'];
	
    $entries=array();
    foreach($open_lead_list as $lead)
	{
		$value=array();
		$lead_fields = array(
				'LEAD_NAME' => $lead['leadname'],
				'COMPANY' => $lead['company'],
				'ANNUAL_REVENUE' => $lead['annualrevenue'],
				'LEAD_STATUS' => $lead['leadstatus'],
				'LEAD_SOURCE' => $lead['leadsource'],
				'LEAD_ID' => $lead['id'],
				);

		$value[]= '<a href="index.php?action=DetailView&module=Leads&record='.$lead_fields['LEAD_ID'].'">'.$lead_fields['LEAD_NAME'].'</a>';
		$value[]=$lead_fields['COMPANY'];
		$value[]=$lead_fields['ANNUAL_REVENUE'];
		$value[]=$lead_fields['LEAD_STATUS'];
		$value[]=$lead_fields['LEAD_SOURCE'];
		
		$entries[$lead_fields['LEAD_ID']]=$value;
	}
	$values=Array('Title'=>$title,'Header'=>$header,'Entries'=>$entries);
		return $values;
}
function getLeadView($lead_view)	
{	
	$today = date("Y-m-d", time());

	if($lead_view == 'Today')
	{	
		$selected1 = 'selected';
	}	
	else if($lead_view == 'Last 2 Days')
	{
		$selected2 = 'selected';
	}
	else if($lead_view == 'Last Week')
	{	
		$selected3 = 'selected';
	}	

	$LEAD_VIEW_SELECT_OPTION = '<select class=small name="lead_view" onchange="showLeadView(this)">';
	$LEAD_VIEW_SELECT_OPTION .= '<option value="Today" '.$selected1.'>';
	$LEAD_VIEW_SELECT_OPTION .= 'Today';
	$LEAD_VIEW_SELECT_OPTION .= '</option>';
	$LEAD_VIEW_SELECT_OPTION .= '<option value="Last 2 Days" '.$selected2.'>';
	$LEAD_VIEW_SELECT_OPTION .= 'Last 2 Days';
	$LEAD_VIEW_SELECT_OPTION .= '</option>';
	$LEAD_VIEW_SELECT_OPTION .= '<option value="Last Week" '.$selected3.'>';
	$LEAD_VIEW_SELECT_OPTION .= 'Last Week';
	$LEAD_VIEW_SELECT_OPTION .= '</option>';
	$LEAD_VIEW_SELECT_OPTION .= '</select>';
	
	return $LEAD_VIEW_SELECT_OPTION;
}
?>

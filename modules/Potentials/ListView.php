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

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/Potentials/Opportunity.php');
require_once('include/utils.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/ListView/ListView.php');
require_once('include/ComboUtil.php');

global $app_strings;
global $app_list_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Potentials');

global $list_max_entries_per_page;
global $urlPrefix;

$log = LoggerManager::getLogger('potential_list');

global $currentModule;
global $theme;

// Get _dom arrays from Database
$comboFieldNames = Array('leadsource'=>'leadsource_dom'
                        ,'opportunity_type'=>'opportunity_type_dom'
                        ,'sales_stage'=>'sales_stage_dom');
$comboFieldArray = getComboArray($comboFieldNames);

if (!isset($where)) $where = "";

$focus = new Potential();

if (isset($_REQUEST['order_by'])) $order_by = $_REQUEST['order_by'];

if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'true')
{
	// we have a query
	if (isset($_REQUEST['potentialname'])) $name = $_REQUEST['potentialname'];
	if (isset($_REQUEST['accountname'])) $accountname = $_REQUEST['accountname'];
	if (isset($_REQUEST['closingdate'])) $date_closed = $_REQUEST['closingdate'];
	if (isset($_REQUEST['amount'])) $amount = $_REQUEST['amount'];
	if (isset($_REQUEST['nextstep'])) $nextstep = $_REQUEST['nextstep'];
	if (isset($_REQUEST['probability'])) $probability = $_REQUEST['probability'];

	if (isset($_REQUEST['leadsource'])) $lead_source = $_REQUEST['leadsource'];	
	if (isset($_REQUEST['opportunity_type'])) $opportunity_type = $_REQUEST['opportunity_type'];
	if (isset($_REQUEST['sales_stage'])) $sales_stage = $_REQUEST['sales_stage'];
	if (isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];
	if (isset($_REQUEST['assigned_user_id'])) $assigned_user_id = $_REQUEST['assigned_user_id'];
	if (isset($_REQUEST['closingdate_start'])) $date_closed_start = $_REQUEST['closingdate_start'];
	if (isset($_REQUEST['closingdate_end'])) $date_closed_end = $_REQUEST['closingdate_end'];

	$where_clauses = array();

//Added for Custom Field Search
$sql="select * from field where tablename='potentialscf' order by fieldlabel";
$result=$adb->query($sql);
for($i=0;$i<$adb->num_rows($result);$i++)
{
        $column[$i]=$adb->query_result($result,$i,'columnname');
        $fieldlabel[$i]=$adb->query_result($result,$i,'fieldlabel');
        if (isset($_REQUEST[$column[$i]])) $customfield[$i] = $_REQUEST[$column[$i]];

        if(isset($customfield[$i]) && $customfield[$i] != '')
        {
                $str=" potentialscf.".$column[$i]." like '$customfield[$i]%'";
                array_push($where_clauses, $str);
        }
}
//upto this added for Custom Field

	if(isset($name) && $name != "") {
			array_push($where_clauses, "potential.potentialname like ".PearDatabase::quote($name.'%')."");
			$query_val .= "&name=".$name;		
	}
	if(isset($accountname) && $accountname != "") {
			array_push($where_clauses, "account.accountname like ".PearDatabase::quote($accountname.'%')."");
			$query_val .= "&accountname=".$accontname;		
			
			
	}
	if(isset($lead_source) && $lead_source == "None") {
			array_push($where_clauses, "potential.leadsource = ".PearDatabase::quote($lead_source)."");
			$query_val .= "&leadsource=".$lead_source;		
	}
	// added to handle request from dashboard GS
	if(isset($lead_source) && $lead_source != "") {
			array_push($where_clauses, "potential.leadsource = ".PearDatabase::quote($lead_source)."");
			$query_val .= "&leadsource=".$lead_source;		
	}
	if(isset($opportunity_type) && $opportunity_type != "") {
			array_push($where_clauses, "potential.potentialtype = ".PearDatabase::quote($opportunity_type)."");
			$query_val .= "&$opportunity_type=".$opportunity_type;		
	}
	if(isset($amount) && $amount != "") {
			array_push($where_clauses, "potential.amount like ".PearDatabase::quote($amount.'%%')."");
			$query_val .= "&$amount=".$amount;		
	}
	if(isset($next_step) && $next_step != "") {
			array_push($where_clauses, "potential.nextstep like ".PearDatabase::quote($next_step.'%')."");
			$query_val .= "&$nextstep=".$nextstep;		
	}
	if(isset($sales_stage) && $sales_stage != "") {
			if($sales_stage=='Other')
				array_push($where_clauses, "(potential.sales_stage <> 'Closed Won' && potential.sales_stage <> 'Closed Lost')");
			else
				array_push($where_clauses, "potential.sales_stage = ".PearDatabase::quote($sales_stage));
			$query_val .= "&$sales_stage=".$sales_stage;		
	}
	if(isset($probability) && $probability != "") {
			array_push($where_clauses, "potential.probability like ".PearDatabase::quote($probability.'%')."");
			$query_val .= "&$probability=".$probability;		
			
	}
	if(isset($current_user_only) && $current_user_only != "") {
			array_push($where_clauses, "crmentity.smcreator='$current_user->id'");
	}
	if(isset($date_closed) && $date_closed != "") {
			array_push($where_clauses, "potential.closingdate like ".PearDatabase::quote($date_closed.'%')."");
	}
	if(isset($date_closed_start) && $date_closed_start != "" && isset($date_closed_end) && $date_closed_end != "")
	{
		array_push($where_clauses, "potential.closingdate >= ".PearDatabase::quote($date_closed_start)." and potential.closingdate <= ".PearDatabase::quote($date_closed_end));
	}

	$where = "";
	foreach($where_clauses as $clause)
	{
		if($where != "")
		$where .= " and ";
		$where .= $clause;
	}

	if (!empty($assigned_user_id)) {
		if (!empty($where)) {
			$where .= " AND ";
		}
		$where .= "crmentity.smcreatorid IN(";
		foreach ($assigned_user_id as $key => $val) {
			$where .= "".PearDatabase::quote($val)."";
			$where .= ($key == count($assigned_user_id) - 1) ? ")" : ", ";
		}
	}

	$log->info("Here is the where clause for the list view: $where");

}

if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
	// Stick the form header out there.
	$search_form=new XTemplate ('modules/Potentials/SearchForm.html');
	$search_form->assign("MOD", $current_module_strings);
	$search_form->assign("APP", $app_strings);

	if (isset($name)) $search_form->assign("NAME", $name);
	if (isset($accountname)) $search_form->assign("ACCOUNT_NAME", $accountname);
	$search_form->assign("JAVASCRIPT", get_clear_form_js());

	if(isset($current_user_only)) $search_form->assign("CURRENT_USER_ONLY", "checked");

	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE'], "", false);
	if (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true') {
		if (isset($amount)) $search_form->assign("AMOUNT", $amount);
		if (isset($date_entered)) $search_form->assign("DATE_ENTERED", $date_entered);
		if (isset($date_closed)) $search_form->assign("DATE_CLOSED", $date_closed);
		if (isset($nextstep)) $search_form->assign("NEXT_STEP", $nextstep);
		if (isset($probability)) $search_form->assign("PROBABILITY", $probability);
		if (isset($date_modified)) $search_form->assign("DATE_MODIFIED", $date_modified);
		if (isset($modified_user_id)) $search_form->assign("MODIFIED_USER_ID", $modified_user_id);

		if (isset($lead_source)) $search_form->assign("LEAD_SOURCE_OPTIONS", get_select_options($comboFieldArray['leadsource_dom'], $lead_source, $_REQUEST['advanced']));
		else $search_form->assign("LEAD_SOURCE_OPTIONS", get_select_options($comboFieldArray['leadsource_dom'], '', $_REQUEST['advanced']));
		if (isset($opportunity_type)) $search_form->assign("TYPE_OPTIONS", get_select_options($comboFieldArray['opportunity_type_dom'], $opportunity_type, $_REQUEST['advanced']));
		else $search_form->assign("TYPE_OPTIONS", get_select_options($comboFieldArray['opportunity_type_dom'], '', $_REQUEST['advanced']));
		$sales_stage_dom = & $comboFieldArray['sales_stage_dom'];
		array_unshift($sales_stage_dom, '');
		if (isset($sales_stage)) $search_form->assign("SALES_STAGE_OPTIONS", get_select_options_with_id($comboFieldArray['sales_stage_dom'], $sales_stage));
		else $search_form->assign("SALES_STAGE_OPTIONS", get_select_options_with_id($comboFieldArray['sales_stage_dom'], ''));

		if (!empty($assigned_user_id)) $search_form->assign("USER_FILTER", get_select_options_with_id(get_user_array(FALSE), $assigned_user_id));
		else $search_form->assign("USER_FILTER", get_select_options_with_id(get_user_array(FALSE), ''));

//Added for Custom Field Search
$sql="select * from field where tablename='potentialscf' order by fieldlabel";
$result=$adb->query($sql);
for($i=0;$i<$adb->num_rows($result);$i++)
{
        $column[$i]=$adb->query_result($result,$i,'columnname');
        $fieldlabel[$i]=$adb->query_result($result,$i,'fieldlabel');
        if (isset($_REQUEST[$column[$i]])) $customfield[$i] = $_REQUEST[$column[$i]];
}
require_once('include/CustomFieldUtil.php');
$custfld = CustomFieldSearch($customfield, "potentialscf", "potentialscf", "potentialid", $app_strings, $theme,$column,$fieldlabel);
$search_form->assign("CUSTOMFIELD", $custfld);
//upto this added for Custom Field

		$search_form->parse("advanced");
		$search_form->out("advanced");
	}
	else {
		$search_form->parse("main");
		$search_form->out("main");
	}
	echo get_form_footer();
	echo "\n<BR>\n";
}
//Retreive the list from Database
$list_query = getListQuery("Potentials");
if(isset($where) && $where != '')
{
	$list_query .= " AND ".$where;
}


if(isset($_REQUEST['viewname']))
{
	if($_REQUEST['viewname'] == 'All')
	   {
           $defaultcv_criteria = '';
           }
         else
       {
            $defaultcv_criteria = $_REQUEST['viewname'];
       }
						       
  $list_query .= " and sales_stage like "."'%" .$defaultcv_criteria ."%'";
}


$url_qry = getURLstring($focus);

if(isset($order_by) && $order_by != '')
{
        $list_query .= ' ORDER BY '.$order_by;
        $url_qry .="&order_by=".$order_by;
}

$list_result = $adb->query($list_query);

//Constructing the list view 

echo get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'],'', false);
$xtpl=new XTemplate ('modules/Potentials/ListView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("IMAGE_PATH",$image_path);

//Retreiving the no of rows
$noofrows = $adb->num_rows($list_result);

//Retreiving the start value from request
if(isset($_REQUEST['start']) && $_REQUEST['start'] != '')
{
	$start = $_REQUEST['start'];
}
else
{
	
	$start = 1;
}
//Retreive the Navigation array
$navigation_array = getNavigationValues($start, $noofrows, $list_max_entries_per_page);

//Retreive the List View Table Header

$listview_header = getListViewHeader($focus,"Potentials",$eddel=1);
$xtpl->assign("LISTHEADER", $listview_header);

$listview_entries = getListViewEntries($focus,"Potentials",$list_result,$navigation_array,$eddel=1);
$xtpl->assign("LISTHEADER", $listview_header);
$xtpl->assign("LISTENTITY", $listview_entries);

if(isset($navigation_array['start']))
{
	$startoutput = '<a href="index.php?action=index&module=Potentials'.$url_qry.'&start=1"><b>Start</b></a>';
}
else
{
	$startoutput = '[ Start ]';
}
if(isset($navigation_array['end']))
{
	$endoutput = '<a href="index.php?action=index&module=Potentials'.$url_qry.'&start='.$navigation_array['end'].'"><b>End</b></a>';
}
else
{
	$endoutput = '[ End ]';
}
if(isset($navigation_array['next']))
{
	$nextoutput = '<a href="index.php?action=index&module=Potentials'.$url_qry.'&start='.$navigation_array['next'].'"><b>Next</b></a>';
}
else
{
	$nextoutput = '[ Next ]';
}
if(isset($navigation_array['prev']))
{
	$prevoutput = '<a href="index.php?action=index&module=Potentials'.$url_qry.'&start='.$navigation_array['prev'].'"><b>Prev</b></a>';
}
else
{
	$prevoutput = '[ Prev ]';
}
$xtpl->assign("Start", $startoutput);
$xtpl->assign("End", $endoutput);
$xtpl->assign("Next", $nextoutput);
$xtpl->assign("Prev", $prevoutput);

$xtpl->parse("main");

$xtpl->out("main");

/*$ListView = new ListView();
$ListView->initNewXTemplate( 'modules/Potentials/ListView.html',$current_module_strings);
$ListView->setHeaderTitle($current_module_strings['LBL_LIST_FORM_TITLE'] );
$ListView->setQuery($where, "", "potentialname", "OPPORTUNITY");
$ListView->processListView($seedOpportunity, "main", "OPPORTUNITY");
*/
?>

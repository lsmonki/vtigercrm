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
require_once('include/uifromdbutil.php');
require_once('modules/CustomView/CustomView.php');

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

$url_string = ''; // assigning http url string
$sorder = 'ASC';  // Default sort order
if(isset($_REQUEST['sorder']) && $_REQUEST['sorder'] != '')
$sorder = $_REQUEST['sorder'];

if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'true')
{
	// we have a query
	$url_string .="&query=true";
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
        $uitype[$i]=$adb->query_result($result,$i,'uitype');
        if (isset($_REQUEST[$column[$i]])) $customfield[$i] = $_REQUEST[$column[$i]];

        if(isset($customfield[$i]) && $customfield[$i] != '')
        {
		if($uitype[$i] == 56)
			$str=" potentialscf.".$column[$i]." = 1";
		else
	                $str=" potentialscf.".$column[$i]." like '$customfield[$i]%'";
                array_push($where_clauses, $str);
		$url_string .="&".$column[$i]."=".$customfield[$i];
        }
}
//upto this added for Custom Field

	if(isset($name) && $name != "") {
			array_push($where_clauses, "potential.potentialname like ".PearDatabase::quote($name.'%')."");
			$url_string .= "&potentialname=".$name;
	}
	if(isset($accountname) && $accountname != "") {
			array_push($where_clauses, "account.accountname like ".PearDatabase::quote($accountname.'%')."");
			$url_string .= "&accountname=".$accountname;


	}
	if(isset($lead_source) && $lead_source == "None") {
			array_push($where_clauses, "potential.leadsource = ".PearDatabase::quote($lead_source)."");
			$url_string .= "&leadsource=".$lead_source;
	}
	// added to handle request from dashboard GS
	if(isset($lead_source) && $lead_source != "") {
			array_push($where_clauses, "potential.leadsource = ".PearDatabase::quote($lead_source)."");
			$url_string .= "&leadsource=".$lead_source;
	}
	if(isset($opportunity_type) && $opportunity_type != "") {
			array_push($where_clauses, "potential.potentialtype = ".PearDatabase::quote($opportunity_type)."");
			$url_string .= "&opportunity_type=".$opportunity_type;
	}
	if(isset($amount) && $amount != "") {
			array_push($where_clauses, "potential.amount like ".PearDatabase::quote($amount.'%')."");
			$url_string .= "&amount=".$amount;
	}
	if(isset($nextstep) && $nextstep != "") {
			array_push($where_clauses, "potential.nextstep like ".PearDatabase::quote($nextstep.'%')."");
			$url_string .= "&nextstep=".$nextstep;
	}
	if(isset($sales_stage) && $sales_stage != "") {
			if($sales_stage=='Other')
				array_push($where_clauses, "(potential.sales_stage <> 'Closed Won' and potential.sales_stage <> 'Closed Lost')");
			else
				array_push($where_clauses, "potential.sales_stage = ".PearDatabase::quote($sales_stage));
			$url_string .= "&sales_stage=".$sales_stage;
	}
	if(isset($probability) && $probability != "") {
			array_push($where_clauses, "potential.probability like ".PearDatabase::quote($probability.'%')."");
			$url_string .= "&probability=".$probability;

	}
	if(isset($current_user_only) && $current_user_only != "") {
			array_push($where_clauses, "crmentity.smownerid='$current_user->id'");
			$url_string .= "&current_user_only=".$current_user_only;
	}
	if(isset($date_closed) && $date_closed != "") {
			array_push($where_clauses, $adb->getDBDateString("potential.closingdate")." like ".PearDatabase::quote($date_closed.'%')."");
			$url_string .= "&closingdate=".$date_closed;
	}
	if(isset($date_closed_start) && $date_closed_start != "" && isset($date_closed_end) && $date_closed_end != "")
	{
			array_push($where_clauses, "potential.closingdate >= ".PearDatabase::quote($date_closed_start)." and potential.closingdate <= ".PearDatabase::quote($date_closed_end));
			$url_string .= "&closingdate_start=".$date_closed_start;
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
		$where .= "crmentity.smownerid IN(";
		foreach ($assigned_user_id as $key => $val) {
			$where .= "".PearDatabase::quote($val)."";
			$where .= ($key == count($assigned_user_id) - 1) ? ")" : ", ";
			// ACIPIA - to allow prev/next button to use criterias
			$url_string .= '&' . urlencode( 'assigned_user_id[]' ) . '=' . $val ;
			// ACIPIA 
		}
	}

	$log->info("Here is the where clause for the list view: $where");

}

//<<<<cutomview>>>>>>>
$oCustomView = new CustomView("Potentials");
$customviewcombo_html = $oCustomView->getCustomViewCombo();
if(isset($_REQUEST['viewname']) == false)
{
	if($oCustomView->setdefaultviewid != "")
	{
		$viewid = $oCustomView->setdefaultviewid;
	}else
	{
		$viewid = "0";
	}
}else
{
	$viewid =  $_REQUEST['viewname'];
}
//<<<<<customview>>>>>

if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
	// Stick the form header out there.
	$search_form=new XTemplate ('modules/Potentials/SearchForm.html');
	$search_form->assign("MOD", $current_module_strings);
	$search_form->assign("APP", $app_strings);

	if ($order_by !='') $search_form->assign("ORDER_BY", $order_by);
	if ($sorder !='') $search_form->assign("SORDER", $sorder);
	
	$search_form->assign("VIEWID",$viewid);

	$search_form->assign("JAVASCRIPT", get_clear_form_js());
	if($order_by != '') {
		$ordby = "&order_by=".$order_by;
	}
	else
	{
		$ordby ='';
	}
	$search_form->assign("BASIC_LINK", "index.php?module=Potentials".$ordby."&action=index".$url_string."&sorder=".$sorder."&viewname=".$viewid);
	$search_form->assign("ADVANCE_LINK", "index.php?module=Potentials&action=index".$ordby."&advanced=true".$url_string."&sorder=".$sorder."&viewname=".$viewid);

	if (isset($name)) $search_form->assign("NAME", $name);
	if (isset($accountname)) $search_form->assign("ACCOUNT_NAME", $accountname);
	$search_form->assign("JAVASCRIPT", get_clear_form_js());

	if(isset($current_user_only)) $search_form->assign("CURRENT_USER_ONLY", "checked");

	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE'], "", false);

	if (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true') {

		$url_string .="&advanced=true";
		$search_form->assign("ALPHABETICAL",AlphabeticalSearch('Potentials','index','potentialname','true','advanced',"","","","",$viewid));

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
		$search_form->assign("DATE_FORMAT", $current_user->date_format);

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
		$search_form->assign("ALPHABETICAL",AlphabeticalSearch('Potentials','index','potentialname','true','basic',"","","","",$viewid));
		$search_form->parse("main");
		$search_form->out("main");
	}
	echo get_form_footer();
	echo "\n<BR>\n";
}



$other_text = '<table width="100%" border="0" cellpadding="1" cellspacing="0">
	<form name="massdelete" method="POST">
	<tr>
	<input name="idlist" type="hidden">
	<input name="viewname" type="hidden" value="'.$viewid.'">';
if(isPermitted('Potentials',2,'') == 'yes')
{
        $other_text .='<td><input class="button" type="submit" value="'.$app_strings[LBL_MASS_DELETE].'" onclick="return massDelete()"/></td>';
}
	/*$other_text .='<td align="right">'.$app_strings[LBL_VIEW].'
			<SELECT NAME="view" onchange="showDefaultCustomView(this)">
				<OPTION VALUE="'.$mod_strings[MOD.LBL_ALL].'">'.$mod_strings[LBL_ALL].'</option>
				<OPTION VALUE="'.$mod_strings[LBL_WON].'">'.$mod_strings[LBL_WON].'</option>
				<OPTION VALUE="'.$mod_strings[LBL_LOST].'">'.$mod_strings[LBL_LOST].'</option>
				<OPTION VALUE="'.$mod_strings[LBL_VALUE_PROPOSITION].'">'.$mod_strings[LBL_VALUE_PROPOSITION].'</option>
				<OPTION VALUE="'.$mod_strings[LBL_PROSPECTING].'">'.$mod_strings[LBL_PROSPECTING].'</option>
			</SELECT>
		</td>
	</tr>
	</table>';*/

if($viewid == 0)
{
$cvHTML = '<span class="bodyText disabled">'.$app_strings['LNK_CV_EDIT'].'</span>
<span class="sep">|</span>
<span class="bodyText disabled">'.$app_strings['LNK_CV_DELETE'].'</span><span class="sep">|</span>
<a href="index.php?module=Potentials&action=CustomView" class="link">'.$app_strings['LNK_CV_CREATEVIEW'].'</a>';
}else
{
$cvHTML = '<a href="index.php?module=Potentials&action=CustomView&record='.$viewid.'" class="link">'.$app_strings['LNK_CV_EDIT'].'</a>
<span class="sep">|</span>
<a href="index.php?module=CustomView&action=Delete&dmodule=Potentials&record='.$viewid.'" class="link">'.$app_strings['LNK_CV_DELETE'].'</a>
<span class="sep">|</span>
<a href="index.php?module=Potentials&action=CustomView" class="link">'.$app_strings['LNK_CV_CREATEVIEW'].'</a>';
}

$other_text .='<td align="right">'.$app_strings[LBL_VIEW].'
			<SELECT NAME="view" onchange="showDefaultCustomView(this)">
				<OPTION VALUE="0">'.$mod_strings[LBL_ALL].'</option>
				'.$customviewcombo_html.'
			</SELECT>
			'.$cvHTML.'
		</td>
	</tr>
	</table>';


//Retreive the list from Database
//<<<<<<<<<customview>>>>>>>>>
if($viewid != "0")
{
	$listquery = getListQuery("Potentials");
	$list_query = $oCustomView->getModifiedCvListQuery($viewid,$listquery,"Accounts");
}else
{
	$list_query = getListQuery("Potentials");
}
//<<<<<<<<customview>>>>>>>>>

if(isset($where) && $where != '')
{
	$list_query .= " AND ".$where;
}

/*if(isset($_REQUEST['viewname']))
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
	$viewname = $_REQUEST['viewname'];
        $view_script = "<script language='javascript'>
                function set_selected()
                {
                        len=document.massdelete.view.length;
                        for(i=0;i<len;i++)
                        {
                                if(document.massdelete.view[i].value == '$viewname')
                                        document.massdelete.view[i].selected = true;
                        }
                }
                set_selected();
                </script>";
}*/

$view_script = "<script language='javascript'>
	function set_selected()
	{
		len=document.massdelete.view.length;
		for(i=0;i<len;i++)
		{
			if(document.massdelete.view[i].value == '$viewid')
				document.massdelete.view[i].selected = true;
		}
	}
	set_selected();
	</script>";

if(isset($order_by) && $order_by != '')
{
        $list_query .= ' ORDER BY '.$order_by.' '.$sorder;
}

$list_result = $adb->query($list_query);

//Constructing the list view

echo get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'],$other_text, false);
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

// Setting the record count string
if ($navigation_array['start'] == 1)
{
	if($noofrows != 0)
	$start_rec = $navigation_array['start'];
	else
	$start_rec = 0;
	if($noofrows > $list_max_entries_per_page)
	{
		$end_rec = $navigation_array['start'] + $list_max_entries_per_page - 1;
	}
	else
	{
		$end_rec = $noofrows;
	}

}
else
{
	if($navigation_array['next'] > $list_max_entries_per_page)
	{
		$start_rec = $navigation_array['next'] - $list_max_entries_per_page;
		$end_rec = $navigation_array['next'] - 1;
	}
	else
	{
		$start_rec = $navigation_array['prev'] + $list_max_entries_per_page;
		$end_rec = $noofrows;
	}
}
$record_string= $app_strings[LBL_SHOWING]." " .$start_rec." - ".$end_rec." " .$app_strings[LBL_LIST_OF] ." ".$noofrows;

//Retreive the List View Table Header
if($viewid !='')
$url_string .="&viewname=".$viewid;

$listview_header = getListViewHeader($focus,"Potentials",$url_string,$sorder,$order_by,"",$oCustomView);
$xtpl->assign("LISTHEADER", $listview_header);

$listview_entries = getListViewEntries($focus,"Potentials",$list_result,$navigation_array,"","","EditView","Delete",$oCustomView);
$xtpl->assign("LISTHEADER", $listview_header);
$xtpl->assign("LISTENTITY", $listview_entries);

if($order_by !='')
$url_string .="&order_by=".$order_by;
if($sorder !='')
$url_string .="&sorder=".$sorder;

$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"Potentials","index",$viewid);
$xtpl->assign("NAVIGATION", $navigationOutput);
$xtpl->assign("RECORD_COUNTS", $record_string);
$xtpl->assign("SELECT_SCRIPT", $view_script);

$xtpl->parse("main");

$xtpl->out("main");

/*$ListView = new ListView();
$ListView->initNewXTemplate( 'modules/Potentials/ListView.html',$current_module_strings);
$ListView->setHeaderTitle($current_module_strings['LBL_LIST_FORM_TITLE'] );
$ListView->setQuery($where, "", "potentialname", "OPPORTUNITY");
$ListView->processListView($seedOpportunity, "main", "OPPORTUNITY");
*/
?>

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
require_once('modules/Quotes/Quote.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/ListView/ListView.php');
require_once('include/database/PearDatabase.php');
require_once('include/ComboUtil.php');
require_once('include/utils.php');
require_once('include/uifromdbutil.php');
require_once('modules/CustomView/CustomView.php');

global $app_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Quotes');

global $list_max_entries_per_page;
global $urlPrefix;

$log = LoggerManager::getLogger('quote_list');

global $currentModule;
global $theme;

// Get _dom arrays from Database
$comboFieldNames = Array('quotestage'=>'quotestage_dom');
$comboFieldArray = getComboArray($comboFieldNames);

// focus_list is the means of passing data to a ListView.
global $focus_list;

if (!isset($where)) $where = "";

if (isset($_REQUEST['order_by'])) $order_by = $_REQUEST['order_by'];

$url_string = '';
$sorder = 'ASC';
if(isset($_REQUEST['sorder']) && $_REQUEST['sorder'] != '')
$sorder = $_REQUEST['sorder'];

if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'true')
{
	// we have a query
	$url_string .="&query=true";
	if (isset($_REQUEST['subject'])) $subject = $_REQUEST['subject'];
	if (isset($_REQUEST['potentialname'])) $potentialname = $_REQUEST['potentialname'];
	if (isset($_REQUEST['quotestage'])) $quotestage = $_REQUEST['quotestage'];
	if (isset($_REQUEST['accountname'])) $accountname = $_REQUEST['accountname'];

	$where_clauses = Array();

//Added for Custom Field Search
$sql="select * from field where tablename='quotescf' order by fieldlabel";
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
			$str=" quotescf.".$column[$i]." = 1";
		else
	                $str=" quotescf.".$column[$i]." like '$customfield[$i]%'";
                array_push($where_clauses, $str);
		$url_string .="&".$column[$i]."=".$customfield[$i];
        }
}
//upto this added for Custom Field
	
	if(isset($subject) && $subject != "") 
	{
		array_push($where_clauses, "quotes.subject like ".PearDatabase::quote($subject."%"));
		$url_string .= "&subject=".$subject;
	}
	if(isset($potentialname) && $potentialname != "")
	{
		array_push($where_clauses, "potential.potentialname like ".PearDatabase::quote("%".$potentialname."%"));
		$url_string .= "&potentialname=".$potentialname;
	}
	if(isset($accountname) && $accountname != "")
	{
		array_push($where_clauses, "account.accountname like ".PearDatabase::quote("%".$accountname."%"));
		$url_string .= "&accountname=".$accountname;
	}

	if(isset($quotestage) && $quotestage != "")
	{
		array_push($where_clauses, "quotes.quotestage like ".PearDatabase::quote("%".$quotestage."%"));
		$url_string .= "&quotestage=".$quotestage;
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
			$where .= PearDatabase::quote($val);
			$where .= ($key == count($assigned_user_id) - 1) ? ")" : ", ";
		}
	}

	$log->info("Here is the where clause for the list view: $where");

}

//<<<<cutomview>>>>>>>
$oCustomView = new CustomView("Quotes");
$customviewcombo_html = $oCustomView->getCustomViewCombo();
if(isset($_REQUEST['viewname']) == false || $_REQUEST['viewname']=='')
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
	$search_form=new XTemplate ('modules/Quotes/SearchForm.html');
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
	$search_form->assign("BASIC_LINK", "index.php?module=Quotes".$ordby."&action=index".$url_string."&sorder=".$sorder."&viewname=".$viewid);
	$search_form->assign("ADVANCE_LINK", "index.php?module=Quotes&action=index".$ordby."&advanced=true".$url_string."&sorder=".$sorder."&viewname=".$viewid);


	$search_form->assign("JAVASCRIPT", get_clear_form_js());
	if (isset($subject)) $search_form->assign("SUBJECT", $subject);
	if (isset($potentialname)) $search_form->assign("POTENTIALNAME", $potentialname);
	if (isset($accountname)) $search_form->assign("ACCOUNTNAME", $accountname);
	
	if (isset($quotestage)) $search_form->assign("QUOTESTAGE", get_select_options($comboFieldArray['quotestage_dom'], $quotestage, $advsearch));
	else $search_form->assign("QUOTESTAGE", get_select_options($comboFieldArray['quotestage_dom'], '', $advsearch));

	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE'], '', false);

	if (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true') {

	$url_string .="&advanced=true";
	$search_form->assign("ALPHABETICAL",AlphabeticalSearch('Quotes','index','subject','true','advanced',"","","","",$viewid));

		if (isset($annual_revenue)) $search_form->assign("ANNUAL_REVENUE", $annual_revenue);
		if (isset($employees)) $search_form->assign("EMPLOYEES", $employees);
		if (isset($address_street)) $search_form->assign("ADDRESS_STREET", $address_street);
		if (isset($address_state)) $search_form->assign("ADDRESS_STATE", $address_state);
		if (isset($address_country)) $search_form->assign("ADDRESS_COUNTRY", $address_country);
		if (isset($address_postalcode)) $search_form->assign("ADDRESS_POSTALCODE", $address_postalcode);
		if (isset($email)) $search_form->assign("EMAIL", $email);
		if (isset($ownership)) $search_form->assign("OWNERSHIP", $ownership);
		if (isset($rating)) $search_form->assign("RATING", $rating);
		if (isset($sic_code)) $search_form->assign("SIC_CODE", $sic_code);
		if (isset($ticker_symbol)) $search_form->assign("TICKER_SYMBOL", $ticker_symbol);

		if (!empty($assigned_user_id)) $search_form->assign("USER_FILTER", get_select_options_with_id(get_user_array(FALSE), $assigned_user_id));
		else $search_form->assign("USER_FILTER", get_select_options_with_id(get_user_array(FALSE), ''));

//Added for Custom Field Search
$sql="select * from field where tablename='quotescf' order by fieldlabel";
$result=$adb->query($sql);
for($i=0;$i<$adb->num_rows($result);$i++)
{
        $column[$i]=$adb->query_result($result,$i,'columnname');
        $fieldlabel[$i]=$adb->query_result($result,$i,'fieldlabel');
        if (isset($_REQUEST[$column[$i]])) $customfield[$i] = $_REQUEST[$column[$i]];
}
require_once('include/CustomFieldUtil.php');
$custfld = CustomFieldSearch($customfield, "quotescf", "quotescf", "quoteid", $app_strings,$theme,$column,$fieldlabel);
$search_form->assign("CUSTOMFIELD", $custfld);
//upto this added for Custom Field

		$search_form->parse("advanced");
		$search_form->out("advanced");
	}
	else {
		$search_form->assign("ALPHABETICAL",AlphabeticalSearch('Quotes','index','subject','true','basic',"","","","",$viewid));
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
	<input name="viewname" type="hidden" value="'.$viewid.'">
	<td>';
if(isPermitted('Quotes',2,'') == 'yes')
{
        $other_text .=	'<input class="button" type="submit" value="'.$app_strings[LBL_MASS_DELETE].'" onclick="return massDelete()"/></td>';
}

if($viewid == 0)
{
$cvHTML = '<span class="bodyText disabled">'.$app_strings['LNK_CV_EDIT'].'</span>
<span class="sep">|</span>
<span class="bodyText disabled">'.$app_strings['LNK_CV_DELETE'].'</span><span class="sep">|</span>
<a href="index.php?module=Quotes&action=CustomView" class="link">'.$app_strings['LNK_CV_CREATEVIEW'].'</a>';
}else
{
$cvHTML = '<a href="index.php?module=Quotes&action=CustomView&record='.$viewid.'" class="link">'.$app_strings['LNK_CV_EDIT'].'</a>
<span class="sep">|</span>
<a href="index.php?module=CustomView&action=Delete&dmodule=Quotes&record='.$viewid.'" class="link">'.$app_strings['LNK_CV_DELETE'].'</a>
<span class="sep">|</span>
<a href="index.php?module=Quotes&action=CustomView" class="link">'.$app_strings['LNK_CV_CREATEVIEW'].'</a>';
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



$focus = new Quote();

echo get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'],$other_text, false);
$xtpl=new XTemplate ('modules/Quotes/ListView.html');
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("IMAGE_PATH",$image_path);

//Retreive the list from Database
//<<<<<<<<<customview>>>>>>>>>
if($viewid != "0")
{
	$listquery = getListQuery("Quotes");
	$query = $oCustomView->getModifiedCvListQuery($viewid,$listquery,"Quotes");
}else
{
	$query = getListQuery("Quotes");
}
//<<<<<<<<customview>>>>>>>>>

if(isset($where) && $where != '')
{
        $query .= ' and '.$where;
}

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
        $query .= ' ORDER BY '.$order_by.' '.$sorder;
}

$list_result = $adb->query($query);

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

$listview_header = getListViewHeader($focus,"Quotes",$url_string,$sorder,$order_by,"",$oCustomView);
$xtpl->assign("LISTHEADER", $listview_header);

$listview_entries = getListViewEntries($focus,"Quotes",$list_result,$navigation_array,"","","EditView","Delete",$oCustomView);
$xtpl->assign("LISTENTITY", $listview_entries);
$xtpl->assign("SELECT_SCRIPT", $view_script);

if($order_by !='')
$url_string .="&order_by=".$order_by;
if($sorder !='')
$url_string .="&sorder=".$sorder;

$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"Quotes","index",$viewid);
$xtpl->assign("NAVIGATION", $navigationOutput);
$xtpl->assign("RECORD_COUNTS", $record_string);

$xtpl->parse("main");
$xtpl->out("main");

?>

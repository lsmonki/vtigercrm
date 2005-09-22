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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Contacts/ListView.php,v 1.25 2005/04/18 10:37:49 samk Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/Contacts/Contact.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/ComboUtil.php');
require_once('include/uifromdbutil.php');
require_once('modules/CustomView/CustomView.php');

global $app_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Contacts');

global $list_max_entries_per_page;
global $urlPrefix;

$log = LoggerManager::getLogger('contact_list');

global $currentModule;
global $theme;

// Get _dom arrays from Database
$comboFieldNames = Array('leadsource'=>'leadsource_dom');
$comboFieldArray = getComboArray($comboFieldNames);

if (!isset($where)) $where = "";

if (isset($_REQUEST['order_by'])) $order_by = $_REQUEST['order_by'];

$url_string = ''; // assigning http url string
$sorder = 'ASC';  // Default sort order
if(isset($_REQUEST['sorder']) && $_REQUEST['sorder'] != '')
$sorder = $_REQUEST['sorder'];

$focus = new Contact();

if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'true')
{
	// we have a query
	$url_string .="&query=true";
	if (isset($_REQUEST['firstname'])) $firstname = $_REQUEST['firstname'];
	if (isset($_REQUEST['lastname'])) $lastname = $_REQUEST['lastname'];
	if (isset($_REQUEST['accountname'])) $accountname = $_REQUEST['accountname'];
	if (isset($_REQUEST['leadsource'])) $leadsource = $_REQUEST['leadsource'];
	if (isset($_REQUEST['donotcall'])) $donotcall = $_REQUEST['donotcall'];
	if (isset($_REQUEST['phone'])) $phone = $_REQUEST['phone'];
	if (isset($_REQUEST['email'])) $email = $_REQUEST['email'];
	if (isset($_REQUEST['yahooid'])) $yahooid = $_REQUEST['yahooid'];
	if (isset($_REQUEST['assistant'])) $assistant = $_REQUEST['assistant'];
	if (isset($_REQUEST['emailoptout'])) $emailoptout = $_REQUEST['emailoptout'];
	if (isset($_REQUEST['mailingstreet'])) $mailingstreet = $_REQUEST['mailingstreet'];
	if (isset($_REQUEST['mailingcity'])) $mailingcity = $_REQUEST['mailingcity'];
	if (isset($_REQUEST['mailingstate'])) $mailingstate = $_REQUEST['mailingstate'];
	if (isset($_REQUEST['mailingzip'])) $mailingzip = $_REQUEST['mailingzip'];
	if (isset($_REQUEST['mailingcountry'])) $mailingcountry = $_REQUEST['mailingcountry'];
	if (isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];
	if (isset($_REQUEST['assigned_user_id'])) $assigned_user_id = $_REQUEST['assigned_user_id'];


	$where_clauses = Array();

//Added for Custom Field Search
$sql="select * from field where tablename='contactscf' order by fieldlabel";
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
			$str=" contactscf.".$column[$i]." = 1";
		else
	        	$str=" contactscf.".$column[$i]." like '$customfield[$i]%'";
                array_push($where_clauses, $str);
		$url_string .="&".$column[$i]."=".$customfield[$i];
        }
}
//upto this added for Custom Field


	if(isset($lastname) && $lastname != "") {
			array_push($where_clauses, "contactdetails.lastname like ".PearDatabase::quote($lastname.'%')."");
			$url_string .= "&lastname=".$lastname;
	}
	if(isset($firstname) && $firstname != "") {
			array_push($where_clauses, "contactdetails.firstname like ".PearDatabase::quote($firstname.'%')."");
			$url_string .= "&firstname=".$firstname;
	}
	if(isset($accountname) && $accountname != "")	{
			array_push($where_clauses, "account.accountname like ".PearDatabase::quote($accountname.'%')."");
			$url_string .= "&accountname=".$accountname;
	}
	if(isset($leadsource) && $leadsource != "") {
			array_push($where_clauses, "contactsubdetails.leadsource = ".PearDatabase::quote($leadsource)."");
			$url_string .= "&leadsource=".$leadsource;
	}
	if(isset($donotcall) && $donotcall != "") {
			array_push($where_clauses, "contactdetails.donotcall = ".PearDatabase::quote($donotcall)."");
			$url_string .= "&donotcall=".$donotcall;
	}
	if(isset($phone) && $phone != "") {
			array_push($where_clauses, "(contactdetails.phone like ".PearDatabase::quote('%'.$phone.'%')." OR contactdetails.mobile like ".PearDatabase::quote('%'.$phone.'%')." OR contactdetails.fax like ".PearDatabase::quote('%'.$phone.'%').")");
			$url_string .= "&phone=".$phone;
	}
	if(isset($email) && $email != "") {
			array_push($where_clauses, "(contactdetails.email like ".PearDatabase::quote($email.'%').")");
			$url_string .= "&email=".$email;
	}
	if(isset($yahooid) && $yahooid != "") {
			array_push($where_clauses, "contactdetails.yahooid like ".PearDatabase::quote($yahooid.'%')."");
			$url_string .= "&yahooid=".$yahooid;
	}
	if(isset($assistant) && $assistant != "") {
			array_push($where_clauses, "contactsubdetails.assistant like ".PearDatabase::quote($assistant.'%')."");
			$url_string .= "&yahooid=".$yahooid;
	}
	if(isset($mailingstreet) && $mailingstreet != "") {
			array_push($where_clauses, "(contactaddress.mailingstreet like ".PearDatabase::quote($mailingstreet.'%')." OR contactaddress.otherstreet like ".PearDatabase::quote($mailingstreet.'%').")");
			$url_string .= "&mailingstreet=".$mailingstreet;
	}
	if(isset($mailingcity) && $mailingcity != "") {
			array_push($where_clauses, "(contactaddress.mailingcity like ".PearDatabase::quote($mailingcity.'%')." OR contactaddress.othercity like ".PearDatabase::quote($mailingcity.'%').")");
			$url_string .= "&mailingcity=".$mailingcity;
	}
	if(isset($mailingstate) && $mailingstate != "") {
			array_push($where_clauses, "(contactaddress.mailingstate like ".PearDatabase::quote($mailingstate.'%')." OR contactaddress.otherstate like ".PearDatabase::quote($mailingstate.'%').")");
			$url_string .= "&mailingstate=".$mailingstate;
	}
	if(isset($mailingzip) && $mailingzip != "") {
			array_push($where_clauses, "(contactaddress.mailingzip like ".PearDatabase::quote($mailingzip.'%')." OR contactaddress.otherzip like ".PearDatabase::quote($mailingzip.'%').")");
			$url_string .= "&mailingzip=".$mailingzip;
	}
	if(isset($mailingcountry) && $mailingcountry != "") {
			array_push($where_clauses, "(contactaddress.mailingcountry like ".PearDatabase::quote($mailingcountry.'%')." OR contactaddress.othercountry like ".PearDatabase::quote($mailingcountry.'%').")");
			$url_string .= "&mailingcountry=".$mailingcountry;
	}
	if(isset($current_user_only) && $current_user_only != "") {
			array_push($where_clauses, "crmentity.smownerid='$current_user->id'");
			$url_string .= "&current_user_only=".$current_user_only;
	}	
	if(isset($emailoptout) && $emailoptout != "") {
			array_push($where_clauses, "contactdetails.emailoptout = 1");
			$url_string .= "&emailoptout=".$emailoptout;
	}
        else
	{
			array_push($where_clauses, "contactdetails.emailoptout = 0");
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
			//ACIPIA
		}
	}

	$log->info("Here is the where clause for the list view: $where");

}

//<<<<cutomview>>>>>>>
$oCustomView = new CustomView("Contacts");
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
	$search_form=new XTemplate ('modules/Contacts/SearchForm.html');
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
	$search_form->assign("BASIC_LINK", "index.php?module=Contacts".$ordby."&action=index".$url_string."&sorder=".$sorder."&viewname=".$viewid);
	$search_form->assign("ADVANCE_LINK", "index.php?module=Contacts&action=index".$ordby."&advanced=true".$url_string."&sorder=".$sorder."&viewname=".$viewid);

	if (isset($firstname)) $search_form->assign("FIRST_NAME", $_REQUEST['firstname']);
	if (isset($lastname)) $search_form->assign("LAST_NAME", $_REQUEST['lastname']);
	if (isset($accountname)) $search_form->assign("ACCOUNT_NAME", $_REQUEST['accountname']);
	$search_form->assign("JAVASCRIPT", get_clear_form_js());

	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE'], "", false);


	if(isset($current_user_only)) $search_form->assign("CURRENT_USER_ONLY", "checked");

	if (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true') {

		$url_string .="&advanced=true";
		$search_form->assign("ALPHABETICAL",AlphabeticalSearch('Contacts','index','lastname','true','advanced',"","","","",$viewid));

		if(isset($accountname)) $search_form->assign("ACCOUNT_NAME", $accountname);
		//if(isset($createdtime)) $search_form->assign("DATE_ENTERED", $createdtime);
		//if(isset($modifiedtime)) $search_form->assign("DATE_MODIFIED", $modifiedtime);
		//if(isset($modified_user_id)) $search_form->assign("MODIFIED_USER_ID", $modified_user_id);
		//if(isset($donotcall)) $search_form->assign("DO_NOT_CALL", $donotcall);
		if(isset($phone)) $search_form->assign("PHONE", $phone);
		if(isset($email)) $search_form->assign("EMAIL", $email);
		if(isset($yahooid)) $search_form->assign("YAHOO_ID", $yahooid);
		if(isset($assistant)) $search_form->assign("ASSISTANT", $assistant);
		if(isset($emailoptout)) $search_form->assign("EMAIL_OPT_OUT", "CHECKED");
		if(isset($mailingstreet)) $search_form->assign("ADDRESS_STREET", $mailingstreet);
		if(isset($mailingcity)) $search_form->assign("ADDRESS_CITY", $mailingcity);
		if(isset($mailingstate)) $search_form->assign("ADDRESS_STATE", $mailingstate);
		if(isset($mailingzip)) $search_form->assign("ADDRESS_POSTALCODE", $mailingzip);
		if(isset($mailingcountry)) $search_form->assign("ADDRESS_COUNTRY", $mailingcountry);

		if (isset($leadsource)) $search_form->assign("LEAD_SOURCE_OPTIONS", get_select_options($comboFieldArray['leadsource_dom'], $leadsource, $_REQUEST['advanced']));
		else $search_form->assign("LEAD_SOURCE_OPTIONS", get_select_options($comboFieldArray['leadsource_dom'], '', $_REQUEST['advanced']));

		if (!empty($assigned_user_id)) $search_form->assign("USER_FILTER", get_select_options_with_id(get_user_array(FALSE), $assigned_user_id));
		else $search_form->assign("USER_FILTER", get_select_options_with_id(get_user_array(FALSE), ''));

//Added for Custom Field Search
$sql="select * from field where tablename='contactscf' order by fieldlabel";
$result=$adb->query($sql);
for($i=0;$i<$adb->num_rows($result);$i++)
{
        $column[$i]=$adb->query_result($result,$i,'columnname');
        $fieldlabel[$i]=$adb->query_result($result,$i,'fieldlabel');
        if (isset($_REQUEST[$column[$i]])) $customfield[$i] = $_REQUEST[$column[$i]];
}
require_once('include/CustomFieldUtil.php');
$custfld = CustomFieldSearch($customfield, "contactscf","contactscf", "contactid", $app_strings,$theme,$column,$fieldlabel);
$search_form->assign("CUSTOMFIELD", $custfld);
//upto this added for Custom Field

		$search_form->parse("advanced");
		$search_form->out("advanced");
	}
	else {
		$search_form->assign("ALPHABETICAL",AlphabeticalSearch('Contacts','index','lastname','true','basic',"","","","",$viewid));
		$search_form->parse("main");
		$search_form->out("main");
	}
	echo get_form_footer();
	echo "\n<BR>\n";
}


// Buttons and View options
if($viewid != 0)
{
	$CActionDtls = $oCustomView->getCustomActionDetails($viewid);
}
$other_text = '<table width="100%" border="0" cellpadding="1" cellspacing="0">
	<form name="massdelete" method="POST">
	<tr>
	<input name="idlist" type="hidden">
	<input name="viewname" type="hidden" value='.$viewid.'>
	<input name="change_status" type="hidden">';

if(isPermitted('Contacts',2,'') == 'yes')
{
$other_text .='<td width="12"><input class="button" type="submit" value="'.$app_strings[LBL_MASS_DELETE].'" onclick="return massDelete()"/>';
}
if(isset($CActionDtls))
{
	$other_text .='<td><input class="button" type="submit" value="'.$app_strings[LBL_SEND_MAIL_BUTTON].'" onclick="return massMail()"/>';
}
if($viewid == 0)
{
$cvHTML = '<span class="bodyText disabled">'.$app_strings['LNK_CV_EDIT'].'</span>
<span class="sep">|</span>
<span class="bodyText disabled">'.$app_strings['LNK_CV_DELETE'].'</span><span class="sep">|</span>
<a href="index.php?module=Contacts&action=CustomView" class="link">'.$app_strings['LNK_CV_CREATEVIEW'].'</a>';
}else
{
$cvHTML = '<a href="index.php?module=Contacts&action=CustomView&record='.$viewid.'" class="link">'.$app_strings['LNK_CV_EDIT'].'</a>
<span class="sep">|</span>
<a href="index.php?module=CustomView&action=Delete&dmodule=Contacts&record='.$viewid.'" class="link">'.$app_strings['LNK_CV_DELETE'].'</a>
<span class="sep">|</span>
<a href="index.php?module=Contacts&action=CustomView" class="link">'.$app_strings['LNK_CV_CREATEVIEW'].'</a>';
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

//

//Retreive the list from Database
//<<<<<<<<<customview>>>>>>>>>
if($viewid != "0")
{
	$listquery = getListQuery("Contacts");
	$list_query = $oCustomView->getModifiedCvListQuery($viewid,$listquery,"Contacts");
}else
{
	$list_query = getListQuery("Contacts");
}
//<<<<<<<<customview>>>>>>>>>

if(isset($where) && $where != '')
{
	$list_query .= " AND ".$where;
}

if(isset($order_by) && $order_by != '')
{
        $list_query .= ' ORDER BY '.$order_by.' '.$sorder;
}

$list_result = $adb->query($list_query);

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

//Constructing the list view
echo get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'],$other_text, false);
$xtpl=new XTemplate ('modules/Contacts/ListView.html');
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

$listview_header = getListViewHeader($focus,"Contacts",$url_string,$sorder,$order_by,"",$oCustomView);
$xtpl->assign("LISTHEADER", $listview_header);

$listview_entries = getListViewEntries($focus,"Contacts",$list_result,$navigation_array,"","","EditView","Delete",$oCustomView);
$xtpl->assign("LISTENTITY", $listview_entries);
$xtpl->assign("SELECT_SCRIPT", $view_script);

if($order_by !='')
$url_string .="&order_by=".$order_by;
if($sorder !='')
$url_string .="&sorder=".$sorder;

$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"Contacts","index",$viewid);
$xtpl->assign("NAVIGATION", $navigationOutput);
$xtpl->assign("RECORD_COUNTS", $record_string);

$xtpl->parse("main");

$xtpl->out("main");

?>

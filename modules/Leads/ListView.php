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
 * $Header: /cvsroot/vtigercrm/vtiger_crm/modules/Leads/ListView.php,v 1.29.2.1 2005/09/09 09:15:18 mickie Exp $
 * Description:  TODO: To be written.
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/Leads/Lead.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/ListView/ListView.php');
require_once('include/database/PearDatabase.php');
require_once('include/ComboUtil.php');
require_once('include/uifromdbutil.php');
require_once('modules/CustomView/CustomView.php');

global $app_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Leads');

global $list_max_entries_per_page;
global $urlPrefix;

$log = LoggerManager::getLogger('contact_list');

global $currentModule;
global $theme;

// Get _dom arrays from Database
$comboFieldNames = Array('leadsource'=>'leadsource_dom'
                      ,'leadstatus'=>'leadstatus_dom'
                      ,'rating'=>'rating_dom'
                      ,'industry'=>'industry_dom');
$comboFieldArray = getComboArray($comboFieldNames);

if (!isset($where)) $where = "";

if (isset($_REQUEST['order_by'])) $order_by = $_REQUEST['order_by'];

$url_string = ''; // assigning http url string
$sorder = 'ASC';  // Default sort order
if(isset($_REQUEST['sorder']) && $_REQUEST['sorder'] != '')
$sorder = $_REQUEST['sorder'];

if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'true')
{
	// we have a query
	$url_string .="&query=true";
	if (isset($_REQUEST['firstname'])) $firstname = $_REQUEST['firstname'];
	if (isset($_REQUEST['lastname'])) $lastname = $_REQUEST['lastname'];
	if (isset($_REQUEST['company'])) $company = $_REQUEST['company'];
	if (isset($_REQUEST['leadsource'])) $leadsource = $_REQUEST['leadsource'];
	if (isset($_REQUEST['industry'])) $industry = $_REQUEST['industry'];
	if (isset($_REQUEST['phone'])) $phone = $_REQUEST['phone'];
	if (isset($_REQUEST['email'])) $email = $_REQUEST['email'];
	if (isset($_REQUEST['mobile'])) $mobile = $_REQUEST['mobile'];
	if (isset($_REQUEST['lead_status'])) $leadstatus = $_REQUEST['lead_status'];
	if (isset($_REQUEST['rating'])) $rating = $_REQUEST['rating'];
	if (isset($_REQUEST['address_street'])) $address_street = $_REQUEST['address_street'];
	if (isset($_REQUEST['address_city'])) $address_city = $_REQUEST['address_city'];
	if (isset($_REQUEST['address_state'])) $address_state = $_REQUEST['address_state'];
	if (isset($_REQUEST['address_postalcode'])) $address_postalcode = $_REQUEST['address_postalcode'];
	if (isset($_REQUEST['address_country'])) $address_country = $_REQUEST['address_country'];
	if (isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];
	if (isset($_REQUEST['assigned_user_id'])) $assigned_user_id = $_REQUEST['assigned_user_id'];


	$where_clauses = Array();

//Added for Custom Field Search
$sql="select * from field  where tablename='leadscf' order by fieldlabel";
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
			$str=" leadscf.".$column[$i]." = 1";
		else
	                $str=" leadscf.".$column[$i]." like '$customfield[$i]%'";
                array_push($where_clauses, $str);
		$url_string .="&".$column[$i]."=".$customfield[$i];
        }
}
//upto this added for Custom Field


	if(isset($lastname) && $lastname != ""){
		array_push($where_clauses, "leaddetails.lastname like '$lastname%'");
		$url_string .= "&lastname=".$lastname;
	}
	if(isset($firstname) && $firstname != ""){
	 	array_push($where_clauses, "leaddetails.firstname like '$firstname%'");
		$url_string .= "&firstname=".$firstname;
	}
	if(isset($company) && $company != ""){
		array_push($where_clauses, "leaddetails.company like '$company%'");
		$url_string .= "&company=".$company;
	}
	if(isset($leadsource) && $leadsource != ""){
		array_push($where_clauses, "leaddetails.leadsource = '$leadsource'");
		$url_string .= "&leadsource=".$leadsource;
	}
	if(isset($industry) && $industry != ""){
	 	array_push($where_clauses, "leaddetails.industry = '$industry'");
		$url_string .= "&industry=".$industry;
	}
	if(isset($phone) && $phone != ""){
		array_push($where_clauses, "leadaddress.phone like '%$phone%'");
		$url_string .= "&phone=".$phone;
	}
	if(isset($email) && $email != ""){
		array_push($where_clauses, "leaddetails.email like '$email%'");
		$url_string .= "&email=".$email;
	}
	if(isset($mobile) && $mobile != ""){
		array_push($where_clauses, "leadaddress.mobile like '%$mobile%'");
		$url_string .= "&mobile=".$mobile;
	}
	if(isset($leadstatus) && $leadstatus != ""){
		array_push($where_clauses, "leaddetails.leadstatus =  '$leadstatus'");
		$url_string .= "&lead_status=".$leadstatus;
	}
	if(isset($rating) && $rating != ""){
		array_push($where_clauses, "leaddetails.rating = '$rating'");
		$url_string .= "&rating=".$rating;
	}
	if(isset($address_street) && $address_street != ""){
		array_push($where_clauses, "leadaddress.lane like '$address_street%'");
		$url_string .= "&address_street=".$address_street;
	}
	if(isset($address_city) && $address_city != ""){
		array_push($where_clauses, "leadaddress.city like '$address_city%'");
		$url_string .= "&address_city=".$address_city;
	}
	if(isset($address_state) && $address_state != ""){
		array_push($where_clauses, "leadaddress.state like '$address_state%'");
		$url_string .= "&address_state=".$address_state;
	}
	if(isset($address_postalcode) && $address_postalcode != ""){
		array_push($where_clauses, "leadaddress.code like '$address_postalcode%'");
		$url_string .= "&address_postalcode=".$address_postalcode;
	}
	if(isset($address_country) && $address_country != ""){
		array_push($where_clauses, "leadaddress.country like '$address_country%'");
		$url_string .= "&address_country=".$address_country;
	}
	if(isset($current_user_only) && $current_user_only != ""){
		array_push($where_clauses, "crmentity.smownerid='$current_user->id'");
		$url_string .= "&current_user_only=".$current_user_only;
	}
	if(isset($assigned_user_id) && $assigned_user_id != ""){
		array_push($where_clauses, "crmentity.smownerid = '$assigned_user_id'");
		$url_string .= "&assigned_user_id=".$assigned_user_id;
	}

	$where = "";
	foreach($where_clauses as $clause)
	{
		if($where != "")
		$where .= " and ";
		$where .= $clause;
	}

	$log->info("Here is the where clause for the list view: $where");

}

//<<<<cutomview>>>>>>>
$oCustomView = new CustomView("Leads");
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
	$search_form=new XTemplate ('modules/Leads/SearchForm.html');
	$search_form->assign("MOD", $current_module_strings);
	$search_form->assign("APP", $app_strings);

	if (isset($firstname)) $search_form->assign("FIRST_NAME", $_REQUEST['firstname']);
	if (isset($lastname)) $search_form->assign("LAST_NAME", $_REQUEST['lastname']);
	if (isset($company)) $search_form->assign("COMPANY", $_REQUEST['company']);
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

	$search_form->assign("BASIC_LINK", "index.php?module=Leads".$ordby."&action=index".$url_string."&sorder=".$sorder."&viewname=".$viewid);
	$search_form->assign("ADVANCE_LINK", "index.php?module=Leads&action=index".$ordby."&advanced=true".$url_string."&sorder=".$sorder."&viewname=".$viewid);

	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE'], "", false);

	if(isset($current_user_only)) $search_form->assign("CURRENT_USER_ONLY", "checked");

	if (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true') {
		$url_string .="&advanced=true";
		$search_form->assign("ALPHABETICAL",AlphabeticalSearch('Leads','index','lastname','true','advanced',"","","","",$viewid));
		$advsearch = 'true';
		//if(isset($date_entered)) $search_form->assign("DATE_ENTERED", $date_entered);
		//if(isset($date_modified)) $search_form->assign("DATE_MODIFIED", $date_modified);
		//if(isset($modified_user_id)) $search_form->assign("MODIFIED_USER_ID", $modified_user_id);
		//if(isset($do_not_call)) $search_form->assign("DO_NOT_CALL", $do_not_call);
		if(isset($phone)) $search_form->assign("PHONE", $phone);
		if(isset($email)) $search_form->assign("EMAIL", $email);
		if(isset($mobile)) $search_form->assign("MOBILE", $mobile);
		if(isset($address_street)) $search_form->assign("ADDRESS_STREET", $address_street);
		if(isset($address_city)) $search_form->assign("ADDRESS_CITY", $address_city);
		if(isset($address_state)) $search_form->assign("ADDRESS_STATE", $address_state);
		if(isset($address_postalcode)) $search_form->assign("ADDRESS_POSTALCODE", $address_postalcode);
		if(isset($address_country)) $search_form->assign("ADDRESS_COUNTRY", $address_country);

		if (isset($leadsource)) $search_form->assign("LEAD_SOURCE_OPTIONS", get_select_options($comboFieldArray['leadsource_dom'], $leadsource, $advsearch));
		else $search_form->assign("LEAD_SOURCE_OPTIONS", get_select_options($comboFieldArray['leadsource_dom'], '', $advsearch));

		if (isset($leadstatus)) $search_form->assign("LEAD_STATUS_OPTIONS", get_select_options($comboFieldArray['leadstatus_dom'], $leadstatus, $advsearch));
		else $search_form->assign("LEAD_STATUS_OPTIONS", get_select_options($comboFieldArray['leadstatus_dom'], '', $advsearch));

		if (isset($rating)) $search_form->assign("RATING_OPTIONS", get_select_options($comboFieldArray['rating_dom'], $rating, $advsearch));
		else $search_form->assign("RATING_OPTIONS", get_select_options($comboFieldArray['rating_dom'], '', $advsearch));

		if (isset($industry)) $search_form->assign("INDUSTRY_OPTIONS", get_select_options($comboFieldArray['industry_dom'], $industry, $advsearch));
		else $search_form->assign("INDUSTRY_OPTIONS", get_select_options($comboFieldArray['industry_dom'], '', $advsearch));
		if (isset($assigned_user_id)) $search_form->assign("ASSIGNED_USER_OPTIONS", get_select_options_with_id(get_user_array(TRUE, "Active", $assigned_user_id), $assigned_user_id));
		else $search_form->assign("ASSIGNED_USER_OPTIONS", get_select_options_with_id(get_user_array(TRUE, "Active",$assigned_user_id),$assigned_user_id));
//Added for Custom Field Search
$sql="select * from field  where tablename='leadscf' order by fieldlabel";
$result=$adb->query($sql);
for($i=0;$i<$adb->num_rows($result);$i++)
{
        $column[$i]=$adb->query_result($result,$i,'columnname');
        $fieldlabel[$i]=$adb->query_result($result,$i,'fieldlabel');
        if (isset($_REQUEST[$column[$i]])) $customfield[$i] = $_REQUEST[$column[$i]];
}
require_once('include/CustomFieldUtil.php');
$custfld = CustomFieldSearch($customfield, "leadscf", "leadcf", "leadid", $app_strings, $theme,$column,$fieldlabel);
$search_form->assign("CUSTOMFIELD", $custfld);
//upto this added for Custom Field


		$search_form->parse("advanced");
		$search_form->out("advanced");
	}
	else {
		$search_form->assign("ALPHABETICAL",AlphabeticalSearch('Leads','index','lastname','true','basic',"","","","",$viewid));
		$search_form->parse("main");
		$search_form->out("main");
	}
	echo get_form_footer();
	echo "\n<BR>\n";
}

if($viewid != 0)
{
	$CActionDtls = $oCustomView->getCustomActionDetails($viewid);
}
// Buttons and View options
$other_text = '<table width="100%" border="0" cellpadding="1" cellspacing="0">
	<form name="massdelete" method="POST">
	<tr>
	<input name="idlist" type="hidden">
	<input name="viewname" type="hidden" value="'.$viewid.'">
	<input name="change_owner" type="hidden">
	<input name="change_status" type="hidden">
	<td>';
if(isPermitted('Leads',2,'') == 'yes')
{
	$other_text .=	'<input class="button" type="submit" value="'.$app_strings[LBL_MASS_DELETE].'" onclick="return massDelete()"/>&nbsp;';
}
if(isPermitted('Leads',1,'') == 'yes')
{
   	$other_text .=	'<input class="button" type="submit" value="'.$app_strings[LBL_CHANGE_OWNER].'" onclick="this.form.change_owner.value=\'true\'; return changeStatus()"/>
	       <input class="button" type="submit" value="'.$app_strings[LBL_CHANGE_STATUS].'" onclick="this.form.change_status.value=\'true\'; return changeStatus()"/>';
}
if(isset($CActionDtls))
{
	$other_text .='&nbsp;<input class="button" type="submit" value="'.$app_strings[LBL_SEND_MAIL_BUTTON].'" onclick="return massMail()"/>';
}
	/*$other_text .=	'</td>
			<td align="right">'.$app_strings[LBL_VIEW].'
			<SELECT NAME="view" onchange="showDefaultCustomView(this)">
				<OPTION VALUE="'.$mod_strings[MOD.LBL_ALL].'">'.$mod_strings[LBL_ALL].'</option>
				<OPTION VALUE="'.$mod_strings[LBL_CONTACTED].'">'.$mod_strings[LBL_CONTACTED].'</option>
				<OPTION VALUE="'.$mod_strings[LBL_LOST].'">'.$mod_strings[LBL_LOST].'</option>
				<OPTION VALUE="'.$mod_strings[LBL_HOT].'">'.$mod_strings[LBL_HOT].'</option>
				<OPTION VALUE="'.$mod_strings[LBL_COLD].'">'.$mod_strings[LBL_COLD].'</option>
			</SELECT>
		</td>
	</tr>
	</table>';*/

if($viewid == 0)
{
$cvHTML = '<span class="bodyText disabled">'.$app_strings['LNK_CV_EDIT'].'</span>
<span class="sep">|</span>
<span class="bodyText disabled">'.$app_strings['LNK_CV_DELETE'].'</span><span class="sep">|</span>
<a href="index.php?module=Leads&action=CustomView" class="link">'.$app_strings['LNK_CV_CREATEVIEW'].'</a>';
}else
{
$cvHTML = '<a href="index.php?module=Leads&action=CustomView&record='.$viewid.'" class="link">'.$app_strings['LNK_CV_EDIT'].'</a>
<span class="sep">|</span>
<a href="index.php?module=CustomView&action=Delete&dmodule=Leads&record='.$viewid.'" class="link">'.$app_strings['LNK_CV_DELETE'].'</a>
<span class="sep">|</span>
<a href="index.php?module=Leads&action=CustomView" class="link">'.$app_strings['LNK_CV_CREATEVIEW'].'</a>';
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

$focus = new Lead();

echo get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'],$other_text, false);
$xtpl=new XTemplate ('modules/Leads/ListView.html');
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
	$listquery = getListQuery("Leads");
	$query = $oCustomView->getModifiedCvListQuery($viewid,$listquery,"Leads");
}else
{
	$query = getListQuery("Leads");
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
$url_string .= "&viewname=".$viewid;

$listview_header = getListViewHeader($focus,"Leads",$url_string,$sorder,$order_by,"",$oCustomView);
$xtpl->assign("LISTHEADER", $listview_header);

$listview_entries = getListViewEntries($focus,"Leads",$list_result,$navigation_array,"","","EditView","Delete",$oCustomView);
$xtpl->assign("LISTENTITY", $listview_entries);
$xtpl->assign("SELECT_SCRIPT", $view_script);

if($order_by !='')
$url_string .="&order_by=".$order_by;
if($sorder !='')
$url_string .="&sorder=".$sorder;

$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"Leads","index",$viewid);
$xtpl->assign("NAVIGATION", $navigationOutput);
$xtpl->assign("RECORD_COUNTS", $record_string);

$xtpl->parse("main");
$xtpl->out("main");

?>

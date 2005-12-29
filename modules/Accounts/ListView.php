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

require_once('Smarty_setup.php');
require_once("data/Tracker.php");
require_once('modules/Accounts/Account.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/ListView/ListView.php');
require_once('include/database/PearDatabase.php');
require_once('include/ComboUtil.php');
require_once('include/utils/utils.php');
require_once('include/utils/utils.php');
require_once('modules/CustomView/CustomView.php');

global $app_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Accounts');

global $list_max_entries_per_page;
global $urlPrefix;

$log = LoggerManager::getLogger('account_list');

global $currentModule;
global $theme;

// Get _dom arrays from Database
$comboFieldNames = Array('accounttype'=>'account_type_dom'
                      ,'industry'=>'industry_dom');
$comboFieldArray = getComboArray($comboFieldNames);
if(isset($_REQUEST['category']) && $_REQUEST['category'] !='')
{
	$category = $_REQUEST['category'];
}
else
{
	$category = getParentTabFromModule($currentModule);
}

// focus_list is the means of passing data to a ListView.
global $focus_list;

if (!isset($where)) $where = "";

$url_string = '';

$focus = new Account();

//<<<<<<< sort ordering >>>>>>>>>>>>>
$sorder = $focus->getSortOrder();
$order_by = $focus->getOrderBy();

$_SESSION['ACCOUNTS_ORDER_BY'] = $order_by;
$_SESSION['ACCOUNTS_SORT_ORDER'] = $sorder;
//<<<<<<< sort ordering >>>>>>>>>>>>>

//<<<<cutomview>>>>>>>
$oCustomView = new CustomView($currentModule);
$customviewcombo_html = $oCustomView->getCustomViewCombo();
$viewid = $oCustomView->getViewId($currentModule);
$groupid = $oCustomView->getGroupId($currentModule);
//<<<<<customview>>>>>

if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'true')
{
	// we have a query
	$url_string .="&query=true";
	if (isset($_REQUEST['accountname'])) $name = $_REQUEST['accountname'];
	if (isset($_REQUEST['website'])) $website = $_REQUEST['website'];
	if (isset($_REQUEST['phone'])) $phone = $_REQUEST['phone'];
	if (isset($_REQUEST['annual_revenue'])) $annual_revenue = $_REQUEST['annual_revenue'];
	if (isset($_REQUEST['email'])) $email = $_REQUEST['email'];
	if (isset($_REQUEST['employees'])) $employees = $_REQUEST['employees'];
	if (isset($_REQUEST['industry'])) $industry = $_REQUEST['industry'];
	if (isset($_REQUEST['ownership'])) $ownership = $_REQUEST['ownership'];
	if (isset($_REQUEST['rating'])) $rating = $_REQUEST['rating'];
	if (isset($_REQUEST['siccode'])) $sic_code = $_REQUEST['siccode'];
	if (isset($_REQUEST['tickersymbol'])) $ticker_symbol = $_REQUEST['tickersymbol'];
	if (isset($_REQUEST['accounttype'])) $account_type = $_REQUEST['accounttype'];
	if (isset($_REQUEST['address_street'])) $address_street = $_REQUEST['address_street'];
	if (isset($_REQUEST['bill_city'])) $address_city = $_REQUEST['bill_city'];
	if (isset($_REQUEST['bill_state'])) $address_state = $_REQUEST['bill_state'];
	if (isset($_REQUEST['bill_country'])) $address_country = $_REQUEST['bill_country'];
	if (isset($_REQUEST['bill_code'])) $address_postalcode = $_REQUEST['bill_code'];
	if (isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];
	if (isset($_REQUEST['assigned_user_id'])) $assigned_user_id = $_REQUEST['assigned_user_id'];
	//Added field emailoptout in search after 4.2 patch 2
	if (isset($_REQUEST['emailoptout'])) $emailoptout = $_REQUEST['emailoptout'];

	$where_clauses = Array();

//Added for Custom Field Search
$sql="select * from field where tablename='accountscf' order by fieldlabel";
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
			$str = " accountscf.".$column[$i]." = 1";
		elseif($uitype[$i] == 15)//Added to handle the picklist customfield - after 4.2 patch2
                        $str = " accountscf.".$column[$i]." = '".$customfield[$i]."'";
		else
	                $str = " accountscf.".$column[$i]." like '$customfield[$i]%'";
                array_push($where_clauses, $str);
		$url_string .="&".$column[$i]."=".$customfield[$i];
        }
}
//upto this added for Custom Field

	if(isset($name) && $name != "")
	{
		array_push($where_clauses, "account.accountname like ".PearDatabase::quote($name."%"));
		$url_string .= "&accountname=".$name;
	}
	if(isset($website) && $website != "")
	{
		array_push($where_clauses, "account.website like ".PearDatabase::quote("%".$website."%"));
		$url_string .= "&website=".$website;
	}
	if(isset($phone) && $phone != "")
	{
		array_push($where_clauses, "(account.phone like ".PearDatabase::quote("%".$phone."%")." OR account.otherphone like ".PearDatabase::quote("%".$phone."%")." OR account.fax like ".PearDatabase::quote("%".$phone."%").")");
		$url_string .= "&phone=".$phone;
	}
	if(isset($annual_revenue) && $annual_revenue != "")
	{
		array_push($where_clauses, "account.annualrevenue like ".PearDatabase::quote($annual_revenue."%"));
		$url_string .= "&annual_revenue=".$annual_revenue;
	}
	if(isset($employees) && $employees != "")
	{
		array_push($where_clauses, "account.employees like ".PearDatabase::quote($employees."%"));
		$url_string .= "&employees=".$employees;
	}
	if(isset($address_street) && $address_street != "")
	{
		array_push($where_clauses, "(accountbillads.street like ".PearDatabase::quote($address_street."%")." OR accountshipads.street like ".PearDatabase::quote($address_street."%").")");
		$url_string .= "&address_street=".$address_street;
	}
	if(isset($address_city) && $address_city != "")
	{
		array_push($where_clauses, "(accountbillads.city like ".PearDatabase::quote($address_city."%")." OR accountshipads.city like ".PearDatabase::quote($address_city."%").")");
		$url_string .= "&bill_city=".$address_city;
	}
	if(isset($address_state) && $address_state != "")
	{
		array_push($where_clauses, "(accountbillads.state like ".PearDatabase::quote($address_state."%")." OR accountshipads.state like ".PearDatabase::quote($address_state."%").")");
		$url_string .= "&bill_state=".$address_state;
	}
	if(isset($address_postalcode) && $address_postalcode != "")
	{
		array_push($where_clauses, "(accountbillads.code like ".PearDatabase::quote($address_postalcode."%")." OR accountshipads.code like ".PearDatabase::quote($address_postalcode."%").")");
		$url_string .= "&bill_code=".$address_postalcode;
	}
	if(isset($address_country) && $address_country != "")
	{
		array_push($where_clauses, "(accountbillads.country like ".PearDatabase::quote($address_country."%")." OR accountshipads.country like ".PearDatabase::quote($address_country."%").")");
		$url_string .= "&bill_country=".$address_country;
	}
	//Added field emailoptout after 4.2 patch 2
	if(isset($emailoptout) && $emailoptout != "")
	{
		array_push($where_clauses, " account.emailoptout =1");//".$emailoptout);
		$url_string .= "&emailoptout=".$emailoptout;
	}
	if(isset($email) && $email != "")
	{
		array_push($where_clauses, "(account.email1 like ".PearDatabase::quote($email."%")." OR account.email2 like ".PearDatabase::quote($email."%").")");
		$url_string .= "&email=".$email;
	}
	if(isset($industry) && $industry != "")
	{
		array_push($where_clauses, "account.industry = ".PearDatabase::quote($industry));
		$url_string .= "&industry=".$industry;
	}
	if(isset($ownership) && $ownership != "")
	{
	 	array_push($where_clauses, "account.ownership like ".PearDatabase::quote($ownership."%"));
		$url_string .= "&ownership=".$ownership;
	}
	if(isset($rating) && $rating != "") array_push($where_clauses, "account.rating like ".PearDatabase::quote($rating."%"));
	if(isset($sic_code) && $sic_code != "")
	{
		array_push($where_clauses, "account.siccode like ".PearDatabase::quote($sic_code."%"));
		$url_string .= "&siccode=".$sic_code;
	}
	if(isset($ticker_symbol) && $ticker_symbol != "")
	{
		array_push($where_clauses, "account.tickersymbol like ".PearDatabase::quote($ticker_symbol."%"));
		$url_string .= "&tickersymbol=".$ticker_symbol;
	}
	if(isset($account_type) && $account_type != "")
	{
		array_push($where_clauses, "account.account_type = ".PearDatabase::quote($account_type));
		$url_string .= "&accounttype=".$account_type;
	}
	if(isset($current_user_only) && $current_user_only != "")
	{
		array_push($where_clauses, "crmentity.smownerid='$current_user->id'");
		$url_string .= "&current_user_only=".$current_user_only;
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
			   // ACIPIA - to allow prev/next button to use criterias
			  $url_string .= '&' . urlencode( 'assigned_user_id[]' ) . '=' . $val ;
			   // ACIPIA 
		}
	}

	$log->info("Here is the where clause for the list view: $where");

}
if($viewid != 0)
{
	$CActionDtls = $oCustomView->getCustomActionDetails($viewid);
}
//Modified by Raju
	$other_text='<form name="massdelete" method="POST">
	<input name="idlist" type="hidden">
	<input name="gname" type="hidden" value="'.$groupid.'">
	<input name="viewname" type="hidden" value="'.$viewid.'">'; //give the viewid to hidden //customview
//Raju	
if(isPermitted('Accounts',2,'') == 'yes')
{
        $other_text .=	'<td width="10"><input class="button" type="submit" value="'.$app_strings[LBL_MASS_DELETE].'" onclick="return massDelete()"/></td>';
}
		$other_text .='<td><input class="button" type="submit" value="'.$app_strings[LBL_SEND_MAIL_BUTTON].'" onclick="return eMail()"/>';

if(isset($CActionDtls))
{
	$other_text .='<td><input class="button" type="submit" value="'.$app_strings[LBL_SEND_CUSTOM_MAIL_BUTTON].'" onclick="return massMail()"/></form>';
}

if($viewid == 0)
{
$cvHTML='<span class="bodyText disabled">'.$app_strings['LNK_CV_EDIT'].'</span>
	<span class="sep">|</span>
	<span class="bodyText disabled">'.$app_strings['LNK_CV_DELETE'].'</span><span class="sep">|</span>
	<a href="index.php?module=Accounts&action=CustomView" class="link">'.$app_strings['LNK_CV_CREATEVIEW'].'</a>';
}else
{
$cvHTML='<a href="index.php?module=Accounts&action=CustomView&record='.$viewid.'" class="link">'.$app_strings['LNK_CV_EDIT'].'</a>
	<span class="sep">|</span>
	<a href="index.php?module=CustomView&action=Delete&dmodule=Accounts&record='.$viewid.'" class="link">'.$app_strings['LNK_CV_DELETE'].'</a>
	<span class="sep">|</span>
	<a href="index.php?module=Accounts&action=CustomView" class="link">'.$app_strings['LNK_CV_CREATEVIEW'].'</a>';
}
	$customviewstrings='<td align="right">'.$app_strings[LBL_VIEW].'
                        <SELECT NAME="view" onchange="showDefaultCustomView(this)">
                                <OPTION VALUE="0">'.$mod_strings[LBL_ALL].'</option>
				'.$customviewcombo_html.'
                        </SELECT>
			'.$cvHTML.'
                </td>';

$smarty = new vtigerCRM_Smarty;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("CUSTOMVIEW",$customviewstrings);
$smarty->assign("BUTTONS",$other_text);
$smarty->assign("MODULE",$currentModule);


//Retreive the list from Database
//<<<<<<<<<customview>>>>>>>>>
if($viewid != "0")
{
	$listquery = getListQuery("Accounts");
	$query = $oCustomView->getModifiedCvListQuery($viewid,$listquery,"Accounts");
}else
{
	$query = getListQuery("Accounts");
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

//$url_qry = getURLstring($focus);

if(isset($order_by) && $order_by != '')
{	
	if($order_by == 'smownerid')
        {
                $query .= ' ORDER BY user_name '.$sorder;
        }
        else
        {
		$tablename = getTableNameForField('Accounts',$order_by);
		$tablename = (($tablename != '')?($tablename."."):'');

                $query .= ' ORDER BY '.$tablename.$order_by.' '.$sorder;
        }
}

$list_result = $adb->query($query);

//Retreiving the no of rows
$noofrows = $adb->num_rows($list_result);

//Retreiving the start value from request
if(isset($_REQUEST['start']) && $_REQUEST['start'] != '')
{
        $start = $_REQUEST['start'];

	//added to remain the navigation when sort
	$url_string = "&start=".$_REQUEST['start'];
}
else
{

        $start = 1;
}

//Retreive the Navigation array
$navigation_array = getNavigationValues($start, $noofrows, $list_max_entries_per_page);


// Setting the record count string
//modified by rdhital
$start_rec = $navigation_array['start'];
$end_rec = $navigation_array['end_val']; 
//By Raju Ends

//mass merge for word templates -- *Raj*17/11
while($row = $adb->fetch_array($list_result))
{
	$ids[] = $row["crmid"];
}
if(isset($ids))
{
	echo "<input name='allids' type='hidden' value='".implode($ids,";")."'>";
}
if(isPermitted("Accounts",8,'') == 'yes') 
{
	$smarty->assign("MERGEBUTTON","<input title=\"$app_strings[LBL_MERGE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_MERGE_BUTTON_KEY]\" class=\"button\" onclick=\"return massMerge()\" type=\"submit\" name=\"Merge\" value=\" $app_strings[LBL_MERGE_BUTTON_LABEL]\"></td>");
	$wordTemplateResult = fetchWordTemplateList("Accounts");
	$tempCount = $adb->num_rows($wordTemplateResult);
	$tempVal = $adb->fetch_array($wordTemplateResult);
	for($templateCount=0;$templateCount<$tempCount;$templateCount++)
	{
		$optionString .="<option value=\"".$tempVal["templateid"]."\">" .$tempVal["filename"] ."</option>";
		$tempVal = $adb->fetch_array($wordTemplateResult);
	}
	$smarty->assign("WORDTEMPLATEOPTIONS","<td align=right>&nbsp;&nbsp;".$mod_strings['LBL_SELECT_TEMPLATE_TO_MAIL_MERGE']."<select name=\"mergefile\">".$optionString."</select>");
}
//mass merge for word templates

$record_string= $app_strings[LBL_SHOWING]." " .$start_rec." - ".$end_rec." " .$app_strings[LBL_LIST_OF] ." ".$noofrows;

//Retreive the List View Table Header
if($viewid !='')
$url_string .= "&viewname=".$viewid;

$listview_header = getListViewHeader($focus,"Accounts",$url_string,$sorder,$order_by,"",$oCustomView);
$smarty->assign("LISTHEADER", $listview_header);


$listview_entries = getListViewEntries($focus,"Accounts",$list_result,$navigation_array,"","","EditView","Delete",$oCustomView);
$smarty->assign("LISTENTITY", $listview_entries);
$smarty->assign("SELECT_SCRIPT", $view_script);
$smarty->assign("CATEGORY",$category);

$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"Accounts","index",$viewid);
$smarty->assign("NAVIGATION", $navigationOutput);
$smarty->assign("RECORD_COUNTS", $record_string);

$smarty->display("ListView.tpl");


?>

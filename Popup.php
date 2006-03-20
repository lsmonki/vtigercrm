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
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/ListView/ListView.php');
require_once('include/database/PearDatabase.php');
require_once('include/ComboUtil.php');
require_once('include/utils/utils.php');

global $app_strings;
global $current_language;
global $currentModule;
global $theme;
$current_module_strings = return_module_language($current_language,$currentModule);
$url_string = '';
$smarty = new vtigerCRM_Smarty;
if (!isset($where)) $where = "";

$popuptype = '';
$popuptype = $_REQUEST["popuptype"];

switch($currentModule)
{
	case 'Contacts':
		require_once("modules/$currentModule/Contact.php");
		$focus = new Contact();
		$log = LoggerManager::getLogger('contact_list');
		$comboFieldNames = Array('leadsource'=>'leadsource_dom');
		$comboFieldArray = getComboArray($comboFieldNames);
		$smarty->assign("SINGLE_MOD",'Contact');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",$_REQUEST['return_module']);
		else
			$smarty->assign("RETURN_MODULE",'Emails');
		if (isset($_REQUEST['order_by'])) $order_by = $_REQUEST['order_by'];
		if (isset($_REQUEST['select'])) $smarty->assign("SELECT",'enable');
		break;
	case 'Accounts':
		require_once("modules/$currentModule/Account.php");
		$focus = new Account();
		$log = LoggerManager::getLogger('account_list');
		$comboFieldNames = Array('accounttype'=>'account_type_dom'
				,'industry'=>'industry_dom');
		$comboFieldArray = getComboArray($comboFieldNames);
		$smarty->assign("SINGLE_MOD",'Account');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",$_REQUEST['return_module']);
		else
			$smarty->assign("RETURN_MODULE",'Emails');
		$sorder = $focus->getSortOrder();
		$order_by = $focus->getOrderBy();
		break;
	case 'Leads':
		require_once("modules/$currentModule/Lead.php");
		$focus = new Lead();
		$log = LoggerManager::getLogger('contact_list');
		$comboFieldNames = Array('leadsource'=>'leadsource_dom'
				,'leadstatus'=>'leadstatus_dom'
				,'rating'=>'rating_dom'
				,'industry'=>'industry_dom');
		$comboFieldArray = getComboArray($comboFieldNames);
		$smarty->assign("SINGLE_MOD",'Lead');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",$_REQUEST['return_module']);
		else
			$smarty->assign("RETURN_MODULE",'Emails');
		if (isset($_REQUEST['order_by'])) $order_by = $_REQUEST['order_by'];
		break;
	case 'Potentials':
		require_once("modules/$currentModule/Opportunity.php");
		$focus = new Potential();
		$log = LoggerManager::getLogger('potential_list');
		$comboFieldNames = Array('leadsource'=>'leadsource_dom'
				,'opportunity_type'=>'opportunity_type_dom'
				,'sales_stage'=>'sales_stage_dom');
		$comboFieldArray = getComboArray($comboFieldNames);
		$smarty->assign("SINGLE_MOD",'Opportunity');
		if(isset($_REQUEST['sorder']) && $_REQUEST['sorder'] != '')
			$sorder = $_REQUEST['sorder'];
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",$_REQUEST['return_module']);
		break;
	case 'Quotes':
		require_once("modules/$currentModule/Quote.php");	
		$focus = new Quote();
		$log = LoggerManager::getLogger('quotes_list');
		$comboFieldNames = Array('quotestage'=>'quotestage_dom');
		$comboFieldArray = getComboArray($comboFieldNames);
		$smarty->assign("SINGLE_MOD",'Quote');
		if(isset($_REQUEST['sorder']) && $_REQUEST['sorder'] != '')
			$sorder = $_REQUEST['sorder'];
		break;
	case 'Invoice':
		require_once("modules/$currentModule/Invoice.php");
		$focus = new Invoice();
		$smarty->assign("SINGLE_MOD",'Invoice');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",$_REQUEST['return_module']);
		if (isset($_REQUEST['order_by'])) $order_by = $_REQUEST['order_by'];
		break;
	case 'Products':
		require_once("modules/$currentModule/Product.php");
		$focus = new Product();
		//echo '<pre>';print_r($_REQUEST);echo '</pre>';
		$smarty->assign("SINGLE_MOD",'Product');
		if(isset($_REQUEST['curr_row']))
		{
			$curr_row = $_REQUEST['curr_row'];
			$smarty->assign("CURR_ROW", $curr_row);
			$url_string .="&curr_row=".$_REQUEST['curr_row'];
		}
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",$_REQUEST['return_module']);
		if(isset($_REQUEST['smodule']) && $_REQUEST['smodule'] !='')
		{
			$smarty->assign("SMODULE",$_REQUEST['smodule']);
			$smodule = $_REQUEST['smodule'];
			$url_string = '&smodule=VENDOR';
			$search_query .= " and vendor_id=''";
		}
		break;
	case 'Vendors':
		require_once("modules/$currentModule/Vendor.php");
		$focus = new Vendor();
		$smarty->assign("SINGLE_MOD",'Vendor');
		if (isset($_REQUEST['order_by'])) $order_by = $_REQUEST['order_by'];
		if(isset($_REQUEST['sorder']) && $_REQUEST['sorder'] != '')
			$sorder = $_REQUEST['sorder'];
		break;
	case 'SalesOrder':
		require_once("modules/$currentModule/SalesOrder.php");
		$focus = new SalesOrder();
		$smarty->assign("SINGLE_MOD",'SalesOrder');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",$_REQUEST['return_module']);
		if (isset($_REQUEST['order_by'])) $order_by = $_REQUEST['order_by'];
		break;
	case 'PurchaseOrder':
		require_once("modules/$currentModule/PurchaseOrder.php");
		$focus = new Order();
		$smarty->assign("SINGLE_MOD",'PurchaseOrder');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",$_REQUEST['return_module']);
		if (isset($_REQUEST['order_by'])) $order_by = $_REQUEST['order_by'];
		break;
	case 'PriceBooks':
		require_once("modules/$currentModule/PriceBook.php");
		$focus = new PriceBook();
		$smarty->assign("SINGLE_MOD",'PriceBook');
		if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
			$smarty->assign("RETURN_MODULE",$_REQUEST['return_module']);
		if (isset($_REQUEST['order_by'])) $order_by = $_REQUEST['order_by'];
		break;

}


if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'true')
{
	$url_string .="&query=true";
	if (isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];
	if (isset($_REQUEST['assigned_user_id'])) $assigned_user_id = $_REQUEST['assigned_user_id'];


	$where_clauses = Array();
	if($currentModule == 'Contacts')
	{
		if (isset($_REQUEST['firstname'])) $firstname = $_REQUEST['firstname'];
		if (isset($_REQUEST['lastname'])) $lastname = $_REQUEST['lastname'];
		if (isset($_REQUEST['title'])) $title = $_REQUEST['title'];

		if(isset($lastname) && $lastname != "") {
			array_push($where_clauses, "contactdetails.lastname like ".PearDatabase::quote($lastname.'%')."");
			$url_string .= "&lastname=".$lastname;
		}
		if(isset($firstname) && $firstname != "") {
			array_push($where_clauses, "contactdetails.firstname like ".PearDatabase::quote($firstname.'%')."");
			$url_string .= "&firstname=".$firstname;
		}
		if(isset($title) && $title != "")       {
			array_push($where_clauses, "contactdetails.title like ".PearDatabase::quote("%".$title.'%')."");
			$url_string .= "&title=".$title;
		}
		if(isset($current_user_only) && $current_user_only != "") {
			array_push($where_clauses, "crmentity.smownerid='$current_user->id'");
			$url_string .= "&current_user_only=on";
		}

	}
	if($currentModule == 'Accounts')
	{
		if (isset($_REQUEST['name'])) $name = $_REQUEST['name'];
		if (isset($_REQUEST['website'])) $website = $_REQUEST['website'];
		if (isset($_REQUEST['phone'])) $phone = $_REQUEST['phone'];
		if (isset($_REQUEST['address_city'])) $address_city = $_REQUEST['address_city'];

		if(isset($name) && $name != ""){
			array_push($where_clauses, "account.accountname like ".PearDatabase::quote($name."%"));
			$url_string .= "&name=".$name;
		}
		if(isset($website) && $website != "") array_push($where_clauses, "account.website like ".PearDatabase::quote("%".$website."%"));
		if(isset($phone) && $phone != "") array_push($where_clauses, "(account.phone like ".PearDatabase::quote("%".$phone."%")." OR account.otherphone like ".PearDatabase::quote("%".$phone."%")." OR account.fax like ".PearDatabase::quote("%".$phone."%").")");
		if(isset($address_city) && $address_city != ""){
			array_push($where_clauses, "(accountbillads.city like ".PearDatabase::quote("%".$address_city."%")." OR accountshipads.city like ".PearDatabase::quote($address_city."%").")");
			$url_string .= "&address_city=".$address_city;
		}
		if(isset($ownership) && $ownership != "") array_push($where_clauses, "account.ownership like ".PearDatabase::quote($ownership."%"));
		if(isset($current_user_only) && $current_user_only != ""){
			array_push($where_clauses, "crmentity.smownerid='$current_user->id'");
			$url_string .= "&current_user_only=".$current_user_only;
		}
	}
	if($currentModule == 'Leads')
	{
		if (isset($_REQUEST['first_name'])) $first_name = $_REQUEST['first_name'];
		if (isset($_REQUEST['last_name'])) $last_name = $_REQUEST['last_name'];
		if (isset($_REQUEST['company'])) $company = $_REQUEST['company'];
		if(isset($last_name) && $last_name != ""){
			array_push($where_clauses, "leaddetails.lastname like '$last_name%'");
			$url_string .= "&last_name=".$last_name;
		}
		if(isset($first_name) && $first_name != ""){
			array_push($where_clauses, "leaddetails.firstname like '%$first_name%'");
			$url_string .= "&first_name=".$first_name;
		}
		if(isset($company) && $company != ""){
			array_push($where_clauses, "leaddetails.company like '%$company%'");
			$url_string .= "&company=".$company;	
		}
		if(isset($current_user_only) && $current_user_only != ""){
			array_push($where_clauses, "crmentity.smownerid='$current_user->id'");
			$url_string .= "&current_user_only=".$current_user_only;
		}
		if(isset($assigned_user_id) && $assigned_user_id != "") array_push($where_clauses, "crmentity.smownerid = '$assigned_user_id'");

	}
	if($currentModule == 'Potentials')
	{
		if (isset($_REQUEST['name'])) $name = $_REQUEST['name'];
		if (isset($_REQUEST['account_name'])) $accountname = $_REQUEST['account_name'];

		if(isset($name) && $name != "") {
			array_push($where_clauses, "potential.potentialname like ".PearDatabase::quote($name.'%')."");
			$url_string .= "&name=".$name;
		}
		if(isset($accountname) && $accountname != "") {
			array_push($where_clauses, "account.accountname like ".PearDatabase::quote('%'.$accountname.'%')."");
			$url_string .= "&account_name=".$accountname;
		}
		if(isset($current_user_only) && $current_user_only != "") {
			array_push($where_clauses, "crmentity.smcreator='$current_user->id'");
			$url_string .= "&current_user_only=".$current_user_only;
		}
		if(isset($assigned_user_id) && $assigned_user_id != "")
			array_push($where_clauses, "crmentity.smownerid = '$assigned_user_id'");
	}
	if($currentModule == 'Quotes')
	{
		if (isset($_REQUEST['subject'])) $subject = $_REQUEST['subject'];
		if (isset($_REQUEST['potentialname'])) $potentialname = $_REQUEST['potentialname'];
		if (isset($_REQUEST['quotestage'])) $quotestage = $_REQUEST['quotestage'];
		if (isset($_REQUEST['accountname'])) $accountname = $_REQUEST['accountname'];
		if(isset($subject) && $subject != "")
		{
			array_push($where_clauses, "quotes.subject like ".PearDatabase::quote($subject."%"));
			$url_string .= "&subject=".$subject;
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


	}
	if($currentModule == 'Invoice')
	{
		if (isset($_REQUEST['subject'])) $subject = $_REQUEST['subject'];
		if (isset($_REQUEST['salesorder'])) $salesorder = $_REQUEST['salesorder'];

		if ($order_by !='') $smarty->assign("ORDER_BY", $order_by);
		if ($sorder !='') $smarty->assign("SORDER", $sorder);
		if (isset($subject) && $subject !='')
		{
			$search_query .= " and invoice.subject like '".$subject."%'";
			$url_string .= "&subject=".$subject;
			$smarty->assign("SUBJECT", $subject);
		}

		if (isset($salesorder) && $salesorder !='')
		{
			$search_query .= " and salesorder.subject like '%".$salesorder."%'";
			$url_string .= "&salesorder=".$salesorder;
			$smarty->assign("SALESORDER", $salesorder);
		}

	}
	if($currentModule == 'Products')
	{
		if (isset($_REQUEST['productname'])) $productname = $_REQUEST['productname'];
		if (isset($_REQUEST['productcode'])) $productcode = $_REQUEST['productcode'];
		if (isset($_REQUEST['unitprice'])) $unitprice = $_REQUEST['unitprice'];

		if ($order_by !='') $smarty->assign("ORDER_BY", $order_by);
		if ($sorder !='') $smarty->assign("SORDER", $sorder);


		if (isset($productname) && $productname !='')
		{
			$search_query .= " and productname like '".$productname."%'";
			$url_string .= "&productname=".$productname;
			$smarty->assign("PRODUCT_NAME", $productname);
		}

		if (isset($productcode) && $productcode !='')
		{
			$search_query .= " and productcode like '%".$productcode."%'";
			$url_string .= "&productcode=".$productcode;
			$smarty->assign("PRODUCT_CODE", $productcode);
		}
		if (isset($unitprice) && $unitprice !='')
		{
			$search_query .= " and unit_price like '%".$unitprice."%'";
			$url_string .= "&unitprice=".$unitprice;
			$smarty->assign("UNITPRICE", $unitprice);
		}


	}
	if($currentModule == 'PurchaseOrder')
	{
		if (isset($_REQUEST['subject'])) $subject = $_REQUEST['subject'];
		if (isset($_REQUEST['vendorname'])) $vendorname = $_REQUEST['vendorname'];
		if (isset($_REQUEST['trackingno'])) $trackingno = $_REQUEST['trackingno'];

		if ($order_by !='') $smarty->assign("ORDER_BY", $order_by);
		if ($sorder !='') $smarty->assign("SORDER", $sorder);

		$where_clauses = Array();
		if(isset($subject) && $subject != '')
		{
			array_push($where_clauses, "purchaseorder.subject like ".PearDatabase::quote($subject."%"));
			$url_string .= "&subject=".$subject;

		}
		if(isset($vendorname) && $vendorname != "")
		{
			array_push($where_clauses, "vendor.vendorname like ".PearDatabase::quote("%".$vendorname."%"));
			$url_string .= "&vendorname=".$vendorname;
		}
		if(isset($trackingno) && $trackingno != "")
		{
			array_push($where_clauses, "purchaseorder.tracking_no like ".PearDatabase::quote("%".$trackingno."%"));
			$url_string .= "&trackingno=".$trackingno;
		}
	}
	if($currentModule == 'SalesOrder')
	{
		if (isset($_REQUEST['subject'])) $subject = $_REQUEST['subject'];
		if (isset($_REQUEST['accountname'])) $accountname = $_REQUEST['accountname'];
		if (isset($_REQUEST['quotename'])) $quotename = $_REQUEST['quotename'];

		if ($order_by !='') $smarty->assign("ORDER_BY", $order_by);
		if ($sorder !='') $smarty->assign("SORDER", $sorder);

		$where_clauses = Array();

		if (isset($subject) && $subject !='')
		{
			array_push($where_clauses, "salesorder.subject like ".PearDatabase::quote($subject.'%'));
			$url_string .= "&subject=".$subject;
		}	

		if (isset($accountname) && $accountname !='')
		{
			array_push($where_clauses, "account.accountname like ".PearDatabase::quote($accountname.'%'));
			$url_string .= "&accountname=".$accountname;
		}

		if (isset($quotename) && $quotename !='')
		{
			array_push($where_clauses, "quotes.subject like ".PearDatabase::quote($quotename.'%'));
			$url_string .= "&quotename=".$quotename;
		}

	}
	if($currentModule == 'Vendors')
	{
		if (isset($_REQUEST['vendorname'])) $vendorname = $_REQUEST['vendorname'];
		if (isset($_REQUEST['companyname'])) $companyname = $_REQUEST['companyname'];
		if (isset($_REQUEST['category'])) $category = $_REQUEST['category'];
		$sql="select * from field where tablename='vendorcf' order by fieldlabel";
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
					$str=" vendorcf.".$column[$i]." = 1";
				else
					$str="vendorcf.".$column[$i]." like '$customfield[$i]%'";
				array_push($where_clauses, $str);
				//        $search_query .= ' and '.$str;
				$url_string .="&".$column[$i]."=".$customfield[$i];
			}
		}
		if (isset($vendorname) && $vendorname !='')
		{
			array_push($where_clauses, "vendorname like ".PearDatabase::quote($vendorname.'%'));
			//$search_query .= " and productname like '".$productname."%'";
			$url_string .= "&vendorname=".$vendorname;
		}

		if (isset($companyname) && $companyname !='')
		{
			array_push($where_clauses, "company_name like ".PearDatabase::quote($companyname.'%'));
			//$search_query .= " and productcode like '".$productcode."%'";
			$url_string .= "&companyname=".$companyname;
		}

		if (isset($category) && $category !='')
		{
			array_push($where_clauses, "category like ".PearDatabase::quote($category.'%'));
			//$search_query .= " and productcode like '".$productcode."%'";
			$url_string .= "&category=".$category;
		}

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
		}
	}

	$log->info("Here is the where clause for the list view: $where");

}


$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("THEME_PATH",$theme_path);
$smarty->assign("MODULE",$currentModule);


//Retreive the list from Database
if($currentModule == 'PriceBooks')
{
	$productid=$_REQUEST['productid'];
	$query = 'select pricebook.*, pricebookproductrel.productid, pricebookproductrel.listprice, crmentity.crmid, crmentity.smownerid, crmentity.modifiedtime from pricebook inner join pricebookproductrel on pricebookproductrel.pricebookid = pricebook.pricebookid inner join crmentity on crmentity.crmid = pricebook.pricebookid where pricebookproductrel.productid='.$productid.' and crmentity.deleted=0';
}
else
	$query = getListQuery($currentModule);

if(isset($where) && $where != '')
{
        $query .= ' and '.$where;
}

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

$focus->list_mode="search";
$focus->popup_type=$popuptype;

$listview_header = getSearchListViewHeader($focus,"$currentModule",$url_string,$sorder,$order_by);
$smarty->assign("LISTHEADER", $listview_header);


$listview_entries = getSearchListViewEntries($focus,"$currentModule",$list_result,$navigation_array);
$smarty->assign("LISTENTITY", $listview_entries);

if($order_by !='')
$url_string .="&order_by=".$order_by;
if($sorder !='')
$url_string .="&sorder=".$sorder;

$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,$currentModule,"Popup");
$smarty->assign("NAVIGATION", $navigationOutput);
$smarty->assign("RECORD_COUNTS", $record_string);


$smarty->display("Popup.tpl");

?>


<?
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/
require_once('include/database/PearDatabase.php');
require_once('XTemplate/xtpl.php');
require_once('modules/Products/Product.php');
require_once('include/utils/utils.php');
require_once('include/utils/utils.php');
require_once('include/ComboUtil.php');

global $app_strings,$mod_strings,$current_language,$theme,$log;
$current_module_strings = return_module_language($current_language, 'Products');

$pricebook_id = $_REQUEST['pricebook_id'];
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');
$pricebookname = getPriceBookName($pricebook_id);

$xtpl=new XTemplate ('modules/Products/AddProductsToPriceBook.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("IMAGE_PATH",$image_path);

$comboFieldNames = Array('manufacturer'=>'manufacturer_dom'
                      ,'productcategory'=>'productcategory_dom');
$comboFieldArray = getComboArray($comboFieldNames);

$focus = new Product();

if (isset($_REQUEST['order_by'])) $order_by = $_REQUEST['order_by'];

$url_string = ''; // assigning http url string
$sorder = 'ASC';  // Default sort order
if(isset($_REQUEST['sorder']) && $_REQUEST['sorder'] != '')
$sorder = $_REQUEST['sorder'];

if(isset($_REQUEST['query']) && $_REQUEST['query'] != '' && $_REQUEST['query'] == 'true')
{
	$url_string .="&query=true";
	if (isset($_REQUEST['productname'])) $productname = $_REQUEST['productname'];
        if (isset($_REQUEST['productcode'])) $productcode = $_REQUEST['productcode'];
        if (isset($_REQUEST['commissionrate'])) $commissionrate = $_REQUEST['commissionrate'];
	if (isset($_REQUEST['qtyperunit'])) $qtyperunit = $_REQUEST['qtyperunit'];
        if (isset($_REQUEST['unitprice'])) $unitprice = $_REQUEST['unitprice'];
        if (isset($_REQUEST['manufacturer'])) $manufacturer = $_REQUEST['manufacturer'];
        if (isset($_REQUEST['productcategory'])) $productcategory = $_REQUEST['productcategory'];
	if (isset($_REQUEST['start_date'])) $start_date = $_REQUEST['start_date'];
        if (isset($_REQUEST['expiry_date'])) $expiry_date = $_REQUEST['expiry_date'];
        if (isset($_REQUEST['purchase_date'])) $purchase_date = $_REQUEST['purchase_date'];

	$where_clauses = Array();
	//Added for Custom Field Search
	$sql="select * from vtiger_field where tablename='productcf' order by fieldlabel";
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
                                $str=" vtiger_productcf.".$column[$i]." = 1";
                        else
			        $str=" vtiger_productcf.".$column[$i]." like '$customfield[$i]%'";
		        array_push($where_clauses, $str);
			$url_string .="&".$column[$i]."=".$customfield[$i];
	        }
	}
	//upto this added for Custom Field

	if (isset($productname) && $productname !='')
	{
		array_push($where_clauses, "productname like ".$adb->quote($productname.'%'));
		$url_string .= "&productname=".$productname;
	}
	
	if (isset($productcode) && $productcode !='')
	{
		array_push($where_clauses, "productcode like ".$adb->quote($productcode.'%'));
		$url_string .= "&productcode=".$productcode;
	}

	if (isset($commissionrate) && $commissionrate !='')
	{
		array_push($where_clauses, "commissionrate like ".$adb->quote($commissionrate.'%'));
		 $url_string .= "&commissionrate=".$commissionrate;
	}
	
	if (isset($qtyperunit) && $qtyperunit !='')
	{
		array_push($where_clauses, "qty_per_unit like ".$adb->quote($qtyperunit.'%'));
		$url_string .= "&qtyperunit=".$qtyperunit;
	}
	
	if (isset($unitprice) && $unitprice !='')
	{
		array_push($where_clauses, "unit_price like ".$adb->quote($unitprice.'%'));
		$url_string .= "&unitprice=".$unitprice;
	}
	if (isset($manufacturer) && $manufacturer !='' && $manufacturer !='--None--')
        {
		array_push($where_clauses, "manufacturer like ".$adb->quote($manufacturer.'%'));
                $url_string .= "&manufacturer=".$manufacturer;
	}
	if (isset($productcategory) && $productcategory !='' && $productcategory !='--None--')
        {
		array_push($where_clauses, "productcategory like ".$adb->quote($productcategory.'%'));
                $url_string .= "&productcategory=".$productcategory;
	}
	if (isset($start_date) && $start_date !='')
        {
		array_push($where_clauses, "start_date like ".$adb->quote($start_date.'%'));
                $url_string .= "&start_date=".$start_date;
        } 
	if (isset($expiry_date) && $expiry_date !='')
        {
		array_push($where_clauses, "expiry_date like ".$adb->quote($expiry_date.'%'));
                $url_string .= "&expiry_date=".$expiry_date;
        } 
	if (isset($purchase_date) && $purchase_date !='')
        {
		array_push($where_clauses, "purchase_date like ".$adb->quote($purchase_date.'%'));
                $url_string .= "&purchase_date=".$purchase_date;
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

$other_text = '<table width="95%" border="0" cellpadding="1" cellspacing="0" align="center">
	<form name="addToPB" method="POST">
	<tr>
	<input name="pricebook_id" type="hidden" value="'.$pricebook_id.'">
	<input name="idlist" type="hidden">
	<input name="viewname" type="hidden">';
        $other_text .='<td align="center"><input class="classBtn" type="submit" value="Add To PriceBook" onclick="return addtopricebook()"/>';
	$other_text .='&nbsp;<input title="'.$app_strings[LBL_CANCEL_BUTTON_TITLE].'" accessKey="'.$app_strings[LBL_CANCEL_BUTTON_KEY].'" class="classBtn" onclick="window.history.back()" type="button" name="button" value="'.$app_strings[LBL_CANCEL_BUTTON_LABEL].'"></td>';

	$other_text .='</tr></table>';

//Retreive the list from Database

$list_query = getListQuery("Products");

if(isset($where) && $where != '')
{
        $list_query .= ' and '.$where;
}

$xtpl->assign("PRODUCTLISTHEADER", get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'], $other_text, false ));

if(isset($order_by) && $order_by != '')
{
        $list_query .= ' ORDER BY '.$order_by.' '.$sorder;
}
$list_result = $adb->query($list_query);
$num_rows = $adb->num_rows($list_result);

$record_string= "Total No of Rows: ".$num_rows;

//Retreiving the array of already releated vtiger_products;

$sql1 = "select productid from vtiger_pricebookproductrel where pricebookid=".$pricebook_id;
$res1 = $adb->query($sql1);
$num_prod_rows = $adb->num_rows($res1);
$prod_array = Array();
for($i=0; $i<$num_prod_rows; $i++)
{
	$prodid=$adb->query_result($res1,$i,"productid"); 
	$prod_array[$prodid] = $prodid;
}

$unit_price_array=array();
$field_name_array=array();
for($i=0; $i<$num_rows; $i++)
{
	
	$entity_id = $adb->query_result($list_result,$i,"crmid");
	if(! array_key_exists($entity_id, $prod_array))
	{
		$unit_price = 	$adb->query_result($list_result,$i,"unit_price");
		$field_name=$entity_id."_listprice";
		$unit_price_array[]="'".$unit_price."'";
		$field_name_array[]="'".$field_name."'";
	}
}
//Retreive the List View Table Header

$xtpl->assign("UNIT_PRICE_ARRAY",implode(",",$unit_price_array));
$xtpl->assign("FIELD_NAME_ARRAY",implode(",",$field_name_array));

$list_header = '';
$list_header .= '<tr>';
$list_header .='<td class="lvtCol"><input type="checkbox" name="selectall" onClick=\'toggleSelect(this.checked,"selected_id");updateAllListPrice()\'></td>';
$list_header .= '<td class="lvtCol">'.$mod_strings['LBL_LIST_PRODUCT_NAME'].'</td>';
$list_header .= '<td class="lvtCol">'.$mod_strings['LBL_PRODUCT_CODE'].'</td>';
$list_header .= '<td class="lvtCol">'.$mod_strings['LBL_PRODUCT_UNIT_PRICE'].'</td>';
$list_header .= '<td class="lvtCol">'.$mod_strings['LBL_PB_LIST_PRICE'].'</td>';
$list_header .= '</tr>';

$xtpl->assign("LISTHEADER", $list_header);

$list_body ='';
for($i=0; $i<$num_rows; $i++)
{
	
	 $log->info("Products :: Showing the List of products to be added in price book");
	$entity_id = $adb->query_result($list_result,$i,"crmid");
	if(! array_key_exists($entity_id, $prod_array))
	{
		$list_body .= '<tr class="lvtColData" onmouseover="this.className=\'lvtColDataHover\'" onmouseout="this.className=\'lvtColData\'" bgcolor="white">';
		$unit_price = 	$adb->query_result($list_result,$i,"unit_price");
		$field_name=$entity_id."_listprice";

		$list_body .= '<td><INPUT type=checkbox NAME="selected_id" value= '.$entity_id.' onClick=\'toggleSelectAll(this.name,"selectall");updateListPrice("'.$unit_price.'","'.$field_name.'")\'></td>';
		$list_body .= '<td>'.$adb->query_result($list_result,$i,"productname").'</td>';
		$list_body .= '<td>'.$adb->query_result($list_result,$i,"productcode").'</td>';
		$list_body .= '<td>'.$unit_price.'</td>';
		$list_body .= '<td><input type="text" name="'.$field_name.'"></td></tr>';
	}
	
}

if($order_by !='')
$url_string .="&order_by=".$order_by;
if($sorder !='')
$url_string .="&sorder=".$sorder;

$xtpl->assign("LISTENTITY", $list_body);

$xtpl->parse("main");
$xtpl->out("main");



?>

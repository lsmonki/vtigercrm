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
require_once('modules/PriceBooks/PriceBook.php');
require_once('include/utils/utils.php');
require_once('include/ComboUtil.php');

global $app_strings,$mod_strings,$current_language,$theme,$log;

$current_module_strings = return_module_language($current_language, 'Products');

$productid = $_REQUEST['return_id'];
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');
$productname = getProductName($productid);

$xtpl=new XTemplate ('modules/Products/AddProductToPriceBooks.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("IMAGE_PATH",$image_path);

$focus = new PriceBook();





$other_text = '<table border="0" cellpadding="1" cellspacing="0" width="90%" align="center">
	<form name="addToPB" method="POST">
	<tr>
	<input name="product_id" type="hidden" value="'.$productid.'">
	<input name="idlist" type="hidden">
	<input name="viewname" type="hidden">';
        $other_text .='<td align="center"><input class="classBtn" type="submit" value="Add To PriceBook" onclick="return addtopricebook()"/>&nbsp;';
	$other_text .='<input title="'.$app_strings[LBL_CANCEL_BUTTON_TITLE].'" accessKey="'.$app_strings[LBL_CANCEL_BUTTON_KEY].'" class="classBtn" onclick="window.history.back()" type="button" name="button" value="'.$app_strings[LBL_CANCEL_BUTTON_LABEL].'"></td>';
	$other_text .='</tr></table>';

//Retreive the list from Database

$list_query = getListQuery("PriceBooks");
$xtpl->assign("PRICEBOOKLISTHEADER", get_form_header($current_module_strings['LBL_LIST_PRICEBOOK_FORM_TITLE'], $other_text, false ));

$list_query .= ' ORDER BY vtiger_pricebookid DESC ';

$list_result = $adb->query($list_query);
$num_rows = $adb->num_rows($list_result);

$record_string= "Total No of Rows: ".$num_rows;

//Retreiving the array of already releated vtiger_products;

$sql1="select vtiger_crmentity.crmid, vtiger_pricebookproductrel.pricebookid,vtiger_products.unit_price from vtiger_pricebookproductrel inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_pricebookproductrel.productid inner join vtiger_products on vtiger_products.productid=vtiger_pricebookproductrel.productid where vtiger_crmentity.deleted=0 and vtiger_pricebookproductrel.productid=".$productid;
$res1 = $adb->query($sql1);
$num_prod_rows = $adb->num_rows($res1);
$pbk_array = Array();
$unit_price = getUnitPrice($productid);
for($i=0; $i<$num_prod_rows; $i++)
{
	$pbkid=$adb->query_result($res1,$i,"pricebookid"); 
	$pbk_array[$pbkid] = $pbkid;
}

$field_name_array=array();
for($i=0; $i<$num_rows; $i++)
{	
	
	$entity_id = $adb->query_result($list_result,$i,"crmid");
	if(! array_key_exists($entity_id, $pbk_array))
	{
		$field_name=$entity_id."_listprice";
		$field_name_array[]="'".$field_name."'";
	}
}

$xtpl->assign("FIELD_NAME_ARRAY",implode(",",$field_name_array));


//Retreive the List View Table Header


$list_header = '';
$list_header .= '<tr>';
$list_header .='<td class="lvtCol" width="9%"><input type="checkbox" name="selectall" onClick=\'toggleSelect(this.checked,"selected_id");updateAllListPrice("'.$unit_price.'") \'></td>';
$list_header .= '<td class="lvtCol" width="45%">'.$mod_strings['LBL_PRICEBOOK'].'</td>';
$list_header .= '<td class="lvtCol" width="23%">'.$mod_strings['LBL_PRODUCT_UNIT_PRICE'].'</td>';
$list_header .= '<td class="lvtCol" width="23%">'.$mod_strings['LBL_PB_LIST_PRICE'].'</td>';
$list_header .= '</tr>';

$xtpl->assign("LISTHEADER", $list_header);

$list_body ='';
for($i=0; $i<$num_rows; $i++)
{	

	$log->info("Products :: Showing Price Books to be added in the product");
	$entity_id = $adb->query_result($list_result,$i,"crmid");
	if(! array_key_exists($entity_id, $pbk_array))
	{
		$list_body .= '<tr class="lvtColData" onmouseover="this.className=\'lvtColDataHover\'" onmouseout="this.className=\'lvtColData\'" bgcolor="white">';
		$field_name=$entity_id."_listprice";
		$list_body .= '<td><INPUT type=checkbox NAME="selected_id" value= '.$entity_id.' onClick=\'toggleSelectAll(this.name,"selectall");updateListPrice("'.$unit_price.'","'.$field_name.'")\'></td>';
		$list_body .= '<td>'.$adb->query_result($list_result,$i,"bookname").'</td>';
		$list_body .= '<td>'.$unit_price.'</td>';
		$list_body .= '<td><input type="text" name="'.$field_name.'"></td>';
		$list_body .= '</tr>';
	}
	
}



if($order_by !='')
$url_string .="&order_by=".$order_by;
if($sorder !='')
$url_string .="&sorder=".$sorder;

$xtpl->assign("LISTENTITY", $list_body);
$xtpl->assign("RETURN_ID", $productid);

$xtpl->parse("main");
$xtpl->out("main");



?>

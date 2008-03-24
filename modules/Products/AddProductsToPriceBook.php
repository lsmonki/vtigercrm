<?php
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
require_once('Smarty_setup.php');
require_once('modules/Products/Products.php');
require_once('include/utils/utils.php');
require_once('include/utils/utils.php');
require_once('include/ComboUtil.php');

global $app_strings,$mod_strings,$current_language,$theme,$log;
$current_module_strings = return_module_language($current_language, 'Products');

$pricebook_id = $_REQUEST['pricebook_id'];
$parenttab = $_REQUEST['parenttab'];
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');
$pricebookname = getPriceBookName($pricebook_id);

$smarty= new vtigerCRM_Smarty;
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);

$focus = new Products();

if (isset($_REQUEST['order_by'])) $order_by = $_REQUEST['order_by'];

$url_string = ''; // assigning http url string
$sorder = 'ASC';  // Default sort order
if(isset($_REQUEST['sorder']) && $_REQUEST['sorder'] != '')
$sorder = $_REQUEST['sorder'];


//Retreive the list of Products 
$list_query = getListQuery("Products");

if(isset($order_by) && $order_by != '')
{
	$list_query .= ' and vtiger_products.discontinued<>0  ORDER BY '.$order_by.' '.$sorder;
}

$list_query .=  " and vtiger_products.discontinued<>0 group by vtiger_crmentity.crmid";
$list_result = $adb->query($list_query);
$num_rows = $adb->num_rows($list_result);

$record_string= "Total No of Product Available : ".$num_rows;

//Retreiving the array of already releated products
$sql1 = "select productid from vtiger_pricebookproductrel where pricebookid=?";
$res1 = $adb->pquery($sql1, array($pricebook_id));
$num_prod_rows = $adb->num_rows($res1);
$prod_array = Array();
for($i=0; $i<$num_prod_rows; $i++)
{
	$prodid=$adb->query_result($res1,$i,"productid"); 
	$prod_array[$prodid] = $prodid;
}


//Buttons Add To PriceBook and Cancel
$other_text = '
	<table width="95%" border="0" cellpadding="1" cellspacing="0" align="center">
	<form name="addToPB" method="POST" id="addToPB">
	   <tr>
		<td align="center">&nbsp;
			<input name="pricebook_id" type="hidden" value="'.$pricebook_id.'">
			<input name="idlist" type="hidden">
			<input name="viewname" type="hidden">
	';

	//we should not display the Add to PriceBook button if there is no products to associate
	if($num_rows != $num_prod_rows)
	        $other_text .='<input class="crmbutton small save" type="submit" value="'.$mod_strings[LBL_ADD_PRICEBOOK_BUTTON_LABEL].'" onclick="return addtopricebook()"/>';

$other_text .='&nbsp;<input title="'.$app_strings[LBL_CANCEL_BUTTON_TITLE].'" accessKey="'.$app_strings[LBL_CANCEL_BUTTON_KEY].'" class="crmbutton small cancel" onclick="window.history.back()" type="button" name="button" value="'.$app_strings[LBL_CANCEL_BUTTON_LABEL].'"></td>';

$other_text .='
	   </tr>
	</table>';

$smarty->assign("PRODUCTLISTHEADER", get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'], $other_text, false ));


//if the product is not associated already then we should display that products
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
$smarty->assign("UNIT_PRICE_ARRAY",implode(",",$unit_price_array));
$smarty->assign("FIELD_NAME_ARRAY",implode(",",$field_name_array));

$list_header = '';
$list_header .= '<tr>';
$list_header .='<td class="lvtCol"><input type="checkbox" name="selectall" onClick=\'toggleSelect(this.checked,"selected_id");updateAllListPrice()\'></td>';
$list_header .= '<td class="lvtCol">'.$mod_strings['LBL_LIST_PRODUCT_NAME'].'</td>';
if(getFieldVisibilityPermission('Products', $current_user->id, 'productcode') == '0')
	$list_header .= '<td class="lvtCol">'.$mod_strings['LBL_PRODUCT_CODE'].'</td>';
if(getFieldVisibilityPermission('Products', $current_user->id, 'unit_price') == '0')
	$list_header .= '<td class="lvtCol">'.$mod_strings['LBL_PRODUCT_UNIT_PRICE'].'</td>';
$list_header .= '<td class="lvtCol">'.$mod_strings['LBL_PB_LIST_PRICE'].'</td>';
$list_header .= '</tr>';

$smarty->assign("LISTHEADER", $list_header);

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

		$list_body .= '<td><INPUT type=checkbox NAME="selected_id" id="check_'.$entity_id.'" value= '.$entity_id.' onClick=\'toggleSelectAll(this.name,"selectall");updateListPrice("'.$unit_price.'","'.$field_name.'",this)\'></td>';
		$list_body .= '<td>'.$adb->query_result($list_result,$i,"productname").'</td>';
		
		if(getFieldVisibilityPermission('Products', $current_user->id, 'productcode') == '0')
			$list_body .= '<td>'.$adb->query_result($list_result,$i,"productcode").'</td>';
		if(getFieldVisibilityPermission('Products', $current_user->id, 'unit_price') == '0')
			$list_body .= '<td>'.$unit_price.'</td>';
		
		$list_body .='<td>';		
		if(isPermitted("PriceBooks","EditView","") == 'yes')
			$list_body .= '<input type="text" name="'.$field_name.'" style="visibility:hidden;" id="'.$field_name.'">';
		else
			$list_body .= '<input type="text" name="'.$field_name.'" style="visibility:hidden;" readonly id="'.$field_name.'">';
		$list_body .= '</td></tr>';	
	}
}

if($order_by !='')
	$url_string .="&order_by=".$order_by;
if($sorder !='')
	$url_string .="&sorder=".$sorder;

$smarty->assign("LISTENTITY", $list_body);
$smarty->assign("CATEGORY", $parenttab);

$smarty->display("AddProductsToPriceBook.tpl");



?>

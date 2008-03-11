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
require_once('modules/PriceBooks/PriceBooks.php');
require_once('include/utils/utils.php');
require_once('include/ComboUtil.php');

global $app_strings,$mod_strings,$current_language,$theme,$log;

$current_module_strings = return_module_language($current_language, 'Products');

$productid = $_REQUEST['return_id'];
$parenttab = $_REQUEST['parenttab'];
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');
$productname = getProductName($productid);

$smarty=new vtigerCRM_Smarty; 

$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);

$focus = new PriceBooks();

//Retreive the list of PriceBooks
$list_query = getListQuery("PriceBooks");

$list_query .= ' and vtiger_pricebook.active<>0  ORDER BY pricebookid DESC ';

$list_result = $adb->query($list_query);
$num_rows = $adb->num_rows($list_result);

$record_string= "Total No of PriceBooks : ".$num_rows;

//Retreiving the array of already releated products
$sql1="select vtiger_crmentity.crmid, vtiger_pricebookproductrel.pricebookid,vtiger_products.unit_price from vtiger_pricebookproductrel inner join vtiger_crmentity on vtiger_crmentity.crmid=vtiger_pricebookproductrel.productid inner join vtiger_products on vtiger_products.productid=vtiger_pricebookproductrel.productid where vtiger_crmentity.deleted=0 and vtiger_pricebookproductrel.productid=?";
$res1 = $adb->pquery($sql1, array($productid));
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


$other_text = '
	<table border="0" cellpadding="1" cellspacing="0" width="90%" align="center">
	<form name="addToPB" method="POST" id="addToPB">
	   <tr>
		<td align="center">&nbsp;
			<input name="product_id" type="hidden" value="'.$productid.'">
			<input name="idlist" type="hidden">
			<input name="viewname" type="hidden">';

	//we should not display the Add to PriceBook button if there is no pricebooks to associate
	if($num_rows != $num_prod_rows)
        	$other_text .='<input class="crmbutton small save" type="submit" value="'.$mod_strings['LBL_ADD_PRICEBOOK_BUTTON_LABEL'].'" onclick="return addtopricebook()"/>&nbsp;';

$other_text .='<input title="'.$app_strings[LBL_CANCEL_BUTTON_TITLE].'" accessKey="'.$app_strings[LBL_CANCEL_BUTTON_KEY].'" class="crmbutton small cancel" onclick="window.history.back()" type="button" name="button" value="'.$app_strings[LBL_CANCEL_BUTTON_LABEL].'"></td>';
$other_text .='
	   </tr>
	</table>';

$smarty->assign("PRICEBOOKLISTHEADER", get_form_header($current_module_strings['LBL_LIST_PRICEBOOK_FORM_TITLE'], $other_text, false ));

$smarty->assign("FIELD_NAME_ARRAY",implode(",",$field_name_array));



//List View Table Header
$list_header = '';
$list_header .= '<tr>';
$list_header .='<td class="lvtCol" width="9%"><input type="checkbox" name="selectall" onClick=\'toggleSelect(this.checked,"selected_id");updateAllListPrice("'.$unit_price.'") \'></td>';
$list_header .= '<td class="lvtCol" width="45%">'.$mod_strings['LBL_PRICEBOOK'].'</td>';
if(getFieldVisibilityPermission('Products', $current_user->id, 'unit_price') == '0')
	$list_header .= '<td class="lvtCol" width="23%">'.$mod_strings['LBL_PRODUCT_UNIT_PRICE'].'</td>';
$list_header .= '<td class="lvtCol" width="23%">'.$mod_strings['LBL_PB_LIST_PRICE'].'</td>';
$list_header .= '</tr>';

$smarty->assign("LISTHEADER", $list_header);

$list_body ='';
for($i=0; $i<$num_rows; $i++)
{	

	$log->info("Products :: Showing Price Books to be added in the product");
	$entity_id = $adb->query_result($list_result,$i,"crmid");
	if(! array_key_exists($entity_id, $pbk_array))
	{
		$list_body .= '<tr class="lvtColData" onmouseover="this.className=\'lvtColDataHover\'" onmouseout="this.className=\'lvtColData\'" bgcolor="white">';
		$field_name=$entity_id."_listprice";
		$list_body .= '<td><INPUT type=checkbox NAME="selected_id" id="check_'.$entity_id.'" value= '.$entity_id.' onClick=\'toggleSelectAll(this.name,"selectall");updateListPrice("'.$unit_price.'","'.$field_name.'",this)\'></td>';
		$list_body .= '<td>'.$adb->query_result($list_result,$i,"bookname").'</td>';
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
$smarty->assign("RETURN_ID", $productid);
$smarty->assign("CATEGORY", $parenttab);

$smarty->display("AddProductToPriceBooks.tpl");



?>

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

global $mod_strings;
global $app_strings;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

if(isset($_REQUEST['return_module']) && $_REQUEST['return_module']=="PriceBooks")
{
	$pricebook_id = $_REQUEST['pricebook_id'];
	$product_id = $_REQUEST['record'];
	$listprice = $_REQUEST['listprice'];
	$return_action = "CallRelatedList";
	$return_id = $_REQUEST['pricebook_id'];
}
else
{
	$product_id = $_REQUEST['record'];
	$pricebook_id = $_REQUEST['pricebook_id'];
	$listprice = getListPrice($product_id,$pricebook_id);
	$return_action = "CallRelatedList";
	$return_id = $_REQUEST['pricebook_id'];
}
$output='';
$output ='<div id="EditListPriceLay">
	<form action="index.php" name="index">
	<input type="hidden" name="module" value="Products">
	<input type="hidden" name="action" value="UpdateListPrice">
	<input type="hidden" name="record" value="'.$return_id.'">
	<input type="hidden" name="pricebook_id" value="'.$pricebook_id.'">
	<input type="hidden" name="product_id" value="'.$product_id.'">
	<table width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td class="genHeaderSmall" align="left" style="border-bottom:1px solid #CCCCCC;" width="50%">EditListPrice</td>
		<td style="border-bottom:1px solid #CCCCCC;">&nbsp;</td>
		<td align="right" style="border-bottom:1px solid #CCCCCC;" width="40%"><a href="#" onClick="document.getElementById(\'EditListPriceLay\').style.display=\'none\';">Close</a></td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;</td>
	</tr>
	<tr>
		<td width="50%"><b>EditListPrice</b></td>
		<td width="2%"><b>:</b></td>
		<td width="48%" align="left" ><input class="dataInput" type="text" id="list_price" name="list_price" value="'.$listprice.'" /></td>
	</tr>
	<tr><td colspan="3" style="border-bottom:1px dashed #CCCCCC;">&nbsp;</td></tr>
	<tr>
		<td colspan="3" align="center">
			<input type="button" onclick="gotoUpdateListPrice('.$return_id.','.$pricebook_id.','.$product_id.');return verify_data(EditView)" name="button" value="'.$app_strings["LBL_SAVE_BUTTON_LABEL"].'" class="small">
			<input title="'.$app_strings["LBL_CANCEL_BUTTON_LABEL"].'" accessKey="'.$app_strings["LBL_CANCEL_BUTTON_KEY"].'" class="small" onClick="document.getElementById(\'EditListPriceLay\').style.display=\'none\';" type="button" name="button" value="'.$app_strings["LBL_CANCEL_BUTTON_LABEL"].'">
		</td>
		
	</tr>
	</table>
</form>
</div>';

echo $output;

?>

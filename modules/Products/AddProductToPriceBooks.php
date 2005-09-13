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
require_once('modules/Products/PriceBook.php');
require_once('include/utils.php');
require_once('include/uifromdbutil.php');
require_once('include/ComboUtil.php');

global $app_strings;
global $mod_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Products');

global $list_max_entries_per_page;
global $urlPrefix;


global $theme;
global $vtlog;
$productid = $_REQUEST['return_id'];
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');
$productname = getProductName($productid);
echo get_module_title($current_module_strings['LBL_MODULE_NAME'], $current_module_strings['LBL_ADD_PRODUCT_PRICEBOOKS'].": ".$productname, true);
echo "<br>";
//echo get_form_header("Product Search", "", false);

$xtpl=new XTemplate ('modules/Products/AddProductToPriceBooks.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("IMAGE_PATH",$image_path);

$focus = new PriceBook();

echo get_form_footer();
//echo '<br><br>';



$other_text = '<table border="0" cellpadding="1" cellspacing="0">
	<form name="addToPB" method="POST">
	<tr>
	<input name="product_id" type="hidden" value="'.$productid.'">
	<input name="idlist" type="hidden">
	<input name="viewname" type="hidden">';
        $other_text .='<td><input class="button" type="submit" value="Add To PriceBook" onclick="return addtopricebook()"/></td>';
	$other_text .='<td>&nbsp;<input title="'.$app_strings[LBL_CANCEL_BUTTON_TITLE].'" accessKey="'.$app_strings[LBL_CANCEL_BUTTON_KEY].'" class="button" onclick="window.history.back()" type="button" name="button" value="'.$app_strings[LBL_CANCEL_BUTTON_LABEL].'"></td>';
	$other_text .='</tr></table>';

//Retreive the list from Database

//$list_query = $focus->get_nonproduct_pricebooks($productid);
$list_query = getListQuery("PriceBook");

$xtpl->assign("PRICEBOOKLISTHEADER", get_form_header($current_module_strings['LBL_LIST_PRICEBOOK_FORM_TITLE'], $other_text, false ));

$list_query .= ' ORDER BY pricebookid DESC ';

$list_result = $adb->query($list_query);
$num_rows = $adb->num_rows($list_result);

$record_string= "Total No of Rows: ".$num_rows;

//Retreiving the array of already releated products;

$sql1="select crmentity.crmid, pricebookproductrel.pricebookid,products.unit_price from pricebookproductrel inner join crmentity on crmentity.crmid=pricebookproductrel.productid inner join products on products.productid=pricebookproductrel.productid where crmentity.deleted=0 and pricebookproductrel.productid=".$productid;
//$sql1 = "select crmentity.crmid, pricebookproductrel.pricebookid,products.unit_price from pricebookproductrel inner join crmentity on crmentity.crmid=pricebookproductrel.productid inner join products on products.productid=pricebookproductrel.productid where crmentity.delete=0 and pricebookproductrel.productid=".$productid;
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
$list_header .= '<tr class="moduleListTitle" height=20>';
$list_header .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
$list_header .='<td WIDTH="1" class="moduleListTitle" style="padding:0px 3px 0px 3px;"><input type="checkbox" name="selectall" onClick=\'toggleSelect(this.checked,"selected_id");updateAllListPrice("'.$unit_price.'") \'></td>';
$list_header .= '<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="{IMAGE_PATH}blank.gif"></td>';
$list_header .= '<td class="moduleListTitle" height="21" style="padding:0px 3px 0px 3px;">'.$mod_strings['LBL_PRICEBOOK'].'</td>';
$list_header .='<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="{IMAGE_PATH}blank.gif"></td>';
$list_header .= '<td class="moduleListTitle" height="21" style="padding:0px 3px 0px 3px;">'.$mod_strings['LBL_PRODUCT_UNIT_PRICE'].'</td>';
$list_header .='<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="{IMAGE_PATH}blank.gif"></td>';
$list_header .= '<td class="moduleListTitle" height="21" style="padding:0px 3px 0px 3px;">'.$mod_strings['LBL_PB_LIST_PRICE'].'</td>';
$list_header .='<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="{IMAGE_PATH}blank.gif"></td>';
$list_header .= '</tr>';

$xtpl->assign("LISTHEADER", $list_header);

$list_body ='';
for($i=0; $i<$num_rows; $i++)
{	
	
	$vtlog->logthis("Products :: Showing Price Books to be added in the product","info");
	$entity_id = $adb->query_result($list_result,$i,"crmid");
	if(! array_key_exists($entity_id, $pbk_array))
	{
		if (($i%2)==0)
			$list_body .= '<tr height=20 class=evenListRow>';
		else
			$list_body .= '<tr height=20 class=oddListRow>';

		//$unit_price = 	$adb->query_result($list_result,$i,"unit_price");
		$field_name=$entity_id."_listprice";

		$list_body .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
		$list_body .= '<td valign=TOP style="padding:0px 3px 0px 3px;"><INPUT type=checkbox NAME="selected_id" value= '.$entity_id.' onClick=\'toggleSelectAll(this.name,"selectall");updateListPrice("'.$unit_price.'","'.$field_name.'")\'></td>';
		$list_body .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
		$list_body .= '<td height="21" style="padding:0px 3px 0px 3px;">'.$adb->query_result($list_result,$i,"bookname").'</td>';
		$list_body .='<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="'.$image_path.'blank.gif"></td>';
		$list_body .= '<td height="21" style="padding:0px 3px 0px 3px;">'.$unit_price.'</td>';
		$list_body .='<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="'.$image_path.'blank.gif"></td>';
		$list_body .= '<td height="21" style="padding:0px 3px 0px 3px;"><input type="text" name="'.$field_name.'"></td>';
		$list_body .='<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="'.$image_path.'blank.gif"></td>';
	}
	
}


//$listview_entries = getListViewEntries($focus,"Products",$list_result,$navigation_array);
//$xtpl->assign("LISTENTITY", $listview_entries);

if($order_by !='')
$url_string .="&order_by=".$order_by;
if($sorder !='')
$url_string .="&sorder=".$sorder;

$xtpl->assign("LISTENTITY", $list_body);
//$xtpl->assign("RECORD_COUNTS", $record_string);
$xtpl->assign("RETURN_ID", $productid);

$xtpl->parse("main");
$xtpl->out("main");



?>

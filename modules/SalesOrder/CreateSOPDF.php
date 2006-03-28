<?php
require('include/fpdf/pdf.php');
require_once('modules/SalesOrder/SalesOrder.php');
require_once('include/database/PearDatabase.php');

global $adb,$app_strings,$products_per_page;

$sql="select currency_symbol from currency_info";
$result = $adb->query($sql);
$currency_symbol = $adb->query_result($result,0,'currency_symbol');

// would you like and end page?  1 for yes 0 for no
$endpage="1";
$products_per_page="6";

$id = $_REQUEST['record'];
//retreiving the invoice info
$focus = new SalesOrder();
$focus->retrieve_entity_info($_REQUEST['record'],"SalesOrder");
$account_name = getAccountName($focus->column_fields[account_id]);


// **************** BEGIN POPULATE DATA ********************
// populate data
if($focus->column_fields["quote_id"] != '')
	$quote_name = getQuoteName($focus->column_fields["quote_id"]);
else
	$quote_name = '';
$po_name = $focus->column_fields["purchaseorder"];
$subject = $focus->column_fields["subject"];

$valid_till = $focus->column_fields["duedate"];
$valid_till = getDisplayDate($valid_till);
$bill_street = $focus->column_fields["bill_street"];
$bill_city = $focus->column_fields["bill_city"];
$bill_state = $focus->column_fields["bill_state"];
$bill_code = $focus->column_fields["bill_code"];
$bill_country = $focus->column_fields["bill_country"];

$ship_street = $focus->column_fields["ship_street"];
$ship_city = $focus->column_fields["ship_city"];
$ship_state = $focus->column_fields["ship_state"];
$ship_code = $focus->column_fields["ship_code"];
$ship_country = $focus->column_fields["ship_country"];

$conditions = $focus->column_fields["terms_conditions"];
$description = $focus->column_fields["description"];
$status = $focus->column_fields["sostatus"];

// Company information
$add_query = "select * from organizationdetails";
$result = $adb->query($add_query);
$num_rows = $adb->num_rows($result);

if($num_rows == 1)
{
		$org_name = $adb->query_result($result,0,"organizationame");
		$org_address = $adb->query_result($result,0,"address");
		$org_city = $adb->query_result($result,0,"city");
		$org_state = $adb->query_result($result,0,"state");
		$org_country = $adb->query_result($result,0,"country");
		$org_code = $adb->query_result($result,0,"code");
		$org_phone = $adb->query_result($result,0,"phone");
		$org_fax = $adb->query_result($result,0,"fax");
		$org_website = $adb->query_result($result,0,"website");

		$logo_name = $adb->query_result($result,0,"logoname");
}

//getting the Total Array
$price_subtotal = $currency_symbol.number_format(StripLastZero($focus->column_fields["hdnSubTotal"]),2,'.',',');
$price_tax = $currency_symbol.number_format(StripLastZero($focus->column_fields["txtTax"]),2,'.',',');
$price_adjustment = $currency_symbol.number_format(StripLastZero($focus->column_fields["txtAdjustment"]),2,'.',',');
$price_total = $currency_symbol.number_format(StripLastZero($focus->column_fields["hdnGrandTotal"]),2,'.',',');

//getting the Product Data
$query="select products.productname,products.unit_price,products.product_description,soproductrel.* from soproductrel inner join products on products.productid=soproductrel.productid where salesorderid=".$id;

global $result;
$result = $adb->query($query);
$num_products=$adb->num_rows($result);
for($i=0;$i<$num_products;$i++) {
		$product_name[$i]=$adb->query_result($result,$i,'productname');
		$prod_description[$i]=$adb->query_result($result,$i,'product_description');
		$product_id[$i]=$adb->query_result($result,$i,'productid');
		$qty[$i]=$adb->query_result($result,$i,'quantity');

		$unit_price[$i]= $currency_symbol.number_format($adb->query_result($result,$i,'unit_price'),2,'.',',');
		$list_price[$i]= $currency_symbol.number_format(StripLastZero($adb->query_result($result,$i,'listprice')),2,'.',',');
		$list_pricet[$i]= $adb->query_result($result,$i,'listprice');
		$prod_total[$i]= $qty[$i]*$list_pricet[$i];


		$product_line[] = array( "Product Name"    => $product_name[$i],
				"Description"  => $prod_description[$i],
				"Qty"     => $qty[$i],
				"List Price"      => $list_price[$i],
				"Unit Price" => $unit_price[$i],
				"Total" => $currency_symbol.number_format($prod_total[$i]),2,'.',',');
}

	$total[]=array("Unit Price" => $app_strings['LBL_SUB_TOTAL'],
		"Total" => $price_subtotal);

	$total[]=array("Unit Price" => $app_strings['LBL_ADJUSTMENT'],
		"Total" => $price_adjustment);

	$total[]=array("Unit Price" => $app_strings['LBL_TAX'],
		"Total" => $price_tax);

	$total[]=array("Unit Price" => $app_strings['LBL_GRAND_TOTAL'],
		"Total" => $price_total);


// ************************ END POPULATE DATA ***************************8

$page_num='1';
$pdf = new PDF( 'P', 'mm', 'A4' );
$pdf->Open();

$num_pages=ceil(($num_products/$products_per_page));


$current_product=0;
for($l=0;$l<$num_pages;$l++)
{
	$line=array();
	if($num_pages == $page_num)
		$lastpage=1;

	while($current_product != $page_num*$products_per_page)
	{
		$line[]=$product_line[$current_product];
		$current_product++;
	}

	$pdf->AddPage();
	include("pdf_templates/header.php");
	include("include/fpdf/templates/body.php");
	include("pdf_templates/footer.php");

	$page_num++;

	if (($endpage) && ($lastpage))
	{
		$pdf->AddPage();
		include("pdf_templates/header.php");
		include("pdf_templates/lastpage/body.php");
		include("pdf_templates/lastpage/footer.php");
	}
}


$pdf->Output('SalesOrder.pdf','D'); //added file name to make it work in IE, also forces the download giving the user the option to save

?>

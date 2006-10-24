<?php
/*
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program; if not, write to the Free Software
 *   Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 *   (c) 2005 Matthew Brichacek <mmbrich@fosslabs.com>
 *
 *   Additions/Changes
 *
 *   (c) 2005 OpenCRM
 *    - Total and Tax labels taken from language files
 */

require_once('modules/Orders/SalesOrder.php');

//retreiving the invoice info
$focus = new SalesOrder();
$focus->retrieve_entity_info($_REQUEST['record'],"SalesOrder");
$account_name = getAccountName($focus->column_fields[account_id]);

// populate data
if($focus->column_fields['quote_id']) {
	$quote_name = getQuoteName($focus->column_fields["quote_id"]);
}
$po_name = $focus->column_fields["purchaseorder"];
$subject = $focus->column_fields["subject"];

$valid_till = $focus->column_fields["duedate"];
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
$price_subtotal = $currency_symbol.number_format($focus->column_fields["hdnSubTotal"],2,'.',',');
$price_tax = $currency_symbol.number_format($focus->column_fields["txtTax"],2,'.',',');
$price_adjustment = $currency_symbol.number_format($focus->column_fields["txtAdjustment"],2,'.',',');
$price_total = $currency_symbol.number_format($focus->column_fields["hdnGrandTotal"],2,'.',',');

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
                $list_price[$i]= $currency_symbol.number_format($adb->query_result($result,$i,'listprice'),2,'.',',');
                $list_pricet[$i]= $adb->query_result($result,$i,'listprice');
                $prod_totalt= $qty[$i]*$list_pricet[$i];
		if(!preg_match("/\./",$prod_totalt))
                        $prod_totalt .=".00";

		$prod_total[$i] = $currency_symbol.number_format($prod_totalt,2,'.',',');



                $product_line[] = array( "Product Name"    => $product_name[$i],
				"Description"  => $prod_description[$i],
                                "Qty"     => $qty[$i],
                                "List Price"      => $list_price[$i],
                                "Unit Price" => $unit_price[$i],
                                "Total" => $prod_total[$i]);
}

        $total[]=array("Unit Price" => $app_strings['LBL_SUB_TOTAL'],
                "Total" => $price_subtotal);

        $total[]=array("Unit Price" => $app_strings['LBL_ADJUSTMENT'],
                "Total" => $price_adjustment);

        $total[]=array("Unit Price" => $app_strings['LBL_TAX'],
                "Total" => $price_tax);

        $total[]=array("Unit Price" => $app_strings['LBL_GRAND_TOTAL'],
                "Total" => $price_total);

?>

<?php
// watermark based on status
// this is the postion of the watermark before the rotate
$waterMarkPositions=array("50","180");
// this is the rotate amount (todo)
$waterMarkRotate=array("45","50","180");
$pdf->watermark( $status, $waterMarkPositions, $waterMarkRotate );

$bottom="130";
$top="80";
// blow a bubble around the table
$Bubble=array("10",$top,"170","$bottom");
$pdf->tableWrapper($Bubble);

/* ************ Begin Table Setup ********************** */
// Each of these arrays needs to have matching keys
// "key" => "Length"
// total of columns needs to be 190 in order to fit the table
// correctly
$prodTable=array("10","60");

if($focus->column_fields["hdnTaxType"] == "individual") {
	$colsAlign["Product Name"] = "L";
	$colsAlign["Description"] = "L";
	$colsAlign["Qty"] = "R";
	$colsAlign["Price"] = "R";
	$colsAlign["Discount"] = "R";
	$colsAlign["Tax"] = "R";
	$colsAlign["Total"] = "R";

	$cols["Product Name"] = "25";
	$cols["Description"] = "70";
	$cols["Qty"] = "10";
	$cols["Price"] = "25";
	$cols["Discount"] = "15";
	$cols["Tax"] = "20";
	$cols["Total"] = "25";
} else {
	$colsAlign["Product Name"] = "L";
	$colsAlign["Description"] = "L";
	$colsAlign["Qty"] = "R";
	$colsAlign["Price"] = "R";
	$colsAlign["Discount"] = "R";
	$colsAlign["Total"] = "R";

	$cols["Product Name"] = "25";
	$cols["Description"] = "70";
	$cols["Qty"] = "15";
	$cols["Price"] = "30";
	$cols["Discount"] = "20";
	$cols["Total"] = "30";
}


$pdf->addCols( $cols,$prodTable,$bottom, $focus->column_fields["hdnTaxType"]);
$pdf->addLineFormat( $colsAlign);

/* ************** End Table Setup *********************** */



/* ************* Begin Product Population *************** */
$ppad=3;
$y    = $top+10;
for($i=0;$i<count($product_name);$i++) {
        $size = $pdf->addProductLine( $y, $line[$i] );
        $y   += $size+$ppad;
}

/* ******************* End product population ********* */


/* ************* Begin Totals ************************** */
$t=$bottom+56;
$pad=6;
for($i=0;$i<count($total);$i++) {
        $size = $pdf->addProductLine( $t, $total[$i], $total[$i] );
        $t   += $pad;
}


if($focus->column_fields["hdnTaxType"] != "individual") {
	$lineData=array("105",$bottom+37,"94");
	$pdf->drawLine($lineData);
	$data= $app_strings['LBL_NET_TOTAL'].":                                                                   ".$price_subtotal."";
	$pdf->SetXY( 105 , 168 );
	$pdf->SetFont( "Helvetica", "", 10);
	$pdf->MultiCell(110, 4, $data);

	$lineData=array("105",$bottom+43,"94");
	$pdf->drawLine($lineData);
	$data= $app_strings['LBL_DISCOUNT'].":                                                                      ".$price_discount."";
	$pdf->SetXY( 105 , 174 );
	$pdf->SetFont( "Helvetica", "", 10);
	$pdf->MultiCell(110, 4, $data);

	$lineData=array("105",$bottom+49,"94");
	$pdf->drawLine($lineData);
	$data= $app_strings['LBL_TAX'].":  ($group_total_tax_percent %)                                                               ".$price_salestax."";
	$pdf->SetXY( 105 , 180 );
	$pdf->SetFont( "Helvetica", "", 10);
	$pdf->MultiCell(110, 4, $data);

	$lineData=array("105",$bottom+55,"94");
	$pdf->drawLine($lineData);
	$data = $app_strings['LBL_SHIPPING_AND_HANDLING_CHARGES'].":                                     ".$price_shipping;
	$pdf->SetXY( 105 , 186 );
	$pdf->SetFont( "Helvetica", "", 10);
	$pdf->MultiCell(110, 4, $data);

} else {
	$lineData=array("105",$bottom+43,"94");
	$pdf->drawLine($lineData);
	$data= $app_strings['LBL_NET_TOTAL'].":                                                                   ".$price_subtotal."";
	$pdf->SetXY( 105 , 174 );
	$pdf->SetFont( "Helvetica", "", 10);
	$pdf->MultiCell(110, 4, $data);

	$lineData=array("105",$bottom+49,"94");
	$pdf->drawLine($lineData);
	$data= $app_strings['LBL_DISCOUNT'].":                                                                      ".$price_discount."";
	$pdf->SetXY( 105 , 180 );
	$pdf->SetFont( "Helvetica", "", 10);
	$pdf->MultiCell(110, 4, $data);

	$lineData=array("105",$bottom+55,"94");
	$pdf->drawLine($lineData);
	$data = $app_strings['LBL_SHIPPING_AND_HANDLING_CHARGES'].":                                   ".$price_shipping;
	$pdf->SetXY( 105 , 186 );
	$pdf->SetFont( "Helvetica", "", 10);
	$pdf->MultiCell(110, 4, $data);
}

$lineData=array("105",$bottom+61,"94");
$pdf->drawLine($lineData);
$data = $app_strings['LBL_TAX_FOR_SHIPPING_AND_HANDLING'].":  ($sh_tax_percent %)                      ".$price_shipping_tax;
$pdf->SetXY( 105 , 192 );
$pdf->SetFont( "Helvetica", "", 10);
$pdf->MultiCell(110, 4, $data);

$lineData=array("105",$bottom+67,"94");
$pdf->drawLine($lineData);
$data = $app_strings['LBL_ADJUSTMENT'].":                                                                    ".$price_adjustment;
$pdf->SetXY( 105 , 198 );
$pdf->SetFont( "Helvetica", "", 10);
$pdf->MultiCell(110, 4, $data);

$lineData=array("105",$bottom+73,"94");
$pdf->drawLine($lineData);
$data = $app_strings['LBL_GRAND_TOTAL'].":(in $currency_symbol)                                                  ".$price_total;
$pdf->SetXY( 105 , 204 );
$pdf->SetFont( "Helvetica", "", 10);
$pdf->MultiCell(110, 4, $data);

/* ************** End Totals *********************** */


?>

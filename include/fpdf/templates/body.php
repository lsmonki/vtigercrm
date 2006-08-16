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
	$colsAlign["Tax"] = "R";
	$colsAlign["Total"] = "R";

	$cols["Product Name"] = "25";
	$cols["Description"] = "80";
	$cols["Qty"] = "15";
	$cols["Price"] = "25";
	$cols["Tax"] = "20";
	$cols["Total"] = "25";
} else {
	$colsAlign["Product Name"] = "L";
	$colsAlign["Description"] = "L";
	$colsAlign["Qty"] = "R";
	$colsAlign["Price"] = "R";
	$colsAlign["Total"] = "R";

	$cols["Product Name"] = "25";
	$cols["Description"] = "80";
	$cols["Qty"] = "20";
	$cols["Price"] = "30";
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
	$lineData=array("115",$bottom+37,"84");
	$pdf->drawLine($lineData);
	$data= $app_strings['LBL_NET_TOTAL'].":                                                ".$price_subtotal."";
	$pdf->SetXY( 119 , 168 );
	$pdf->SetFont( "Helvetica", "", 10);
	$pdf->MultiCell(119, 4, $data);

	$lineData=array("115",$bottom+43,"84");
	$pdf->drawLine($lineData);
	$data= $app_strings['LBL_DISCOUNT'].":                                                ".$price_discount."";
	$pdf->SetXY( 119 , 174 );
	$pdf->SetFont( "Helvetica", "", 10);
	$pdf->MultiCell(119, 4, $data);

	$lineData=array("115",$bottom+49,"84");
	$pdf->drawLine($lineData);
	$data= $app_strings['LBL_TAX'].":                                                         ".$price_salestax."";
	$pdf->SetXY( 119 , 180 );
	$pdf->SetFont( "Helvetica", "", 10);
	$pdf->MultiCell(119, 4, $data);

	$lineData=array("115",$bottom+55,"84");
	$pdf->drawLine($lineData);
	$data = $app_strings['LBL_SHIPPING_AND_HANDLING_CHARGES'].":               ".$price_shipping;
	$pdf->SetXY( 119 , 186 );
	$pdf->SetFont( "Helvetica", "", 10);
	$pdf->MultiCell(119, 4, $data);

} else {
	$lineData=array("115",$bottom+43,"84");
	$pdf->drawLine($lineData);
	$data= $app_strings['LBL_NET_TOTAL'].":                                                ".$price_subtotal."";
	$pdf->SetXY( 119 , 174 );
	$pdf->SetFont( "Helvetica", "", 10);
	$pdf->MultiCell(119, 4, $data);

	$lineData=array("115",$bottom+49,"84");
	$pdf->drawLine($lineData);
	$data= $app_strings['LBL_DISCOUNT'].":                                                ".$price_discount."";
	$pdf->SetXY( 119 , 180 );
	$pdf->SetFont( "Helvetica", "", 10);
	$pdf->MultiCell(119, 4, $data);

	$lineData=array("115",$bottom+55,"84");
	$pdf->drawLine($lineData);
	$data = $app_strings['LBL_SHIPPING_AND_HANDLING_CHARGES'].":               ".$price_shipping;
	$pdf->SetXY( 119 , 186 );
	$pdf->SetFont( "Helvetica", "", 10);
	$pdf->MultiCell(119, 4, $data);
}

$lineData=array("115",$bottom+61,"84");
$pdf->drawLine($lineData);
$data = $app_strings['LBL_TAX_FOR_SHIPPING_AND_HANDLING'].":         ".$price_shipping_tax;
$pdf->SetXY( 119 , 192 );
$pdf->SetFont( "Helvetica", "", 10);
$pdf->MultiCell(119, 4, $data);

$lineData=array("115",$bottom+67,"84");
$pdf->drawLine($lineData);
$data = $app_strings['LBL_ADJUSTMENT'].":                                            ".$price_adjustment;
$pdf->SetXY( 119 , 198 );
$pdf->SetFont( "Helvetica", "", 10);
$pdf->MultiCell(119, 4, $data);

$lineData=array("115",$bottom+73,"84");
$pdf->drawLine($lineData);
$data = $app_strings['LBL_GRAND_TOTAL'].":                                           ".$price_total;
$pdf->SetXY( 119 , 204 );
$pdf->SetFont( "Helvetica", "", 10);
$pdf->MultiCell(119, 4, $data);

/* ************** End Totals *********************** */


?>

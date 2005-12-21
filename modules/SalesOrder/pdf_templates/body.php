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
// alignment of each column
$colsAlign=array( "Product Name"    => "L",
             "Description"  => "L",
             "Qty"     => "C",
             "List Price"      => "R",
             "Unit Price" => "R",
             "Total"          => "R" );
$prodTable=array("10","60");
$cols=array( "Product Name" => 25,
             "Description" => 80,
             "Qty" => 10,
             "List Price" => 25,
             "Unit Price" => 25,
             "Total" => 25
            );
$pdf->addCols( $cols,$prodTable,$bottom );
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
        $size = $pdf->addProductLine( $t, $total[$i] );
        $t   += $pad;
}


// These are the lines in-between the totals, remove if you want
// $x,$y,$length
$lineData=array("150",$bottom+73,"49");
$pdf->drawLine($lineData);
$lineData=array("150",$lineData[1]-$pad,"49");
$pdf->drawLine($lineData);
$lineData=array("150",$lineData[1]-$pad,"49");
$pdf->drawLine($lineData);
$lineData=array("150",$lineData[1]-$pad,"49");
$pdf->drawLine($lineData);

/* ************* End Totals *************************** */

?>

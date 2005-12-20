<?php

// ************** Begin company information *****************
$imageBlock=array("10","3","0","0");
$pdf->addImage( $logo_name, $imageBlock);

// x,y,width
$companyBlockPositions=array( "10","23","60" );
$companyText=$org_address."\n".$org_city.", ".$org_state." ".$org_code." ".$org_country;
$pdf->addTextBlock( $org_name, $companyText ,$companyBlockPositions );

// ************** End company information *******************



// ************* Begin Top-Right Header ***************
// Quote title
$titleBlock=array("163","7");
$pdf->title( "Quote","", $titleBlock );

//  Account Number
$acctBubble=array("147","17","12");
$pdf->addBubbleBlock($account_id, "Account Number", $acctBubble);

// page number
$pageBubble=array("180","17",0);
$pdf->addBubbleBlock($page_num, "Page", $pageBubble);
// ************** End Top-Right Header *****************



// ************** Begin Addresses **************
// shipping Address
$shipLocation = array("10","43","60");
$shipText=$ship_street."\n".$ship_city.", ".$ship_state." ".$ship_code."\n".$ship_country;
$pdf->addTextBlock( "Shipping Address:", $shipText, $shipLocation );

// billing Address
$billPositions = array("147","43","60");
$billText=$bill_street."\n".$bill_city.", ".$bill_state." ".$bill_code."\n".$bill_country;
$pdf->addTextBlock("Billing Address:",$billText, $billPositions);
// ********** End Addresses ******************



/*  ******** Begin Quote Data ************************ */
// terms block
$termBlock=array("10","65");
$pdf->addRecBlock($account_name, "Customer Name", $termBlock);

// due date block
$dueBlock=array("80","65");
$pdf->addRecBlock($valid_till, "Valid Till",$dueBlock);

// invoice number block
$invBlock=array("147","65");
$pdf->addRecBlock($quote_id, "Quote Number",$invBlock);

/* ************ End Quote Data ************************ */



?>

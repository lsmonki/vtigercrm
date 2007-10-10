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


// ************** Begin company information *****************
$imageBlock=array("10","3","0","0");
$pdf->addImage( $logo_name, $imageBlock);

// x,y,width
if($org_phone != '')
  $phone ="\nPhone:".$org_phone;
$companyBlockPositions=array( "10","23","62" );
$companyText=$org_address."\n".$org_city.", ".$org_state." ".$org_code." ".$org_country." ".$phone."\n".$org_website;
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
$shipLocation = array("147","40","61");
if(trim($ship_street)!='')
	$shipText = $ship_street."\n";
if(trim($ship_city) !='')
	$shipText .= $ship_city.", ";
if(trim($ship_state)!='' || trim($ship_code)!= '')
	$shipText .= $ship_state." ".$ship_code."\n";

	$shipText .=$ship_country;
$pdf->addTextBlock( "Shipping Address:", $shipText, $shipLocation );

// billing Address
$billPositions = array("10","51","61");
if(trim($bill_street)!='')
	$billText = $bill_street."\n";
if(trim($bill_city) !='')
	$billText .= $bill_city.", ";
if(trim($bill_state)!='' || trim($bill_code)!= '')
	$billText .= $bill_state." ".$bill_code."\n";

	$billText .=$bill_country;
$pdf->addTextBlock("Billing Address:",$billText, $billPositions);
// ********** End Addresses ******************



/*  ******** Begin Quote Data ************************ */
// issue date block
$issueBlock=array("80","35");
$pdf->addRecBlock(getDisplayDate(date("Y-m-d")), "Issue Date",$issueBlock);

// valid till block
$dueBlock=array("80","50");
$pdf->addRecBlock($valid_till, "Valid Till",$dueBlock);

// customer name block
$termBlock=array("10","67");
$pdf->addRecBlock($account_name, "Customer Name", $termBlock);


// Contact Name block
$conBlock=array("79","65");
$pdf->addRecBlock($contact_name, "Contact Name",$conBlock);


// quote number block
$invBlock=array("147","65");
$pdf->addRecBlock($quote_id, "Quote Number",$invBlock);

/* ************ End Quote Data ************************ */



?>

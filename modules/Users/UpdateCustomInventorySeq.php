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
require_once('include/utils/InventoryUtils.php');
$req_mode = $_REQUEST['mode'];
$req_no = $_REQUEST['no'];    
$req_str = $_REQUEST['str'];
if($_REQUEST['semodule']=='po') $semodule = 'PurchaseOrder';
else if($_REQUEST['semodule']=='so') $semodule = 'SalesOrder';
else if($_REQUEST['semodule']=='quote') $semodule = 'Quotes';
else if($_REQUEST['semodule']=='invoice') $semodule = 'Invoice'; 

if($req_mode == "configure_invno" && $semodule == "Invoice")
{

	$tmp_str=$req_str.$req_no;
	if(CheckDuplicateInvoiceNumber($tmp_str))	
	{
		echo "Duplicate Invoice Number. \nInvoice Number already exist ";
		die();
	}
}
if($req_mode == "configure_invno" && $semodule == "Quote")
{

	$tmp_str=$req_str.$req_no;
	if(CheckDuplicateQuoteNumber($tmp_str))	
	{
		echo "Duplicate Quote Number. \nQuote Number already exist ";
		die();
	}
}
if($req_mode == "configure_invno" && $semodule == "SalesOrder")
{

	$tmp_str=$req_str.$req_no;
	if(CheckDuplicateSONumber($tmp_str))	
	{
		echo "Duplicate SO Number. \nSO Number already exist ";
		die();
	}
}
if($req_mode == "configure_invno" && $semodule == "PurchaseOrder")
{

	$tmp_str=$req_str.$req_no;
	if(CheckDuplicatePONumber($tmp_str))	
	{
		echo "Duplicate PO Number. \nPO Number already exist ";
		die();
	}
}
if (isset($req_mode))
setInventorySeqNumber($req_mode,$semodule, $req_str,$req_no);

?>

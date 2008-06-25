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
//echo "aaa";
if($req_mode == "configure_invoiceno")
{

	$tmp_str=$req_str.$req_no;
	if(CheckDuplicateInvoiceNumber($tmp_str))	
	{
		echo "Duplicate Invoice Number. \nInvoice Number already exist ";
		die();
	}
}
if (isset($req_mode))
setInventoryInvoiceNumber($req_mode,$req_str,$req_no);

?>

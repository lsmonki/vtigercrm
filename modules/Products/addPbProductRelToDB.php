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

global $adb;	
global $vtlog;
require_once('include/database/PearDatabase.php');
$idlist = $_POST['idlist'];
$returnmodule=$_REQUEST['return_module'];
$pricebook_id=$_REQUEST['pricebook_id'];
$productid=$_REQUEST['product_id'];
if(isset($_REQUEST['pricebook_id']) && $_REQUEST['pricebook_id']!='')
{
	//split the string and store in an array
	$storearray = explode(";",$idlist);
	foreach($storearray as $id)
	{
		$lp_name = $id.'_listprice';
		$list_price = $_REQUEST[$lp_name];
		//Updating the pricebook product rel table
		$vtlog->logthis("Products :: Inserting products to price book","info");
		$query= "insert into pricebookproductrel (pricebookid,productid,listprice) values(".$pricebook_id.",".$id.",".$list_price.")";
		$adb->query($query);
	}
	header("Location: index.php?module=Products&action=PriceBookDetailView&record=".$pricebook_id);
}
elseif(isset($_REQUEST['product_id']) && $_REQUEST['product_id']!='')
{
	//split the string and store in an array
	$storearray = explode(";",$idlist);
	foreach($storearray as $id)
	{
		$lp_name = $id.'_listprice';
		$list_price = $_REQUEST[$lp_name];
		//Updating the pricebook product rel table
		$vtlog->logthis("Products :: Inserting PriceBooks to Product","info");
		$query= "insert into pricebookproductrel (pricebookid,productid,listprice) values(".$id.",".$productid.",".$list_price.")";
		$adb->query($query);
	}
	header("Location: index.php?module=Products&action=DetailView&record=".$productid);
}

?>


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
require_once('database/DatabaseConnection.php');
require_once('include/utils.php');
$product_name;
$product_code;
$product_active;
$product_commissionrate;
$product_qtyperunit;
$product_unitprice;
$product_image;
$product_description;

if(isset($_REQUEST["productname"]))
{
$product_name = $_REQUEST["productname"];
}

if(isset($_REQUEST["productcode"]))
{
$product_code = $_REQUEST["productcode"];
}

if(isset($_REQUEST["active"]) && $_REQUEST["active"] == 'on')
{
	$product_active = 1;
}
else
{
	$product_active = 0;
}

if(isset($_REQUEST["commissionrate"]))
{
$product_commissionrate = $_REQUEST["commissionrate"];
}

if(isset($_REQUEST["qtyperunit"]))
{
$product_qtyperunit = $_REQUEST["qtyperunit"];
}

if(isset($_REQUEST["unitprice"]))
{
$product_unitprice = $_REQUEST["unitprice"];
}
if(isset($_REQUEST["description"]))
{
$product_description = $_REQUEST["description"];
}

$mode = $_REQUEST['mode'];
$return_action = $_REQUEST['return_action'];
$return_module = $_REQUEST['return_module'];
$return_id = $_REQUEST['return_id'];

if(isset($mode) && $mode != '' && $mode == 'Edit')
{
	$productid = $_REQUEST['id'];
	$sql = "update products set productname='".$product_name."',category='".$product_code."',product_description='".$product_description."',qty_per_unit='".$product_qtyperunit."',unit_price='".$product_unitprice."',commissionrate='".$product_commissionrate."',discontinued='".$product_active."' where id=".$productid;
	mysql_query($sql);
}
else
{
	$sql = "insert into products(productname,category,product_description,qty_per_unit,unit_price,commissionrate,discontinued) values('". $product_name ."','" .$product_code ."','" .$product_description ."','" .$product_qtyperunit ."','" .$product_unitprice ."','" .$product_commissionrate ."','" .$product_active ."')";
	mysql_query($sql);
	//Retreiving the max id
	$idquery = "select max(id) as id from products";
	$idresult = mysql_query($idquery);
	$return_id = mysql_result($idresult,0,"id");

}

$loc = "Location: index.php?action=".$return_action."&module=".$return_module."&record=".$return_id;
header($loc);

?>

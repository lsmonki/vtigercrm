<?php
global $adb;
$record = $_REQUEST['record'];
$pricebook_id = $_REQUEST['pricebook_id'];
$product_id = $_REQUEST['product_id'];
$listprice = $_REQUEST['list_price'];
$return_action = $_REQUEST['return_action'];
$query = "update pricebookproductrel set listprice=".$listprice." where pricebookid=".$pricebook_id." and productid=".$product_id;
//echo $query;
$adb->query($query); 

header("Location: index.php?action=$return_action&module=Products&record=$record");
?>

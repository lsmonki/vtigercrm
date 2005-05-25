<?php
global $adb;
$pricebook_id = $_REQUEST['record'];
$product_id = $_REQUEST['product_id'];
$listprice = $_REQUEST['list_price'];
$query = "update pricebookproductrel set listprice=".$listprice." where pricebookid=".$pricebook_id." and productid=".$product_id;
//echo $query;
$adb->query($query); 

header("Location: index.php?action=PriceBookDetailView&module=Products&record=$pricebook_id");
?>

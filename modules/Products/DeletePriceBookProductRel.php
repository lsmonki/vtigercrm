<?php
global $adb;
$product_id = $_REQUEST['record'];
$pricebook_id = $_REQUEST['pricebook_id'];
$query = "delete from pricebookproductrel where pricebookid=".$pricebook_id." and productid=".$product_id;
$adb->query($query); 

header("Location: index.php?action=PriceBookDetailView&module=Products&record=$pricebook_id");
?>

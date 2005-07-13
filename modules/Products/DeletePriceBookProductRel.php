<?php
global $adb;
global $vtlog;
$return_id = $_REQUEST['return_id'];
$record = $_REQUEST['record'];
$return_action = $_REQUEST['return_action'];
if($return_action !='' && $return_action=="DetailView")
{
	$vtlog->logthis("Products :: Deleting Price Book","info");
	$query = "delete from pricebookproductrel where pricebookid=".$record." and productid=".$return_id;
	$adb->query($query); 
}
else
{
	$vtlog->logthis("Products :: Deleting Price Book","info");
	$query = "delete from pricebookproductrel where pricebookid=".$return_id." and productid=".$record;
	$adb->query($query); 
}

header("Location: index.php?action=".$return_action."&module=Products&record=".$return_id);
?>

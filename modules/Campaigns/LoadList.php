<?php
require_once('modules/CustomView/CustomView.php');

$cvObj = new CustomView($_REQUEST["return_type"]);

$listquery = getListQuery($_REQUEST["list_type"]);
$rs = $adb->query($cvObj->getModifiedCvListQuery($_REQUEST["cvid"],$listquery,$_REQUEST["list_type"]));

if($_REQUEST["list_type"] == "Leads")
		$reltable = "vtiger_campaignleadrel";
elseif($_REQUEST["list_type"] == "Contacts")
		$reltable = "vtiger_campaigncontrel";

while($row=$adb->fetch_array($rs)) {
	$adb->query("INSERT INTO ".$reltable." VALUES('".$_REQUEST["return_id"]."','".$row["crmid"]."')");
}

?>
<script>
addOnloadEvent(function() {
	window.location.href = "index.php?action=CallRelatedList&module=Campaigns&record=<? echo $_REQUEST['return_id'];?>";
});
</script>

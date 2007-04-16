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

require_once('include/database/PearDatabase.php');
global $adb;
global $log;
$dest_mod = $_REQUEST['destination_module'];

$log->debug("Entering Products/updateRelations.php");

if($singlepane_view == 'true')
	$return_action = "DetailView";
else
	$return_action = "CallRelatedList";

if($_REQUEST['return_module'] != '') $return_module = $_REQUEST['return_module'];

//if select Lead, Account, Contact, Potential from Product RelatedList 
//we have to insert in vtiger_seproductsrel
if($dest_mod =='Leads' || $dest_mod =='Accounts' ||$dest_mod =='Contacts' ||$dest_mod =='Potentials')
{
	//For Bulk updates
	if($_REQUEST['idlist'] != '')
	{
		$entityids = explode(';',trim($_REQUEST['idlist'],';'));
		$productid = $_REQUEST['parentid'];
	}
	else
	{
		$entityids[] = $_REQUEST['entityid'];
		$productid = $_REQUEST['parid'];
	}
	
	foreach($entityids as $ind => $crmid)
	{
		if($crmid != '' && $productid != '')
		{
			$sql = "insert into vtiger_seproductsrel values ($crmid,$productid,'".$dest_mod."')";
			$adb->query($sql);
		}
	}
	
	$return_module = 'Products';
	$return_record = $productid;
}

//if the destination module is also a product, we have to update dependency
//lists
else if($dest_mod=='Products') {
    if( !isset( $_REQUEST['dependency_type']) || $_REQUEST['dependency_type'] == '') {

	//select product from vendor related list
	if($_REQUEST['parid'] != '' && $_REQUEST['entityid'] != '')
	{
		$sql = "update vtiger_products set vendor_id=".$_REQUEST['parid']." where productid=".$_REQUEST['entityid'];
		$adb->query($sql);
	}
	
	$return_module = 'Products';
	$return_record = $_REQUEST['parid'];

    } else {
	//module dependency list change
	$sql = "SELECT count(*) FROM vtiger_products2products_rel
		    WHERE productid=".$_REQUEST['parentid']." AND relation_type=".$_REQUEST['dependency_type'];
	$count_result = $adb->query( $sql);
	$sequence = $adb->query_result($count_result,0,"count");

	//get the list of dependencies from the request
	$idlist = $_REQUEST['idlist'];
	$storearray = explode (";",$idlist);
	$qtylist = $_REQUEST['qtylist'];
	$tmparray = explode (";",$qtylist);
	$qtyarray = array();
	foreach( $tmparray as $avp) {
	    $avplist = explode( ":",$avp);
	    $qtyarray[$avplist[0]] = $avplist[1];
	}

	//get the values from the requies and store them into the database
	foreach($storearray as $id) {
	    if($id != '') {

		//We have quantities for piecelists and purchase lists
		$qty = $grp = 1;
		if( isset( $qtyarray[$id])) {
		    if( $_REQUEST['dependency_type'] == 10 || $_REQUEST['dependency_type'] == 40) {
			$qty = $qtyarray[$id];
		    } elseif( $_REQUEST['dependency_type'] == 20) {
			$grp = $qtyarray[$id];
		    }
		}

		//database query
		$sql = "INSERT INTO vtiger_products2products_rel(productid,related_productid,relation_type,sequence_no,quantity,comment,product_relgroup) VALUES(".$_REQUEST["parentid"].",".$id.",".$_REQUEST['dependency_type'].",".++$sequence.",".$qty.",'',".$grp.")";
		$adb->query($sql);
	    }
	}

	//Navigation
	$return_action = "CallDependencyList";
	$return_module = "Products";
	$return_record = $_REQUEST['parentid'];
    }
}


if( $return_action == "") {
    $return_action = 'DetailView';
    if($_REQUEST['return_action'] != '')
	    $return_action = $_REQUEST['return_action'];
}

header("Location:index.php?action=$return_action&module=$return_module&record=$return_record");

?>

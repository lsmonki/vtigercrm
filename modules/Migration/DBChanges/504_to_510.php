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

//5.0.4 to 5.1.0 database changes

//we have to use the current object (stored in PatchApply.php) to execute the queries
$adb = $_SESSION['adodb_current_object'];
$conn = $_SESSION['adodb_current_object'];

$migrationlog->debug("\n\nDB Changes from 5.0.4 to 5.1.0 -------- Starts \n\n");

/* Add Total column in default customview of Purchase Order */
$res = $adb->query("select cvid from vtiger_customview where viewname='All' and entitytype='PurchaseOrder'");
$po_cvid = $adb->query_result($res, 0, 'cvid');
$adb->query("update vtiger_cvcolumnlist set columnindex = 5 where columnindex = 4 and cvid = $po_cvid");
$adb->query("insert into vtiger_cvcolumnlist values ($po_cvid, 4, 'vtiger_purchaseorder:total:hdnGrandTotal:PurchaseOrder_Total:V')");
                        


/* To Provide an option to Create Product from Quick Create */
ExecuteQuery("UPDATE vtiger_field SET quickcreate = 0 WHERE tablename='vtiger_products' and columnname='productname'");
ExecuteQuery("UPDATE vtiger_field SET quickcreate = 0 WHERE tablename='vtiger_products' and columnname='discontinued'");
ExecuteQuery("UPDATE vtiger_field SET quickcreate = 0 WHERE tablename='vtiger_products' and columnname='unit_price'");
ExecuteQuery("UPDATE vtiger_field SET quickcreate = 0 WHERE tablename='vtiger_products' and columnname='qtyinstock'");

/* Necessary DB Changes for Recycle bin feature */
ExecuteQuery("create table vtiger_relatedlists_rb(entityid int(19), action varchar(50), rel_table varchar(200), rel_column varchar(200), ref_column varchar(200), related_crm_ids text)");

ExecuteQuery("insert into vtiger_tab values('30', 'Recyclebin', '0', '27', 'Recyclebin', null, null, 0, '1')");

ExecuteQuery("insert into vtiger_parenttabrel values('7', '30', '4')");

// Enable Search icon for all profiles by default for Recyclebin module
$profileresult = $adb->query("select * from vtiger_profile");
$countprofiles = $adb->num_rows($profileresult);
for($i=0;$i<$countprofiles;$i++)
{
	$profileid = $adb->query_result($profileresult,$i,'profileid');
	ExecuteQuery("insert into vtiger_profile2utility values($profileid,30,3,0)");
	ExecuteQuery("insert into vtiger_profile2tab values ($profileid,30,0)");
}

/* For Role based customview support */
ExecuteQuery("alter table vtiger_customview add column status int(1) default '3'");
ExecuteQuery("update vtiger_customview set status=0 where viewname='All'");
ExecuteQuery("alter table vtiger_customview add column userid int(19) default '1'");


/* To provide Inventory number customziation (For Invoice/Quote/SO/PO) */
ExecuteQuery("create table IF NOT EXISTS vtiger_inventory_num(num_id int(19) NOT NULL, semodule varchar(50) NOT NULL, prefix varchar(50) NOT NULL, start_id varchar(50) NOT NULL, cur_id varchar(50) NOT NULL, active int(2) NOT NULL, PRIMARY KEY(num_id))");

ExecuteQuery("alter table vtiger_purchaseorder add column purchaseorder_no varchar(100)");
ExecuteQuery("alter table vtiger_salesorder add column salesorder_no varchar(100)");
ExecuteQuery("alter table vtiger_quotes add column quote_no varchar(100)");
$cvchange = ChangeCVColumnlist(array(array('module'=>'Quotes'),array('module'=>'SalesOrder'),array('module'=>'PurchaseOrder')));

require_once('user_privileges/CustomInvoiceNo.php');
$inventory_num_entry = AddColumns(
	array(
		array(
			'semodule'=>'Invoice','active'=>'1','prefix'=>$inv_str,'startid'=>$inv_no,'curid'=>$inv_no
		),	
		array(
			'semodule'=>'Quotes','active'=>'1','prefix'=>'QUOTE','startid'=>'1','curid'=>'1'
		),	
		array(
			'semodule'=>'SalesOrder','active'=>'1','prefix'=>'SO','startid'=>'1','curid'=>'1'
		),	
		array(
			'semodule'=>'PurchaseOrder','active'=>'1','prefix'=>'PO','startid'=>'1','curid'=>'1'
		)	
	)
);

$field_entry = AddModuleFields(
	array(
		array(
			'module' => 'SalesOrder', 'columnname' => 'salesorder_no', 'tablename' => 'vtiger_salesorder', 
			'generatedtype' => '1','uitype'=>3,      'fieldname'=>'salesorder_no',  'fieldlabel'=>'SalesOrder No', 'readonly'=> '1',
			'presence'=>'0','selected' => '0', 'maximumlength' => '100', 'sequence'=>3, 'typeofdata'=>'V~M', 'quickcreate'=>'1',
			'block'=>null, 'blocklabel'=>'LBL_SO_INFORMATION','displaytype'=>'1', 'quickcreatesequence'=>null, 'info_type'=>'BAS'),
		array(
			'module' => 'PurchaseOrder', 'columnname' => 'purchaseorder_no', 'tablename' => 'vtiger_purchaseorder',
			'generatedtype' => '1','uitype'=>3,      'fieldname'=>'purchaseorder_no',  'fieldlabel'=>'PurchaseOrder No', 'readonly'=> '1',
			'presence'=>'0','selected' => '0', 'maximumlength' => '100', 'sequence'=>3, 'typeofdata'=>'V~M', 'quickcreate'=>'1',
			'block'=>null, 'blocklabel'=>'LBL_PO_INFORMATION','displaytype'=>'1', 'quickcreatesequence'=>null, 'info_type'=>'BAS'),
		array(
			'module' => 'Quotes', 'columnname' => 'quote_no', 'tablename' => 'vtiger_quotes',
			'generatedtype' => '1','uitype'=>3,      'fieldname'=>'quote_no',  'fieldlabel'=>'Quote No', 'readonly'=> '1',
			'presence'=>'0','selected' => '0', 'maximumlength' => '100', 'sequence'=>3, 'typeofdata'=>'V~M', 'quickcreate'=>'1',
			'block'=>null, 'blocklabel'=>'LBL_QUOTE_INFORMATION','displaytype'=>'1', 'quickcreatesequence'=>null, 'info_type'=>'BAS')
	)
);

// Enable Search icon for all profiles by default for Recyclebin module
$soresult = $adb->query("select * from vtiger_salesorder");
$countprofiles = $adb->num_rows($soresult);
for($i=0;$i<$countprofiles;$i++)
{
	$sores= $adb->query("select prefix, cur_id from vtiger_inventory_num where semodule='SalesOrder' and active=1");
	$prefix=$adb->query_result($sores,0,'prefix');
	$cur_id=$adb->query_result($sores,0,'cur_id');
	$so_id = $adb->query_result($soresult,$i,'salesorderid');
	$adb->query("UPDATE vtiger_salesorder set salesorder_no='".$prefix."_".$cur_id."' where salesorderid=".$so_id);
	$adb->query("UPDATE vtiger_inventory_num set cur_id='".($cur_id+1)."' where semodule='SalesOrder' and active=1");
}

$poresult = $adb->query("select * from vtiger_purchaseorder");
$countprofiles = $adb->num_rows($poresult);
for($i=0;$i<$countprofiles;$i++)
{
	$pores= $adb->query("select prefix, cur_id from vtiger_inventory_num where semodule='PurchaseOrder' and active=1");
	$prefix=$adb->query_result($pores,0,'prefix');
	$cur_id=$adb->query_result($pores,0,'cur_id');
	$poid = $adb->query_result($poresult,$i,'purchaseorderid');
	$adb->query("UPDATE vtiger_purchaseorder set purchaseorder_no='".$prefix.$cur_id."' where purchaseorderid=".$poid);
	$adb->query("UPDATE vtiger_inventory_num set cur_id='".($cur_id+1)."' where semodule='PurchaseOrder' and active=1");
}

$quoteresult = $adb->query("select * from vtiger_quotes");
$countprofiles = $adb->num_rows($quoteresult);
for($i=0;$i<$countprofiles;$i++)
{
	$quores= $adb->query("select prefix, cur_id from vtiger_inventory_num where semodule='Quotes' and active=1");
	$prefix=$adb->query_result($quores,0,'prefix');
	$cur_id=$adb->query_result($quores,0,'cur_id');
	$quoteid = $adb->query_result($quoteresult,$i,'quoteid');
	$adb->query("UPDATE vtiger_quotes set quote_no='".$prefix."_".$cur_id."' where quoteid=".$quoteid);
	$adb->query("UPDATE vtiger_inventory_num set cur_id='".($cur_id+1)."' where semodule='Quotes' and active=1");
}


function AddModuleFields($paramArray) {
	global $adb;

	$fieldCreateCount = 0;

	for($index = 0; $index < count($paramArray); ++$index) {
		$criteria = $paramArray[$index];

		$sqlresult = $adb->query("select tabid from vtiger_tab where name='".($criteria['module'])."'");
		$tabid = $adb->query_result($sqlresult, 0, "tabid");
		$sqlresult = $adb->query("select fieldid from vtiger_field where tablename = '". 
			($criteria['tablename']) . "' and columnname = '".
			($criteria['columnname']) . "' and fieldname  = '".
			($criteria['fieldname']) . "' and fieldlabel = '".
			($criteria['fieldlabel']) . "' and tabid = '$tabid'");

		$fieldid = $adb->query_result($sqlresult, 0, "fieldid");
		// Avoid duplicate entries
		if(isset($fieldid)) continue;

		$fieldid = $adb->getUniqueId("vtiger_field");

		$columnname    = $criteria['columnname'];
		$tablename     = $criteria['tablename'];
		$generatedtype = $criteria['generatedtype'];
		$uitype        = $criteria['uitype'];
		$fieldname     = $criteria['fieldname'];
		$fieldlabel    = $criteria['fieldlabel'];
		$readonly      = $criteria['readonly'];
		$presence      = $criteria['presence'];
		$selected      = $criteria['selected'];
		$maximumlength = $criteria['maximumlength'];
		$sequence      = $criteria['sequence'];
		$block         = $criteria['block'];
		$displaytype   = $criteria['displaytype'];
		$typeofdata    = $criteria['typeofdata'];
		$quickcreate   = $criteria['quickcreate'];
		$quickcreatesequence = $criteria['quickcreatesequence'];
		$info_type     = $criteria['info_type'];

		// Set proper values for input if not sent
		if(is_null($generatedtype)) $generatedtype = 1;

		if(!isset($block)) {
			$blocklabel = $criteria['blocklabel'];
			$sqlresult = $adb->query("select blockid from vtiger_blocks where tabid=$tabid and blocklabel='$blocklabel'");
			$block = $adb->query_result($sqlresult, 0, "blockid");
		}

		// Add the field entry
		$sql = "INSERT INTO vtiger_field 
			(tabid, fieldid, columnname, tablename, generatedtype, uitype, fieldname, fieldlabel, 
			readonly, presence, selected, maximumlength, sequence, block, displaytype, typeofdata, quickcreate, quickcreatesequence, info_type)
			values ($tabid, $fieldid, '$columnname', '$tablename', '$generatedtype', '$uitype', '$fieldname', '$fieldlabel', 
			'$readonly','$presence','$selected','$maximumlength','$sequence','$block','$displaytype','$typeofdata','$quickcreate','$quickcreatesequence','$info_type')";

		$adb->query($sql);

		// Make the field available to all the existing profiles.
		$adb->query("INSERT INTO vtiger_def_org_field (tabid, fieldid, visible, readonly) VALUES ($tabid, $fieldid, 0, 1)");
	
		$sqlresult = $adb->query("select profileid from vtiger_profile");
		$profilecnt = $adb->num_rows($sqlresult);
		for($pridx = 0; $pridx < $profilecnt; ++$pridx) {
			$profileid = $adb->query_result($sqlresult, $pridx, "profileid");
			$adb->query("INSERT INTO vtiger_profile2field (profileid, tabid, fieldid, visible, readonly) VALUES($profileid, $tabid, $fieldid, 0, 1)");
		}

		++$fieldCreateCount;
	}
	return $fieldCreateCount;
}

function AddColumns($paramArray){
	global $adb;

	$fieldCreateCount = 0;

	for($index = 0; $index < count($paramArray); ++$index) {
		$criteria = $paramArray[$index];
		
		$sqlresult = $adb->query("select num_id from vtiger_inventory_num where semodule='".$criteria['semodule']."' and prefix='".$criteria['prefix']."'");
		$numid = $adb->query_result($sqlresult, 0, "num_id");
		if(isset($numid)) continue;
		$numid=$adb->getUniqueId("vtiger_inventory_num");
		$semodule    = $criteria['semodule'];
		$prefix     = $criteria['prefix'];
		$startid = $criteria['startid'];
		$curid        = $criteria['curid'];
		$active     = $criteria['active'];
		ExecuteQuery("INSERT INTO vtiger_inventory_num values($numid,'$semodule','$prefix','$startid','$curid',$active)");			
	}
}
function ChangeCVColumnlist($paramArray){
	global $adb;

	$fieldCreateCount = 0;

	for($index = 0; $index < count($paramArray); ++$index) {
		$criteria = $paramArray[$index];
		
		$sqlresult = $adb->query("select cvid from vtiger_customview where entitytype='".$criteria['module']."' and viewname='All'");
		$cvid = $adb->query_result($sqlresult, 0, "cvid");
		if($criteria['module']=='Quotes')$columnname='vtiger_quotes:quote_no:quote_no:Quotes_Quote_No:V';
		if($criteria['module']=='PurchaseOrder')$columnname='vtiger_purchaseorder:purchaseorder_no:purchaseorder_no:PurchaseOrder_Order_No:V';
		if($criteria['module']=='SalesOrder')$columnname='vtiger_salesorder:salesorder_no:salesorder_no:SalesOrder_Order_No:V';
		$adb->query("UPDATE vtiger_cvcolumnlist SET columnname='$columnname' where cvid=$cvid and columnindex=0");			
	}
}

$migrationlog->debug("\n\nDB Changes from 5.0.4 to 5.1.0 -------- Ends \n\n");

?>
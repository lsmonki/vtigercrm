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

$migrationlog->debug("\n\nDB Changes from 5.0.4 to 5.1.0 -------- Ends \n\n");

?>
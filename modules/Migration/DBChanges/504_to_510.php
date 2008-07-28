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

/* Reminder Popup support for Calendar Events */
ExecuteQuery("CREATE TABLE vtiger_activity_reminder_popup(reminderid int(19) NOT NULL AUTO_INCREMENT,semodule varchar(100) NOT NULL,recordid varchar(100) NOT NULL,date_start DATE,time_start varchar(100) NOT NULL,status int(2) NOT NULL, PRIMARY KEY(reminderid))");
ExecuteQuery("CREATE TABLE vtiger_reminder_interval(reminder_intervalid int(19) NOT NULL AUTO_INCREMENT,reminder_interval varchar(200) NOT NULL,sortorderid int(19) NOT NULL,presence int(1) NOT NULL, PRIMARY KEY(reminder_intervalid))");
ExecuteQuery("alter table vtiger_users add column reminder_interval varchar(100)");
ExecuteQuery("alter table vtiger_users add column reminder_next_time varchar(100)");
$adb->query("INSERT INTO vtiger_reminder_interval values(1,'None',0,1)");
$adb->query("INSERT INTO vtiger_reminder_interval values(2,'1 Minute',1,1)");
$adb->query("INSERT INTO vtiger_reminder_interval values(3,'5 Minutes',2,1)");
$adb->query("INSERT INTO vtiger_reminder_interval values(4,'15 Minutes',3,1)");
$adb->query("INSERT INTO vtiger_reminder_interval values(5,'30 Minutes',4,1)");
$adb->query("INSERT INTO vtiger_reminder_interval values(6,'45 Minutes',5,1)");
$adb->query("INSERT INTO vtiger_reminder_interval values(7,'1 Hour',6,1)");
$adb->query("INSERT INTO vtiger_reminder_interval values(8,'1 Day',7,1)");
$adb->query("UPDATE vtiger_users SET reminder_interval='1 Minute' AND reminder_next_time='".date('Y-m-d H:i')."'");

/* For Duplicate Records Merging feature */
ExecuteQuery("INSERT INTO vtiger_actionmapping values(10,'DuplicatesHandling',0)");
ExecuteQuery("CREATE TABLE vtiger_user2mergefields (userid int(11) REFERENCES vtiger_users( id ) , tabid int( 19 ) ,fieldid int( 19 ), visible int(2))");

function insertUser2mergefields($userid)
{
	global $log,$adb;
	$log->debug("Entering insertUser2mergefields(".$userid.") method ...");
        $log->info("in insertUser2mergefields ".$userid);

	//$adb->database->SetFetchMode(ADODB_FETCH_ASSOC); 
	$fld_result = $adb->query("select * from vtiger_field where generatedtype=1 and displaytype in (1,2,3) and tabid != 29 and uitype not in(70,69) and fieldid not in(87,148,151,155,102)");
    $num_rows = $adb->num_rows($fld_result);
    for($i=0; $i<$num_rows; $i++)
    {
		$tab_id = $adb->query_result($fld_result,$i,'tabid');
		$field_id = $adb->query_result($fld_result,$i,'fieldid');
		$adb->query("insert into vtiger_user2mergefields values ($userid, $tab_id, $field_id, 0)");
	}
	$log->debug("Exiting insertUser2mergefields method ...");
}
insertUser2mergefields(0);
insertUser2mergefields(1);
insertUser2mergefields(2);
ExecuteQuery("update vtiger_user2mergefields set visible=1 where fieldid in(1,38,40,65,104,106,111,152,156,255)");

ExecuteQuery("insert into vtiger_profile2utility values(1,2,10,0)");
ExecuteQuery("insert into vtiger_profile2utility values(1,4,10,0)");
ExecuteQuery("insert into vtiger_profile2utility values(1,6,10,0)");
ExecuteQuery("insert into vtiger_profile2utility values(1,7,10,0)");
ExecuteQuery("insert into vtiger_profile2utility values(1,13,10,0)");
ExecuteQuery("insert into vtiger_profile2utility values(1,14,10,0)");
ExecuteQuery("insert into vtiger_profile2utility values(1,18,10,0)");

ExecuteQuery("insert into vtiger_profile2utility values(2,2,10,0)");
ExecuteQuery("insert into vtiger_profile2utility values(2,4,10,0)");
ExecuteQuery("insert into vtiger_profile2utility values(2,6,10,0)");
ExecuteQuery("insert into vtiger_profile2utility values(2,7,10,0)");
ExecuteQuery("insert into vtiger_profile2utility values(2,13,10,0)");
ExecuteQuery("insert into vtiger_profile2utility values(2,14,10,0)");
ExecuteQuery("insert into vtiger_profile2utility values(2,18,10,0)");

ExecuteQuery("insert into vtiger_profile2utility values(3,2,10,0)");
ExecuteQuery("insert into vtiger_profile2utility values(3,4,10,0)");
ExecuteQuery("insert into vtiger_profile2utility values(3,6,10,0)");
ExecuteQuery("insert into vtiger_profile2utility values(3,7,10,0)");
ExecuteQuery("insert into vtiger_profile2utility values(3,13,10,0)");
ExecuteQuery("insert into vtiger_profile2utility values(3,14,10,0)");
ExecuteQuery("insert into vtiger_profile2utility values(3,18,10,0)");

ExecuteQuery("insert into vtiger_profile2utility values(4,2,10,0)");
ExecuteQuery("insert into vtiger_profile2utility values(4,4,10,0)");
ExecuteQuery("insert into vtiger_profile2utility values(4,6,10,0)");
ExecuteQuery("insert into vtiger_profile2utility values(4,7,10,0)");
ExecuteQuery("insert into vtiger_profile2utility values(4,13,10,0)");
ExecuteQuery("insert into vtiger_profile2utility values(4,14,10,0)");
ExecuteQuery("insert into vtiger_profile2utility values(4,18,10,0)");

/* Local Backup Feature */
ExecuteQuery("alter table vtiger_systems add column server_path varchar(256)");

/* Multi-Currency Support in Products, Pricebooks and Other Inventory Modules */

// To save mapping between products and its price in different currencies.
ExecuteQuery("CREATE TABLE IF NOT EXISTS vtiger_productcurrencyrel (productid int(11) not null, currencyid int(11) not null, converted_price decimal(25,2) default NULL, actual_price decimal(25, 2) default NULL) ENGINE=InnoDB");

// Update Product related tables
ExecuteQuery("alter table vtiger_products drop column currency");
ExecuteQuery("alter table vtiger_products add column currency_id int(19) not null default '1'");

// Update Currency related tables
ExecuteQuery("alter table vtiger_currency_info add column deleted int(1) not null default '0'");

// Update Inventory related tables
ExecuteQuery("alter table vtiger_quotes drop column currency");
ExecuteQuery("alter table vtiger_quotes add column currency_id int(19) not null default '1'");
ExecuteQuery("alter table vtiger_quotes add column conversion_rate decimal(10,3) not null default '1.000'");
ExecuteQuery("insert into vtiger_field values(20,".$adb->getUniqueID('vtiger_field').",'currency_id','vtiger_quotes','1','117','currency_id','Currency','1','0','1','100','21','51','3','I~O','1',null,'BAS')");
ExecuteQuery("insert into vtiger_field values(20,".$adb->getUniqueID('vtiger_field').",'conversion_rate','vtiger_quotes','1','1','conversion_rate','Conversion Rate','1','0','1','100','22','51','3','N~O','1',null,'BAS')");

ExecuteQuery("alter table vtiger_purchaseorder add column currency_id int(19) not null default '1'");
ExecuteQuery("alter table vtiger_purchaseorder add column conversion_rate decimal(10,3) not null default '1.000'");
ExecuteQuery("insert into vtiger_field values(21,".$adb->getUniqueID('vtiger_field').",'currency_id','vtiger_purchaseorder','1','117','currency_id','Currency','1','0','1','100','18','57','3','I~O','1',null,'BAS')");
ExecuteQuery("insert into vtiger_field values(21,".$adb->getUniqueID('vtiger_field').",'conversion_rate','vtiger_purchaseorder','1','1','conversion_rate','Conversion Rate','1','0','1','100','19','57','3','N~O','1',null,'BAS')");

ExecuteQuery("alter table vtiger_salesorder add column currency_id int(19) not null default '1'");
ExecuteQuery("alter table vtiger_salesorder add column conversion_rate decimal(10,3) not null default '1.000'");
ExecuteQuery("insert into vtiger_field values(22,".$adb->getUniqueID('vtiger_field').",'currency_id','vtiger_salesorder','1','117','currency_id','Currency','1','0','1','100','19','63','3','I~O','1',null,'BAS')");
ExecuteQuery("insert into vtiger_field values(22,".$adb->getUniqueID('vtiger_field').",'conversion_rate','vtiger_salesorder','1','1','conversion_rate','Conversion Rate','1','0','1','100','20','63','3','N~O','1',null,'BAS')");

ExecuteQuery("alter table vtiger_invoice add column currency_id int(19) not null default '1'");
ExecuteQuery("alter table vtiger_invoice add column conversion_rate decimal(10,3) not null default '1.000'");
ExecuteQuery("insert into vtiger_field values(23,".$adb->getUniqueID('vtiger_field').",'currency_id','vtiger_invoice','1','117','currency_id','Currency','1','0','1','100','18','69','3','I~O','1',null,'BAS')");
ExecuteQuery("insert into vtiger_field values(23,".$adb->getUniqueID('vtiger_field').",'conversion_rate','vtiger_invoice','1','1','conversion_rate','Conversion Rate','1','0','1','100','19','69','3','N~O','1',null,'BAS')");

// Update Price Book related tables
ExecuteQuery("alter table vtiger_pricebook drop column description");
ExecuteQuery("alter table vtiger_pricebook add column currency_id int(19) not null default '1'");
ExecuteQuery("alter table vtiger_pricebookproductrel add column usedcurrency int(11) not null default '1'");
$pb_currency_field_id = $adb->getUniqueID('vtiger_field');
$pb_tab_id = getTabid('PriceBooks');
$adb->query("insert into vtiger_field values($pb_tab_id,$pb_currency_field_id,'currency_id','vtiger_pricebook','1','117','currency_id','Currency','1','0','0','100','5','48','1','I~M','0','3','BAS')");
$adb->query("insert into vtiger_cvcolumnlist values('23','2','vtiger_pricebook:currency_id:currency_id:PriceBooks_Currency:I')");
$adb->query("insert into vtiger_def_org_field values($pb_tab_id, $pb_currency_field_id, 0, 1)");
$profile_list = $adb->query("select profileid from vtiger_profile");
$num_profiles = $adb->num_rows($profile_list);
for($i=0;$i<$num_profiles;$i++) {
	$profileid = $adb->query_result($profile_list,$i,'profileid');
	$adb->query("insert into vtiger_profile2field values($profileid, $pb_tab_id, $pb_currency_field_id, 0, 1)");
}

/* Documents module */
ExecuteQuery("alter table vtiger_notes add(folderid int(19) NOT NULL,filepath varchar(255) default NULL,filetype varchar(50) default NULL,filelocationtype varchar(5) default NULL,filedownloadcount int(19) default NULL,filestatus int(19) default NULL,filesize int(19) NOT NULL default '0',filearchitecture varchar(50) default NULL,fileversion varchar(50) default NULL,os varchar(200) default NULL)");

ExecuteQuery("create table vtiger_attachmentsfolder ( folderid int(19) NOT NULL auto_increment,foldername varchar(200) NOT NULL default '', description varchar(250) default '', createdby int(19) NOT NULL, sequence int(19) default NULL, PRIMARY KEY  (folderid))");

ExecuteQuery("insert into vtiger_attachmentsfolder values (1,'Existing Notes','Contains all Notes migrated from the earlier version',1,1)");

$notesQuery = $adb->query("select notesid from vtiger_notes");
$noofnotes = $adb->num_rows($notesQuery);
if($noofnotes > 0)
{
    for($k=0;$k<$noofnotes;$k++)
    {
        $notesid = $adb->query_result($notesQuery,$k,'notesid');
        ExecuteQuery("update vtiger_notes set folderid=1 where notesid = ".$notesid);
    }
}

ExecuteQuery("create table `vtiger_os`(`osid` int(19) NOT NULL auto_increment,`os` varchar(300) NOT NULL,`sortorderid` int(19) NOT NULL default '0',`presence` int(1) NOT NULL default '1',PRIMARY KEY  (`osid`),UNIQUE KEY `os_os_idx` (`os`))");

ExecuteQuery("insert into vtiger_os values(1,'Windows',0,1)");
ExecuteQuery("insert into vtiger_os values(2,'Linux',1,1)");
ExecuteQuery("insert into vtiger_os values(3,'Mac',2,1)");

$fieldid = Array();
for($i=0;$i<8;$i++)
{
	$fieldid[$i] = $adb->getUniqueID("vtiger_field");
}

ExecuteQuery("insert into vtiger_blocks values(85,8,'LBL_FILE_INFORMATION',3,0,0,0,0,0)");

ExecuteQuery("insert into vtiger_field values (8,".$fieldid[0].",'smownerid','vtiger_crmentity',1,53,'assigned_user_id','Assigned To',1,0,0,100,6,17,1,'V~M',1,NULL,'BAS')");
ExecuteQuery("insert into vtiger_field values(8,".$fieldid[1].",'filetype','vtiger_notes',1,1,'filetype','File Type',1,0,0,100,2,85,2,'V~O',1,'','BAS')");
ExecuteQuery("insert into vtiger_field values(8,".$fieldid[2].",'filesize','vtiger_notes',1,1,'filesize','File Size',1,0,0,100,3,85,2,'V~O',1,'','BAS')");
ExecuteQuery("insert into vtiger_field values(8,".$fieldid[3].",'filelocationtype','vtiger_notes',1,1,'filelocationtype','Download Type',1,0,0,100,4,85,2,'V~O',1,'','BAS')");
ExecuteQuery("insert into vtiger_field values(8,".$fieldid[4].",'fileversion','vtiger_notes',1,1,'fileversion','Version',1,0,0,100,5,85,2,'V~O',1,'','BAS')");
ExecuteQuery("insert into vtiger_field values(8,".$fieldid[5].",'filestatus','vtiger_notes',1,56,'filestatus','Active',1,0,0,100,6,85,2,'V~O',1,'','BAS')");
ExecuteQuery("insert into vtiger_field values(8,".$fieldid[6].",'filedownloadcount','vtiger_notes',1,1,'filedownloadcount','Download Count',1,0,0,100,11,85,2,'I~O',1,'','BAS')");
ExecuteQuery("insert into vtiger_field values(8,".$fieldid[7].",'os','vtiger_notes',1,1,'filearchitecture','Platform',1,0,0,100,12,85,2,'V~O',1,'','BAS')");

for($i=0;$i<8;$i++)
{
	$adb->query("insert into vtiger_def_org_field values(8, ".$fieldid[$i].", 0, 1)");
	for($j=0;$j<$num_profiles;$j++)
	{
		$profileid = $adb->query_result($profile_list,$j,'profileid');
		$adb->query("insert into vtiger_profile2field values($profileid, 8, ".$fieldid[$i].", 0, 1)");
	}
}

$dbQuery = "select notesid,contact_id from vtiger_notes";
$dbresult = $adb->query($dbQuery);
$noofrecords = $adb->num_rows($dbresult);
if($noofrecords > 0)
{
    for($i=0;$i<$noofrecords;$i++)
    {
        $contactid = $adb->query_result($dbresult,$i,'contact_id');
        $notesid = $adb->query_result($dbresult,$i,'notesid');
        if($contactid != 0)
            ExecuteQuery("insert into vtiger_senotesrel values (".$contactid.",".$notesid.")");
    }
}

ExecuteQuery("delete from vtiger_field where tabid = 8 and fieldname = 'contact_id'");
ExecuteQuery("delete from vtiger_field where tabid = 8 and fieldname = 'parent_id'");

ExecuteQuery("alter table vtiger_notes drop column contact_id");

ExecuteQuery("delete from vtiger_cvcolumnlist where columnname like '%Notes_Contact_Name%'");
ExecuteQuery("delete from vtiger_cvcolumnlist where columnname like '%Notes_Related_to%'");
ExecuteQuery("create table vtiger_notegrouprelation (notesid int(19) NOT NULL, groupname vachar(100) default NULL)");

ExecuteQuery("insert into vtiger_def_org_share values (13,8,2,0)");

for($i=0;$i<4;$i++)
{
	ExecuteQuery("insert into vtiger_org_share_action2tab values(".$i.",8)");
}	

ExecuteQuery("update vtiger_tab set ownedby=0,name='Documents',tablabel='Documents' where tabid=8");
//End: Database changes regarding Documents module

/* Home Page Customization */
ExecuteQuery("CREATE TABLE vtiger_homestuff (stuffid int(19) NOT NULL default '0', stuffsequence int(19) NOT NULL default '0', stufftype varchar(100) default NULL, userid int(19) NOT NULL, visible int(10) NOT NULL default '0', stufftitle varchar(100) default NULL, PRIMARY KEY  (stuffid), KEY stuff_stuffid_idx (stuffid), KEY fk_1_vtiger_homestuff (userid))");
ExecuteQuery("CREATE TABLE vtiger_homedashbd (stuffid int(19) NOT NULL default 0, dashbdname varchar(100) default NULL, dashbdtype varchar(100) default NULL, PRIMARY KEY  (stuffid), KEY stuff_stuffid_idx (stuffid))");
ExecuteQuery("CREATE TABLE vtiger_homedefault (stuffid int(19) NOT NULL default 0, hometype varchar(30) NOT NULL, maxentries int(19) default NULL, setype varchar(30) default NULL, PRIMARY KEY  (stuffid), KEY stuff_stuffid_idx (stuffid))");
ExecuteQuery("CREATE TABLE vtiger_homemodule (stuffid int(19) NOT NULL, modulename varchar(100) default NULL, maxentries int(19) NOT NULL, customviewid int(19) NOT NULL, setype varchar(30) NOT NULL, PRIMARY KEY  (stuffid), KEY stuff_stuffid_idx (stuffid))");
ExecuteQuery("CREATE TABLE vtiger_homemoduleflds (stuffid int(19) default NULL, fieldname varchar(255) default NULL, KEY stuff_stuffid_idx (stuffid))");
ExecuteQuery("CREATE TABLE vtiger_homerss (stuffid int(19) NOT NULL default 0, url varchar(100) default NULL, maxentries int(19) NOT NULL, PRIMARY KEY  (stuffid), KEY stuff_stuffid_idx (stuffid))"); 

ExecuteQuery("ALTER TABLE vtiger_homestuff ADD CONSTRAINT fk_1_vtiger_homestuff FOREIGN KEY (userid) REFERENCES vtiger_users (id) ON DELETE CASCADE");
ExecuteQuery("ALTER TABLE vtiger_homedashbd ADD CONSTRAINT fk_1_vtiger_homedashbd FOREIGN KEY (stuffid) REFERENCES vtiger_homestuff (stuffid) ON DELETE CASCADE");
ExecuteQuery("ALTER TABLE vtiger_homedefault ADD CONSTRAINT fk_1_vtiger_homedefault FOREIGN KEY (stuffid) REFERENCES vtiger_homestuff (stuffid) ON DELETE CASCADE");
ExecuteQuery("ALTER TABLE vtiger_homemodule ADD CONSTRAINT fk_1_vtiger_homemodule FOREIGN KEY (stuffid) REFERENCES vtiger_homestuff (stuffid) ON DELETE CASCADE");
ExecuteQuery("ALTER TABLE vtiger_homemoduleflds ADD CONSTRAINT fk_1_vtiger_homemoduleflds FOREIGN KEY (stuffid) REFERENCES vtiger_homemodule (stuffid) ON DELETE CASCADE");
ExecuteQuery("ALTER TABLE vtiger_homerss ADD CONSTRAINT fk_1_vtiger_homerss FOREIGN KEY (stuffid) REFERENCES vtiger_homestuff (stuffid) ON DELETE CASCADE");

//to get the users lists
$query = $adb->pquery('select * from vtiger_users',array());
for($i=0;$i<$adb->num_rows($query);$i++)
{
	$userid = $adb->query_result($query,$i,'id');

	$s1=$adb->getUniqueID("vtiger_homestuff");
	$sql="insert into vtiger_homestuff values(".$s1.",1,'Default',".$userid.",1,'Top Accounts')";
	$res=$adb->pquery($sql,array());

	$s2=$adb->getUniqueID("vtiger_homestuff");
	$sql="insert into vtiger_homestuff values(".$s2.",2,'Default',".$userid.",1,'Home Page Dashboard')";
	$res=$adb->pquery($sql,array());

	$s3=$adb->getUniqueID("vtiger_homestuff");
	$sql="insert into vtiger_homestuff values(".$s3.",3,'Default',".$userid.",1,'Top Potentials')";
	$res=$adb->pquery($sql,array());

	$s4=$adb->getUniqueID("vtiger_homestuff");
	$sql="insert into vtiger_homestuff values(".$s4.",4,'Default',".$userid.",1,'Top Quotes')";
	$res=$adb->pquery($sql,array());

	$s5=$adb->getUniqueID("vtiger_homestuff");
	$sql="insert into vtiger_homestuff values(".$s5.",5,'Default',".$userid.",1,'Key Metrics')";
	$res=$adb->pquery($sql,array());

	$s6=$adb->getUniqueID("vtiger_homestuff");
	$sql="insert into vtiger_homestuff values(".$s6.",6,'Default',".$userid.",1,'Top Trouble Tickets')";
	$res=$adb->pquery($sql,array());

	$s7=$adb->getUniqueID("vtiger_homestuff"); 
	$sql="insert into vtiger_homestuff values(".$s7.",7,'Default',".$userid.",1,'Upcoming Activities')";
	$res=$adb->pquery($sql,array());

	$s8=$adb->getUniqueID("vtiger_homestuff");
	$sql="insert into vtiger_homestuff values(".$s8.",8,'Default',".$userid.",1,'My Group Allocation')";
	$res=$adb->pquery($sql,array());

	$s9=$adb->getUniqueID("vtiger_homestuff");
	$sql="insert into vtiger_homestuff values(".$s9.",9,'Default',".$userid.",1,'Top Sales Orders')";
	$res=$adb->pquery($sql,array());

	$s10=$adb->getUniqueID("vtiger_homestuff");
	$sql="insert into vtiger_homestuff values(".$s10.",10,'Default',".$userid.",1,'Top Invoices')";
	$res=$adb->pquery($sql,array());

	$s11=$adb->getUniqueID("vtiger_homestuff");
	$sql="insert into vtiger_homestuff values(".$s11.",11,'Default',".$userid.",1,'My New Leads')";
	$res=$adb->pquery($sql,array());

	$s12=$adb->getUniqueID("vtiger_homestuff");
	$sql="insert into vtiger_homestuff values(".$s12.",12,'Default',".$userid.",1,'Top Purchase Orders')";
	$res=$adb->pquery($sql,array());

	$s13=$adb->getUniqueID("vtiger_homestuff");
	$sql="insert into vtiger_homestuff values(".$s13.",13,'Default',".$userid.",1,'Pending Activities')";
	$res=$adb->pquery($sql,array());

	$s14=$adb->getUniqueID("vtiger_homestuff");
	$sql="insert into vtiger_homestuff values(".$s14.",14,'Default',".$userid.",1,'My Recent FAQs')";
	$res=$adb->pquery($sql,array());

	$sql="insert into vtiger_homedefault values(".$s1.",'ALVT',5,'Accounts')";
	$adb->pquery($sql,array());

	$sql="insert into vtiger_homedefault values(".$s2.",'HDB',5,'Dashboard')";
	$adb->pquery($sql,array());

	$sql="insert into vtiger_homedefault values(".$s3.",'PLVT',5,'Potentials')";
	$adb->pquery($sql,array());

	$sql="insert into vtiger_homedefault values(".$s4.",'QLTQ',5,'Quotes')";
	$adb->pquery($sql,array());

	$sql="insert into vtiger_homedefault values(".$s5.",'CVLVT',5,'NULL')";
	$adb->pquery($sql,array());

	$sql="insert into vtiger_homedefault values(".$s6.",'HLT',5,'HelpDesk')";
	$adb->pquery($sql,array());

	$sql="insert into vtiger_homedefault values(".$s7.",'UA',5,'Calendar')";
	$adb->pquery($sql,array());

	$sql="insert into vtiger_homedefault values(".$s8.",'GRT',5,'NULL')";
	$adb->pquery($sql,array());

	$sql="insert into vtiger_homedefault values(".$s9.",'OLTSO',5,'SalesOrder')";
	$adb->pquery($sql,array());

	$sql="insert into vtiger_homedefault values(".$s10.",'ILTI',5,'Invoice')";
	$adb->pquery($sql,array());

	$sql="insert into vtiger_homedefault values(".$s11.",'MNL',5,'Leads')";
	$adb->pquery($sql,array());

	$sql="insert into vtiger_homedefault values(".$s12.",'OLTPO',5,'PurchaseOrder')";
	$adb->pquery($sql,array());

	$sql="insert into vtiger_homedefault values(".$s13.",'PA',5,'Calendar')";
	$adb->pquery($sql,array());

	$sql="insert into vtiger_homedefault values(".$s14.",'LTFAQ',5,'Faq')";
	$adb->pquery($sql,array());
}
for($i=0;$i<$adb->num_rows($query);$i++)
{
	$def_homeorder = $adb->query_result($query,$i,'homeorder');
	$user_id = $adb->query_result($query,$i,'id');
	$def_array = explode(",",$def_homeorder);
	$sql = $adb->pquery("SELECT vtiger_homestuff.stuffid FROM vtiger_homestuff INNER JOIN vtiger_homedefault WHERE vtiger_homedefault.hometype in (". generateQuestionMarks($def_array) . ") AND vtiger_homestuff.stuffid = vtiger_homedefault.stuffid AND vtiger_homestuff.userid = ?",array($def_array,$user_id));
	$stuffid_list = array();
	for($j=0;$j<$adb->num_rows($sql);$j++) {
		$stuffid_list[] = $adb->query_result($sql,$j,'stuffid');
	}
	$adb->pquery("UPDATE vtiger_homestuff SET visible = 0 WHERE stuffid in (". generateQuestionMarks($stuffid_list) .")",array($stuffid_list));
}

$migrationlog->debug("\n\nDB Changes from 5.0.4 to 5.1.0 -------- Ends \n\n");

?>
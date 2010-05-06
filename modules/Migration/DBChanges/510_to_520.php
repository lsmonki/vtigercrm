<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *********************************************************************************/

//5.1.0 to 5.2.0 database changes

//we have to use the current object (stored in PatchApply.php) to execute the queries
require_once ('vtlib/Vtiger/Utils.php');

$adb = $_SESSION['adodb_current_object'];
$conn = $_SESSION['adodb_current_object'];

$migrationlog->debug("\n\nDB Changes from 5.1.0 to 5.2.0 -------- Starts \n\n");
function migration520_populateFieldForSecurity($tabid,$fieldid)
{
	global $adb;

	$check_deforg_res = $adb->pquery("SELECT 1 FROM vtiger_def_org_field WHERE tabid=? AND fieldid = ? LIMIT 1", array($tabid, $fieldid));
	if($check_deforg_res && $adb->num_rows($check_deforg_res)) {
		// Entry already exists, no need to act
	} else {
		$adb->pquery("INSERT INTO vtiger_def_org_field (tabid, fieldid, visible, readonly) VALUES(?,?,?,?)",
			array($tabid, $fieldid, 0, 1));
	}
			
	$profileresult = $adb->pquery("SELECT * FROM vtiger_profile", array());
	$countprofiles = $adb->num_rows($profileresult);
	for ($i=0;$i<$countprofiles;$i++)
	{
    	$profileid = $adb->query_result($profileresult,$i,'profileid');
    	$checkres  = $adb->pquery("SELECT 1 FROM vtiger_profile2field WHERE profileid=? AND tabid=? AND fieldid=?", array($profileid, $tabid, $fieldid));
    	if($checkres && $adb->num_rows($checkres)) {
    		// Entry already exists, do nothing
    	} else {
    		$adb->pquery("INSERT INTO vtiger_profile2field (profileid, tabid, fieldid, visible, readonly) VALUES(?,?,?,?,?)",
				array($profileid, $tabid, $fieldid, 0, 1));
    	}		
	}	
}
ExecuteQuery("CREATE TABLE IF NOT EXISTS vtiger_tab_info (tabid INT, prefname VARCHAR(256), prefvalue VARCHAR(256), FOREIGN KEY fk_1_vtiger_tab_info(tabid) REFERENCES vtiger_tab(tabid) ON DELETE CASCADE ON UPDATE CASCADE)  ENGINE=InnoDB DEFAULT CHARSET=utf8;");

$documents_tab_id=getTabid('Documents'); 
ExecuteQuery("update vtiger_field set quickcreate=3 where tabid = $documents_tab_id and columnname = 'filelocationtype'"); 
/* For Campaigns enhancement */
$accounts_tab_id = getTabid('Accounts');
$campaigns_tab_id = getTabid('Campaigns');
$contacts_tab_id = getTabid('Contacts');
$leads_tab_id = getTabid('Leads');


$campignrelstatus_contacts_fieldid  = $adb->getUniqueID('vtiger_field');
ExecuteQuery("INSERT INTO vtiger_field(tabid, fieldid, columnname, tablename, generatedtype, uitype, fieldname, fieldlabel, readonly, presence, selected, maximumlength, sequence, block, displaytype, typeofdata, quickcreate, quickcreatesequence, info_type, masseditable, helpinfo) VALUES ($contacts_tab_id,".$campignrelstatus_contacts_fieldid.", 'campaignrelstatus', 'vtiger_campaignrelstatus', 1, '16', 'campaignrelstatus', 'Status', 1, 0, 0, 100, 1, NULL, 1, 'V~O', 1, NULL, 'BAS', 0, NULL)");
migration520_populateFieldForSecurity($contacts_tab_id, $campignrelstatus_contacts_fieldid);

$campignrelstatus_accounts_fieldid = $adb->getUniqueID('vtiger_field');
ExecuteQuery("INSERT INTO vtiger_field(tabid, fieldid, columnname, tablename, generatedtype, uitype, fieldname, fieldlabel, readonly, presence, selected, maximumlength, sequence, block, displaytype, typeofdata, quickcreate, quickcreatesequence, info_type, masseditable, helpinfo) VALUES ($accounts_tab_id,".$campignrelstatus_accounts_fieldid.", 'campaignrelstatus', 'vtiger_campaignrelstatus', 1, '16', 'campaignrelstatus', 'Status', 1, 0, 0, 100, 1, NULL, 1, 'V~O', 1, NULL, 'BAS', 0, NULL)");
migration520_populateFieldForSecurity($accounts_tab_id, $campignrelstatus_accounts_fieldid);

$campignrelstatus_leads_fieldid     = $adb->getUniqueID('vtiger_field');
ExecuteQuery("INSERT INTO vtiger_field(tabid, fieldid, columnname, tablename, generatedtype, uitype, fieldname, fieldlabel, readonly, presence, selected, maximumlength, sequence, block, displaytype, typeofdata, quickcreate, quickcreatesequence, info_type, masseditable, helpinfo) VALUES ($leads_tab_id,".$campignrelstatus_leads_fieldid.", 'campaignrelstatus', 'vtiger_campaignrelstatus', 1, '16', 'campaignrelstatus', 'Status', 1, 0, 0, 100, 1, NULL, 1, 'V~O', 1, NULL, 'BAS', 0, NULL)");
migration520_populateFieldForSecurity($leads_tab_id, $campignrelstatus_leads_fieldid);

$campignrelstatus_campaigns_fieldid = $adb->getUniqueID('vtiger_field');
ExecuteQuery("INSERT INTO vtiger_field(tabid, fieldid, columnname, tablename, generatedtype, uitype, fieldname, fieldlabel, readonly, presence, selected, maximumlength, sequence, block, displaytype, typeofdata, quickcreate, quickcreatesequence, info_type, masseditable, helpinfo) VALUES ($campaigns_tab_id,".$campignrelstatus_campaigns_fieldid.", 'campaignrelstatus', 'vtiger_campaignrelstatus', 1, '16', 'campaignrelstatus', 'Status', 1, 0, 0, 100, 1, NULL, 1, 'V~O', 1, NULL, 'BAS', 0, NULL)");
migration520_populateFieldForSecurity($campaigns_tab_id, $campignrelstatus_campaigns_fieldid);

ExecuteQuery("CREATE TABLE vtiger_campaignrelstatus (
	campaignrelstatusid INTEGER, campaignrelstatus VARCHAR(200), sortorderid INT, presence INT) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

ExecuteQuery("INSERT INTO vtiger_campaignrelstatus VALUES (".$adb->getUniqueID('vtiger_campaignrelstatus').", '--None--',1,1)");
ExecuteQuery("INSERT INTO vtiger_campaignrelstatus VALUES (".$adb->getUniqueID('vtiger_campaignrelstatus').", 'Contacted - Successful',2,1)");
ExecuteQuery("INSERT INTO vtiger_campaignrelstatus VALUES (".$adb->getUniqueID('vtiger_campaignrelstatus').", 'Contected - Unsuccessful',3,1)");
ExecuteQuery("INSERT INTO vtiger_campaignrelstatus VALUES (".$adb->getUniqueID('vtiger_campaignrelstatus').", 'Contacted - Never Contact Again',4,1)");

ExecuteQuery("CREATE TABLE vtiger_campaignaccountrel (
	campaignid INTEGER UNSIGNED NOT NULL,
	accountid INTEGER UNSIGNED NOT NULL,
	campaignrelstatusid INTEGER UNSIGNED DEFAULT 1) ENGINE = InnoDB DEFAULT CHARSET=utf8;");
ExecuteQuery("ALTER TABLE vtiger_campaignaccountrel ADD PRIMARY KEY (campaignid, accountid)");

ExecuteQuery("ALTER TABLE vtiger_campaigncontrel ADD COLUMN campaignrelstatusid INTEGER UNSIGNED NOT NULL DEFAULT 1");
ExecuteQuery("ALTER TABLE vtiger_campaignleadrel ADD COLUMN campaignrelstatusid INTEGER UNSIGNED NOT NULL DEFAULT 1");

ExecuteQuery("INSERT INTO vtiger_relatedlists VALUES (".$adb->getUniqueID('vtiger_relatedlists').", $accounts_tab_id, $campaigns_tab_id, 'get_campaigns', 13, 'Campaigns', 0, 'select')");
ExecuteQuery("INSERT INTO vtiger_relatedlists VALUES (".$adb->getUniqueID('vtiger_relatedlists').", $campaigns_tab_id, $accounts_tab_id, 'get_accounts', 5, 'Accounts', 0, 'add,select')");

Vtiger_Utils::AddColumn('vtiger_inventorynotification', 'status','VARCHAR(30)');

//Fix : 6182 after migration from 510 'fields to be shown' at a profile for Email module

	$query = "SELECT * from vtiger_profile";
	$result = $adb->pquery($query,array());
	$rows = $adb->num_rows($result);

	$fields = "SELECT fieldid from vtiger_field where tablename = ?";
	$fieldResult = $adb->pquery($fields,array('vtiger_emaildetails'));
	$fieldRows = $adb->num_rows($fieldResult);
	$EmailTabid = getTabid('Emails');
	for($i=0; $i<$rows ;$i++){
		$profileid = $adb->query_result($result ,$i ,'profileid');
		for($j=0 ;$j<$fieldRows; $j++) {
			$fieldid = $adb->query_result($fieldResult, $j ,'fieldid');

			$sql_profile2field = "select * from vtiger_profile2field where fieldid=? and profileid=?";
			$result_profile2field = $adb->pquery($sql_profile2field,array($fieldid,$profileid));
			$rows_profile2field = $adb->num_rows($result_profile2field);
			if(!($rows_profile2field > 0)){
				$adb->query("INSERT INTO vtiger_profile2field(profileid ,tabid,fieldid,visible,readonly) VALUES ($profileid, $EmailTabid, $fieldid, 0 , 1)");
			}
		}
	}
	for($k=0;$k<$fieldRows;$k++){
		$fieldid = $adb->query_result($fieldResult, $k ,'fieldid');
		$sql_deforgfield = "select * from vtiger_def_org_field where tabid=? and fieldid=?";
		$result_deforgfield = $adb->pquery($sql_deforgfield,array($EmailTabid,$fieldid));
		$rows_deforgfield = $adb->num_rows($result_deforgfield);
		if(!($rows_deforgfield)){
			$adb->query("INSERT INTO vtiger_def_org_field(tabid ,fieldid,visible,readonly) VALUES ($EmailTabid, $fieldid, 0 , 1)");
		}
	}
	$sql = 'update vtiger_field set block=(select blockid from vtiger_blocks where '.
        "blocklabel=?) where tablename=?";
        $params = array('LBL_EMAIL_INFORMATION','vtiger_emaildetails');
        $adb->pquery($sql,$params);
	//END
	//update vtiger_systems to add a email field to be used as the from email address
		$sql = "ALTER TABLE vtiger_systems ADD from_email_field varchar(50);";
		ExecuteQuery($sql);
	//END

	// to disable unit_price from the massedit wizndow for products
	ExecuteQuery("update vtiger_field set masseditable=0 where tablename='vtiger_products' and columnname='unit_price'");
	//END
function VT520_webserviceMigrate(){
	require_once 'include/Webservices/Utils.php';
	$customWebserviceDetails = array(
		"name"=>"convertlead",
		"include"=>"include/Webservices/ConvertLead.php",
		"handler"=>"vtws_convertlead",
		"prelogin"=>0,
		"type"=>"POST"
	);

	$customWebserviceParams = array(
		array("name"=>'leadId',"type"=>'String' ),
		array("name"=>'assignedTo','type'=>'String'),
		array("name"=>'accountName','type'=>'String'),
		array("name"=>'avoidPotential','type'=>'Boolean'),
		array("name"=>'potential','type'=>'Encoded')
	);
	echo 'INITIALIZING WEBSERVICE...';
	$operationId = vtws_addWebserviceOperation($customWebserviceDetails['name'],$customWebserviceDetails['include'],
		$customWebserviceDetails['handler'],$customWebserviceDetails['type']);
	if($operationId === null && $operationId > 0){
		echo 'FAILED TO SETUP '.$customWebserviceDetails['name'].' WEBSERVICE';
		die;
	}
	$sequence = 1;
	foreach ($customWebserviceParams as $param) {
		$status = vtws_addWebserviceOperationParam($operationId,$param['name'],$param['type'],$sequence++);
		if($status === false){
			echo 'FAILED TO SETUP '.$customWebserviceDetails['name'].' WEBSERVICE HALFWAY THOURGH';
			die;
		}
	}
}

VT520_webserviceMigrate();

$update_InvProductRel = "ALTER vtiger_inventoryproductrel MODIFY discount_amount decimal(25,3)";
ExecuteQuery($update_InvProductRel);

$migrationlog->debug("\n\nDB Changes from 5.1.0 to 5.2.0 -------- Ends \n\n");


?>

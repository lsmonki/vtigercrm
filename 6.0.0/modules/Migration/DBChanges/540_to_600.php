<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *********************************************************************************/

//--
$adb = PearDatabase::getInstance();
$Vtiger_Utils_log = true;

if (!defined('INSTALLATION_MODE')) {
	$adb->pquery("CREATE TABLE IF NOT EXISTS com_vtiger_workflow_tasktypes (
						id int(11) NOT NULL,
						tasktypename varchar(255) NOT NULL,
						label varchar(255),
						classname varchar(255),
						classpath varchar(255),
						templatepath varchar(255),
						modules text(500),
						sourcemodule varchar(255)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8", array());

	$taskTypes = array();
	$defaultModules = array('include' => array(), 'exclude'=>array());
	$createToDoModules = array('include' => array("Leads","Accounts","Potentials","Contacts","HelpDesk","Campaigns","Quotes","PurchaseOrder","SalesOrder","Invoice"), 'exclude'=>array("Calendar", "FAQ", "Events"));
	$createEventModules = array('include' => array("Leads","Accounts","Potentials","Contacts","HelpDesk","Campaigns"), 'exclude'=>array("Calendar", "FAQ", "Events"));

	$taskTypes[] = array("name"=>"VTEmailTask", "label"=>"Send Mail", "classname"=>"VTEmailTask", "classpath"=>"modules/com_vtiger_workflow/tasks/VTEmailTask.inc", "templatepath"=>"com_vtiger_workflow/taskforms/VTEmailTask.tpl", "modules"=>$defaultModules, "sourcemodule"=>'');
	$taskTypes[] = array("name"=>"VTEntityMethodTask", "label"=>"Invoke Custom Function", "classname"=>"VTEntityMethodTask", "classpath"=>"modules/com_vtiger_workflow/tasks/VTEntityMethodTask.inc", "templatepath"=>"com_vtiger_workflow/taskforms/VTEntityMethodTask.tpl", "modules"=>$defaultModules, "sourcemodule"=>'');
	$taskTypes[] = array("name"=>"VTCreateTodoTask", "label"=>"Create Todo", "classname"=>"VTCreateTodoTask", "classpath"=>"modules/com_vtiger_workflow/tasks/VTCreateTodoTask.inc", "templatepath"=>"com_vtiger_workflow/taskforms/VTCreateTodoTask.tpl", "modules"=>$createToDoModules, "sourcemodule"=>'');
	$taskTypes[] = array("name"=>"VTCreateEventTask", "label"=>"Create Event", "classname"=>"VTCreateEventTask", "classpath"=>"modules/com_vtiger_workflow/tasks/VTCreateEventTask.inc", "templatepath"=>"com_vtiger_workflow/taskforms/VTCreateEventTask.tpl", "modules"=>$createEventModules, "sourcemodule"=>'');
	$taskTypes[] = array("name"=>"VTUpdateFieldsTask", "label"=>"Update Fields", "classname"=>"VTUpdateFieldsTask", "classpath"=>"modules/com_vtiger_workflow/tasks/VTUpdateFieldsTask.inc", "templatepath"=>"com_vtiger_workflow/taskforms/VTUpdateFieldsTask.tpl", "modules"=>$defaultModules, "sourcemodule"=>'');
	$taskTypes[] = array("name"=>"VTCreateEntityTask", "label"=>"Create Entity", "classname"=>"VTCreateEntityTask", "classpath"=>"modules/com_vtiger_workflow/tasks/VTCreateEntityTask.inc", "templatepath"=>"com_vtiger_workflow/taskforms/VTCreateEntityTask.tpl", "modules"=>$defaultModules, "sourcemodule"=>'');
	$taskTypes[] = array("name"=>"VTSMSTask", "label"=>"SMS Task", "classname"=>"VTSMSTask", "classpath"=>"modules/com_vtiger_workflow/tasks/VTSMSTask.inc", "templatepath"=>"com_vtiger_workflow/taskforms/VTSMSTask.tpl", "modules"=>$defaultModules, "sourcemodule"=>'SMSNotifier');

	foreach ($taskTypes as $taskType) {
		VTTaskType::registerTaskType($taskType);
	}
}

$moduleInstance = Vtiger_Module::getInstance('Potentials');
$block = Vtiger_Block::getInstance('LBL_OPPORTUNITY_INFORMATION', $moduleInstance);

$forecast_field = new Vtiger_Field();
$forecast_field->name = 'forecast_amount';
$forecast_field->label = 'Forecast Amount';
$forecast_field->table ='vtiger_potential';
$forecast_field->column = 'forecast_amount';
$forecast_field->columntype = 'decimal(25,4)';
$forecast_field->typeofdata = 'N~O';
$forecast_field->uitype = '71';
$forecast_field->masseditable = '0';
$block->addField($forecast_field);

$workflowManager = new VTWorkflowManager($adb);
$taskManager = new VTTaskManager($adb);

$potentailsWorkFlow = $workflowManager->newWorkFlow("Potentials");
$potentailsWorkFlow->test = '';
$potentailsWorkFlow->description = "Calculate or Update forecast amount";
$potentailsWorkFlow->executionCondition = VTWorkflowManager::$ON_EVERY_SAVE;
$potentailsWorkFlow->defaultworkflow = 1;
$workflowManager->save($potentailsWorkFlow);

$task = $taskManager->createTask('VTUpdateFieldsTask', $potentailsWorkFlow->id);
$task->active = true;
$task->summary = 'update forecast amount';
$task->field_value_mapping = '[{"fieldname":"forecast_amount","valuetype":"expression","value":"amount * probability / 100"}]';
$taskManager->saveTask($task);

// Change default Sales Man rolename to Sales Person
$adb->pquery("UPDATE vtiger_role SET rolename=? WHERE rolename=? and roleid=?", array('Sales Person', 'Sales Man', 'H5'));

//--
$invoiceModuleInstance = Vtiger_Module::getInstance('Invoice');
$fieldInstance=Vtiger_Field::getInstance('invoicestatus', $invoiceModuleInstance);
$fieldInstance->setPicklistValues( Array ('Cancel'));
            
// Email Reporting - added default email reports.
PopulateReportFolder('Email Reports','Email Reports');

$reportmodules = Array(
		Array('primarymodule'=>'Contacts','secondarymodule'=>'Emails'),
		Array('primarymodule'=>'Accounts','secondarymodule'=>'Emails'),
		Array('primarymodule'=>'Leads','secondarymodule'=>'Emails'),
		Array('primarymodule'=>'Vendors','secondarymodule'=>'Emails')
);

$reports = Array(
		Array('reportname'=>'Contacts Email Report',
				'reportfolder'=>'Email Reports',
				'description'=>'Emails sent to Contacts',
				'reporttype'=>'tabular',
				'sortid'=>'','stdfilterid'=>'','advfilterid'=>'0'),

		Array('reportname'=>'Accounts Email Report',
				'reportfolder'=>'Email Reports',
				'description'=>'Emails sent to Organizations',
				'reporttype'=>'tabular',
				'sortid'=>'','stdfilterid'=>'','advfilterid'=>'0'),

		Array('reportname'=>'Leads Email Report',
				'reportfolder'=>'Email Reports',
				'description'=>'Emails sent to Leads',
				'reporttype'=>'tabular',
				'sortid'=>'','stdfilterid'=>'','advfilterid'=>'0'),

		Array('reportname'=>'Vendors Email Report',
				'reportfolder'=>'Email Reports',
				'description'=>'Emails sent to Vendors',
				'reporttype'=>'tabular',
				'sortid'=>'','stdfilterid'=>'','advfilterid'=>'0')
);

$selectcolumns = Array(
		Array('vtiger_contactdetails:lastname:Contacts_Last_Name:lastname:V',
				'vtiger_contactdetails:email:Contacts_Email:email:E',
				'vtiger_activity:subject:Emails_Subject:subject:V',
				'vtiger_email_track:access_count:Emails_Access_Count:access_count:V'),

		Array('vtiger_account:accountname:Accounts_Account_Name:accountname:V',
				'vtiger_account:phone:Accounts_Phone:phone:V',
				'vtiger_account:email1:Accounts_Email:email1:E',
				'vtiger_activity:subject:Emails_Subject:subject:V',
				'vtiger_email_track:access_count:Emails_Access_Count:access_count:V'),

		Array('vtiger_leaddetails:lastname:Leads_Last_Name:lastname:V',
				'vtiger_leaddetails:company:Leads_Company:company:V',
				'vtiger_leaddetails:email:Leads_Email:email:E',
				'vtiger_activity:subject:Emails_Subject:subject:V',
				'vtiger_email_track:access_count:Emails_Access_Count:access_count:V'),

		Array('vtiger_vendor:vendorname:Vendors_Vendor_Name:vendorname:V',
				'vtiger_vendor:glacct:Vendors_GL_Account:glacct:V',
				'vtiger_vendor:email:Vendors_Email:email:E',
				'vtiger_activity:subject:Emails_Subject:subject:V',
				'vtiger_email_track:access_count:Emails_Access_Count:access_count:V'),
);

$advfilters = Array(
				Array(
					Array(
						'columnname'=>'vtiger_email_track:access_count:Emails_Access_Count:access_count:V',
						'comparator'=>'n',
						'value'=>''
					)
				)
			);

foreach($reports as $key=>$report) {
	$queryid = insertSelectQuery();
	$sql = 'SELECT MAX(folderid) AS count FROM vtiger_reportfolder';
	$result = $adb->query($sql);
	$folderid = $adb->query_result($result, 0, 'count');
	insertReports($queryid, $folderid, $report['reportname'], $report['description'], $report['reporttype']);
	insertSelectColumns($queryid,$selectcolumns[$key]);
	insertReportModules($queryid, $reportmodules[$key]['primarymodule'], $reportmodules[$key]['secondarymodule']);
	if(isset($advfilters[$report['advfilterid']])) {
		insertAdvFilter($queryid, $advfilters[$report['advfilterid']]);
	}
}
$adb->pquery("UPDATE vtiger_report SET sharingtype='Public'",array());
//End.

//Currency Decimal places handling
$adb->pquery("ALTER TABLE vtiger_account MODIFY COLUMN annualrevenue decimal(25,5)", array());
$adb->pquery("ALTER TABLE vtiger_leaddetails MODIFY COLUMN annualrevenue decimal(25,5)", array());
$adb->pquery("UPDATE vtiger_field SET typeofdata='N~O' WHERE fieldlabel='Annual Revenue' and typeofdata='I~O'",array());

$adb->pquery("ALTER TABLE vtiger_currency_info MODIFY COLUMN conversion_rate decimal(12,5)", array());
$adb->pquery("ALTER TABLE vtiger_productcurrencyrel MODIFY COLUMN actual_price decimal(28,5)", array());
$adb->pquery("ALTER TABLE vtiger_productcurrencyrel MODIFY COLUMN converted_price decimal(28,5)", array());
$adb->pquery("ALTER TABLE vtiger_pricebookproductrel MODIFY COLUMN listprice decimal(27,5)", array());
$adb->pquery("ALTER TABLE vtiger_inventoryproductrel MODIFY COLUMN listprice decimal(27,5)", array());
$adb->pquery("ALTER TABLE vtiger_inventoryproductrel MODIFY COLUMN discount_amount decimal(27,5)", array());

$currencyField = new CurrencyField($value);
$result = $adb->pquery("SELECT fieldname,tablename,columnname FROM vtiger_field WHERE uitype IN (?,?)",array('71','72'));
$count = $adb->num_rows($result);
for($i=0;$i<$count;$i++) {
	$fieldName = $adb->query_result($result,$i,'fieldname');
	$tableName = $adb->query_result($result,$i,'tablename');
	$columnName = $adb->query_result($result,$i,'columnname');

	$tableAndColumnSize = array();
	$tableInfo = $adb->database->MetaColumns($tableName);
	foreach ($tableInfo as $column) {
		$max_length = $column->max_length;
		$scale = $column->scale;

		$tableAndColumnSize[$tableName][$column->name]['max_length'] = $max_length;
		$tableAndColumnSize[$tableName][$column->name]['scale'] = $scale;
	}
	if(!empty($tableAndColumnSize[$tableName][$columnName]['scale'])) {
		$decimalsToChange = $currencyField->maxNumberOfDecimals - $tableAndColumnSize[$tableName][$columnName]['scale'];
		if($decimalsToChange != 0) {
			$maxlength = $tableAndColumnSize[$tableName][$columnName]['max_length'] + $decimalsToChange;
			$decimalDigits = $tableAndColumnSize[$tableName][$columnName]['scale'] + $decimalsToChange;

			$adb->pquery("ALTER TABLE " .$tableName." MODIFY COLUMN ".$columnName." decimal(?,?)", array($maxlength, $decimalDigits));
		}
	}
}

$moduleInstance = Vtiger_Module::getInstance('Users');
$currencyBlock = Vtiger_Block::getInstance('LBL_CURRENCY_CONFIGURATION', $moduleInstance);

$currency_decimals_field = new Vtiger_Field();
$currency_decimals_field->name = 'no_of_currency_decimals';
$currency_decimals_field->label = 'Number Of Currency Decimals';
$currency_decimals_field->table ='vtiger_users';
$currency_decimals_field->column = 'no_of_currency_decimals';
$currency_decimals_field->columntype = 'VARCHAR(2)';
$currency_decimals_field->typeofdata = 'V~O';
$currency_decimals_field->uitype = 16;
$currency_decimals_field->defaultvalue = '2';
$currency_decimals_field->sequence = 6;
$currency_decimals_field->helpinfo = "<b>Currency - Number of Decimal places</b> <br/><br/>".
		"Number of decimal places specifies how many number of decimals will be shown after decimal separator.<br/>".
		"<b>Eg:</b> 123.00";
$currencyBlock->addField($currency_decimals_field);
$currency_decimals_field->setPicklistValues(array("1","2","3","4","5"));
//Currency Decimal places handling - END

$inventoryModules = array('Invoice','SalesOrder','PurchaseOrder','Quotes');
$actions = array('Import','Export');

for($i = 0; $i < count($inventoryModules); $i++) {
	$moduleName = $inventoryModules[$i];
	$moduleInstance = Vtiger_Module::getInstance($moduleName);

	$blockInstance = new Vtiger_Block();

	$blockInstance->label = 'LBL_ITEM_DETAILS';
	$blockInstance->sequence = '5';
	$blockInstance->showtitle = '0';

	$moduleInstance->addBlock($blockInstance);

	foreach ($actions as $actionName) {
		Vtiger_Access::updateTool($moduleInstance, $actionName, true, '');
	}
}

$itemFieldsName = array('productid','quantity','listprice','comment','discount_amount','discount_percent','tax1','tax2','tax3');
$itemFieldsLabel = array('Item Name','Quantity','List Price','Item Comment','Item Discount Amount','Item Discount Percent','Tax1','Tax2','Tax3');
$itemFieldsTypeOfData = array('V~M','V~M','V~M','V~O','V~O','V~O','V~O','V~O','V~O');
$itemFieldsDisplayType = array('10','7','19','19','7','7','83','83','83');

for($i=0; $i<count($inventoryModules); $i++) {
	$moduleName = $inventoryModules[$i];
	$moduleInstance = Vtiger_Module::getInstance($moduleName);
	$blockInstance = Vtiger_Block::getInstance('LBL_ITEM_DETAILS',$moduleInstance);

	$relatedmodules = array('Products','Services');

	for($j=0;$j<count($itemFieldsName);$j++) {
		$field = new Vtiger_Field();

		$field->name = $itemFieldsName[$j];
		$field->label = $itemFieldsLabel[$j];
		$field->column = $itemFieldsName[$j];
		$field->table = 'vtiger_inventoryproductrel';
		$field->uitype = $itemFieldsDisplayType[$j];
		$field->typeofdata = $itemFieldsTypeOfData[$j];
		$field->readonly = '0';
		$field->displaytype = '5';
		$field->masseditable = '0';

		$blockInstance->addField($field);

		if($itemFieldsName[$j] == 'productid') {
			$field->setRelatedModules($relatedmodules);
		}
	}
}

// Register a new actor type for LineItem API
vtws_addActorTypeWebserviceEntityWithoutName('LineItem',
		'include/Webservices/LineItem/VtigerLineItemOperation.php',
		'VtigerLineItemOperation', array());

$webserviceObject = VtigerWebserviceObject::fromName($adb,'LineItem');
$adb->pquery("INSERT INTO vtiger_ws_entity_tables(webservice_entity_id,table_name) VALUES (?,?)", array($webserviceObject->getEntityId(), 'vtiger_inventoryproductrel'));

$fieldTypeId = $adb->getUniqueID("vtiger_ws_entity_fieldtype");
$adb->pquery("INSERT INTO vtiger_ws_entity_fieldtype(fieldtypeid,table_name, field_name,fieldtype) VALUES (?,?,?,?);", array($fieldTypeId, 'vtiger_inventoryproductrel', 'productid',"reference"));
$adb->pquery("INSERT INTO vtiger_ws_entity_referencetype(fieldtypeid,type) VALUES (?,?)",array($fieldTypeId,'Products'));

$fieldTypeId = $adb->getUniqueID("vtiger_ws_entity_fieldtype");
$adb->pquery("INSERT INTO vtiger_ws_entity_fieldtype(fieldtypeid,table_name, field_name,fieldtype) VALUES (?,?,?,?);", array($fieldTypeId, 'vtiger_inventoryproductrel', 'id',"reference"));
$adb->pquery("INSERT INTO vtiger_ws_entity_referencetype(fieldtypeid,type) VALUES (?,?)",array($fieldTypeId,'Invoice'));
$adb->pquery("INSERT INTO vtiger_ws_entity_referencetype(fieldtypeid,type) VALUES (?,?)",array($fieldTypeId,'SalesOrder'));
$adb->pquery("INSERT INTO vtiger_ws_entity_referencetype(fieldtypeid,type) VALUES (?,?)",array($fieldTypeId,'PurchaseOrder'));
$adb->pquery("INSERT INTO vtiger_ws_entity_referencetype(fieldtypeid,type) VALUES (?,?)",array($fieldTypeId,'Quotes'));

$fieldTypeId = $adb->getUniqueID("vtiger_ws_entity_fieldtype");
$adb->pquery("INSERT INTO vtiger_ws_entity_fieldtype(fieldtypeid,table_name,field_name,fieldtype) VALUES (?,?,?,?);", array($fieldTypeId,'vtiger_inventoryproductrel', 'incrementondel',"autogenerated"));

$adb->getUniqueID("vtiger_inventoryproductrel");
$adb->pquery("UPDATE vtiger_inventoryproductrel_seq SET id=(select max(lineitem_id) from vtiger_inventoryproductrel);",array());
$adb->pquery("UPDATE vtiger_ws_entity SET handler_path='include/Webservices/LineItem/VtigerInventoryOperation.php',handler_class='VtigerInventoryOperation' where name in ('Invoice','Quotes','PurchaseOrder','SalesOrder');",array());

$purchaseOrderTabId = getTabid("PurchaseOrder");

$purchaseOrderAddressInformationBlockId = getBlockId($purchaseOrderTabId, "LBL_ADDRESS_INFORMATION");

$invoiceTabId = getTabid("Invoice");
$invoiceTabIdAddressInformationBlockId = getBlockId($invoiceTabId, "LBL_ADDRESS_INFORMATION");
$adb->pquery('UPDATE vtiger_field SET block=? where tabid=? and block=?;',
		array($invoiceTabIdAddressInformationBlockId,$invoiceTabId,$purchaseOrderAddressInformationBlockId));
vtws_addActorTypeWebserviceEntityWithName('Tax',
		'include/Webservices/LineItem/VtigerTaxOperation.php',
		'VtigerTaxOperation', array('fieldNames'=>'taxlabel', 'indexField'=>'taxid', 'tableName'=>'vtiger_inventorytaxinfo'), true);

$webserviceObject = VtigerWebserviceObject::fromName($adb,'Tax');
$adb->pquery("INSERT INTO vtiger_ws_entity_tables(webservice_entity_id,table_name) VALUES (?,?)",array($webserviceObject->getEntityId(),'vtiger_inventorytaxinfo'));

vtws_addActorTypeWebserviceEntityWithoutName('ProductTaxes',
		'include/Webservices/LineItem/VtigerProductTaxesOperation.php',
		'VtigerProductTaxesOperation', array());

$webserviceObject = VtigerWebserviceObject::fromName($adb,'ProductTaxes');
$adb->pquery("INSERT INTO vtiger_ws_entity_tables(webservice_entity_id,table_name) VALUES (?,?)",array($webserviceObject->getEntityId(),'vtiger_producttaxrel'));

$fieldTypeId = $adb->getUniqueID("vtiger_ws_entity_fieldtype");
$adb->pquery("INSERT INTO vtiger_ws_entity_fieldtype(fieldtypeid,table_name,field_name,fieldtype) VALUES (?,?,?,?);", array($fieldTypeId,'vtiger_producttaxrel', 'productid',"reference"));
$adb->pquery("INSERT INTO vtiger_ws_entity_referencetype(fieldtypeid,type) VALUES (?,?)",array($fieldTypeId,'Products'));

$fieldTypeId = $adb->getUniqueID("vtiger_ws_entity_fieldtype");
$adb->pquery("INSERT INTO vtiger_ws_entity_fieldtype(fieldtypeid,table_name,field_name,fieldtype) VALUES (?,?,?,?);", array($fieldTypeId,'vtiger_producttaxrel', 'taxid',"reference"));
$adb->pquery("INSERT INTO vtiger_ws_entity_referencetype(fieldtypeid,type) VALUES (?,?)",array($fieldTypeId,'Tax'));

$adb->pquery('UPDATE vtiger_users SET email1="noreply@vtiger.com" WHERE id=?',array(1));

if (!function_exists('PopulateReportFolder')) {
	// In case modules/Reports/PopulateReports.php is not included define the required functions

	function PopulateReportFolder($fldrname,$fldrdescription) {
		global $adb;
		$sql = "INSERT INTO vtiger_reportfolder (FOLDERNAME,DESCRIPTION,STATE) VALUES(?,?,?)";
		$params = array($fldrname, $fldrdescription, 'SAVED');
		$result = $adb->pquery($sql, $params);
	}

	function insertSelectQuery() {
		global $adb;
		$genQueryId = $adb->getUniqueID("vtiger_selectquery");
		if($genQueryId != "") {
			$iquerysql = "insert into vtiger_selectquery (QUERYID,STARTINDEX,NUMOFOBJECTS) values (?,?,?)";
			$iquerysqlresult = $adb->pquery($iquerysql, array($genQueryId,0,0));
		}
		return $genQueryId;
	}

	function insertSelectColumns($queryid,$columnname) {
		global $adb;
		if($queryid != "") {
			for($i=0;$i < count($columnname);$i++) {
				$icolumnsql = "insert into vtiger_selectcolumn (QUERYID,COLUMNINDEX,COLUMNNAME) values (?,?,?)";
				$icolumnsqlresult = $adb->pquery($icolumnsql, array($queryid, $i, $columnname[$i]));
			}
		}
	}

	function insertReports($queryid,$folderid,$reportname,$description,$reporttype) {
		global $adb;
		if($queryid != "") {
			$ireportsql = "insert into vtiger_report (REPORTID,FOLDERID,REPORTNAME,DESCRIPTION,REPORTTYPE,QUERYID,STATE) values (?,?,?,?,?,?,?)";
			$ireportparams = array($queryid, $folderid, $reportname, $description, $reporttype, $queryid, 'SAVED');
			$ireportresult = $adb->pquery($ireportsql, $ireportparams);
		}
	}

	function insertReportModules($queryid,$primarymodule,$secondarymodule) {
		global $adb;
		if($queryid != "") {
			$ireportmodulesql = "insert into vtiger_reportmodules (REPORTMODULESID,PRIMARYMODULE,SECONDARYMODULES) values (?,?,?)";
			$ireportmoduleresult = $adb->pquery($ireportmodulesql, array($queryid, $primarymodule, $secondarymodule));
		}
	}

	function insertAdvFilter($queryid,$filters) {
		global $adb;
		if($queryid != "") {
			$columnIndexArray = array();
			foreach($filters as $i=>$filter) {
				$irelcriteriasql = "insert into vtiger_relcriteria(QUERYID,COLUMNINDEX,COLUMNNAME,COMPARATOR,VALUE) values (?,?,?,?,?)";
				$irelcriteriaresult = $adb->pquery($irelcriteriasql, array($queryid,$i,$filter['columnname'],$filter['comparator'],$filter['value']));
				$columnIndexArray[] = $i;
			}
			$conditionExpression = implode(' and ', $columnIndexArray);
			$adb->pquery('INSERT INTO vtiger_relcriteria_grouping VALUES(?,?,?,?)', array(1, $queryid, '', $conditionExpression));
		}
	}
}

//--
//Changed Columns Display in List view of Leads
$leadsFirstName = 'vtiger_leaddetails:firstname:firstname:Leads_First_Name:V';
$leadsLastName = 'vtiger_leaddetails:lastname:lastname:Leads_Last_Name:V';
$adb->pquery("UPDATE vtiger_cvcolumnlist SET columnname=? WHERE cvid=? AND columnindex=?", array($leadsFirstName, '1', '1'));
$adb->pquery("UPDATE vtiger_cvcolumnlist SET columnname=? WHERE cvid=? AND columnindex=?", array($leadsLastName, '1', '2'));

//Changed the Currency Symbol of Moroccan, Dirham to DH
$adb->pquery("UPDATE vtiger_currencies SET currency_symbol=? WHERE currency_name=? AND currency_code=?", array('DH', 'Moroccan, Dirham', 'MAD'));

//Changing picklist values for sales stage of opportunities
$adb->pquery("UPDATE vtiger_sales_stage SET sales_stage=? WHERE sales_stage=?", array('Proposal or Price Quote', 'Proposal/Price Quote'));
$adb->pquery("UPDATE vtiger_sales_stage SET sales_stage=? WHERE sales_stage=?", array('Negotiation or Review', 'Negotiation/Review'));

//Updating the new picklist values of sales stage in opportunities for migration instances
$adb->pquery("UPDATE vtiger_potential SET sales_stage=? WHERE sales_stage=?", array('Proposal or Price Quote', 'Proposal/Price Quote'));
$adb->pquery("UPDATE vtiger_potential SET sales_stage=? WHERE sales_stage=?", array('Negotiation or Review', 'Negotiation/Review'));

//Updating Sales Stage History in opportunities related list for migration instances
$adb->pquery("UPDATE vtiger_potstagehistory SET stage=? WHERE stage=?", array('Proposal or Price Quote', 'Proposal/Price Quote'));
$adb->pquery("UPDATE vtiger_potstagehistory SET stage=? WHERE stage=?", array('Negotiation or Review', 'Negotiation/Review'));

//Updating the sales stage picklist values of opportunities in picklist dependency setup for migration instances
$adb->pquery("UPDATE vtiger_picklist_dependency SET sourcevalue=? WHERE sourcevalue=?", array('Proposal or Price Quote', 'Proposal/Price Quote'));
$adb->pquery("UPDATE vtiger_picklist_dependency SET sourcevalue=? WHERE sourcevalue=?", array('Negotiation or Review', 'Negotiation/Review'));

//Internationalized the description for webforms
$adb->pquery("UPDATE vtiger_settings_field SET description=? WHERE description=?", array('LBL_WEBFORMS_DESCRIPTION', 'Allows you to manage Webforms'));

$adb->pquery('CREATE TABLE IF NOT EXISTS vtiger_crmsetup(userid INT(11) NOT NULL, setup_status INT(2))', array());

$result = $adb->pquery('SELECT id FROM vtiger_users', array());
$num_rows = $adb->num_rows($result);

for ($i=0; $i<$num_rows; $i++) {
	$userid = $adb->query_result($result, $i, 'id');
	Users_CRMSetup::insertEntryIntoCRMSetup($userid);
}

$discountResult = $adb->pquery('SELECT * FROM vtiger_selectcolumn WHERE columnname LIKE "vtiger_inventoryproductrel:discount:%" ORDER BY columnindex', array());
$num_rows = $adb->num_rows($discountResult);

for ($i=0; $i<$num_rows; $i++) {
	$columnIndex = $adb->query_result($discountResult, $i, 'columnindex');
    $columnName = $adb->query_result($discountResult, $i, 'columnname');
    $queryId = $adb->query_result($discountResult, $i, 'queryid');
      
    $updatedColumnName = str_replace(':discount:', ':discount_amount:', $columnName);
    $updateQuery = 'UPDATE vtiger_selectcolumn SET columnname = ? WHERE columnindex = ? and queryid = ?';
    $updateParams = array($updatedColumnName, $columnIndex,$queryId);

	$adb->pquery($updateQuery, $updateParams);                                
}

//--
$adb->pquery('INSERT INTO vtiger_ws_referencetype VALUES (?,?)', array(31,'Campaigns'));
			
$moduleInstance = Vtiger_Module::getInstance('Users');
$currencyBlock = Vtiger_Block::getInstance('LBL_CURRENCY_CONFIGURATION', $moduleInstance);
$truncateTrailingZeros = new Vtiger_Field();

$truncateTrailingZeros->name = 'truncate_trailing_zeros';
$truncateTrailingZeros->label = 'Truncate Trailing Zeros';
$truncateTrailingZeros->table ='vtiger_users';
$truncateTrailingZeros->column = 'truncate_trailing_zeros';
$truncateTrailingZeros->columntype = 'varchar(3)';
$truncateTrailingZeros->typeofdata = 'V~O';
$truncateTrailingZeros->uitype = 56;
$truncateTrailingZeros->sequence = 7;
$truncateTrailingZeros->defaultvalue = 0;
$truncateTrailingZeros->helpinfo = "<b> Truncate Trailing Zeros </b> <br/><br/>".
    "It truncated trailing 0s in any of Currency, Decimal and Percentage Field types<br/><br/>".
    "<b>Ex:</b><br/>".
    "If value is 89.00000 then <br/>".
    "decimal and Percentage fields were shows 89<br/>".
    "currency field type - shows 89.00<br/>";
$currencyBlock->addField($truncateTrailingZeros);

$adb->pquery("ALTER TABLE vtiger_productcurrencyrel MODIFY COLUMN actual_price decimal(28,8)", array());
$adb->pquery("ALTER TABLE vtiger_productcurrencyrel MODIFY COLUMN converted_price decimal(28,8)", array());
$adb->pquery("ALTER TABLE vtiger_pricebookproductrel MODIFY COLUMN listprice decimal(27,8)", array());
$adb->pquery("ALTER TABLE vtiger_inventoryproductrel MODIFY COLUMN listprice decimal(27,8)", array());
$adb->pquery("ALTER TABLE vtiger_inventoryproductrel MODIFY COLUMN discount_amount decimal(27,8)", array());

$currencyField = new CurrencyField($value);
$result = $adb->pquery("SELECT tablename,columnname FROM vtiger_field WHERE uitype IN (?,?)",array('71','72'));
$count = $adb->num_rows($result);
for($i=0;$i<$count;$i++) {
	$tableName = $adb->query_result($result,$i,'tablename');
	$columnName = $adb->query_result($result,$i,'columnname');
	$adb->pquery("ALTER TABLE " .$tableName." MODIFY COLUMN ".$columnName." decimal(?,?)", array(25, 8));
}

$adb->pquery('DELETE FROM vtiger_no_of_currency_decimals WHERE no_of_currency_decimalsid=?', array(1));

$adb->pquery('UPDATE vtiger_field SET uitype=?, typeofdata=? WHERE fieldname=?',array(71, 'N~O', 'listprice'));

$adb->pquery('UPDATE vtiger_field SET typeofdata=? WHERE fieldname=?',array('N~O', 'quantity'));

//--
$adb->pquery('UPDATE vtiger_field SET typeofdata=?, uitype =?, fieldlabel=? WHERE fieldname =? and tablename=?', array('N~O', 71, 'Discount', 'discount_amount', 'vtiger_inventoryproductrel'));

//deleting default workflows
$adb->pquery("delete from com_vtiger_workflowtasks where task_id=?", array(11));
$adb->pquery("delete from com_vtiger_workflowtasks where task_id=?", array(12));

// Creating Default workflows
$workflowManager = new VTWorkflowManager($adb);
$taskManager = new VTTaskManager($adb);

// Events workflow when Send Notification is checked
$eventsWorkflow = $workflowManager->newWorkFlow("Events");
$eventsWorkflow->test = '[{"fieldname":"sendnotification","operation":"is","value":"true:boolean"}]';
$eventsWorkflow->description = "Workflow for Events when Send Notification is True";
$eventsWorkflow->executionCondition = VTWorkflowManager::$ON_EVERY_SAVE;
$eventsWorkflow->defaultworkflow = 1;
$workflowManager->save($eventsWorkflow);

$task = $taskManager->createTask('VTEmailTask', $eventsWorkflow->id);
$task->active = true;
$task->summary = 'Send Notification Email to Record Owner';
$task->recepient = "\$(assigned_user_id : (Users) email1)";
$task->subject = "Event :  \$subject";
$task->content = '$(assigned_user_id : (Users) last_name) $(assigned_user_id : (Users) first_name) ,<br/>'
        . '<b>Activity Notification Details:</b><br/>'
        . 'Subject             : $subject<br/>'
        . 'Start date and time : $date_start  $time_start ( $(general : (__VtigerMeta__) dbtimezone) ) <br/>'
        . 'End date and time   : $due_date  $time_end ( $(general : (__VtigerMeta__) dbtimezone) ) <br/>'
        . 'Status              : $eventstatus <br/>'
        . 'Priority            : $taskpriority <br/>'
        . 'Related To          : $(parent_id : (Leads) lastname) $(parent_id : (Leads) firstname) $(parent_id : (Accounts) accountname) '
                                . '$(parent_id : (Potentials) potentialname) $(parent_id : (HelpDesk) ticket_title)'
                                . ' $(parent_id : (Campaigns) campaignname) <br/>'
        . 'Contacts List       : $(contact_id : (Contacts) lastname) $(contact_id : (Contacts) firstname) <br/>'
        . 'Location            : $location <br/>'
        . 'Description         : $description';
$taskManager->saveTask($task);

// Calendar workflow when Send Notification is checked
$calendarWorkflow = $workflowManager->newWorkFlow("Calendar");
$calendarWorkflow->test = '[{"fieldname":"sendnotification","operation":"is","value":"true:boolean"}]';
$calendarWorkflow->description = "Workflow for Calendar Todos when Send Notification is True";
$calendarWorkflow->executionCondition = VTWorkflowManager::$ON_EVERY_SAVE;
$calendarWorkflow->defaultworkflow = 1;
$workflowManager->save($calendarWorkflow);

$task = $taskManager->createTask('VTEmailTask', $calendarWorkflow->id);
$task->active = true;
$task->summary = 'Send Notification Email to Record Owner';
$task->recepient = "\$(assigned_user_id : (Users) email1)";
$task->subject = "Task :  \$subject";
$task->content = '$(assigned_user_id : (Users) last_name) $(assigned_user_id : (Users) first_name) ,<br/>'
        . '<b>Task Notification Details:</b><br/>'
        . 'Subject : $subject<br/>'
        . 'Start date and time : $date_start  $time_start ( $(general : (__VtigerMeta__) dbtimezone) ) <br/>'
        . 'End date and time   : $due_date ( $(general : (__VtigerMeta__) dbtimezone) ) <br/>'
        . 'Status              : $taskstatus <br/>'
        . 'Priority            : $taskpriority <br/>'
        . 'Related To          : $(parent_id : (Leads) lastname) $(parent_id : (Leads) firstname) $(parent_id : (Accounts) accountname) '
                                . '$(parent_id : (Potentials) potentialname) $(parent_id : (HelpDesk) ticket_title)'
                                . ' $(parent_id : (Campaigns) campaignname) <br/>'
        . 'Contacts List       : $(contact_id : (Contacts) lastname) $(contact_id : (Contacts) firstname) <br/>'
        . 'Description         : $description';
$taskManager->saveTask($task);

global $current_user;
$adb = PearDatabase::getInstance();
$user = new Users();
$current_user = $user->retrieveCurrentUserInfoFromFile(Users::getActiveAdminId());

$allTabIdResult = $adb->pquery('SELECT tabid, name FROM vtiger_tab', array());
$noOfTabs = $adb->num_rows($allTabIdResult);
$allTabIds = array();
for($i=0; $i<$noOfTabs; ++$i) {
	$tabId = $adb->query_result($allTabIdResult, $i, 'tabid');
	$tabName = $adb->query_result($allTabIdResult, $i, 'name');
	$allTabIds[$tabName] = $tabId;
}

//Adding status field for project task

$moduleInstance = Vtiger_Module::getInstance('ProjectTask');
$blockInstance = Vtiger_Block::getInstance('LBL_PROJECT_TASK_INFORMATION', $moduleInstance);
$fieldInstance = new Vtiger_Field();
$fieldInstance->name = 'projecttaskstatus';
$fieldInstance->label = 'Status';
$fieldInstance->uitype = 15;
$fieldInstance->quickcreate = 0;
$blockInstance->addField($fieldInstance);

$pickListValues = array('--None--', 'Open', 'In Progress', 'Completed', 'Deferred', 'Canceled ');

$fieldInstance->setPicklistValues($pickListValues);

//Dashboard schema changes
Vtiger_Utils::CreateTable('vtiger_module_dashboard_widgets', '(id INT(19) NOT NULL AUTO_INCREMENT, linkid INT(19), userid INT(19), filterid INT(19),
				title VARCHAR(100), data VARCHAR(500) DEFAULT "[]", PRIMARY KEY(id))');
$potentials = Vtiger_Module::getInstance('Potentials');
$potentials->addLink('DASHBOARDWIDGET', 'History', 'index.php?module=Potentials&view=ShowWidget&name=History','', '1');
$potentials->addLink('DASHBOARDWIDGET', 'Upcoming Activities', 'index.php?module=Potentials&view=ShowWidget&name=CalendarActivities','', '2');
$potentials->addLink('DASHBOARDWIDGET', 'Funnel', 'index.php?module=Potentials&view=ShowWidget&name=GroupedBySalesStage','', '3');
$potentials->addLink('DASHBOARDWIDGET', 'Potentials by Stage', 'index.php?module=Potentials&view=ShowWidget&name=GroupedBySalesPerson','', '4');
$potentials->addLink('DASHBOARDWIDGET', 'Pipelined Amount', 'index.php?module=Potentials&view=ShowWidget&name=PipelinedAmountPerSalesPerson','', '5');
$potentials->addLink('DASHBOARDWIDGET', 'Total Revenue', 'index.php?module=Potentials&view=ShowWidget&name=TotalRevenuePerSalesPerson','', '6');
$potentials->addLink('DASHBOARDWIDGET', 'Top Potentials', 'index.php?module=Potentials&view=ShowWidget&name=TopPotentials','', '7');
//$potentials->addLink('DASHBOARDWIDGET', 'Forecast', 'index.php?module=Potentials&view=ShowWidget&name=Forecast','', '8');
$potentials->addLink('DASHBOARDWIDGET', 'Overdue Activities', 'index.php?module=Potentials&view=ShowWidget&name=OverdueActivities','', '9');

$accounts = Vtiger_Module::getInstance('Accounts');
$accounts->addLink('DASHBOARDWIDGET', 'History', 'index.php?module=Accounts&view=ShowWidget&name=History','', '1');
$accounts->addLink('DASHBOARDWIDGET', 'Upcoming Activities', 'index.php?module=Accounts&view=ShowWidget&name=CalendarActivities','', '2');
$accounts->addLink('DASHBOARDWIDGET', 'Overdue Activities', 'index.php?module=Accounts&view=ShowWidget&name=OverdueActivities','', '3');

$contacts = Vtiger_Module::getInstance('Contacts');
$contacts->addLink('DASHBOARDWIDGET', 'History', 'index.php?module=Contacts&view=ShowWidget&name=History','', '1');
$contacts->addLink('DASHBOARDWIDGET', 'Upcoming Activities', 'index.php?module=Contacts&view=ShowWidget&name=CalendarActivities','', '2');
$contacts->addLink('DASHBOARDWIDGET', 'Overdue Activities', 'index.php?module=Contacts&view=ShowWidget&name=OverdueActivities','', '3');

$leads = Vtiger_Module::getInstance('Leads');
$leads->addLink('DASHBOARDWIDGET', 'History', 'index.php?module=Leads&view=ShowWidget&name=History','', '1');
$leads->addLink('DASHBOARDWIDGET', 'Upcoming Activities', 'index.php?module=Leads&view=ShowWidget&name=CalendarActivities','', '2');
//$leads->addLink('DASHBOARDWIDGET', 'Leads Created', 'index.php?module=Leads&view=ShowWidget&name=LeadsCreated','', '3');
$leads->addLink('DASHBOARDWIDGET', 'Leads by Status', 'index.php?module=Leads&view=ShowWidget&name=LeadsByStatus','', '4');
$leads->addLink('DASHBOARDWIDGET', 'Leads by Source', 'index.php?module=Leads&view=ShowWidget&name=LeadsBySource','', '5');
$leads->addLink('DASHBOARDWIDGET', 'Leads by Industry', 'index.php?module=Leads&view=ShowWidget&name=LeadsByIndustry','', '6');
$leads->addLink('DASHBOARDWIDGET', 'Overdue Activities', 'index.php?module=Leads&view=ShowWidget&name=OverdueActivities','', '7');

$helpDesk = Vtiger_Module::getInstance('HelpDesk');
$helpDesk->addLink('DASHBOARDWIDGET', 'Tickets by Status', 'index.php?module=HelpDesk&view=ShowWidget&name=TicketsByStatus','', '1');
$helpDesk->addLink('DASHBOARDWIDGET', 'Open Ticktes', 'index.php?module=HelpDesk&view=ShowWidget&name=OpenTickets','', '2');

$home = Vtiger_Module::getInstance('Home');
$home->addLink('DASHBOARDWIDGET', 'History', 'index.php?module=Home&view=ShowWidget&name=History','', '1');
$home->addLink('DASHBOARDWIDGET', 'Upcoming Activities', 'index.php?module=Home&view=ShowWidget&name=CalendarActivities','', '2');
$home->addLink('DASHBOARDWIDGET', 'Funnel', 'index.php?module=Potentials&view=ShowWidget&name=GroupedBySalesStage','', '3');
$home->addLink('DASHBOARDWIDGET', 'Potentials by Stage', 'index.php?module=Potentials&view=ShowWidget&name=GroupedBySalesPerson','', '4');
$home->addLink('DASHBOARDWIDGET', 'Pipelined Amount', 'index.php?module=Potentials&view=ShowWidget&name=PipelinedAmountPerSalesPerson','', '5');
$home->addLink('DASHBOARDWIDGET', 'Total Revenue', 'index.php?module=Potentials&view=ShowWidget&name=TotalRevenuePerSalesPerson','', '6');
$home->addLink('DASHBOARDWIDGET', 'Top Potentials', 'index.php?module=Potentials&view=ShowWidget&name=TopPotentials','', '7');
//$home->addLink('DASHBOARDWIDGET', 'Forecast', 'index.php?module=Potentials&view=ShowWidget&name=Forecast','', '8');

//$home->addLink('DASHBOARDWIDGET', 'Leads Created', 'index.php?module=Leads&view=ShowWidget&name=LeadsCreated','', '9');
$home->addLink('DASHBOARDWIDGET', 'Leads by Status', 'index.php?module=Leads&view=ShowWidget&name=LeadsByStatus','', '10');
$home->addLink('DASHBOARDWIDGET', 'Leads by Source', 'index.php?module=Leads&view=ShowWidget&name=LeadsBySource','', '11');
$home->addLink('DASHBOARDWIDGET', 'Leads by Industry', 'index.php?module=Leads&view=ShowWidget&name=LeadsByIndustry','', '12');
$home->addLink('DASHBOARDWIDGET', 'Overdue Activities', 'index.php?module=Home&view=ShowWidget&name=OverdueActivities','', '13');

$home->addLink('DASHBOARDWIDGET', 'Tickets by Status', 'index.php?module=HelpDesk&view=ShowWidget&name=TicketsByStatus','', '13');
$home->addLink('DASHBOARDWIDGET', 'Open Ticktes', 'index.php?module=HelpDesk&view=ShowWidget&name=OpenTickets','', '14');

//Calendar and Events module clean up
$calendarTabId = getTabid('Calendar');
$eventTabId = getTabid('Events');
$adb->pquery('UPDATE vtiger_blocks SET blocklabel ="LBL_DESCRIPTION_INFORMATION" WHERE blockid=20',array());
$adb->pquery('UPDATE vtiger_field SET displaytype=1 WHERE fieldname="location" AND tabid = ?', array($calendarTabId));
$adb->pquery('UPDATE vtiger_field SET displaytype=1 WHERE fieldname="visibility" AND tabid = ?', array($eventTabId));

$eventBlockId = getBlockId($eventTabId, 'LBL_EVENT_INFORMATION');
$adb->pquery('UPDATE vtiger_field SET block = ? WHERE block = 41', array($eventBlockId));
$adb->pquery('UPDATE vtiger_blocks SET blocklabel = "LBL_REMINDER_INFORMATION", show_title = 0 WHERE blockid = 40',array());
$adb->pquery('UPDATE vtiger_blocks SET blocklabel = "LBL_DESCRIPTION_INFORMATION", show_title = 0 WHERE blockid = 41',array());
$adb->pquery('UPDATE vtiger_field SET block = 41 WHERE fieldname = "description" AND tabid = ?',array($eventTabId));
$adb->pquery('UPDATE vtiger_field SET block = ? WHERE fieldname = "contact_id" AND tabid = ?', array($eventBlockId, $eventTabId));
$adb->pquery('UPDATE vtiger_field SET displaytype = 3 WHERE fieldname = ? AND tabid = ?', array('notime', $eventTabId));
$adb->pquery('UPDATE vtiger_field SET displaytype = 3 WHERE fieldname = ? AND tabid = ?', array('duration_hours', $eventTabId));

$projectTabId = getTabid('Project');
$projectTaskTabId = getTabid('ProjectTask');
$projectMilestoneTabId = getTabid('ProjectMilestone');
$contactsTabId = getTabid('Contacts');
$accountsTabId = getTabid('Accounts');
$helpDeskTabId = getTabid('HelpDesk');

$adb->pquery('UPDATE vtiger_relatedlists SET actions=? WHERE tabid in(?,?) and related_tabid in (?,?,?)',
        array('add', $helpDeskTabId, $projectTabId, $calendarTabId, $projectTaskTabId,  $projectMilestoneTabId));

$adb->pquery('UPDATE vtiger_relatedlists SET actions=? WHERE tabid in(?, ?) and related_tabid in (?)',
        array('add', $contactsTabId, $accountsTabId, $projectTabId));

$adb->pquery('UPDATE vtiger_field SET presence = 1 WHERE tabid = ? AND fieldname = ?', array($helpDeskTabId, 'comments'));
$faqTabId = getTabid('Faq');
$adb->pquery('UPDATE vtiger_field SET presence = 1 WHERE tabid = ? AND fieldname = ?', array($faqTabId, 'comments'));

$adb->pquery('UPDATE vtiger_users SET truncate_trailing_zeros = ?', array(1));

//deleted the id column from the All filter
$adb->pquery("DELETE FROM vtiger_cvcolumnlist WHERE cvid IN
			(SELECT cvid FROM vtiger_customview WHERE viewname='All' AND entitytype NOT IN
				('Emails','Calendar','ModComments','ProjectMilestone','Project','SMSNotifier','PBXManager','Webmails'))
			AND columnindex = 0", array());

//--

// Collating all module package updates here
updateVtlibModule('FieldFormulas', "packages/vtiger/optional/FieldFormulas.zip");
updateVtlibModule('Import', 'packages/vtiger/mandatory/Import.zip');
updateVtlibModule('WSAPP', 'packages/vtiger/mandatory/WSAPP.zip');

updateVtlibModule('Services', "packages/vtiger/mandatory/Services.zip");
updateVtlibModule('ServiceContracts', "packages/vtiger/mandatory/ServiceContracts.zip");
updateVtlibModule('Assets', "packages/vtiger/optional/Assets.zip");
updateVtlibModule('ModComments', "packages/vtiger/optional/ModComments.zip");
updateVtlibModule('Projects', "packages/vtiger/optional/Projects.zip");
updateVtlibModule('SMSNotifier', "packages/vtiger/optional/SMSNotifier.zip");
updateVtlibModule('MailManager', "packages/vtiger/mandatory/MailManager.zip");
updateVtlibModule('Mobile', 'packages/vtiger/mandatory/Mobile.zip');
updateVtlibModule('CronTasks', 'packages/vtiger/optional/CronTasks.zip');
updateVtlibModule("Webforms","packages/vtiger/optional/Webforms.zip");
updateVtlibModule('ModTracker', 'packages/vtiger/mandatory/ModTracker.zip');
installVtlibModule('Google', 'packages/vtiger/optional/Google.zip');

// updated language packs.
updateVtlibModule('Dutch', 'packages/vtiger/optional/Dutch.zip');
updateVtlibModule('Hungarian', 'packages/vtiger/optional/Hungarian.zip');
updateVtlibModule('PT Brasil', 'packages/vtiger/optional/BrazilianLanguagePack_bz_bz.zip');
updateVtlibModule('British English', 'packages/vtiger/optional/BritishLanguagePack_br_br.zip');
updateVtlibModule('Deutsch', 'packages/vtiger/optional/Deutsch.zip');
updateVtlibModule('French', 'packages/vtiger/optional/French.zip');
updateVtlibModule('Italian', 'packages/vtiger/optional/ItalianLanguagePack_it_it.zip');
updateVtlibModule('Mexican Spanish', 'packages/vtiger/optional/MexicanSpanishLanguagePack_es_mx.zip');
updateVtlibModule('RomanianLanguagePack_rm_rm', 'packages/vtiger/optional/RomanianLanguagePack_rm_rm.zip');
updateVtlibModule('Spanish', 'packages/vtiger/optional/Spanish.zip');
updateVtlibModule('Turkce', 'packages/vtiger/optional/TurkishLanguagePack_tr_tr.zip');

//--

// Added indexes for Modtracker Module to improve performance
$adb->pquery('ALTER TABLE vtiger_modtracker_basic ADD INDEX crmidx (crmid)', array());
$adb->pquery('ALTER TABLE vtiger_modtracker_basic ADD INDEX idx (id)', array());
$adb->pquery('ALTER TABLE vtiger_modtracker_detail ADD INDEX idx (id)', array());
// Ends

require_once 'modules/com_vtiger_workflow/VTEntityMethodManager.inc';
$emm = new VTEntityMethodManager($adb);
$emm->addEntityMethod("ModComments","CustomerCommentFromPortal","modules/ModComments/ModCommentsHandler.php","CustomerCommentFromPortal");
$emm->addEntityMethod("ModComments","TicketOwnerComments","modules/ModComments/ModCommentsHandler.php","TicketOwnerComments");

require_once 'modules/com_vtiger_workflow/VTWorkflowManager.inc';
require_once 'modules/com_vtiger_workflow/VTTaskManager.inc';
$workflowManager = new VTWorkflowManager($adb);
$taskManager = new VTTaskManager($adb);

$commentsWorkflow = $workflowManager->newWorkFlow("ModComments");
$commentsWorkflow->test = '[{"fieldname":"related_to : (HelpDesk) ticket_title","operation":"is not empty","value":""}]';
$commentsWorkflow->description = "Workflow for comments on Tickets";
$commentsWorkflow->executionCondition = VTWorkflowManager::$ON_FIRST_SAVE;
$commentsWorkflow->defaultworkflow = 1;
$workflowManager->save($commentsWorkflow);

$task = $taskManager->createTask('VTEntityMethodTask', $commentsWorkflow->id);
$task->active = true;
$task->summary = 'Customer commented from Portal';
$task->methodName = "CustomerCommentFromPortal";
$taskManager->saveTask($task);

$task1 = $taskManager->createTask('VTEntityMethodTask', $commentsWorkflow->id);
$task1->active = true;
$task1->summary = 'Notify Customer when commenting on a Ticket';
$task1->methodName = "TicketOwnerComments";
$taskManager->saveTask($task1);
// Ends

$adb->pquery('ALTER TABLE vtiger_links MODIFY column linktype VARCHAR(50)', array());
$adb->pquery('ALTER TABLE vtiger_links MODIFY column linklabel VARCHAR(50)', array());
$adb->pquery('ALTER TABLE vtiger_links MODIFY column handler_class VARCHAR(50)', array());
$adb->pquery('ALTER TABLE vtiger_links MODIFY column handler VARCHAR(50)', array());
//--
//Add ModComments to HelpDesk and Faq module
require_once 'modules/ModComments/ModComments.php';
ModComments::addWidgetTo(array("HelpDesk", "Faq"));
global $current_user, $VTIGER_BULK_SAVE_MODE;
$VTIGER_BULK_SAVE_MODE = true;

$ticketComments = $adb->pquery('SELECT * FROM vtiger_ticketcomments', array());
$rows = $adb->num_rows($ticketComments);

$modComments = CRMEntity::getInstance('ModComments');
for($i=0; $i<$rows; $i++) {
	$modComments->column_fields['commentcontent'] = $adb->query_result($ticketComments, $i, 'comments');
	$ownerId = $adb->query_result($ticketComments, $i, 'ownerid');
	$current_user->id = $ownerId;
	$modComments->column_fields['assigned_user_id'] = $modComments->column_fields['creator'] = $ownerId;
	$modComments->column_fields['createdtime'] = $adb->query_result($ticketComments, $i, 'createdtime');
	$modComments->column_fields['modifiedtime'] = $adb->query_result($ticketComments, $i, 'createdtime');
	$modComments->column_fields['related_to'] = $adb->query_result($ticketComments, $i, 'ticketid');
	$modComments->save('ModComments');
	$adb->pquery('UPDATE vtiger_crmentity SET modifiedtime = ?, smcreatorid = ?, modifiedby = ? WHERE crmid = ?',
		array($modComments->column_fields['createdtime'], $ownerId, $ownerId, $modComments->id));
}


$faqComments = $adb->pquery('SELECT * FROM vtiger_faqcomments', array());
$rows = $adb->num_rows($faqComments);

for($i=0; $i<$rows; $i++) {
	$modComments->column_fields['commentcontent'] = $adb->query_result($faqComments, $i, 'comments');
	$modComments->column_fields['assigned_user_id'] = $modComments->column_fields['creator'] = '6';
	$modComments->column_fields['createdtime'] = $adb->query_result($faqComments, $i, 'createdtime');
	$modComments->column_fields['modifiedtime'] = $adb->query_result($faqComments, $i, 'createdtime');
	$modComments->column_fields['related_to'] = $adb->query_result($faqComments, $i, 'faqid');
	$modComments->save('ModComments');
	$adb->pquery('UPDATE vtiger_crmentity SET modifiedtime = ?, smcreatorid = ?, modifiedby = ? WHERE crmid = ?',
		array($modComments->column_fields['createdtime'], '6', '6', $modComments->id));
}
//--

// Added label column in vtiger_crmentity table for easier lookup - Also added Event handler to update the label on save of a record
$adb->pquery("ALTER TABLE vtiger_crmentity ADD COLUMN label varchar(255)", array());

// To avoid infinite-loop if we not able fix label for non-entity/special modules.
$lastMaxCRMId = 0;
do {
	$rs = $adb->pquery("SELECT crmid,setype FROM vtiger_crmentity WHERE label IS NULL AND crmid > ? LIMIT 500", array($lastMaxCRMId));
	if (!$adb->num_rows($rs)) {
		break;
	}
	while ($row = $adb->fetch_array($rs)) {
		/**
		 * TODO: Optimize underlying API to cache re-usable data, for speedy data.
		 */
		$labelInfo = getEntityName($row['setype'], array(intval($row['crmid'])));

		if ($labelInfo) {
			$label = $labelInfo[$row['crmid']];
			$adb->pquery('UPDATE vtiger_crmentity SET label=? WHERE crmid=? AND setype=?',
						array($label, $row['crmid'], $row['setype']));
		}

		if (intval($row['crmid']) > $lastMaxCRMId) {
			$lastMaxCRMId = intval($row['crmid']);
		}
	}
	$rs = null;
	unset($rs);
} while(true);

$adb->pquery('CREATE INDEX vtiger_crmentity_labelidx ON vtiger_crmentity(label)', array());

$homeModule = Vtiger_Module::getInstance('Home');
Vtiger_Event::register($homeModule, 'vtiger.entity.aftersave', 'Vtiger_RecordLabelUpdater_Handler', 'vtiger6/modules/Vtiger/handlers/RecordLabelUpdater.php');
// Ends here
//--
$moduleInstance = Vtiger_Module::getInstance('ModComments');
$customer = Vtiger_Field::getInstance('customer', $moduleInstance);
if (!$customer) {
	$customer = new Vtiger_Field();
	$customer->name = 'customer';
	$customer->label = 'Customer';
	$customer->uitype = '10';
	$customer->displaytype = '3';
	$blockInstance = Vtiger_Block::getInstance('LBL_MODCOMMENTS_INFORMATION', $moduleInstance);
	$blockInstance->addField($customer);
	$customer->setRelatedModules(array('Contacts'));
}

$moduleInstance = Vtiger_Module::getInstance('Potentials');
$filter = Vtiger_Filter::getInstance('All', $moduleInstance);
$fieldInstance = Vtiger_Field::getInstance('amount', $moduleInstance);
$filter->addField($fieldInstance,6);


if(file_exists('modules/ModTracker/ModTrackerUtils.php')) {
	require_once 'modules/ModTracker/ModTrackerUtils.php';
	$modules = $adb->pquery('SELECT * FROM vtiger_tab WHERE isentitytype = 1', array());
	$rows = $adb->num_rows($modules);
	for($i=0; $i<$rows; $i++) {
		$tabid=$adb->query_result($modules, $i, 'tabid');
		ModTrackerUtils::modTrac_changeModuleVisibility($tabid, 'module_enable');
	}
}	

$operationId = vtws_addWebserviceOperation('retrieve_inventory', 'include/Webservices/LineItem/RetrieveInventory.php', 'vtws_retrieve_inventory', 'GET');
vtws_addWebserviceOperationParam($operationId, 'id', 'String', 1);

$moduleInstance = Vtiger_Module::getInstance('Events');
$tabId = getTabid('Events');

// Update/Increment the sequence for the succeeding blocks of Events module, with starting sequence 3
$adb->pquery('UPDATE vtiger_blocks SET sequence = sequence+1 WHERE tabid=? AND sequence >= 3',
											array($tabId));

// Create Recurrence Information block
$recurrenceBlock = new Vtiger_Block();
$recurrenceBlock->label = 'LBL_RECURRENCE_INFORMATION';
$recurrenceBlock->sequence = 3;
$moduleInstance->addBlock($recurrenceBlock);

$blockId = getBlockId($tabId, 'LBL_RECURRENCE_INFORMATION');
$adb->pquery('UPDATE vtiger_field SET block=? WHERE fieldname=? and tabid=?', array($blockId, 'recurringtype', $tabId));

// Update/Increment the sequence for the succeeding blocks of Users module, with starting sequence 2
$moduleInstance = Vtiger_Module::getInstance('Users');
$tabId = getTabid('Users');
$adb->pquery('UPDATE vtiger_blocks SET sequence = sequence+1 WHERE tabid=? AND sequence >= 2', array($tabId));

// Create Calendar Settings block 
$calendarSettings = new Vtiger_Block();
$calendarSettings->label = 'LBL_CALENDAR_SETTINGS';
$calendarSettings->sequence = 2;
$moduleInstance->addBlock($calendarSettings);

$calendarSettings = Vtiger_Block::getInstance('LBL_CALENDAR_SETTINGS', $moduleInstance);

$dayOfTheWeek = new Vtiger_Field();
$dayOfTheWeek->name = 'dayoftheweek';
$dayOfTheWeek->label = 'Starting Day of the week';
$dayOfTheWeek->table ='vtiger_users';
$dayOfTheWeek->column = 'dayoftheweek';
$dayOfTheWeek->columntype = 'varchar(100)';
$dayOfTheWeek->typeofdata = 'V~O';
$dayOfTheWeek->uitype = 16;
$dayOfTheWeek->sequence = 2;
$dayOfTheWeek->defaultvalue = 'Sunday';
$calendarSettings->addField($dayOfTheWeek);
$dayOfTheWeek->setPicklistValues(array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'));

$defaultCallDuration = new Vtiger_Field();
$defaultCallDuration->name = 'callduration';
$defaultCallDuration->label = 'Default Call Duration';
$defaultCallDuration->table ='vtiger_users';
$defaultCallDuration->column = 'callduration';
$defaultCallDuration->columntype = 'varchar(100)';
$defaultCallDuration->typeofdata = 'V~O';
$defaultCallDuration->uitype = 16;
$defaultCallDuration->sequence = 3;
$defaultCallDuration->defaultvalue = 5;
$calendarSettings->addField($defaultCallDuration);
$defaultCallDuration->setPicklistValues(array('5','10','30','60','120'));

$otherEventDuration = new Vtiger_Field();
$otherEventDuration->name = 'othereventduration';
$otherEventDuration->label = 'Other Event Duration';
$otherEventDuration->table ='vtiger_users';
$otherEventDuration->column = 'othereventduration';
$otherEventDuration->columntype = 'varchar(100)';
$otherEventDuration->typeofdata = 'V~O';
$otherEventDuration->uitype = 16;
$otherEventDuration->sequence = 4;
$otherEventDuration->defaultvalue = 5;
$calendarSettings->addField($otherEventDuration);
$otherEventDuration->setPicklistValues(array('5','10','30','60','120'));

$blockId = getBlockId($tabId, 'LBL_CALENDAR_SETTINGS');
$sql = 'UPDATE vtiger_field SET block = ? , displaytype = ? WHERE tabid = ? AND tablename = ? AND columnname in (?,?,?,?,?,?)';
$adb->pquery($sql, array($blockId, 1, $tabId, 'vtiger_users', 'time_zone','activity_view','reminder_interval','date_format','start_hour', 'hour_format'));

$adb->pquery('UPDATE vtiger_field SET uitype = ? WHERE tabid = ? AND tablename = ? AND columnname in (?,?)',
		array(16, $tabId, 'vtiger_users', 'hour_format', 'start_hour'));

$fieldid = getFieldid($tabId, 'hour_format');
$hour_format = Vtiger_Field::getInstance($fieldid, $moduleInstance);
$hour_format->setPicklistValues(array(12,24));

$fieldid = getFieldid($tabId, 'start_hour');
$start_hour = Vtiger_Field::getInstance($fieldid, $moduleInstance);
$start_hour->setPicklistValues(array('00:00','01:00','02:00','03:00','04:00','05:00','06:00','07:00','08:00','09:00','10:00','11:00'
								,'12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00','21:00','22:00','23:00'));
					
//update hour_format value in existing customers
$adb->pquery('UPDATE vtiger_users SET hour_format = ? WHERE hour_format = ? OR hour_format = ?', array(12, 'am/pm', ''));

//add user default values			
$adb->pquery('UPDATE vtiger_users SET dayoftheweek = ?, callduration = ?, othereventduration = ?, start_hour = ? ', array('Sunday', 5, 5, '00:00'));

$moduleInstance = Vtiger_Module::getInstance('Events');
$tabId = getTabid('Events');

// Update/Increment the sequence for the succeeding blocks of Events module, with starting sequence 4
$adb->pquery('UPDATE vtiger_blocks SET sequence = sequence+1 WHERE tabid=? AND sequence >= 4', array($tabId));

// Create Recurrence Information block
$recurrenceBlock = new Vtiger_Block();
$recurrenceBlock->label = 'LBL_RELATED_TO';
$recurrenceBlock->sequence = 4;
$moduleInstance->addBlock($recurrenceBlock);

$blockId = getBlockId($tabId, 'LBL_RELATED_TO');

$adb->pquery('UPDATE vtiger_field SET block=? WHERE fieldname IN (?,?) and tabid=?', array($blockId, 'contact_id','parent_id', $tabId));
$adb->pquery('UPDATE vtiger_field SET displaytype=1 WHERE fieldname=? and tabid=?',array('recurringtype',$tabId));

// END 2012.12.02

// //////////////////////////////////////////////
$inventoryModules = array(
    'Invoice' => array('LBL_INVOICE_INFORMATION', 'vtiger_invoice', 'invoiceid'),
    'SalesOrder' => array('LBL_SO_INFORMATION', 'vtiger_salesorder', 'salesorderid'),
    'PurchaseOrder' => array('LBL_PO_INFORMATION', 'vtiger_purchaseorder', 'purchaseorderid'),
    'Quotes' => array('LBL_QUOTE_INFORMATION', 'vtiger_quotes', 'quoteid')
);

foreach ($inventoryModules as $module => $details) {
    $tableName = $details[1];
    $moduleInstance = Vtiger_Module::getInstance($module);
    $block = Vtiger_Block::getInstance($details[0], $moduleInstance);

    $preTaxTotalField = new Vtiger_Field();
    $preTaxTotalField->name = 'pre_tax_total';
    $preTaxTotalField->label = 'Pre Tax Total';
    $preTaxTotalField->table = $tableName;
    $preTaxTotalField->column = 'pre_tax_total';
    $preTaxTotalField->columntype = 'decimal(25,8)';
    $preTaxTotalField->typeofdata = 'N~O';
    $preTaxTotalField->uitype = '72';
    $preTaxTotalField->masseditable = '1';
    $preTaxTotalField->displaytype = '3';
    $block->addField($preTaxTotalField);

    $tableId = $details[2];

    $result = $adb->pquery("SELECT $tableId, subtotal, s_h_amount, discount_percent, discount_amount FROM $tableName", array());
    $numOfRows = $adb->num_rows($result);

    for ($i = 0; $i < $numOfRows; $i++) {
        $id = $adb->query_result($result, $i, $tableId);
        $subTotal = (float) $adb->query_result($result, $i, "subtotal");
        $shAmount = (float) $adb->query_result($result, $i, "s_h_amount");
        $discountAmount = (float) $adb->query_result($result, $i, "discount_amount");
        $discountPercent = (float) $adb->query_result($result, $i, "discount_percent");

        if ($discountPercent != '0') {
            $discountAmount = ($subTotal * $discountPercent) / 100;
        }
        $preTaxTotalValue = $subTotal + $shAmount - $discountAmount;

        $adb->pquery("UPDATE $tableName set pre_tax_total = ? WHERE $tableId = ?", array($preTaxTotalValue, $id));
    }
}

$moduleInstance = Vtiger_Module::getInstance('Users');

$calendarSettings = Vtiger_Block::getInstance('LBL_CALENDAR_SETTINGS', $moduleInstance);
$calendarsharedtype = new Vtiger_Field();
$calendarsharedtype->name = 'calendarsharedtype';
$calendarsharedtype->label = 'Calendar Shared Type';
$calendarsharedtype->table ='vtiger_users';
$calendarsharedtype->column = 'calendarsharedtype';
$calendarsharedtype->columntype = 'varchar(100)';
$calendarsharedtype->typeofdata = 'V~O';
$calendarsharedtype->uitype = 16;
$calendarsharedtype->sequence = 2;
$calendarsharedtype->displaytype = 3;
$calendarsharedtype->defaultvalue = 'Public';
$calendarSettings->addField($calendarsharedtype);
$calendarsharedtype->setPicklistValues(array('public','private','seletedusers'));
echo "<br> Calendar Shared type field added <br>";

$allUsers = get_user_array(false);
foreach ($allUsers as $id => $name) {
    $query = 'select sharedid from vtiger_sharedcalendar where userid=?';
    $result = $adb->pquery($query, array($id));
	$count = $adb->num_rows($result);
    if($count > 0){
		$adb->pquery('UPDATE vtiger_users SET calendarsharedtype = ? WHERE id = ?', array('selectedusers', $id));
    }else{
		$adb->pquery('UPDATE vtiger_users SET calendarsharedtype = ? WHERE id = ? ', array('public', $id));
        foreach ($allUsers as $sharedid => $name) {
            if($sharedid != $id){
                $sql = "INSERT INTO vtiger_sharedcalendar VALUES (?,?)";
                $adb->pquery($sql, array($id, $sharedid));
            }
        }
    }
}

// Add Key Metrics widget.
$homeModule = Vtiger_Module::getInstance('Home');
$homeModule->addLink('DASHBOARDWIDGET', 'Key Metrics', 'index.php?module=Home&view=ShowWidget&name=KeyMetrics');

$moduleArray = array('Accounts' => 'LBL_ACCOUNT_INFORMATION', 'Contacts' => 'LBL_CONTACT_INFORMATION', 'Potentials' => 'LBL_OPPORTUNITY_INFORMATION');
foreach ($moduleArray as $module => $block) {
    $moduleInstance = Vtiger_Module::getInstance($module);
    $blockInstance = Vtiger_Block::getInstance($block, $moduleInstance);

    $field = new Vtiger_Field();
    $field->name = 'isconvertedfromlead';
    $field->label = 'Is Converted From Lead';
    $field->uitype = 56;
    $field->column = 'isconvertedfromlead';
    $field->displaytype = 2;
    $field->defaultvalue = 'no';
    $field->columntype = 'varchar(3)';
    $field->typeofdata = 'C~O';
    $blockInstance->addField($field);
    echo "Converted From field added to $module  <br/>";
}

$homeModule = Vtiger_Module::getInstance('Home');
$homeModule->addLink('DASHBOARDWIDGET', 'Mini List', 'index.php?module=Home&view=ShowWidget&name=MiniList');

$moduleInstance = Vtiger_Module::getInstance('Users');
$moreInfoBlock = Vtiger_Block::getInstance('LBL_MORE_INFORMATION', $moduleInstance);

$viewField = new Vtiger_Field();
$viewField->name = 'default_record_view';
$viewField->label = 'Default Record View';
$viewField->table ='vtiger_users';
$viewField->column = 'default_record_view';
$viewField->columntype = 'VARCHAR(10)';
$viewField->typeofdata = 'V~O';
$viewField->uitype = 16;
$viewField->defaultvalue = 'Summary';

$moreInfoBlock->addField($viewField);
$viewField->setPicklistValues(array('Summary', 'Detail'));

$adb->pquery('UPDATE vtiger_users SET default_record_view = ?', array('Summary'));
echo "Default Record View option is added User preferences";


$InvoiceInstance = Vtiger_Module::getInstance('Invoice');
Vtiger_Event::register($InvoiceInstance, 'vtiger.entity.aftersave', 'InvoiceHandler', 'modules/Invoice/InvoiceHandler.php');

$POInstance = Vtiger_Module::getInstance('PurchaseOrder');
Vtiger_Event::register($POInstance, 'vtiger.entity.aftersave', 'PurchaseOrderHandler', 'modules/PurchaseOrder/PurchaseOrderHandler.php');
            
$InvoiceBlockInstance = Vtiger_Block::getInstance('LBL_INVOICE_INFORMATION', $InvoiceInstance);
$field1 = Vtiger_Field::getInstance('received', $InvoiceInstance);
if (!$field1) {
    $field1 = new Vtiger_Field();
    $field1->name = 'received';
    $field1->label = 'Received';
    $field1->table = 'vtiger_invoice';
    $field1->uitype = 72;
    $field1->displaytype = 3;
    $field1->typeofdata = 'N~O';
    $field1->defaultvalue = 0;
    $InvoiceBlockInstance->addField($field1);
}
$field2 = Vtiger_Field::getInstance('balance', $InvoiceInstance);
if (!$field2) {
    $field2 = new Vtiger_Field();
    $field2->name = 'balance';
    $field2->label = 'Balance';
    $field1->table = 'vtiger_invoice';
    $field2->uitype = 72;
    $field2->typeofdata = 'N~O';
    $field2->defaultvalue = 0;
    $field2->displaytype = 3;
    $InvoiceBlockInstance->addField($field2);
}

$POBlockInstance = Vtiger_Block::getInstance('LBL_PO_INFORMATION', $POInstance);
$field3 = Vtiger_Field::getInstance('paid', $POInstance);
if (!$field3) {
    $field3 = new Vtiger_Field();
    $field3->name = 'paid';
    $field3->label = 'Paid';
    $field3->table = 'vtiger_purchaseorder';
    $field3->uitype = 72;
    $field3->displaytype = 3;
    $field3->typeofdata = 'N~O';
    $field3->defaultvalue = 0;
    $POBlockInstance->addField($field3);
}
$field4 = Vtiger_Field::getInstance('balance', $POInstance);
if (!$field4) {
    $field4 = new Vtiger_Field();
    $field4->name = 'balance';
    $field4->label = 'Balance';
    $field4->table = 'vtiger_purchaseorder';
    $field4->uitype = 72;
    $field4->typeofdata = 'N~O';
    $field4->defaultvalue = 0;
    $field4->displaytype = 3;
    $POBlockInstance->addField($field4);
}
            

$sqltimelogTable = "CREATE TABLE vtiger_sqltimelog ( id integer, type VARCHAR(10),
					data text, started decimal(18,2), ended decimal(18,2), loggedon datetime)";

$adb->pquery($sqltimelogTable, array());

$moduleName = 'PurchaseOrder';
$emm = new VTEntityMethodManager($adb);
$emm->addEntityMethod($moduleName,"UpdateInventory","include/InventoryHandler.php","handleInventoryProductRel");

$vtWorkFlow = new VTWorkflowManager($adb);
$poWorkFlow = $vtWorkFlow->newWorkFlow($moduleName);
$poWorkFlow->description = "Update Inventory Products On Every Save";
$poWorkFlow->defaultworkflow = 1;
$poWorkFlow->executionCondition = 3;
$vtWorkFlow->save($poWorkFlow);

$tm = new VTTaskManager($adb);
$task = $tm->createTask('VTEntityMethodTask', $poWorkFlow->id);
$task->active = true;
$task->summary = "Update Inventory Products";
$task->methodName = "UpdateInventory";
$tm->saveTask($task);

echo "Workflow for Purchase Order Created";

// Add Tag Cloud widget.
$homeModule = Vtiger_Module::getInstance('Home');
$homeModule->addLink('DASHBOARDWIDGET', 'Tag Cloud', 'index.php?module=Home&view=ShowWidget&name=TagCloud');
echo "successfully added tag cloud widget";

// Schema changed for capturing Dashboard widget positions
$adb->pquery('ALTER TABLE vtiger_module_dashboard_widgets ADD COLUMN position VARCHAR(50)',array());
            
$moduleInstance = Vtiger_Module::getInstance('Contacts');
if($moduleInstance) {
	$moduleInstance->addLink('LISTVIEWSIDEBARWIDGET','Google Contacts',
		'module=Google&view=List&sourcemodule=Contacts', '','', '');
}

$moduleInstance = Vtiger_Module::getInstance('Calendar');
if($moduleInstance) {
	$moduleInstance->addLink('LISTVIEWSIDEBARWIDGET','Google Calendar',
		'module=Google&view=List&sourcemodule=Calendar', '','', '');
}

$adb->pquery('ALTER TABLE vtiger_cvadvfilter MODIFY comparator VARCHAR(20)', array());
$adb->pquery('UPDATE vtiger_cvadvfilter SET comparator = ? WHERE comparator = ?', array('next120days', 'next120day'));
$adb->pquery('UPDATE vtiger_cvadvfilter SET comparator = ? WHERE comparator = ?', array('last120days', 'last120day'));

$adb->pquery("UPDATE vtiger_relatedlists SET actions = ? WHERE tabid = ? AND related_tabid IN (?, ?)",
	array('ADD', getTabid('Project'), getTabid('ProjectTask'), getTabid('ProjectMilestone')));

$adb->pquery("UPDATE vtiger_field SET typeofdata = ? WHERE columnname = ? AND tablename = ?", array("V~O", "company", "vtiger_leaddetails"));

if(Vtiger_Utils::CheckTable('vtiger_cron_task')) {
	$adb->pquery('ALTER TABLE vtiger_cron_task MODIFY COLUMN laststart INT(11) UNSIGNED',Array());
	$adb->pquery('ALTER TABLE vtiger_cron_task MODIFY COLUMN lastend INT(11) UNSIGNED',Array());
}

if(Vtiger_Utils::CheckTable('vtiger_cron_log')) {
	$adb->pquery('ALTER TABLE vtiger_cron_log MODIFY COLUMN start INT(11) UNSIGNED',Array());
   	$adb->pquery('ALTER TABLE vtiger_cron_log MODIFY COLUMN end INT(11) UNSIGNED',Array());
}

require_once 'vtlib/Vtiger/Cron.php';
Vtiger_Cron::deregister('ScheduleReports');

// END 2013.02.18

// Start 2013.03.19 (odu24)

// Mail Converter schema changes
$adb->pquery('ALTER TABLE vtiger_mailscanner ADD COLUMN timezone VARCHAR(10) default NULL', array());
$adb->pquery('UPDATE vtiger_mailscanner SET timezone=? WHERE server LIKE ? AND timezone IS NULL', array('-8:00', '%.gmail.com'));

$adb->pquery('UPDATE vtiger_report SET state=?', array('CUSTOM'));

$adb->pquery("ALTER TABLE vtiger_relcriteria MODIFY value VARCHAR(512)", array());
$adb->pquery("ALTER TABLE vtiger_cvadvfilter MODIFY value VARCHAR(512)", array());

//  End 2013.03.19 (odu24)
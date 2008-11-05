<?php
$Vtiger_Utils_Log = true;
include_once('vtlib/Vtiger/Utils.php');
include_once('vtlib/Vtiger/Field.php');
include_once('vtlib/Vtiger/CustomView.php');
include_once('vtlib/Vtiger/Tab.php');
include_once('vtlib/Vtiger/Block.php');

Vtiger_Tab::create('Payslip', 'Payslip', 'Tools');          // include/language/en_us.lang.php entry needed
Vtiger_Block::create('Payslip', 'LBL_PAYSLIP_INFORMATION'); // modules/Payslip/language/en_us.lang.php entry needed
Vtiger_Block::create('Payslip', 'LBL_CUSTOM_INFORMATION');  // Mandatory block to support custom fields (need entry in module language file)

Vtiger_Utils::CreateTable('vtiger_payslip', '(payslipid integer)');
// Create mandatory picklist table for module, with the format below
// Tablename = (Lowercase(<MODULENAME>) + 'cf') and primary key column
// NOTE: Make sure to update $tab_name and $tab_name_index Array in your Module Class File.
Vtiger_Utils::CreateTable('vtiger_payslipcf', '(payslipid integer, primary key (payslipid))');

// NOTE: Make sure to update $tab_name and $tab_name_index Array in your Module Class File.
Vtiger_Utils::CreateTable('vtiger_payslipgrouprel', 
	'(payslipid integer, groupname varchar(100), primary key(payslipid))');

// Create Name field
$field1 = new Vtiger_Field();
$field1-> set('module',        'Payslip')
	-> set('columnname',    'payslipname')
	-> set('tablename',     'vtiger_payslip')
	-> set('columntype',    'varchar(255)')
	-> set('generatedtype', '1')
	-> set('uitype',        2)
	-> set('fieldname',     'payslipname')
	-> set('fieldlabel',    'PayslipName') // modules/Payslip/language/en_us.lang.php entry needed
	-> set('readonly',       '1')
	-> set('presence',       '0')
	-> set('selected',       '0')
	-> set('maximumlength',  '100')
	-> set('sequence',       null)
	-> set('typeofdata',     'V~M')
	-> set('quickcreate',    '1')
	-> set('block',          null)
	-> set('blocklabel',     'LBL_PAYSLIP_INFORMATION')
	-> set('displaytype',    '1')
	-> set('quickcreatesequence', null)
	-> set('info_type',      'BAS');
$field1->create();

// Module should have atleast one field set as an identifier
$field1->set('entityidfield', 'payslipname')->set('entityidcolumn', 'payslipid');
$field1->setEntityIdentifier();

// Create Payslip Type field
$field2 = new Vtiger_Field();
$field2-> set('module',        'Payslip')
	-> set('columnname',    'paysliptype')
	-> set('tablename',     'vtiger_payslip') // NOTE: For picklist uitype this should point to module base table.
	-> set('columntype',    'varchar(255)')
	-> set('generatedtype', '1')
	-> set('uitype',        15)
	-> set('fieldname',     'paysliptype')
	-> set('fieldlabel',    'Payslip Type') // modules/Payslip/language/en_us.lang.php entry needed
	-> set('readonly',       '1')
	-> set('presence',       '0')
	-> set('selected',       '0')
	-> set('maximumlength',  '100')
	-> set('sequence',       null)
	-> set('typeofdata',     'V~O')
	-> set('quickcreate',    '1')
	-> set('block',          null)
	-> set('blocklabel',     'LBL_PAYSLIP_INFORMATION')
	-> set('displaytype',    '1')
	-> set('quickcreatesequence', null)
	-> set('info_type',      'BAS');
$field2->create();

$field2->setupPicklistValues( Array ('Employee', 'Trainee') );

// Create Date field
$field3 = new Vtiger_Field();
$field3-> set('module',        'Payslip')
	-> set('columnname',    'payslipmonth')
	-> set('tablename',     'vtiger_payslip')
	-> set('columntype',    'date')
	-> set('generatedtype', '1')
	-> set('uitype',        23)
	-> set('fieldname',     'payslipmonth')
	-> set('fieldlabel',    'Month') // modules/Payslip/language/en_us.lang.php entry needed
	-> set('readonly',       '1')
	-> set('presence',       '0')
	-> set('selected',       '0')
	-> set('maximumlength',  '100')
	-> set('sequence',       null)
	-> set('typeofdata',     'D~M')
	-> set('quickcreate',    '1')
	-> set('block',          null)
	-> set('blocklabel',     'LBL_PAYSLIP_INFORMATION')
	-> set('displaytype',    '1')
	-> set('quickcreatesequence', null)
	-> set('info_type',      'BAS');
$field3->create();

// Create Assigned To field
$field4 = new Vtiger_Field();
$field4-> set('module',        'Payslip')
	-> set('columnname',    'smownerid')
	-> set('tablename',     'vtiger_crmentity')
	//-> set('columntype',    'int')
	-> set('generatedtype', '1')
	-> set('uitype',        53)
	-> set('fieldname',     'assigned_user_id')
	-> set('fieldlabel',    'Assigned To') // modules/Payslip/language/en_us.lang.php entry needed
	-> set('readonly',       '1')
	-> set('presence',       '0')
	-> set('selected',       '0')
	-> set('maximumlength',  '100')
	-> set('sequence',       null)
	-> set('typeofdata',     'V~M')
	-> set('quickcreate',    '1')
	-> set('block',          null)
	-> set('blocklabel',     'LBL_PAYSLIP_INFORMATION')
	-> set('displaytype',    '1')
	-> set('quickcreatesequence', null)
	-> set('info_type',      'BAS');
$field4->create();

// Create Created Time field
$field5 = new Vtiger_Field();
$field5-> set('module',        'Payslip')
	-> set('columnname',    'createdtime')
	-> set('tablename',     'vtiger_crmentity')
	//-> set('columntype',    'int')
	-> set('generatedtype', '1')
	-> set('uitype',        70)
	-> set('fieldname',     'createdtime')
	-> set('fieldlabel',    'Created Time') // modules/Payslip/language/en_us.lang.php entry needed
	-> set('readonly',       '1')
	-> set('presence',       '0')
	-> set('selected',       '0')
	-> set('maximumlength',  '100')
	-> set('sequence',       null)
	-> set('typeofdata',     'T~O')
	-> set('quickcreate',    '1')
	-> set('block',          null)
	-> set('blocklabel',     'LBL_PAYSLIP_INFORMATION')
	-> set('displaytype',    '2')
	-> set('quickcreatesequence', null)
	-> set('info_type',      'BAS');
$field5->create();

// Create Modified Time field
$field6 = new Vtiger_Field();
$field6-> set('module',        'Payslip')
	-> set('columnname',    'modifiedtime')
	-> set('tablename',     'vtiger_crmentity')
	//-> set('columntype',    'int')
	-> set('generatedtype', '1')
	-> set('uitype',        70)
	-> set('fieldname',     'modifiedtime') // modules/Payslip/language/en_us.lang.php entry needed
	-> set('fieldlabel',    'Modified Time')
	-> set('readonly',       '1')
	-> set('presence',       '0')
	-> set('selected',       '0')
	-> set('maximumlength',  '100')
	-> set('sequence',       null)
	-> set('typeofdata',     'T~O')
	-> set('quickcreate',    '1')
	-> set('block',          null)
	-> set('blocklabel',     'LBL_PAYSLIP_INFORMATION')
	-> set('displaytype',    '2')
	-> set('quickcreatesequence', null)
	-> set('info_type',      'BAS');
$field6->create();

// Custom View
Vtiger_CustomView::create('Payslip', 'All',true);
$cv = new Vtiger_CustomView('Payslip', 'All');
$cv->addColumn($field1)
	->addColumn($field3, 1)
	->addColumn($field4, 2)
	->addColumn($field6, 3);

Vtiger_CustomView::create('Payslip', 'All2');
$cv = new Vtiger_CustomView('Payslip', 'All2');
$cv->addColumn($field1)
	->addColumn($field2, 1)
	->addColumn($field5, 3)
	->addColumn($field3, 2);

// Enable Import and Export
Vtiger_Module::disableAction('Payslip','Import');
Vtiger_Module::enableAction('Payslip', 'Export');

Vtiger_Module::setDefaultSharingAccess('Payslip', 'Private');

?>

<?php

include_once('vtlib/Vtiger/Utils.php');
include_once('vtlib/Vtiger/Field.php');

Vtiger_Utils::CreateTable('vtiger_payslip', '(payslipid integer)');

// Create Name field
$field1 = new Vtiger_Field();
$field1-> set('module',        'Payslip')
	-> set('columnname',    'payslipname')
	-> set('tablename',     'vtiger_payslip')
	-> set('columntype',    'varchar(255)')
	-> set('generatedtype', '1')
	-> set('uitype',        2)
	-> set('fieldname',     'payslipname')
	-> set('fieldlabel',    'PayslipName')
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

// Create Date field
$field2 = new Vtiger_Field();
$field2-> set('module',        'Payslip')
	-> set('columnname',    'payslipmonth')
	-> set('tablename',     'vtiger_payslip')
	-> set('columntype',    'date')
	-> set('generatedtype', '1')
	-> set('uitype',        23)
	-> set('fieldname',     'payslipmonth')
	-> set('fieldlabel',    'Month')
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
$field2->create();

// Create Assigned To field
$field3 = new Vtiger_Field();
$field3-> set('module',        'Payslip')
	-> set('columnname',    'smownerid')
	-> set('tablename',     'vtiger_crmentity')
	//-> set('columntype',    'int')
	-> set('generatedtype', '1')
	-> set('uitype',        53)
	-> set('fieldname',     'assigned_user_id')
	-> set('fieldlabel',    'Assigned To')
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
$field3->create();

// Create Created Time field
$field4 = new Vtiger_Field();
$field4-> set('module',        'Payslip')
	-> set('columnname',    'createdtime')
	-> set('tablename',     'vtiger_crmentity')
	//-> set('columntype',    'int')
	-> set('generatedtype', '1')
	-> set('uitype',        70)
	-> set('fieldname',     'createdtime')
	-> set('fieldlabel',    'Created Time')
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
$field4->create();

// Create Modified Time field
$field5 = new Vtiger_Field();
$field5-> set('module',        'Payslip')
	-> set('columnname',    'modifiedtime')
	-> set('tablename',     'vtiger_crmentity')
	//-> set('columntype',    'int')
	-> set('generatedtype', '1')
	-> set('uitype',        70)
	-> set('fieldname',     'modifiedtime')
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
$field5->create();
?>

<?php
$Vtiger_Utils_Log = true;
include_once('vtlib/Vtiger/Utils.php');
include_once('vtlib/Vtiger/Field.php');
include_once('vtlib/Vtiger/CustomView.php');
include_once('vtlib/Vtiger/Tab.php');
include_once('vtlib/Vtiger/Block.php');

Vtiger_Tab::create('EmailScanner', 'EmailScanner', 'Tools'); // modules/EmailScanner/language/en_us.lang.php entry needed

Vtiger_Block::create('EmailScanner', 'LBL_EMAILSCANNER_INFORMATION'); // include/language/en_us.lang.php entry needed
Vtiger_Block::create('EmailScanner', 'LBL_TIME_INFORMATION'); // include/language/en_us.lang.php entry needed
Vtiger_Block::create('EmailScanner', 'LBL_CUSTOM_INFORMATION');  // Mandatory block to support custom fields (need entry in module language file)

Vtiger_Utils::CreateTable('vtiger_emailscanner', '(emailscannerid integer)');
Vtiger_Utils::CreateTable('vtiger_emailscannercf', '(id integer, primary key (id))');
Vtiger_Utils::CreateTable('vtiger_emailscannergrouprel', 
	'(emailscannerid integer, groupname varchar(100), primary key(emailscannerid))');

// Create Name field
$field1 = new Vtiger_Field();
$field1-> set('module',        'EmailScanner')
	-> set('columnname',    'emailscannername')
	-> set('tablename',     'vtiger_emailscanner')
	-> set('columntype',    'varchar(255)')
	-> set('generatedtype', '1')
	-> set('uitype',        2)
	-> set('fieldname',     'emailscannername')
	-> set('fieldlabel',    'EmailScannerName') // modules/EmailScanner/language/en_us.lang.php entry needed
	-> set('readonly',       '1')
	-> set('presence',       '0')
	-> set('selected',       '0')
	-> set('maximumlength',  '100')
	-> set('sequence',       null)
	-> set('typeofdata',     'V~M')
	-> set('quickcreate',    '1')
	-> set('block',          null)
	-> set('blocklabel',     'LBL_EMAILSCANNER_INFORMATION')
	-> set('displaytype',    '1')
	-> set('quickcreatesequence', null)
	-> set('info_type',      'BAS');
$field1->create();

// Module should have atleast one field set as an identifier
$field1->set('entityidfield', 'emailscannername')->set('entityidcolumn', 'emailscannerid');
$field1->setEntityIdentifier();

// Create Assigned To field
$field2 = new Vtiger_Field();
$field2-> set('module',        'EmailScanner')
	-> set('columnname',    'smownerid')
	-> set('tablename',     'vtiger_crmentity')
	//-> set('columntype',    'int')
	-> set('generatedtype', '1')
	-> set('uitype',        53)
	-> set('fieldname',     'assigned_user_id')
	-> set('fieldlabel',    'Assigned To') // modules/EmailScanner/language/en_us.lang.php entry needed
	-> set('readonly',       '1')
	-> set('presence',       '0')
	-> set('selected',       '0')
	-> set('maximumlength',  '100')
	-> set('sequence',       null)
	-> set('typeofdata',     'V~M')
	-> set('quickcreate',    '1')
	-> set('block',          null)
	-> set('blocklabel',     'LBL_EMAILSCANNER_INFORMATION')
	-> set('displaytype',    '1')
	-> set('quickcreatesequence', null)
	-> set('info_type',      'BAS');
$field2->create();

// Create Created Time field
$field3 = new Vtiger_Field();
$field3-> set('module',     'EmailScanner')
	-> set('columnname',    'createdtime')
	-> set('tablename',     'vtiger_crmentity')
	//-> set('columntype',    'int')
	-> set('generatedtype', '1')
	-> set('uitype',        70)
	-> set('fieldname',     'createdtime')
	-> set('fieldlabel',    'Created Time') // modules/EmailScanner/language/en_us.lang.php entry needed
	-> set('readonly',       '1')
	-> set('presence',       '0')
	-> set('selected',       '0')
	-> set('maximumlength',  '100')
	-> set('sequence',       null)
	-> set('typeofdata',     'T~O')
	-> set('quickcreate',    '1')
	-> set('block',          null)
	-> set('blocklabel',     'LBL_TIME_INFORMATION')
	-> set('displaytype',    '2')
	-> set('quickcreatesequence', null)
	-> set('info_type',      'BAS');
$field3->create();

// Create Modified Time field
$field4 = new Vtiger_Field();
$field4-> set('module',        'EmailScanner')
	-> set('columnname',    'modifiedtime')
	-> set('tablename',     'vtiger_crmentity')
	//-> set('columntype',    'int')
	-> set('generatedtype', '1')
	-> set('uitype',        70)
	-> set('fieldname',     'modifiedtime')
	-> set('fieldlabel',    'Modified Time') // modules/EmailScanner/language/en_us.lang.php entry needed
	-> set('readonly',       '1')
	-> set('presence',       '0')
	-> set('selected',       '0')
	-> set('maximumlength',  '100')
	-> set('sequence',       null)
	-> set('typeofdata',     'T~O')
	-> set('quickcreate',    '1')
	-> set('block',          null)
	-> set('blocklabel',     'LBL_TIME_INFORMATION')
	-> set('displaytype',    '2')
	-> set('quickcreatesequence', null)
	-> set('info_type',      'BAS');
$field4->create();

// Custom View
Vtiger_CustomView::create('EmailScanner', 'All',true);
$cv = new Vtiger_CustomView('EmailScanner', 'All');
$cv->addColumn($field1)
	->addColumn($field2, 1)
	->addColumn($field3, 2)
	->addColumn($field4, 3);

Vtiger_Module::setDefaultSharingAccess('EmailScanner', 'Public_ReadOnly');

?>

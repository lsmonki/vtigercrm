<?php
// Just a bit of HTML formatting
echo '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">';

echo '<html><head><title>vtlib Module Script</title>';
echo '<style type="text/css">@import url("themes/softed/style.css");br { display: block; margin: 2px; }</style>';
echo '</head><body class=small style="font-size: 12px; margin: 2px; padding: 2px;">';
echo '<a href="index.php"><img src="themes/softed/images/vtiger-crm.gif" alt="vtiger CRM" title="vtiger CRM" border=0></a><hr style="height: 1px">';

// Turn on debugging level
$Vtiger_Utils_Log = true;

include_once('vtlib/Vtiger/Menu.php');
include_once('vtlib/Vtiger/Module.php');

if(Vtiger_Module::getInstance('PBXManager')) {
   echo "PBXManager Already Installed."; 
   die;
}

// Create module instance and save it first
$module = new Vtiger_Module();
$module->name = 'PBXManager';
$module->save();

// Initialize all the tables required
$module->initTables();

// Add the module to the Menu (entry point from UI)
$menu = Vtiger_Menu::getInstance('Tools');
$menu->addModule($module);

// Add the basic module block
$block1 = new Vtiger_Block();
$block1->label = 'LBL_CALL_INFORMATION';
$module->addBlock($block1);

// Add custom block (required to support Custom Fields)
$block2 = new Vtiger_Block();
$block2->label = 'LBL_CUSTOM_INFORMATION';
$module->addBlock($block2);

/** Create required fields and add to the block */
$field1 = new Vtiger_Field();
$field1->name = 'callfrom';
$field1->label = 'Call From';
$field1->table = $module->basetable;
$field1->column = 'callfrom';
$field1->columntype = 'VARCHAR(255)';
$field1->uitype = 2;
$field1->typeofdata = 'V~M';
$block1->addField($field1); /** Creates the field and adds to block */
$module->setEntityIdentifier($field1);

$field2 = new Vtiger_Field();
$field2->name = 'callto';
$field2->label = 'Call To';
$field2->table = $module->basetable;
$field2->column = 'callto';
$field2->columntype = 'VARCHAR(255)';
$field2->uitype = 2;
$field2->typeofdata = 'V~M';
$block1->addField($field2);

$field3 = new Vtiger_Field();
$field3->name = 'timeofcall';
$field3->label = 'Time Of Call';
$field3->table = $module->basetable;
$field3->column = 'timeofcall';
$field3->columntype = 'VARCHAR(255)';
$field3->uitype = 2;
$field3->typeofdata = 'V~O';
$block1->addField($field3);

$field4 = new Vtiger_Field();
$field4->name = 'status';
$field4->label = 'Status';
$field4->table = $module->basetable;
$field4->column = 'status';
$field4->columntype = 'VARCHAR(255)';
$field4->uitype = 2;
$field4->typeofdata = 'V~O';
$block1->addField($field4);

// Create default custom filter (mandatory)
$filter1 = new Vtiger_Filter();
$filter1->name = 'All';
$filter1->isdefault = true;
$module->addFilter($filter1);

// Add fields to the filter created
$filter1->addField($field1)->addField($field2, 1)->addField($field3, 2)->addField($field4, 3);

$filter2 = new Vtiger_Filter();
$filter2->name = 'Missed';
$module->addFilter($filter2);
$filter2->addField($field1)->addField($field2, 1)->addField($field3, 2)->addField($field4, 3);
$filter2->addRule($field4, 'EQUALS', 'Missed');

$filter3 = new Vtiger_Filter();
$filter3->name = 'Dialed';
$module->addFilter($filter3);
$filter3->addField($field1)->addField($field2, 1)->addField($field3, 2)->addField($field4, 3);
$filter3->addRule($field4, 'EQUALS', 'outgoing');

$filter4 = new Vtiger_Filter();
$filter4->name = 'Received';
$module->addFilter($filter4);
$filter4->addField($field1)->addField($field2, 1)->addField($field3, 2)->addField($field4, 3);
$filter4->addRule($field4, 'EQUALS', 'incoming');


/** Set sharing access of this module */
$module->setDefaultSharing('Private'); 

/** Enable and Disable available tools */
$module->disableTools(Array('Import', 'Export', 'Merge'));

echo '</body></html>';

?>

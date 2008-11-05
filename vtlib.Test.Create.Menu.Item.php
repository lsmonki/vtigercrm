<?php

include_once('vtlib/Vtiger/Tab.php');

Vtiger_Tab::create('Payslip', 'Payslip', 'Tools');

include_once('include/language/en_us.lang.php');

global $app_strings;

if(!isset($app_strings['Payslip'])) {
	echo 'Please add mapping to include/language/en_us.lang.php $app_strings[\'Payslip\'] = \'Payslip\'';
}

?>

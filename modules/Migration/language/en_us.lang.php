<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/


$mod_strings = Array(
'LBL_MIGRATE_INFO'=>'Enter Values to Migrate Data from <b><i> Source </i></b> to <b><i> Current (Latest) vtigerCRM </i></b>',
'LBL_CURRENT_VT_MYSQL_EXIST'=>'Current vtiger\'s MySQL Exist in',
'LBL_THIS_MACHINE'=>'This Machine',
'LBL_DIFFERENT_MACHINE'=>'Different Machine',
'LBL_CURRENT_VT_MYSQL_PATH'=>'Current vtiger\'s MySQL path',
'LBL_SOURCE_VT_MYSQL_DUMPFILE'=>'vtiger <b>Source</b> Dump File name',
'LBL_NOTE_TITLE'=>'Note:',
'LBL_NOTES_LIST1'=>'If Current MySQL Exist in the Same Machine then enter the MySQL Path (or) you can enter the Dump file if you have.',
'LBL_NOTES_LIST2'=>'If Current MySQL Exist in different Machine then enter the (Source) Dump filename with the full Path.',
'LBL_NOTES_DUMP_PROCESS'=>'To take Database dump please execute the following command
			   <br><b>mysqldump --user="mysql_username"  --password="mysql-password" -h "hostname"  --port="mysql_port" "database_name" > dump_filename</b>
			   <br>add <b>SET_FOREIGN_KEY_CHECKS = 0;</b> -- at the start of the dump file
			   <br>add <b>SET_FOREIGN_KEY_CHECKS = 1;</b> -- at the end of the dump file',
'LBL_NOTES_LIST3'=>'Give the MySQL path like <b>/home/crm/vtigerCRM4_5/mysql</b>',
'LBL_NOTES_LIST4'=>'Give the Dump filename with full Path like <b>/home/fullpath/4_2_dump.txt</b>',

'LBL_CURRENT_MYSQL_PATH_FOUND'=>'Current installation\'s MySQL path has been found.',
'LBL_SOURCE_HOST_NAME'=>'Source Host Name :',
'LBL_SOURCE_MYSQL_PORT_NO'=>'Source MySql Port No :',
'LBL_SOURCE_MYSQL_USER_NAME'=>'Source MySql User Name :',
'LBL_SOURCE_MYSQL_PASSWORD'=>'Source MySql Password :',
'LBL_SOURCE_DB_NAME'=>'Source Database Name :',
'LBL_MIGRATE'=>'Migrate to Current Version',

);






?>

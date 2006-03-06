<?php 
 /********************************************************************************* 
3  * The contents of this file are subject to the SugarCRM Public License Version 1.1.2 
4  * ("License"); You may not use this file except in compliance with the  
5  * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL 
6  * Software distributed under the License is distributed on an  "AS IS"  basis, 
7  * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for 
8  * the specific language governing rights and limitations under the License. 
9  * The Original Code is:  SugarCRM Open Source 
10  * The Initial Developer of the Original Code is SugarCRM, Inc. 
11  * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.; 
12  * All Rights Reserved. 
13  * Contributor(s): ______________________________________. 
14 ********************************************************************************/ 
  
 include('vtigerversion.php'); 
  
 // more than 8MB memory needed for graphics 
 // memory limit default value = 16M 
 ini_set('memory_limit','16M'); 
  
 // show or hide world clock and calculator 
 // world_clock_display default value = true 
 // calculator_clock_display default value = true 
 $WORLD_CLOCK_DISPLAY = 'true'; 
 $CALCULATOR_DISPLAY = 'true'; 
  
 // url for customer portal (Example: http://vtiger.com/portal) 
 $PORTAL_URL = 'http://your-domain.com/customerportal'; 
  
 // helpdesk support email id and support name (Example: 'support@vtiger.com' and 'vtiger support') 
 $HELPDESK_SUPPORT_EMAIL_ID = 'support@your-domain.com'; 
 $HELPDESK_SUPPORT_NAME = 'your-domain name'; 
  
 /* database configuration 
36       db_server 
37       db_port 
38       db_hostname 
39       db_username 
40       db_password 
41       db_name 
42 */ 
   $mysql_dir = 'MYSQLINSTALLDIR'; 

 $mysql_bundled = 'MYSQLBUNDLEDSTATUS';
 $dbconfig['db_server'] = '_DBC_SERVER_';
 $dbconfig['db_name'] = '_DBC_NAME_'; 
 $dbconfig['db_type'] = '_DBC_TYPE_'; 
$apache_dir = 'APACHEINSTALLDIR';
$apache_bin = 'APACHEBIN';
$apache_conf = 'APACHECONF';
$apache_port = 'APACHEPORT';
 $apache_bundled = 'APACHEBUNDLED';
   $dbconfig['db_username'] = '_DBC_USER_';
   $dbconfig['db_password'] = '_DBC_PASS_';
   $dbconfig['db_port'] = ':_DBC_PORT_';
  $mysql_username = '_DBC_USER_';
  $mysql_password = '_DBC_PASS_';
  $mysql_port = '_DBC_PORT_';

 // TODO: test if port is empty 
 // TODO: set db_hostname dependending on db_type 
 $dbconfig['db_hostname'] = $dbconfig['db_server'].$dbconfig['db_port']; 
  
 // log_sql default value = false 
 $dbconfig['log_sql'] = false; 
  
 // persistent default value = true 
 $dbconfigoption['persistent'] = true; 
  
 // autofree default value = false 
 $dbconfigoption['autofree'] = false; 
  
 // debug default value = 0 
 $dbconfigoption['debug'] = 0; 
  
 // seqname_format default value = '%s_seq' 
 $dbconfigoption['seqname_format'] = '%s_seq'; 
  
// portability default value = 0 
 $dbconfigoption['portability'] = 0; 
  
 // ssl default value = false 
 $dbconfigoption['ssl'] = false; 
  
 $host_name = $dbconfig['db_hostname']; 
  
 $site_URL = '_SITE_URL_'; 
  
 // root directory path 
 $root_directory = 'C:\Program Files\vtigerCRM4.2.4\apache\htdocs\vtigerCRM/'; 
  
 // cache direcory path 
 $cache_dir = 'cache/'; 
  
 // tmp_dir default value prepended by cache_dir = images/ 
 $tmp_dir = 'cache/images/'; 
  
 // import_dir default value prepended by cache_dir = import/ 
 $import_dir = 'cache/import/'; 
  
 // upload_dir default value prepended by cache_dir = upload/ 
 $upload_dir = 'cache/upload/'; 
  
 // mail server parameters 
 $mail_server = ''; 
 $mail_server_username = ''; 
 $mail_server_password = ''; 
  
 // maximum file size for uploaded files in bytes also used when uploading import files 
 // upload_maxsize default value = 3000000 
 $upload_maxsize = 3000000; 
  
 // flag to allow export functionality 
 // 'all' to allow anyone to use exports  
 // 'admin' to only allow admins to export  
 // 'none' to block exports completely  
 // allow_exports default value = all 
 $allow_exports = 'all'; 
  
 // files with one of these extensions will have '.txt' appended to their filename on upload 
 // upload_badext default value = php, php3, php4, php5, pl, cgi, py, asp, cfm, js, vbs, html, htm 
 $upload_badext = array('php', 'php3', 'php4', 'php5', 'pl', 'cgi', 'py', 'asp', 'cfm', 'js', 'vbs', 'html', 'htm'); 
  
 // full path to include directory including the trailing slash 
 // includeDirectory default value = $root_directory..'include/ 
 $includeDirectory = $root_directory.'include/'; 
  
 // list_max_entries_per_page default value = 20 
 $list_max_entries_per_page = '20'; 
  
 // history_max_viewed default value = 5 
 $history_max_viewed = '5'; 
  
 // define list of menu tabs 
 //$moduleList = Array('Home', 'Dashboard', 'Contacts', 'Accounts', 'Opportunities', 'Cases', 'Notes', 'Calls', 'Emails', 'Meetings', 'Tasks','MessageBoard'); 
  
 // map sugar language codes to jscalendar language codes 
 // unimplemented until jscalendar language files are fixed 
 // $cal_codes = array('en_us'=>'en', 'ja'=>'jp', 'sp_ve'=>'sp', 'it_it'=>'it', 'tw_zh'=>'zh', 'pt_br'=>'pt', 'se'=>'sv', 'cn_zh'=>'zh', 'ge_ge'=>'de', 'ge_ch'=>'de', 'fr'=>'fr'); 
  
 // default_module default value = Home 
 $default_module = 'Home'; 
  
 // default_action default value = index 
 $default_action = 'index'; 
  
 // set default theme 
 // default_theme default value = blue 
 $default_theme = 'blue'; 
  
 // show or hide time to compose each page 
 // calculate_response_time default value = true 
 $calculate_response_time = true; 
  
 // default text that is placed initially in the login form for user name 
 // no default_user_name default value 
 $default_user_name = ''; 
 
 // default text that is placed initially in the login form for password 
 // no default_password default value 
 $default_password = ''; 
  
 // create user with default username and password 
 // create_default_user default value = false 
 $create_default_user = false; 
 // default_user_is_admin default value = false 
 $default_user_is_admin = false; 
  
 // if your MySQL/PHP configuration does not support persistent connections set this to true to avoid a large performance slowdown 
 // disable_persistent_connections default value = false 
 $disable_persistent_connections = false; 
  
 // defined languages available. the key must be the language file prefix. (Example 'en_us' is the prefix for every 'en_us.lang.php' file) 
 // languages default value = en_us=>US English 
 $languages = Array('en_us'=>'US English',); 
  
 // default charset 
 // default charset default value = ISO-8859-1 
 $default_charset = 'ISO-8859-1'; 
  
 // default language 
 // default_language default value = en_us 
 $default_language = 'en_us'; 
  
 // add the language pack name to every translation string in the display. 
 // translation_string_prefix default value = false 
 $translation_string_prefix = false; 
?>

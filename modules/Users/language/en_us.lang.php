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
/*********************************************************************************
 * $Header:  vtiger_crm/sugarcrm/modules/Users/language/en_us.lang.php,v 1.12 2005/01/10 10:40:43 jack Exp $
 * Description:  Defines the English language pack for the Account module.
 ********************************************************************************/
 
$mod_strings = Array(
'LBL_MODULE_NAME'=>'Users',
'LBL_MODULE_TITLE'=>'Users: Home',
'LBL_SEARCH_FORM_TITLE'=>'User Search',
'LBL_LIST_FORM_TITLE'=>'User List',
'LBL_NEW_FORM_TITLE'=>'New User',
'LBL_USER'=>'Users:',
'LBL_LOGIN'=>'Login',
'LBL_USER_ROLE'=>'Role',
'LBL_LIST_NAME'=>'Name',
'LBL_LIST_LAST_NAME'=>'Last Name',
'LBL_LIST_USER_NAME'=>'User Name',
'LBL_LIST_DEPARTMENT'=>'Department',
'LBL_LIST_EMAIL'=>'Email',
'LBL_LIST_PRIMARY_PHONE'=>'Primary Phone',
'LBL_LIST_ADMIN'=>'Admin',
//added for patch2
'LBL_GROUP_NAME'=>'Group',

'LBL_NEW_USER_BUTTON_TITLE'=>'New User [Alt+N]',
'LBL_NEW_USER_BUTTON_LABEL'=>'New User',
'LBL_NEW_USER_BUTTON_KEY'=>'N',

'LBL_ERROR'=>'Error:',
'LBL_PASSWORD'=>'Password:',
'LBL_USER_NAME'=>'User Name:',
'LBL_FIRST_NAME'=>'First Name:',
'LBL_LAST_NAME'=>'Last Name:',
'LBL_YAHOO_ID'=>'Yahoo ID:',
'LBL_USER_SETTINGS'=>'User Settings',
'LBL_THEME'=>'Theme:',
'LBL_LANGUAGE'=>'Language:',
'LBL_ADMIN'=>'Admin:',
'LBL_USER_INFORMATION'=>'User Information',
'LBL_OFFICE_PHONE'=>'Office Phone:',
'LBL_REPORTS_TO'=>'Reports to:',
'LBL_OTHER_PHONE'=>'Other:',
'LBL_OTHER_EMAIL'=>'Other Email:',
'LBL_NOTES'=>'Notes:',
'LBL_DEPARTMENT'=>'Department:',
'LBL_STATUS'=>'Status:',
'LBL_TITLE'=>'Title:',
'LBL_ANY_PHONE'=>'Any Phone:',
'LBL_ANY_EMAIL'=>'Any Email:',
'LBL_ADDRESS'=>'Address:',
'LBL_CITY'=>'City:',
'LBL_STATE'=>'State:',
'LBL_POSTAL_CODE'=>'Postal Code:',
'LBL_COUNTRY'=>'Country:',
'LBL_NAME'=>'Name:',
'LBL_USER_SETTINGS'=>'User Settings',
'LBL_USER_INFORMATION'=>'User Information',
'LBL_MOBILE_PHONE'=>'Mobile:',
'LBL_OTHER'=>'Other:',
'LBL_FAX'=>'Fax:',
'LBL_EMAIL'=>'Email:',
'LBL_HOME_PHONE'=>'Home Phone:',
'LBL_ADDRESS_INFORMATION'=>'Address Information',
'LBL_PRIMARY_ADDRESS'=>'Primary Address:',

'LBL_CHANGE_PASSWORD_BUTTON_TITLE'=>'Change Password [Alt+P]',
'LBL_CHANGE_PASSWORD_BUTTON_KEY'=>'P',
'LBL_CHANGE_PASSWORD_BUTTON_LABEL'=>'Change Password',
'LBL_LOGIN_BUTTON_TITLE'=>'Login [Alt+L]',
'LBL_LOGIN_BUTTON_KEY'=>'L',
'LBL_LOGIN_BUTTON_LABEL'=>'Login',
'LBL_LOGIN_HISTORY_BUTTON_TITLE'=>'Login History [Alt+H]',
'LBL_LOGIN_HISTORY_BUTTON_KEY'=>'H',
'LBL_LOGIN_HISTORY_BUTTON_LABEL'=>'Login History',
'LBL_LOGIN_HISTORY_TITLE'=>'Users: Login History',
'LBL_RESET_PREFERENCES'=>'Reset To Default Preferences',

'LBL_CHANGE_PASSWORD'=>'Change Password',
'LBL_OLD_PASSWORD'=>'Old Password:',
'LBL_NEW_PASSWORD'=>'New Password:',
'LBL_CONFIRM_PASSWORD'=>'Confirm Password:',
'ERR_ENTER_OLD_PASSWORD'=>'Please enter your old password.',
'ERR_ENTER_NEW_PASSWORD'=>'Please enter your new password.',
'ERR_ENTER_CONFIRMATION_PASSWORD'=>'Please enter your password confirmation.',
'ERR_REENTER_PASSWORDS'=>'Please re-enter passwords.  The \"new password\" and \"confirm password\" values do not match.',
'ERR_INVALID_PASSWORD'=>'You must specify a valid username and password.',
'ERR_PASSWORD_CHANGE_FAILED_1'=>'User password change failed for ',
'ERR_PASSWORD_CHANGE_FAILED_2'=>' failed.  The new password must be set.',
'ERR_PASSWORD_INCORRECT_OLD'=>'Incorrect old password for user $this->user_name. Re-enter password information.',
'ERR_USER_NAME_EXISTS_1'=>'The user name ',
'ERR_USER_NAME_EXISTS_2'=>' already exists.  Duplicate user names are not allowed.<br>Change the user name to be unique.',
'ERR_LAST_ADMIN_1'=>'The user name ',
'ERR_LAST_ADMIN_2'=>' is the last Admin user.  At least one user must be an Admin user.<br>Check the Admin user setting.',

'LNK_NEW_LEAD'=>'New Lead',
'LNK_NEW_CONTACT'=>'New Contact',
'LNK_NEW_ACCOUNT'=>'New Account',
'LNK_NEW_OPPORTUNITY'=>'New Opportunity',
'LNK_NEW_CASE'=>'New Case',
'LNK_NEW_NOTE'=>'New Note',
'LNK_NEW_CALL'=>'New Call',
'LNK_NEW_EMAIL'=>'New Email',
'LNK_NEW_MEETING'=>'New Meeting',
'LNK_NEW_TASK'=>'New Task',
'ERR_DELETE_RECORD'=>"A record number must be specified to delete the account.",

// Additional Fields for i18n --- Release vtigerCRM 3.2 Patch 2
// Users--listroles.php , createrole.php , ListPermissions.php , editpermissions.php

'LBL_ROLES'=>'Roles',
'LBL_ROLE_NAME'=>'Role Name',
'LBL_CREATE_NEW_ROLE'=>'Create New Role',

'LBL_CREATE_NEW_ROLE'=>'Create New Role',
'LBL_INDICATES_REQUIRED_FIELD'=>'Indicates Required Field',
'LBL_NEW_ROLE'=>'New Role',
'LBL_PARENT_ROLE'=>'Parent Role',

'LBL_LIST_ROLES'=>'List Roles',
'LBL_ENTITY_LEVEL_PERMISSIONS'=>'Entity Level Permissions',
'LBL_ENTITY'=>'Entity',
'LBL_CREATE_EDIT'=>'Create/Edit',
'LBL_DELETE'=>'Delete',
'LBL_ALLOW'=>'Allow',
'LBL_LEADS'=>'Leads',
'LBL_ACCOUNTS'=>'Accounts',
'LBL_CONTACTS'=>'Contacts',
'LBL_OPPURTUNITIES'=>'Opportunities',
'LBL_TASKS'=>'Tasks',
'LBL_CASES'=>'Cases',
'LBL_EMAILS'=>'Emails',
'LBL_NOTES'=>'Notes',
'LBL_MEETINGS'=>'Meetings',
'LBL_CALLS'=>'Calls',
'LBL_IMPORT_PERMISSIONS'=>'Import Permissions',
'LBL_IMPORT_LEADS'=>'Import Leads',
'LBL_IMPORT_ACCOUNTS'=>'Import Accounts',
'LBL_IMPORT_CONTACTS'=>'Import Contacts',
'LBL_IMPORT_OPPURTUNITIES'=>'Import Opportunities',

'LBL_ROLE_DETAILS'=>'Role Details',
//added for vtigercrm4 rc
'LBL_FILE'=> 'File Name',
'LBL_UPLOAD'=>'Upload File',
'LBL_ATTACH_FILE'=>'Attach Word Template ',
'LBL_EMAIL_TEMPLATES'=>'Email Templates',
'LBL_TEMPLATE_NAME'=>'Template Name',
'LBL_DESCRIPTION'=>'Description',
'LBL_EMAIL_TEMPLATES_LIST'=>'Email Templates  List',

'LBL_COLON'=>':',
'LBL_EMAIL_TEMPLATE'=>'Email Template',
'LBL_NEW_TEMPLATE'=>'New Template',
'LBL_USE_MERGE_FIELDS_TO_EMAIL_CONTENT'=>'Use merge fields to personalize your email content. You can add substitute text to any merge field.',
'LBL_AVAILABLE_MERGE_FIELDS'=>'Available Merge Fields',
'LBL_SELECT_FIELD_TYPE'=>'Select Field Type',
'LBL_SELECT_FIELD'=>'Select Field',
'LBL_MERGE_FIELD_VALUE'=>'Copy Merge Field Value',
'LBL_CONTACT_FIELDS'=>'Contact Fields',
'LBL_LEAD_FIELDS'=>'Lead Fields',
'LBL_COPY_AND_PASTE_MERGE_FIELD'=>'Copy and paste the merge field value into your template below.',
'LBL_EMAIL_TEMPLATE_INFORMATION'=>'Email Template Information:',
'LBL_FOLDER'=>'Folder:',
'LBL_PERSONAL'=>'Personal',
'LBL_PUBLIC'=>'Public',
'LBL_TEMPLATE_NAME'=>'Template Name:',
'LBL_SUBJECT'=>'Subject',
'LBL_BODY'=>'Email Body',

// Added fields in createnewgroup.php
'LBL_CREATE_NEW_GROUP'=>'Create New Group',
'LBL_NEW_GROUP'=>'New Group',
'LBL_GROUP_NAME'=>'Group Name',
'LBL_DESCRIPTION'=>'Description',

// Added fields in detailViewmailtemplate.html,listgroupmembers.php,listgroups.php
'LBL_DETAIL_VIEW_OF_EMAIL_TEMPLATE'=>'Detail View of Email Template',
'LBL_GROUP_MEMBERS_LIST'=>'Group members list',
'LBL_GROUPS'=>'Groups',
'LBL_WORD_TEMPLATES'=>'Word Template',
'LBL_NEW_WORD_TEMPLATE'=>'New Word Template',

// Added fields in TabCustomise.php,html and UpdateTab.php,html
'LBL_CUSTOMISE_TABS'=>'Customize Tabs',
'LBL_CHOOSE_TABS'=>'Choose Tabs',
'LBL_AVAILABLE_TABS'=>'Available Tabs',
'LBL_SELECTED_TABS'=>'Selected Tabs',
'LBL_USER'=>'User',
'LBL_TAB_MENU_UPDATED'=>'Tab Menu Updated! kindly go to ',
'LBL_TO_VIEW_CHANGES'=>' to view the changes',

// Added fields in binaryfilelist.php
'LBL_OERATION'=>'Operation',

);

?>

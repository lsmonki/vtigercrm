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

 * $Header$

 * Description:  Defines the English language pack

 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.

 * All Rights Reserved.

 * Contributor(s): ______________________________________..

 ********************************************************************************/



$mod_strings = Array(

'LBL_MODULE_NAME'=>'Settings',

'LBL_MODULE_TITLE'=>'Settings: Home',

'LBL_LIST_CONTACT_ROLE'=>'Role',



'LBL_LIST_LAST_NAME'=>'Last Name',

'LBL_FIRST_NAME'=>'First Name:',

'LBL_LAST_NAME'=>'Last Name:',

'LBL_PHONE'=>'Phone:',

'LBL_EMAIL_ADDRESS'=>'Email',

'LBL_NEW_FORM_TITLE'=>'New Contact',



'NTC_DELETE_CONFIRMATION'=>'Are you sure you want to delete this record?',

'LEADCUSTOMFIELDS'=>'Lead Custom Fields',

'ACCOUNTCUSTOMFIELDS'=>'Account Custom Fields',

'CONTACTCUSTOMFIELDS'=>'Contact Custom Fields',

'OPPORTUNITYCUSTOMFIELDS'=>'Potential Custom Fields',

'HELPDESKCUSTOMFIELDS'=>'Helpdesk Custom Fields',

'PRODUCTCUSTOMFIELDS'=>'Product Custom Fields',



'EDITLEADPICKLISTVALUES'=>'Edit Lead PickList ',

'EDITACCOUNTPICKLISTVALUES'=>'Edit Account PickList ',

'EDITCONTACTPICKLISTVALUES'=>'Edit Contact PickList ',

'EDITOPPORTUNITYPICKLISTVALUES'=>'Edit Potential PickList ',

'EDITHELPDESKPICKLISTVALUES'=>'Edit HeldDesk PickList ',

'EDITPRODUCTPICKLISTVALUES'=>'Edit Product PickList ',

'EDITEVENTPICKLISTVALUES'=>'Edit Event PickList ',

'EDITTASKPICKLISTVALUES'=>'Edit Task PickList ',



// Added for Release vtigerCRM 3.2 PATCH 1.0

//Settings/index.php

'LBL_CUSTOM_FIELD_SETTINGS'=>'Custom Field Settings:',

'LBL_PICKLIST_FIELD_SETTINGS'=>'Picklist Field Settings:',



//SETTINGS/CustomFieldList.php,ComboFieldList.php,CreateCustomField.php

'Leads'=>'Lead',

'Accounts'=>'Account',

'Contacts'=>'Contact',

'Potentials'=>'Potential',

'HelpDesk'=>'HelpDesk',

'Products'=>'Product',

'Events'=>'Event',

'Activities'=>'Task',





'CustomFields'=>' Custom Fields',

'NewCustomField'=>'New Custom Field',

'NewCustomFieldAltC'=>'New Custom Field [Alt+c]:',

'FieldName'=>'Field Name',

'FieldType'=>'Field Type',

'Delete'=>'Del',

'NEW'=>'New',

'CUSTOMFIELD'=>'Custom Field',



'PicklistFields'=>' Picklist Fields',

'Edit'=>'Edit',



//Settings/CustomField.html

'LBL_PROVIDE_FIELD_INFORMATION'=>'Provide Field Information: ',

'LBL_SELECT_FIELD_TYPE'=>'Select Field Type: ', 

'LBL_PROVIDE_FIELD_DETAILS'=>'Provide Field Details: ',

'LBL_LABEL'=>'Label: ',

'LBL_LENGTH'=>'Length: ',

'LBL_DECIMAL_PLACES'=>'Decimal Places: ',

'LBL_PICK_LIST_VALUES'=>'Pick List Values: ',



//Settings/EditComboField.php

'EditPickListValues'=>'Edit Pick List ',



//Settings/EditField.html

'LBL_FIELD_INFORMATION'=>'Field Information:',

'Values'=>'Values',

'EnterListOfValues'=>'Please enter the list of values below. Each value should be in its own line.',



//Settings/fieldtypes.php

'Text'=>'Text',

'Number'=>'Number',

'Percent'=>'Percent',

'Currency'=>'Currency',

'Date'=>'Date',

'Email'=>'Email',

'Phone'=>'Phone',

'PickList'=>'Pick List',



//added for patch2

'USERGROUPLIST'=>'Groups',

'EMAILTEMPLATES'=>'Email Templates',

'WORDINTEGRATION'=>'Mail Merge Templates',

'NOTIFICATIONSCHEDULERS'=>'Notification Schedulers',



//Added fields for Title Informations -- after 4 Beta

'LBL_EMAIL_CONFIG'=>'Email Configuration',

'LBL_WORD_INTEGRATION'=>'Word Integration:',

'LBL_GROUP_SETTINGS'=>'Group Settings:',

'LBL_TEMPLATE_SETTINGS'=>'Template Settings:',

'LBL_NOTIFICATION_SETTINGS'=>'Notification Settings:',

'LBL_EMAIL_SETTINGS'=>'Email Settings:',

'LBL_SECURITY_SETTINGS'=>'Security Settings:',



//Added fields after RC1 - Release

'LBL_MAIL_SERVER_INFO'=>'Mail Server Information',

'LBL_OUTGOING_MAIL_SERVER'=>'OutGoing Mail Server',

'LBL_OUTGOING_MAIL_SERVER_LOGIN_USER_NAME'=>'OutGoing Mail Server Login User Name',

'LBL_OUTGOING_MAIL_SERVER_PASSWORD'=>'OutGoing Mail Server Password',



'LBL_BACKUP_SERVER_CONFIG'=>'Backup Server Configuration',

'LBL_FTP_SERVER_NAME'=>'ftp Server Name',

'LBL_FTP_USER_NAME'=>'ftp User Name',

'LBL_FTP_PASSWORD'=>'ftp Password',

'LBL_SYSTEM_CONFIG'=>'System Configuration',



//Fields for Settings

'LBL_USER_MANAGEMENT'=>'User Management',

'LBL_USERS'=>'Users',

'LBL_CREATE_AND_MANAGE_USERS'=>'- Create and Manage Users (e.g., admin)',

'LBL_ROLES'=>'Roles',

'LBL_CREATE_AND_MANAGE_USER_ROLES'=>'- Create and Manage user roles (e.g., Administrator, Standard User, etc.)',

'LBL_PROFILES'=>'Profiles',

'LBL_CREATE_AND_MANAGE_USER_PROFILES'=>'- Create and Manage user profiles (e.g., CEO, Sales Manager, etc.)',

'LBL_CREATE_AND_MANAGE_USER_GROUPS'=>'- Create and Manage user groups',

'LBL_DEFAULT_ORGANIZATION_SHARING_ACCESS'=>'Default Organization Sharing Access',

'LBL_SETTING_DEFAULT_SHARING_ACCESS'=>'- Setting Default Sharing Access within the Organization',

'LBL_FIELD_ACCESSIBILITY'=>'Field Accessibility',

'LBL_SETTING_FIELD_ACCESSIBILITY'=>' - Setting Field Accessibility for each profiles',

'LBL_LEAD_FIELD_ACCESS'=>'Lead Field Access',

'LBL_ACCOUNT_FIELD_ACCESS'=>'Account Field Access',

'LBL_CONTACT_FIELD_ACCESS'=>'Contact Field Access',

'LBL_OPPORTUNITY_FIELD_ACCESS'=>'Opportunity Field Access',

'LBL_HELPDESK_FIELD_ACCESS'=>'HelpDesk Field Access',

'LBL_PRODUCT_FIELD_ACCESS'=>'Product Field Access',

'LBL_NOTE_FIELD_ACCESS'=>'Note Field Access',

'LBL_EMAIL_FIELD_ACCESS'=>'Email Field Access',

'LBL_TASK_FIELD_ACCESS'=>'Task Field Access',

'LBL_EVENT_FIELD_ACCESS'=>'Event Field Access',

'LBL_DELETE_DEMO_DATA'=>'Delete Demo Data',

'LBL_DELETE_DEMO_DATA_INFO'=>'- Delete the Demo Data which is created at the time of installation',



'LBL_STUDIO'=>'Studio',

'LBL_CUSTOM_FIELD_SETTINGS'=>'Custom Field Settings',

'LBL_CREATE_AND_MANAGE_USER_DEFINED_FIELDS'=>'- Create and Manage user defined fields',



'LBL_PICKLIST_SETTINGS'=>'Picklist Settings',

'LBL_EDIT_PICKLIST_VALUES'=>' - Edit values of Picklist fields',



'LBL_COMMUNICATION_TEMPLATES'=>'Communication Templates',

'LBL_CREATE_EMAIL_TEMPLATES'=>' - Create Email Templates',

'LBL_UPLOAD_MSWORD_TEMPLATES'=>' - Upload MS Word Templates for Mail Merge',

'LBL_SCHEDULE_EMAIL_NOTIFICATION'=>' - Schedule Email Notifications',



'LBL_CONFIGURATION'=>'Configuration',

'LBL_CONFIGURE_MAIL_SERVER'=>' - Configure Mail Server',

'LBL_BACKUP_SERVER_CONFIGURATION'=>'Backup Server Configuration',

'LBL_BACKUP_SERVER_INFO'=>'Backup Server Information',

'LBL_CONFIGURE_BACKUP_SERVER'=>' - Configure Backup Server',

'LBL_SYSTEM_CONFIGURATION'=>' - System Configuration',



//Field Types for custom fields

'LBL_URL'=>'URL',

'LBL_CHECK_BOX'=>'Checkbox',



//PickList Settings

'LBL_STANDARD_FIELDS'=>'Standard Fields',

'LBL_LEAD_SOURCE'=>'Lead Source',

'LBL_SALUTATION'=>'Salutation',

'LBL_LEAD_STATUS'=>'Lead Status',

'LBL_INDUSTRY'=>'Industry',

'LBL_RATING'=>'Rating',

'LBL_ACCOUNT_TYPE'=>'Account Type',

'LBL_BUSINESS_TYPE'=>'Business Type',

'LBL_CURRENCY_TYPE'=>'Currency Type',

'LBL_SALES_STAGE'=>'Sales Stage',

'LBL_PRIORITY'=>'Priority',

'LBL_STATUS'=>'Status',

'LBL_CATEGORY'=>'Category',

'LBL_MANUFACTURER'=>'Manufacturer',

'LBL_PRODUCT_CATEGORY'=>'Product Category',



);




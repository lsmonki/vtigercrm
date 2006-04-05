<?php

/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('data/CRMEntity.php');
require_once('include/utils/utils.php');

class Security extends CRMEntity {
	var $table_name = "role";
		
	function Security() {
		$this->log = LoggerManager::getLogger('security');
		$this->db = new PearDatabase();
	}

	var $new_schema = true;

	function create_tables () {
		global $app_strings;

		$role1_id = $this->db->getUniqueID("role");
		$role2_id = $this->db->getUniqueID("role");
		$role3_id = $this->db->getUniqueID("role");

		$profile1_id = $this->db->getUniqueID("profile");
		$profile2_id = $this->db->getUniqueID("profile");
		$profile3_id = $this->db->getUniqueID("profile");
		$profile4_id = $this->db->getUniqueID("profile");

                $this->db->query("insert into role values('H".$role1_id."','Organisation','H".$role1_id."',0)");
                $this->db->query("insert into role values('H".$role2_id."','administrator','H".$role1_id."::H".$role2_id."',1)");
                $this->db->query("insert into role values('H".$role3_id."','standard_user','H".$role1_id."::H".$role2_id."::H".$role3_id."',2)");
                
                $table_name="user2role";
                $table_name="role2tab";

 $this->db->query("INSERT INTO tab VALUES (3,'Home',0,1,'Home',null,null,1)");
 $this->db->query("INSERT INTO tab VALUES (7,'Leads',0,4,'Leads',null,null,1)");
 $this->db->query("INSERT INTO tab VALUES (6,'Accounts',0,5,'Accounts',null,null,1)");
 $this->db->query("INSERT INTO tab VALUES (4,'Contacts',0,6,'Contacts',null,null,1)");
 $this->db->query("INSERT INTO tab VALUES (2,'Potentials',0,7,'Potentials',null,null,1)");
 $this->db->query("INSERT INTO tab VALUES (8,'Notes',0,9,'Notes',null,null,1)");
 $this->db->query("INSERT INTO tab VALUES (9,'Activities',0,3,'Activities',null,null,1)");
 $this->db->query("INSERT INTO tab VALUES (10,'Emails',0,10,'Emails',null,null,1)");
 $this->db->query("INSERT INTO tab VALUES (13,'HelpDesk',0,11,'HelpDesk',null,null,1)");
 $this->db->query("INSERT INTO tab VALUES (14,'Products',0,8,'Products',null,null,1)");
 $this->db->query("INSERT INTO tab VALUES (1,'Dashboard',0,12,'Dashboards',null,null,1)");
 $this->db->query("INSERT INTO tab VALUES (15,'Faq',2,14,'Faq',null,null,1)");
 $this->db->query("INSERT INTO tab VALUES (16,'Events',2,13,'Events',null,null,1)");
 $this->db->query("INSERT INTO tab VALUES (17,'Calendar',0,2,'Calendar',null,null,1)");
 $this->db->query("INSERT INTO tab VALUES (18,'Vendors',0,15,'Vendors',null,null,1)");
 $this->db->query("INSERT INTO tab VALUES (19,'PriceBooks',0,16,'PriceBooks',null,null,1)");
 $this->db->query("INSERT INTO tab VALUES (20,'Quotes',0,17,'Quotes',null,null,1)");
 $this->db->query("INSERT INTO tab VALUES (21,'PurchaseOrder',0,18,'PurchaseOrder',null,null,1)");
 $this->db->query("INSERT INTO tab VALUES (22,'SalesOrder',0,19,'SalesOrder',null,null,1)");
 $this->db->query("INSERT INTO tab VALUES (23,'Invoice',0,20,'Invoice',null,null,1)");
 $this->db->query("INSERT INTO tab VALUES (24,'Rss',0,21,'Rss',null,null,1)");
 $this->db->query("INSERT INTO tab VALUES (25,'Reports',0,22,'Reports',null,null,1)");
 $this->db->query("INSERT INTO tab VALUES (26,'Campaigns',0,23,'Campaigns',null,null,1)");
 $this->db->query("INSERT INTO tab VALUES (27,'Portal',0,24,'Portal',null,null,1)");

// Populate the blocks table
$this->db->query("insert into blocks values (1,2,'LBL_OPPORTUNITY_INFORMATION',1,0,0,0,0,0)");
$this->db->query("insert into blocks values (2,2,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)");
$this->db->query("insert into blocks values (3,2,'LBL_DESCRIPTION_INFORMATION',3,0,0,0,0,0)");
$this->db->query("insert into blocks values (4,4,'LBL_CONTACT_INFORMATION',1,0,0,0,0,0)");
$this->db->query("insert into blocks values (5,4,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)");
$this->db->query("insert into blocks values (6,4,'LBL_CUSTOMER_PORTAL_INFORMATION',3,0,0,0,0,0)");
$this->db->query("insert into blocks values (7,4,'LBL_ADDRESS_INFORMATION',4,0,0,0,0,0)");
$this->db->query("insert into blocks values (8,4,'LBL_DESCRIPTION_INFORMATION',6,0,0,0,0,0)");
$this->db->query("insert into blocks values (9,6,'LBL_ACCOUNT_INFORMATION',1,0,0,0,0,0)");
$this->db->query("insert into blocks values (10,6,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)");
$this->db->query("insert into blocks values (11,6,'LBL_ADDRESS_INFORMATION',3,0,0,0,0,0)");
$this->db->query("insert into blocks values (12,6,'LBL_DESCRIPTION_INFORMATION',4,0,0,0,0,0)");
$this->db->query("insert into blocks values (13,7,'LBL_LEAD_INFORMATION',1,0,0,0,0,0)");
$this->db->query("insert into blocks values (14,7,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)");
$this->db->query("insert into blocks values (15,7,'LBL_ADDRESS_INFORMATION',3,0,0,0,0,0)");
$this->db->query("insert into blocks values (16,7,'LBL_DESCRIPTION_INFORMATION',4,0,0,0,0,0)");
$this->db->query("insert into blocks values (17,8,'LBL_NOTE_INFORMATION',1,0,0,0,0,0)");
$this->db->query("insert into blocks values (18,8,'',2,1,0,0,0,0)");
$this->db->query("insert into blocks values (19,9,'LBL_TASK_INFORMATION',1,0,0,0,0,0)");
$this->db->query("insert into blocks values (20,9,'',2,1,0,0,0,0)");
$this->db->query("insert into blocks values (21,10,'LBL_EMAIL_INFORMATION',1,0,0,0,0,0)");
$this->db->query("insert into blocks values (22,10,'',2,1,0,0,0,0)");
$this->db->query("insert into blocks values (23,10,'',3,1,0,0,0,0)");
$this->db->query("insert into blocks values (24,10,'',4,1,0,0,0,0)");
$this->db->query("insert into blocks values (25,13,'LBL_TICKET_INFORMATION',1,0,0,0,0,0)");
$this->db->query("insert into blocks values (26,13,'',2,1,0,0,0,0)");
$this->db->query("insert into blocks values (27,13,'LBL_CUSTOM_INFORMATION',3,0,0,0,0,0)");
$this->db->query("insert into blocks values (28,13,'LBL_DESCRIPTION_INFORMATION',4,0,0,0,0,0)");
$this->db->query("insert into blocks values (29,13,'LBL_TICKET_RESOLUTION',5,0,0,1,0,0)");
$this->db->query("insert into blocks values (30,13,'LBL_COMMENTS',6,0,0,1,0,0)");
$this->db->query("insert into blocks values (31,14,'LBL_PRODUCT_INFORMATION',1,0,0,0,0,0)");
$this->db->query("insert into blocks values (32,14,'LBL_PRICING_INFORMATION',2,0,0,0,0,0)");
$this->db->query("insert into blocks values (33,14,'LBL_STOCK_INFORMATION',3,0,0,0,0,0)");
$this->db->query("insert into blocks values (34,14,'LBL_CUSTOM_INFORMATION',4,0,0,0,0,0)");
$this->db->query("insert into blocks values (35,14,'LBL_IMAGE_INFORMATION',5,0,0,0,0,0)");
$this->db->query("insert into blocks values (36,14,'LBL_DESCRIPTION_INFORMATION',6,0,0,0,0,0)");
$this->db->query("insert into blocks values (37,15,'LBL_FAQ_INFORMATION',1,0,0,0,0,0)");
$this->db->query("insert into blocks values (38,15,'',2,1,0,0,0,0)");
$this->db->query("insert into blocks values (39,15,'',3,1,0,0,0,0)");
$this->db->query("insert into blocks values (40,15,'LBL_COMMENT_INFORMATION',4,0,0,1,0,0)");
$this->db->query("insert into blocks values (41,16,'LBL_EVENT_INFORMATION',1,0,0,0,0,0)");
$this->db->query("insert into blocks values (42,16,'',2,1,0,0,0,0)");
$this->db->query("insert into blocks values (43,16,'',3,1,0,0,0,0)");
$this->db->query("insert into blocks values (44,18,'LBL_VENDOR_INFORMATION',1,0,0,0,0,0)");
$this->db->query("insert into blocks values (45,18,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)");
$this->db->query("insert into blocks values (46,18,'LBL_VENDOR_ADDRESS_INFORMATION',3,0,0,0,0,0)");
$this->db->query("insert into blocks values (47,18,'LBL_DESCRIPTION_INFORMATION',4,0,0,0,0,0)");
$this->db->query("insert into blocks values (48,19,'LBL_PRICEBOOK_INFORMATION',1,0,0,0,0,0)");
$this->db->query("insert into blocks values (49,19,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)");
$this->db->query("insert into blocks values (50,19,'LBL_DESCRIPTION_INFORMATION',3,0,0,0,0,0)");
$this->db->query("insert into blocks values (51,20,'LBL_QUOTE_INFORMATION',1,0,0,0,0,0)");
$this->db->query("insert into blocks values (52,20,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)");
$this->db->query("insert into blocks values (53,20,'LBL_ADDRESS_INFORMATION',3,0,0,0,0,0)");
$this->db->query("insert into blocks values (54,20,'LBL_RELATED_PRODUCTS',4,0,0,0,0,0)");
$this->db->query("insert into blocks values (55,20,'LBL_TERMS_INFORMATION',5,0,0,0,0,0)");
$this->db->query("insert into blocks values (56,20,'LBL_DESCRIPTION_INFORMATION',6,0,0,0,0,0)");
$this->db->query("insert into blocks values (57,21,'LBL_PO_INFORMATION',1,0,0,0,0,0)");
$this->db->query("insert into blocks values (58,21,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)");
$this->db->query("insert into blocks values (59,21,'LBL_ADDRESS_INFORMATION',3,0,0,0,0,0)");
$this->db->query("insert into blocks values (60,21,'LBL_RELATED_PRODUCTS',4,0,0,0,0,0)");
$this->db->query("insert into blocks values (61,21,'LBL_TERMS_INFORMATION',5,0,0,0,0,0)");
$this->db->query("insert into blocks values (62,21,'LBL_DESCRIPTION_INFORMATION',6,0,0,0,0,0)");
$this->db->query("insert into blocks values (63,22,'LBL_SO_INFORMATION',1,0,0,0,0,0)");
$this->db->query("insert into blocks values (64,22,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)");
$this->db->query("insert into blocks values (65,22,'LBL_ADDRESS_INFORMATION',3,0,0,0,0,0)");
$this->db->query("insert into blocks values (66,22,'LBL_RELATED_PRODUCTS',4,0,0,0,0,0)");
$this->db->query("insert into blocks values (67,22,'LBL_TERMS_INFORMATION',5,0,0,0,0,0)");
$this->db->query("insert into blocks values (68,22,'LBL_DESCRIPTION_INFORMATION',6,0,0,0,0,0)");
$this->db->query("insert into blocks values (69,23,'LBL_INVOICE_INFORMATION',1,0,0,0,0,0)");
$this->db->query("insert into blocks values (70,23,'LBL_CUSTOM_INFORMATION',2,0,0,0,0,0)");
$this->db->query("insert into blocks values (71,23,'LBL_ADDRESS_INFORMATION',3,0,0,0,0,0)");
$this->db->query("insert into blocks values (72,23,'LBL_RELATED_PRODUCTS',4,0,0,0,0,0)");
$this->db->query("insert into blocks values (73,23,'LBL_TERMS_INFORMATION',5,0,0,0,0,0)");
$this->db->query("insert into blocks values (74,23,'LBL_DESCRIPTION_INFORMATION',6,0,0,0,0,0)");
$this->db->query("insert into blocks values (75,4,'LBL_IMAGE_INFORMATION',5,0,0,0,0,0)");
$this->db->query("insert into blocks values (76,26,'LBL_CAMPAIGN_INFORMATION',1,0,0,0,0,0)");
$this->db->query("insert into blocks values (77,26,'LBL_DESCRIPTION_INFORMATION',2,0,0,0,0,0)");

//

//Account Details -- START
 //Block9

$this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'accountname','account',1,'2','accountname','Account Name',1,0,0,100,1,9,1,'V~M',0,1,'BAS')");	
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'phone','account',1,'11','phone','Phone',1,0,0,100,2,9,1,'V~O',0,2,'BAS')");	
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'website','account',1,'17','website','Website',1,0,0,100,3,9,1,'V~O',0,3,'BAS')");	

 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'fax','account',1,'1','fax','Fax',1,0,0,100,4,9,1,'V~O',1,null,'BAS')");	
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'tickersymbol','account',1,'1','tickersymbol','Ticker Symbol',1,0,0,100,5,9,1,'V~O',1,null,'ADV')");	
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'otherphone','account',1,'11','otherphone','Other Phone',1,0,0,100,6,9,1,'V~O',1,null,'ADV')");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'parentid','account',1,'51','account_id','Member Of',1,0,0,100,7,9,1,'I~O',1,null,'ADV')");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'email1','account',1,'13','email1','Email',1,0,0,100,8,9,1,'E~O',1,null,'BAS')");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'employees','account',1,'7','employees','Employees',1,0,0,100,9,9,1,'I~O',1,null,'ADV')");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'email2','account',1,'13','email2','Other Email',1,0,0,100,10,9,1,'E~O',1,null,'ADV')");

 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'ownership','account',1,'1','ownership','Ownership',1,0,0,100,11,9,1,'V~O',1,null,'ADV')");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'rating','account',1,'1','rating','Rating',1,0,0,100,12,9,1,'V~O',1,null,'ADV')");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'industry','account',1,'15','industry','industry',1,0,0,100,13,9,1,'V~O',1,null,'ADV')");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'siccode','account',1,'1','siccode','SIC Code',1,0,0,100,14,9,1,'I~O',1,null,'ADV')");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'account_type','account',1,'15','accounttype','Type',1,0,0,100,15,9,1,'V~O',1,null,'ADV')");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'annualrevenue','account',1,'71','annual_revenue','Annual Revenue',1,0,0,100,16,9,1,'I~O',1,null,'ADV')");
 //Added field emailoptout for accounts -- after 4.2 patch2
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'emailoptout','account',1,'56','emailoptout','Email Opt Out',1,0,0,100,17,9,1,'C~O',1,null,'ADV')");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'smownerid','crmentity',1,'53','assigned_user_id','Assigned To',1,0,0,100,18,9,1,'V~M',1,null,'BAS')");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'70','createdtime','Created Time',1,0,0,100,19,9,2,'T~O',1,null,'BAS')");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'70','modifiedtime','Modified Time',1,0,0,100,19,9,2,'T~O',1,null,'BAS')");




 //Block 11
$this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'street','accountbillads',1,'21','bill_street','Billing Address',1,0,0,100,1,11,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'street','accountshipads',1,'21','ship_street','Shipping Address',1,0,0,100,2,11,1,'V~O',1,null,'BAS')");

 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'city','accountbillads',1,'1','bill_city','Billing City',1,0,0,100,5,11,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'city','accountshipads',1,'1','ship_city','Shipping City',1,0,0,100,6,11,1,'V~O',1,null,'BAS')");

 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'state','accountbillads',1,'1','bill_state','Billing State',1,0,0,100,7,11,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'state','accountshipads',1,'1','ship_state','Shipping State',1,0,0,100,8,11,1,'V~O',1,null,'BAS')");

 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'code','accountbillads',1,'1','bill_code','Billing Code',1,0,0,100,9,11,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'code','accountshipads',1,'1','ship_code','Shipping Code',1,0,0,100,10,11,1,'V~O',1,null,'BAS')");


 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'country','accountbillads',1,'1','bill_country','Billing Country',1,0,0,100,11,11,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'country','accountshipads',1,'1','ship_country','Shipping Country',1,0,0,100,12,11,1,'V~O',1,null,'BAS')");

$this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'pobox','accountbillads',1,'1','bill_pobox','Billing Po Box',1,0,0,100,3,11,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'pobox','accountshipads',1,'1','ship_pobox','Shipping Po Box',1,0,0,100,4,11,1,'V~O',1,null,'BAS')");


 //Block12
$this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'description','crmentity',1,'19','description','Description',1,0,0,100,1,12,1,'V~O',1,null,'BAS')");
 


//Account Details -- END

			
//Lead Details --- START

//Block13 -- Start

$this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'salutation','leaddetails',1,'55','salutationtype','Salutation',1,0,0,100,1,13,3,'V~O',1,null,'BAS')");	
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'firstname','leaddetails',1,'55','firstname','First Name',1,0,0,100,2,13,1,'V~O',0,1,'BAS')");	
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'phone','leadaddress',1,'11','phone','Phone',1,0,0,100,3,13,1,'V~O',0,4,'BAS')");	

 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'lastname','leaddetails',1,'2','lastname','Last Name',1,0,0,100,4,13,1,'V~M',0,2,'BAS')");	
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'mobile','leadaddress',1,'1','mobile','Mobile',1,0,0,100,5,13,1,'V~O',1,null,'BAS')");	
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'company','leaddetails',1,'2','company','Company',1,0,0,100,6,13,1,'V~M',0,3,'BAS')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'campaignid','leaddetails',1,'51','campaignid','Campaign Name',1,0,0,100,6,4,3,'I~O',1,null,'BAS')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'fax','leadaddress',1,'1','fax','Fax',1,0,0,100,7,13,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'designation','leaddetails',1,'1','designation','Designation',1,0,0,100,8,13,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'email','leaddetails',1,'13','email','Email',1,0,0,100,9,13,1,'E~O',0,5,'BAS')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'leadsource','leaddetails',1,'15','leadsource','Lead Source',1,0,0,100,10,13,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'website','leadsubdetails',1,'17','website','Website',1,0,0,100,11,13,1,'V~O',1,null,'ADV')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'industry','leaddetails',1,'15','industry','Industry',1,0,0,100,12,13,1,'V~O',1,null,'ADV')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'leadstatus','leaddetails',1,'15','leadstatus','Lead Status',1,0,0,100,13,13,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'annualrevenue','leaddetails',1,'71','annualrevenue','Annual Revenue',1,0,0,100,14,13,1,'I~O',1,null,'ADV')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'rating','leaddetails',1,'15','rating','Rating',1,0,0,100,15,13,1,'V~O',1,null,'ADV')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'noofemployees','leaddetails',1,'1','noofemployees','No Of Employees',1,0,0,100,16,13,1,'V~O',1,null,'ADV')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'smownerid','crmentity',1,'53','assigned_user_id','Assigned To',1,0,0,100,17,13,1,'V~M',1,null,'BAS')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'yahooid','leaddetails',1,'13','yahooid','Yahoo Id',1,0,0,100,18,13,1,'V~O',1,null,'ADV')");
$this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'70','createdtime','Created Time',1,0,0,100,19,13,2,'T~O',1,null,'BAS')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'70','modifiedtime','Modified Time',1,0,0,100,20,13,2,'T~O',1,null,'BAS')");

//Block13 -- End


//Block15 -- Start

$this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'lane','leadaddress',1,'21','lane','Street',1,0,0,100,1,15,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'code','leadaddress',1,'1','code','Postal Code',1,0,0,100,3,15,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'city','leadaddress',1,'1','city','City',1,0,0,100,4,15,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'country','leadaddress',1,'1','country','Country',1,0,0,100,5,15,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'state','leadaddress',1,'1','state','State',1,0,0,100,6,15,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'pobox','leadaddress',1,'1','pobox','Po Box',1,0,0,100,2,15,1,'V~O',1,null,'BAS')");

//Block15 --End

//Block16 -- Start

$this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'description','crmentity',1,'19','description','Description',1,0,0,100,1,16,1,'V~O',1,null,'BAS')");

//Block16 -- End

//Lead Details -- END


//Contact Details -- START
//Block4 -- Start

$this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'salutation','contactdetails',1,'55','salutationtype','Salutation',1,0,0,100,1,4,3,'V~O',1,null,'BAS')");	
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'firstname','contactdetails',1,'55','firstname','First Name',1,0,0,100,2,4,1,'V~O',0,1,'BAS')");	
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'phone','contactdetails',1,'11','phone','Office Phone',1,0,0,100,3,4,1,'V~O',0,4,'BAS')");	
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'lastname','contactdetails',1,'2','lastname','Last Name',1,0,0,100,4,4,1,'V~M',0,2,'BAS')");	
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'mobile','contactdetails',1,'1','mobile','Mobile',1,0,0,100,5,4,1,'V~O',1,null,'BAS')");	
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'accountid','contactdetails',1,'51','account_id','Account Name',1,0,0,100,6,4,1,'I~O',0,3,'BAS')");	
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'campaignid','contactdetails',1,'51','campaignid','Campaign Name',1,0,0,100,6,4,3,'I~O',1,null,'BAS')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'homephone','contactsubdetails',1,'11','homephone','Home Phone',1,0,0,100,7,4,1,'V~O',1,null,'ADV')");	
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'leadsource','contactsubdetails',1,'15','leadsource','Lead Source',1,0,0,100,8,4,1,'V~O',1,null,'BAS')");	
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'otherphone','contactsubdetails',1,'11','otherphone','Phone',1,0,0,100,9,4,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'title','contactdetails',1,'1','title','Title',1,0,0,100,10,4,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'fax','contactdetails',1,'1','fax','Fax',1,0,0,100,11,4,1,'V~O',1,null,'BAS')");	
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'department','contactdetails',1,'1','department','Department',1,0,0,100,12,4,1,'V~O',1,null,'ADV')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'birthday','contactsubdetails',1,'5','birthday','Birthdate',1,0,0,100,14,4,1,'V~O',1,null,'ADV')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'email','contactdetails',1,'13','email','Email',1,0,0,100,15,4,1,'E~O',0,5,'ADV')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'reportsto','contactdetails',1,'57','contact_id','Reports To',1,0,0,100,16,4,1,'V~O',1,null,'ADV')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'assistant','contactsubdetails',1,'1','assistant','Assistant',1,0,0,100,17,4,1,'V~O',1,null,'ADV')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'yahooid','contactdetails',1,'13','yahooid','Yahoo Id',1,0,0,100,18,4,1,'V~O',1,null,'ADV')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'assistantphone','contactsubdetails',1,'11','assistantphone','Assistant Phone',1,0,0,100,19,4,1,'V~O',1,null,'ADV')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'donotcall','contactdetails',1,'56','donotcall','Do Not Call',1,0,0,100,20,4,1,'C~O',1,null,'ADV')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'emailoptout','contactdetails',1,'56','emailoptout','Email Opt Out',1,0,0,100,21,4,1,'C~O',1,null,'ADV')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'smownerid','crmentity',1,'53','assigned_user_id','Assigned To',1,0,0,100,22,4,1,'V~M',1,null,'BAS')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'reference','contactdetails',1,'56','reference','Reference',1,0,0,10,23,4,1,'C~O',1,null,'ADV')");
$this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'70','createdtime','Created Time',1,0,0,100,24,4,2,'T~O',1,null,'BAS')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'70','modifiedtime','Modified Time',1,0,0,100,25,4,2,'T~O',1,null,'BAS')");

//Block4 -- End

//Block6 - Begin Customer Portal

$this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'portal','customerdetails',1,'56','portal','Portal User',1,0,0,100,1,6,1,'C~O',1,null,'ADV')");
$this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'support_start_date','customerdetails',1,'5','support_start_date','Support Start Date',1,0,0,100,2,6,1,'D~O',1,null,'ADV')");
$this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'support_end_date','customerdetails',1,'5','support_end_date','Support End Date',1,0,0,100,3,6,1,'D~O~OTH~GE~support_start_date~Support Start Date',1,null,'ADV')");

//Block6 - End Customer Portal

//Block 7 -- Start

$this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'mailingstreet','contactaddress',1,'21','mailingstreet','Mailing Street',1,0,0,100,1,7,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'otherstreet','contactaddress',1,'21','otherstreet','Other Street',1,0,0,100,2,7,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'mailingcity','contactaddress',1,'1','mailingcity','Mailing City',1,0,0,100,5,7,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'othercity','contactaddress',1,'1','othercity','Other City',1,0,0,100,6,7,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'mailingstate','contactaddress',1,'1','mailingstate','Mailing State',1,0,0,100,7,7,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'otherstate','contactaddress',1,'1','otherstate','Other State',1,0,0,100,8,7,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'mailingzip','contactaddress',1,'1','mailingzip','Mailing Zip',1,0,0,100,9,7,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'otherzip','contactaddress',1,'1','otherzip','Other Zip',1,0,0,100,10,7,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'mailingcountry','contactaddress',1,'1','mailingcountry','Mailing Country',1,0,0,100,11,7,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'othercountry','contactaddress',1,'1','othercountry','Other Country',1,0,0,100,12,7,1,'V~O',1,null,'BAS')");
$this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'mailingpobox','contactaddress',1,'1','mailingpobox','Mailing Po Box',1,0,0,100,3,7,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'otherpobox','contactaddress',1,'1','otherpobox','Other Po Box',1,0,0,100,4,7,1,'V~O',1,null,'BAS')");
//Block7 -- End

//ContactImageInformation
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'imagename','contactdetails',1,'69','imagename','Contact Image',1,0,0,100,1,75,1,'V~O',1,null,'ADV')");


//Block8 -- Start
$this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'description','crmentity',1,'19','description','Description',1,0,0,100,1,8,1,'V~O',1,null,'BAS')");
//Block8 -- End
//Contact Details -- END


//Potential Details -- START
//Block1 -- Start

$this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'potentialname','potential',1,'2','potentialname','Potential Name',1,0,0,100,1,1,1,'V~M',0,1,'BAS')");	
 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'amount','potential',1,71,'amount','Amount',1,0,0,100,2,1,1,'N~O',0,5,'BAS')");	
 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'accountid','potential',1,'50','account_id','Account Name',1,0,0,100,3,1,1,'V~M',0,2,'BAS')");	
 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'closingdate','potential',1,'23','closingdate','Expected Close Date',1,0,0,100,5,1,1,'D~M',0,3,'BAS')");	
 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'potentialtype','potential',1,'15','opportunity_type','Type',1,0,0,100,6,1,1,'V~O',1,null,'BAS')");	
 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'nextstep','potential',1,'1','nextstep','Next Step',1,0,0,100,7,1,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'leadsource','potential',1,'15','leadsource','Lead Source',1,0,0,100,8,1,1,'V~O',1,null,'BAS')");	
 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'sales_stage','potential',1,'16','sales_stage','Sales Stage',1,0,0,100,9,1,1,'V~O',0,4,'BAS')");
 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'smownerid','crmentity',1,'53','assigned_user_id','Assigned To',1,0,0,100,10,1,1,'V~M',1,null,'BAS')");
 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'probability','potential',1,'9','probability','Probability',1,0,0,100,11,1,1,'N~O',1,null,'BAS')");
$this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'70','createdtime','Created Time',1,0,0,100,13,1,2,'T~O',1,null,'BAS')");
 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'70','modifiedtime','Modified Time',1,0,0,100,14,1,2,'T~O',1,null,'BAS')");

//Block1 -- End

//Block3 -- Start

 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'description','crmentity',1,'19','description','Description',1,0,0,100,1,3,1,'V~O',1,null,'BAS')");

//Block3 -- End
//Potential Details -- END


//campaign entries being added


 $this->db->query("insert into field values (26,".$this->db->getUniqueID("field").",'campaignname','campaign',1,'2','campaignname','Campaign Name',1,0,0,100,1,76,1,'V~M',0,1,'BAS')");
 $this->db->query("insert into field values (26,".$this->db->getUniqueID("field").",'campaigntype','campaign',1,15,'campaigntype','Campaign Type',1,0,0,100,2,76,1,'V~O',0,5,'BAS')");
 $this->db->query("insert into field values (26,".$this->db->getUniqueID("field").",'campaignstatus','campaign',1,15,'campaignstatus','Campaign Status',1,0,0,100,3,76,1,'V~O',0,5,'BAS')");
 $this->db->query("insert into field values (26,".$this->db->getUniqueID("field").",'closingdate','campaign',1,'23','closingdate','Expected Close Date',1,0,0,100,5,76,1,'D~M',0,3,'BAS')");
 $this->db->query("insert into field values (26,".$this->db->getUniqueID("field").",'expectedrevenue','campaign',1,'15','expectedrevenue','Expected Revenue',1,0,0,100,6,76,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (26,".$this->db->getUniqueID("field").",'budgetcost','campaign',1,'1','budgetcost','Budget Cost',1,0,0,100,7,76,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (26,".$this->db->getUniqueID("field").",'actualcost','campaign',1,'15','actualcost','Actual Cost',1,0,0,100,8,76,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (26,".$this->db->getUniqueID("field").",'expectedresponse','campaign',1,'16','expectedresponse','Expected Response',1,0,0,100,9,76,1,'V~O',0,4,'BAS')");
 $this->db->query("insert into field values (26,".$this->db->getUniqueID("field").",'smownerid','crmentity',1,'53','assigned_user_id','Assigned To',1,0,0,100,10,76,1,'V~M',1,null,'BAS')");
 $this->db->query("insert into field values (26,".$this->db->getUniqueID("field").",'numsent','campaign',1,'9','numsent','Num Sent',1,0,0,100,11,76,1,'N~O',1,null,'BAS')");
$this->db->query("insert into field values (26,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'70','createdtime','Created Time',1,0,0,100,13,76,2,'T~O',1,null,'BAS')");
 $this->db->query("insert into field values (26,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'70','modifiedtime','Modified Time',1,0,0,100,14,76,2,'T~O',1,null,'BAS')");

$this->db->query("insert into field values (26,".$this->db->getUniqueID("field").",'description','crmentity',1,'19','description','Description',1,0,0,100,1,77,1,'V~O',1,null,'BAS')");

//Campaign entries end


//Ticket Details -- START
//Block25 -- Start

 $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'smownerid','crmentity',1,'53','assigned_user_id','Assigned To',1,0,0,100,2,25,1,'V~M',1,null,'BAS')");
 $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'parent_id','troubletickets',1,'68','parent_id','Related To',1,0,0,100,4,25,1,'I~O',1,null,'BAS')");
 $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'priority','troubletickets',1,'15','ticketpriorities','Priority',1,0,0,100,5,25,1,'V~O',0,3,'BAS')");
 $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'product_id','troubletickets',1,'59','product_id','Product Name',1,0,0,100,6,25,1,'I~O',1,null,'BAS')");
 $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'severity','troubletickets',1,'15','ticketseverities','Severity',1,0,0,100,7,25,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'status','troubletickets',1,'15','ticketstatus','Status',1,0,0,100,8,25,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'category','troubletickets',1,'15','ticketcategories','Category',1,0,0,100,9,25,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'update_log','troubletickets',1,'15','update_log','Update History',1,0,0,100,9,25,3,'V~O',1,null,'BAS')");
$this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'70','createdtime','Created Time',1,0,0,100,10,25,2,'T~O',1,null,'BAS')");
 $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'70','modifiedtime','Modified Time',1,0,0,100,11,25,2,'T~O',1,null,'BAS')");
 //Added on 26-12-2005 to add attachment in ticket editview
 $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'filename','attachments',1,'61','filename','Attachment',1,0,0,100,12,26,1,'V~O',0,1,'BAS')");

 $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'title','troubletickets',1,'22','ticket_title','Title',1,0,0,100,1,26,1,'V~M',0,1,'BAS')");
 $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'description','troubletickets',1,'19','description','Description',1,0,0,100,1,28,1,'V~O',0,2,'BAS')");
 $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'solution','troubletickets',1,'19','solution','Solution',1,0,0,100,1,29,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'comments','ticketcomments',1,'19','comments','Add Comment',1,0,0,100,1,30,1,'V~O',1,null,'BAS')");

//Block25-30 -- End
//Ticket Details -- END

//Product Details -- START
//Block31-36 -- Start

$this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'productname','products',1,'2','productname','Product Name',1,0,0,100,1,31,1,'V~M',0,1,'BAS')");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'productcode','products',1,'1','productcode','Product Code',1,0,0,100,2,31,1,'V~O',0,2,'BAS')");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'discontinued','products',1,'56','discontinued','Product Active',1,0,0,100,3,31,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'manufacturer','products',1,'15','manufacturer','Manufacturer',1,0,0,100,4,1,31,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'productcategory','products',1,'15','productcategory','Product Category',1,0,0,100,4,31,1,'V~O',0,3,'BAS')");
$this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'sales_start_date','products',1,'5','sales_start_date','Sales Start Date',1,0,0,100,5,31,1,'D~O',1,null,'BAS')");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'sales_end_date','products',1,'5','sales_end_date','Sales End Date',1,0,0,100,6,31,1,'D~O~OTH~GE~sales_start_date~Sales Start Date',1,null,'BAS')");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'start_date','products',1,'5','start_date','Support Start Date',1,0,0,100,7,31,1,'D~O',1,null,'BAS')");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'expiry_date','products',1,'5','expiry_date','Support Expiry Date',1,0,0,100,8,26,1,'D~O~OTH~GE~start_date~Start Date',1,null,'BAS')");
 

 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'crmid','seproductsrel',1,'66','parent_id','Related To',1,0,0,100,10,31,1,'I~O',1,null,'BAS')");

 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'contactid','products',1,'57','contact_id','Contact Name',1,0,0,100,11,31,1,'I~O',1,null,'BAS')");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'website','products',1,'17','website','Website',1,0,0,100,12,31,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'vendor_id','products',1,'75','vendor_id','Vendor Name',1,0,0,100,13,31,1,'I~O',1,null,'BAS')");
$this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'mfr_part_no','products',1,'1','mfr_part_no','Mfr PartNo',1,0,0,100,14,31,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'vendor_part_no','products',1,'1','vendor_part_no','Vendor PartNo',1,0,0,100,15,31,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'serialno','products',1,'1','serial_no','Serial No',1,0,0,100,16,31,1,'V~O',1,null,'BAS')");
$this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'productsheet','products',1,'1','productsheet','Product Sheet',1,0,0,100,17,31,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'glacct','products',1,'15','glacct','GL Account',1,0,0,100,18,31,1,'V~O',1,null,'BAS')");
$this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'70','createdtime','Created Time',1,0,0,100,19,31,2,'T~O',1,null,'BAS')");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'70','modifiedtime','Modified Time',1,0,0,100,20,31,2,'T~O',1,null,'BAS')");


//Block32 Pricing Information

$this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'unit_price','products',1,'71','unit_price','Unit Price',1,0,0,100,1,32,1,'N~O',1,null,'BAS')"); 
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'commissionrate','products',1,'9','commissionrate','Commission Rate',1,0,0,100,2,32,1,'N~O',1,null,'BAS')"); 
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'taxclass','products',1,'15','taxclass','Tax Class',1,0,0,100,4,32,1,'V~O',1,null,'BAS')");


//Block 33 stock info

 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'usageunit','products',1,'15','usageunit','Usage Unit',1,0,0,100,1,33,1,'V~O',1,null,'ADV')");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'qty_per_unit','products',1,'1','qty_per_unit','Qty/Unit',1,0,0,100,2,33,1,'N~O',1,null,'ADV')");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'qtyinstock','products',1,'1','qtyinstock','Qty In Stock',1,0,0,100,3,33,1,'I~O',1,null,'ADV')");
$this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'reorderlevel','products',1,'1','reorderlevel','Reorder Level',1,0,0,100,4,33,1,'I~O',1,null,'ADV')");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'handler','products',1,'52','assigned_user_id','Handler',1,0,0,100,5,33,1,'I~O',1,null,'ADV')");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'qtyindemand','products',1,'1','qtyindemand','Qty In Demand',1,0,0,100,6,33,1,'I~O',1,null,'ADV')");


//ProductImageInformation

 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'imagename','products',1,'69','imagename','Product Image',1,0,0,100,1,35,1,'V~O',1,null,'ADV')");


//Block 36 Description Info
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'product_description','products',1,'19','product_description','Description',1,0,0,100,1,36,1,'V~O',1,null,'BAS')");

//Product Details -- END

//Note Details -- START
//Block17 -- Start

$this->db->query("insert into field values (8,".$this->db->getUniqueID("field").",'contact_id','notes',1,'57','contact_id','Contact Name',1,0,0,100,1,17,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (8,".$this->db->getUniqueID("field").",'crmid','senotesrel',1,'62','parent_id','Related To',1,0,0,100,2,17,1,'I~O',1,null,'BAS')");
 $this->db->query("insert into field values (8,".$this->db->getUniqueID("field").",'title','notes',1,'2','title','Subject',1,0,0,100,3,17,1,'V~M',0,1,'BAS')");
$this->db->query("insert into field values (8,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'70','createdtime','Created Time',1,0,0,100,4,17,2,'T~O',1,null,'BAS')");
 $this->db->query("insert into field values (8,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'70','modifiedtime','Modified Time',1,0,0,100,5,17,2,'T~O',1,null,'BAS')");
 $this->db->query("insert into field values (8,".$this->db->getUniqueID("field").",'filename','notes',1,'61','filename','Attachment',1,0,0,100,4,17,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (8,".$this->db->getUniqueID("field").",'notecontent','notes',1,'19','notecontent','Note',1,0,0,100,5,18,1,'V~O',1,null,'BAS')");

//Block17 -- End
//Note Details -- END

//Email Details -- START
//Block21 -- Start

	$this->db->query("insert into field values (10,".$this->db->getUniqueID("field").",'date_start','activity',1,'6','date_start','Date & Time Sent',1,0,0,100,1,21,1,'DT~M~time_start~Time Start',0,2,'BAS')");
	$this->db->query("insert into field values (10,".$this->db->getUniqueID("field").",'semodule','activity',1,'2','parent_type','Sales Enity Module',1,0,0,100,2,21,3,'',1,null,'BAS')");
	$this->db->query("insert into field values (10,".$this->db->getUniqueID("field").",'activitytype','activity',1,'2','activitytype','Activtiy Type',1,0,0,100,3,21,3,'V~O',1,null,'BAS')");
	$this->db->query("insert into field values (10,".$this->db->getUniqueID("field").",'crmid','seactivityrel',1,'357','parent_id','Related To',1,0,0,100,1,22,1,'I~O',1,null,'BAS')");
	$this->db->query("insert into field values (10,".$this->db->getUniqueID("field").",'smownerid','crmentity',1,'53','assigned_user_id','Assigned To',1,0,0,100,5,21,1,'V~M',1,null,'BAS')");
	$this->db->query("insert into field values (10,".$this->db->getUniqueID("field").",'subject','activity',1,'2','subject','Subject',1,0,0,100,1,23,1,'V~M',0,1,'BAS')");
	$this->db->query("insert into field values (10,".$this->db->getUniqueID("field").",'filename','attachments',1,'61','filename','Attachment',1,0,0,100,1,23,1,'V~O',1,null,'BAS')");
	$this->db->query("insert into field values (10,".$this->db->getUniqueID("field").",'description','crmentity',1,'19','description','Description',1,0,0,100,1,24,1,'V~O',1,null,'BAS')");
	$this->db->query("insert into field values (10,".$this->db->getUniqueID("field").",'time_start','activity',1,'2','time_start','Time Start',1,0,0,100,9,1,23,'T~O',1,null,'BAS')");
	$this->db->query("insert into field values (10,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'70','createdtime','Created Time',1,0,0,100,10,1,22,'T~O',1,null,'BAS')");
 	$this->db->query("insert into field values (10,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'70','modifiedtime','Modified Time',1,0,0,100,11,21,2,'T~O',1,null,'BAS')");

//Block21 -- End
//Email Details -- END

//Task Details --START
//Block19 -- Start
$this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'subject','activity',1,'2','subject','Subject',1,0,0,100,1,19,1,'V~M',0,1,'BAS')");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'smownerid','crmentity',1,'53','assigned_user_id','Assigned To',1,0,0,100,2,19,1,'V~M',1,null,'BAS')");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'date_start','activity',1,'6','date_start','Start Date & Time',1,0,0,100,3,19,1,'DT~M~time_start',0,2,'BAS')");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'time_start','activity',1,'2','time_start','Time Start',1,0,0,100,4,19,3,'T~O',1,null,'BAS')");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'due_date','activity',1,'23','due_date','Due Date',1,0,0,100,5,19,1,'D~M~OTH~GE~date_start~Start Date & Time',1,null,'BAS')");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'crmid','seactivityrel',1,'66','parent_id','Related To',1,0,0,100,7,19,1,'I~O',1,null,'BAS')");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'contactid','cntactivityrel',1,'57','contact_id','Contact Name',1,0,0,100,8,19,1,'I~O',1,null,'BAS')");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'status','activity',1,'15','taskstatus','Status',1,0,0,100,9,19,1,'V~O',0,3,'BAS')");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'eventstatus','activity',1,'15','eventstatus','Status',1,0,0,100,9,19,3,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'priority','activity',1,'15','taskpriority','Priority',1,0,0,100,10,19,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'sendnotification','activity',1,'56','sendnotification','Send Notification',1,0,0,100,11,19,1,'C~O',1,null,'BAS')");
$this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'70','createdtime','Created Time',1,0,0,100,14,19,2,'T~O',1,null,'BAS')");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'70','modifiedtime','Modified Time',1,0,0,100,15,19,2,'T~O',1,null,'BAS')");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'activitytype','activity',1,'15','activitytype','Activity Type',1,0,0,100,16,19,3,'V~O',1,null,'BAS')");
 $this->db->query("Insert into field values (9,".$this->db->getUniqueID("field").",'visibility','activity',1,15,'visibility','Visibility',1,0,0,100,17,19,3,'V~O',1,null,'BAS')");

 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'description','crmentity',1,'19','description','Description',1,0,0,100,1,20,1,'V~O',1,null,'BAS')");


$this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'duration_hours','activity',1,'63','duration_hours','Duration',1,0,0,100,17,19,3,'T~O',1,null,'BAS')");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'duration_minutes','activity',1,'15','duration_minutes','Duration Minutes',1,0,0,100,18,19,3,'T~O',1,null,'BAS')");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'location','activity',1,'1','location','Location',1,0,0,100,19,19,3,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'reminder_time','activity_reminder',1,'30','reminder_time','Send Reminder',1,0,0,100,1,19,3,'I~O',1,null,'BAS')");
 
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'recurringtype','recurringevents',1,'15','recurringtype','Recurrence',1,0,0,100,6,19,3,'O~O',1,null,'BAS')");

 $this->db->query("Insert into field values (9,".$this->db->getUniqueID("field").",'notime','activity',1,56,'notime','No Time',1,0,0,100,20,19,3,'C~O',1,null,'BAS')");
//Block19 -- End
//Task Details -- END

//Event Details --START
//Block41-43-- Start
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'subject','activity',1,'2','subject','Subject',1,0,0,100,1,41,1,'V~M',0,1,'BAS')");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'smownerid','crmentity',1,'53','assigned_user_id','Assigned To',1,0,0,100,2,41,1,'I~O',1,null,'BAS')");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'date_start','activity',1,'6','date_start','Start Date & Time',1,0,0,100,3,41,1,'DT~M~time_start',0,2,'BAS')");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'time_start','activity',1,'2','time_start','Time Start',1,0,0,100,4,41,3,'T~M',1,null,'BAS')");

 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'due_date','activity',1,'23','due_date','End Date',1,0,0,100,5,41,1,'D~M~OTH~GE~date_start~Start Date & Time',1,null,'BAS')");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'recurringtype','recurringevents',1,'15','recurringtype','Recurrence',1,0,0,100,6,41,1,'O~O',1,null,'BAS')");
  
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'duration_hours','activity',1,'63','duration_hours','Duration',1,0,0,100,7,41,1,'I~M',0,5,'BAS')");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'duration_minutes','activity',1,'15','duration_minutes','Duration Minutes',1,0,0,100,8,41,3,'O~O',1,null,'BAS')");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'crmid','seactivityrel',1,'66','parent_id','Related To',1,0,0,100,9,41,1,'I~O',1,null,'BAS')");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'contactid','cntactivityrel',1,'57','contact_id','Contact Name',1,0,0,100,10,41,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'eventstatus','activity',1,'15','eventstatus','Status',1,0,0,100,11,41,1,'V~O',0,3,'BAS')");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'sendnotification','activity',1,'56','sendnotification','Send Notification',1,0,0,100,12,41,1,'C~O',1,null,'BAS')");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'activitytype','activity',1,'15','activitytype','Activity Type',1,0,0,100,13,41,1,'V~O',0,4,'BAS')");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'location','activity',1,'1','location','Location',1,0,0,100,14,41,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'70','createdtime','Created Time',1,0,0,100,15,41,2,'T~O',1,null,'BAS')");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'70','modifiedtime','Modified Time',1,0,0,100,16,41,2,'T~O',1,null,'BAS')");
 $this->db->query("Insert into field values (16,".$this->db->getUniqueID("field").",'priority','activity',1,15,'taskpriority','Priority',1,0,0,100,17,41,1,'V~O',1,null,'BAS')");
 $this->db->query("Insert into field values (16,".$this->db->getUniqueID("field").",'notime','activity',1,56,'notime','No Time',1,0,0,100,18,41,1,'C~O',1,null,'BAS')");
 $this->db->query("Insert into field values (16,".$this->db->getUniqueID("field").",'visibility','activity',1,15,'visibility','Visibility',1,0,0,100,19,41,1,'V~O',1,null,'BAS')");
 
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'description','crmentity',1,'19','description','Description',1,0,0,100,1,43,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'reminder_time','activity_reminder',1,'30','reminder_time','Send Reminder',1,0,0,100,1,42,1,'I~O',1,null,'BAS')");
//Block41-43 -- End
//Event Details -- END

//Faq Details -- START
//Block37-40 -- Start

$this->db->query("insert into field values (15,".$this->db->getUniqueID("field").",'product_id','faq',1,'59','product_id','Product Name',1,0,0,100,1,37,1,'I~O',1,null,'BAS')");
 $this->db->query("insert into field values (15,".$this->db->getUniqueID("field").",'category','faq',1,'15','faqcategories','Category',1,0,0,100,2,37,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (15,".$this->db->getUniqueID("field").",'status','faq',1,'15','faqstatus','Status',1,0,0,100,3,37,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (15,".$this->db->getUniqueID("field").",'question','faq',1,'20','question','Question',1,0,0,100,1,38,1,'V~M',1,null,'BAS')");
 $this->db->query("insert into field values (15,".$this->db->getUniqueID("field").",'answer','faq',1,'20','faq_answer','Answer',1,0,0,100,1,39,1,'V~M',1,null,'BAS')");
 $this->db->query("insert into field values (15,".$this->db->getUniqueID("field").",'comments','faqcomments',1,'19','comments','Add Comment',1,0,0,100,1,40,1,'V~O',1,null,'BAS')");
$this->db->query("insert into field values (15,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'70','createdtime','Created Time',1,0,0,100,3,37,2,'T~O',1,null,'BAS')");
 $this->db->query("insert into field values (15,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'70','modifiedtime','Modified Time',1,0,0,100,4,37,2,'T~O',1,null,'BAS')");


//Block37-40 -- End
//Ticket Details -- END

//Vendor Details --START
//Block44-47

$this->db->query("insert into field values (18,".$this->db->getUniqueID("field").",'vendorname','vendor',1,'2','vendorname','Vendor Name',1,0,0,100,1,44,1,'V~M',1,null,'BAS')");
 $this->db->query("insert into field values (18,".$this->db->getUniqueID("field").",'phone','vendor',1,'1','phone','Phone',1,0,0,100,3,44,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (18,".$this->db->getUniqueID("field").",'email','vendor',1,'13','email','Email',1,0,0,100,4,44,1,'E~O',1,null,'BAS')");
 $this->db->query("insert into field values (18,".$this->db->getUniqueID("field").",'website','vendor',1,'17','website','Website',1,0,0,100,5,44,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (18,".$this->db->getUniqueID("field").",'glacct','vendor',1,'15','glacct','GL Account',1,0,0,100,6,44,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (18,".$this->db->getUniqueID("field").",'category','vendor',1,'1','category','Category',1,0,0,100,7,44,1,'V~O',1,null,'BAS')");
$this->db->query("insert into field values (18,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'70','createdtime','Created Time',1,0,0,100,8,44,2,'T~O',1,null,'BAS')");
 $this->db->query("insert into field values (18,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'70','modifiedtime','Modified Time',1,0,0,100,9,44,2,'T~O',1,null,'BAS')");

//Block 46

$this->db->query("insert into field values (18,".$this->db->getUniqueID("field").",'street','vendor',1,'21','treet','Street',1,0,0,100,1,46,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (18,".$this->db->getUniqueID("field").",'pobox','vendor',1,'1','pobox','Po Box',1,0,0,100,2,46,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (18,".$this->db->getUniqueID("field").",'city','vendor',1,'1','city','City',1,0,0,100,3,46,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (18,".$this->db->getUniqueID("field").",'state','vendor',1,'1','state','State',1,0,0,100,4,46,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (18,".$this->db->getUniqueID("field").",'postalcode','vendor',1,'1','postalcode','Postal Code',1,0,0,100,5,46,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (18,".$this->db->getUniqueID("field").",'country','vendor',1,'1','country','Country',1,0,0,100,6,46,1,'V~O',1,null,'BAS')");

//Block 47

$this->db->query("insert into field values (18,".$this->db->getUniqueID("field").",'description','crmentity',1,'19','description','Description',1,0,0,100,1,47,1,'V~O',1,null,'BAS')");

//Vendor Details -- END

//PriceBook Details Start
//Block48

$this->db->query("insert into field values (19,".$this->db->getUniqueID("field").",'bookname','pricebook',1,'2','bookname','Price Book Name',1,0,0,100,1,48,1,'V~M',1,null,'BAS')");
 $this->db->query("insert into field values (19,".$this->db->getUniqueID("field").",'active','pricebook',1,'56','active','Active',1,0,0,100,3,48,1,'V~O',1,null,'BAS')");
$this->db->query("insert into field values (19,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'70','createdtime','Created Time',1,0,0,100,4,48,2,'T~O',1,null,'BAS')");
 $this->db->query("insert into field values (19,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'70','modifiedtime','Modified Time',1,0,0,100,5,48,2,'T~O',1,null,'BAS')");

//Block50

$this->db->query("insert into field values (19,".$this->db->getUniqueID("field").",'description','crmentity',1,'19','description','Description',1,0,0,100,1,50,1,'V~O',1,null,'BAS')");

//PriceBook Details End


//Quote Details -- START
 //Block51

$this->db->query("insert into field values (20,".$this->db->getUniqueID("field").",'subject','quotes',1,'2','subject','Subject',1,0,0,100,1,51,1,'V~M',1,null,'BAS')");	
 $this->db->query("insert into field values (20,".$this->db->getUniqueID("field").",'potentialid','quotes',1,'76','potential_id','Potential Name',1,0,0,100,2,51,1,'I~O',1,null,'BAS')");	
 $this->db->query("insert into field values (20,".$this->db->getUniqueID("field").",'quotestage','quotes',1,'15','quotestage','Quote Stage',1,0,0,100,3,51,1,'V~O',1,null,'BAS')");	

 $this->db->query("insert into field values (20,".$this->db->getUniqueID("field").",'validtill','quotes',1,'5','validtill','Valid Till',1,0,0,100,4,51,1,'D~O',1,null,'BAS')");	
 $this->db->query("insert into field values (20,".$this->db->getUniqueID("field").",'team','quotes',1,'1','team','Team',1,0,0,100,5,51,1,'V~O',1,null,'BAS')");	
 $this->db->query("insert into field values (20,".$this->db->getUniqueID("field").",'contactid','quotes',1,'57','contact_id','Contact Name',1,0,0,100,6,51,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (20,".$this->db->getUniqueID("field").",'carrier','quotes',1,'15','carrier','Carrier',1,0,0,100,8,51,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (20,".$this->db->getUniqueID("field").",'subtotal','quotes',1,'1','hdnSubTotal','Sub Total',1,0,0,100,9,51,3,'N~O',1,null,'BAS')");
 $this->db->query("insert into field values (20,".$this->db->getUniqueID("field").",'shipping','quotes',1,'1','shipping','Shipping',1,0,0,100,10,51,1,'V~O',1,null,'BAS')");

 $this->db->query("insert into field values (20,".$this->db->getUniqueID("field").",'inventorymanager','quotes',1,'77','assigned_user_id1','Inventory Manager',1,0,0,100,11,51,1,'I~O',1,null,'BAS')");
 $this->db->query("insert into field values (20,".$this->db->getUniqueID("field").",'tax','quotes',1,'1','txtTax','Tax',1,0,0,100,13,51,3,'N~O',1,null,'BAS')");
 $this->db->query("insert into field values (20,".$this->db->getUniqueID("field").",'adjustment','quotes',1,'1','txtAdjustment','Adjustment',1,0,0,100,20,51,3,'NN~O',1,null,'BAS')");
 $this->db->query("insert into field values (20,".$this->db->getUniqueID("field").",'total','quotes',1,'1','hdnGrandTotal','Total',1,0,0,100,14,51,3,'N~O',1,null,'BAS')");
 $this->db->query("insert into field values (20,".$this->db->getUniqueID("field").",'accountid','quotes',1,'73','account_id','Account Name',1,0,0,100,16,51,1,'I~M',1,null,'BAS')");
 $this->db->query("insert into field values (20,".$this->db->getUniqueID("field").",'smownerid','crmentity',1,'53','assigned_user_id','Assigned To',1,0,0,100,17,51,1,'V~M',1,null,'BAS')");
 $this->db->query("insert into field values (20,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'70','createdtime','Created Time',1,0,0,100,18,51,2,'T~O',1,null,'BAS')");
 $this->db->query("insert into field values (20,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'70','modifiedtime','Modified Time',1,0,0,100,19,51,2,'T~O',1,null,'BAS')");


 //Block 53

$this->db->query("insert into field values (20,".$this->db->getUniqueID("field").",'bill_street','quotesbillads',1,'24','bill_street','Billing Address',1,0,0,100,1,53,1,'V~M',1,null,'BAS')");
 $this->db->query("insert into field values (20,".$this->db->getUniqueID("field").",'ship_street','quotesshipads',1,'24','ship_street','Shipping Address',1,0,0,100,2,53,1,'V~M',1,null,'BAS')");

 $this->db->query("insert into field values (20,".$this->db->getUniqueID("field").",'bill_city','quotesbillads',1,'1','bill_city','Billing City',1,0,0,100,5,53,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (20,".$this->db->getUniqueID("field").",'ship_city','quotesshipads',1,'1','ship_city','Shipping City',1,0,0,100,6,53,1,'V~O',1,null,'BAS')");

 $this->db->query("insert into field values (20,".$this->db->getUniqueID("field").",'bill_state','quotesbillads',1,'1','bill_state','Billing State',1,0,0,100,7,53,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (20,".$this->db->getUniqueID("field").",'ship_state','quotesshipads',1,'1','ship_state','Shipping State',1,0,0,100,8,53,1,'V~O',1,null,'BAS')");

 $this->db->query("insert into field values (20,".$this->db->getUniqueID("field").",'bill_code','quotesbillads',1,'1','bill_code','Billing Code',1,0,0,100,9,53,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (20,".$this->db->getUniqueID("field").",'ship_code','quotesshipads',1,'1','ship_code','Shipping Code',1,0,0,100,10,53,1,'V~O',1,null,'BAS')");


 $this->db->query("insert into field values (20,".$this->db->getUniqueID("field").",'bill_country','quotesbillads',1,'1','bill_country','Billing Country',1,0,0,100,11,53,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (20,".$this->db->getUniqueID("field").",'ship_country','quotesshipads',1,'1','ship_country','Shipping Country',1,0,0,100,12,53,1,'V~O',1,null,'BAS')");

$this->db->query("insert into field values (20,".$this->db->getUniqueID("field").",'bill_pobox','quotesbillads',1,'1','bill_pobox','Billing Po Box',1,0,0,100,3,53,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (20,".$this->db->getUniqueID("field").",'ship_pobox','quotesshipads',1,'1','ship_pobox','Shipping Po Box',1,0,0,100,4,53,1,'V~O',1,null,'BAS')");
 //Block55

$this->db->query("insert into field values (20,".$this->db->getUniqueID("field").",'description','crmentity',1,'19','description','Description',1,0,0,100,1,56,1,'V~O',1,null,'BAS')");

//Block 56
$this->db->query("insert into field values (20,".$this->db->getUniqueID("field").",'terms_conditions','quotes',1,'19','terms_conditions','Terms & Conditions',1,0,0,100,1,55,1,'V~O',1,null,'BAS')");


//Quote Details -- END

//Purchase Order Details -- START
 //Block57
 
$this->db->query("insert into field values (21,".$this->db->getUniqueID("field").",'subject','purchaseorder',1,'2','subject','Subject',1,0,0,100,1,57,1,'V~M',1,null,'BAS')");	
 $this->db->query("insert into field values (21,".$this->db->getUniqueID("field").",'vendorid','purchaseorder',1,'81','vendor_id','Vendor Name',1,0,0,100,3,57,1,'I~M',1,null,'BAS')");	
 $this->db->query("insert into field values (21,".$this->db->getUniqueID("field").",'requisition_no','purchaseorder',1,'1','requisition_no','Requisition No',1,0,0,100,4,57,1,'V~O',1,null,'BAS')");	

 $this->db->query("insert into field values (21,".$this->db->getUniqueID("field").",'tracking_no','purchaseorder',1,'1','tracking_no','Tracking Number',1,0,0,100,5,57,1,'V~O',1,null,'BAS')");	
 $this->db->query("insert into field values (21,".$this->db->getUniqueID("field").",'contactid','purchaseorder',1,'57','contact_id','Contact Name',1,0,0,100,6,57,1,'I~O',1,null,'BAS')");	
 $this->db->query("insert into field values (21,".$this->db->getUniqueID("field").",'duedate','purchaseorder',1,'5','duedate','Due Date',1,0,0,100,7,57,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (21,".$this->db->getUniqueID("field").",'carrier','purchaseorder',1,'15','carrier','Carrier',1,0,0,100,8,57,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (21,".$this->db->getUniqueID("field").",'salestax','purchaseorder',1,'1','txtTax','Sales Tax',1,0,0,100,10,57,3,'N~O',1,null,'BAS')");
 $this->db->query("insert into field values (21,".$this->db->getUniqueID("field").",'adjustment','purchaseorder',1,'1','txtAdjustment','Adjustment',1,0,0,100,10,57,3,'NN~O',1,null,'BAS')");
 $this->db->query("insert into field values (21,".$this->db->getUniqueID("field").",'salescommission','purchaseorder',1,'1','salescommission','Sales Commission',1,0,0,100,11,57,1,'N~O',1,null,'BAS')");
 $this->db->query("insert into field values (21,".$this->db->getUniqueID("field").",'exciseduty','purchaseorder',1,'1','exciseduty','Excise Duty',1,0,0,100,12,57,1,'N~O',1,null,'BAS')");
 $this->db->query("insert into field values (21,".$this->db->getUniqueID("field").",'total','purchaseorder',1,'1','hdnGrandTotal','Total',1,0,0,100,13,57,3,'N~O',1,null,'BAS')");
 $this->db->query("insert into field values (21,".$this->db->getUniqueID("field").",'subtotal','purchaseorder',1,'1','hdnSubTotal','Sub Total',1,0,0,100,14,57,3,'N~O',1,null,'BAS')");
 $this->db->query("insert into field values (21,".$this->db->getUniqueID("field").",'postatus','purchaseorder',1,'15','postatus','Status',1,0,0,100,15,57,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (21,".$this->db->getUniqueID("field").",'smownerid','crmentity',1,'53','assigned_user_id','Assigned To',1,0,0,100,16,57,1,'V~M',1,null,'BAS')");
 $this->db->query("insert into field values (21,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'70','createdtime','Created Time',1,0,0,100,17,57,2,'T~O',1,null,'BAS')");
 $this->db->query("insert into field values (21,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'70','modifiedtime','Modified Time',1,0,0,100,18,57,2,'T~O',1,null,'BAS')");



 //Block 59

$this->db->query("insert into field values (21,".$this->db->getUniqueID("field").",'bill_street','pobillads',1,'24','bill_street','Billing Address',1,0,0,100,1,59,1,'V~M',1,null,'BAS')");
 $this->db->query("insert into field values (21,".$this->db->getUniqueID("field").",'ship_street','poshipads',1,'24','ship_street','Shipping Address',1,0,0,100,2,59,1,'V~M',1,null,'BAS')");

 $this->db->query("insert into field values (21,".$this->db->getUniqueID("field").",'bill_city','pobillads',1,'1','bill_city','Billing City',1,0,0,100,5,59,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (21,".$this->db->getUniqueID("field").",'ship_city','poshipads',1,'1','ship_city','Shipping City',1,0,0,100,6,59,1,'V~O',1,null,'BAS')");

 $this->db->query("insert into field values (21,".$this->db->getUniqueID("field").",'bill_state','pobillads',1,'1','bill_state','Billing State',1,0,0,100,7,59,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (21,".$this->db->getUniqueID("field").",'ship_state','poshipads',1,'1','ship_state','Shipping State',1,0,0,100,8,59,1,'V~O',1,null,'BAS')");

 $this->db->query("insert into field values (21,".$this->db->getUniqueID("field").",'bill_code','pobillads',1,'1','bill_code','Billing Code',1,0,0,100,9,59,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (21,".$this->db->getUniqueID("field").",'ship_code','poshipads',1,'1','ship_code','Shipping Code',1,0,0,100,10,59,1,'V~O',1,null,'BAS')");


 $this->db->query("insert into field values (21,".$this->db->getUniqueID("field").",'bill_country','pobillads',1,'1','bill_country','Billing Country',1,0,0,100,11,59,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (21,".$this->db->getUniqueID("field").",'ship_country','poshipads',1,'1','ship_country','Shipping Country',1,0,0,100,12,59,1,'V~O',1,null,'BAS')");

 $this->db->query("insert into field values (21,".$this->db->getUniqueID("field").",'bill_pobox','pobillads',1,'1','bill_pobox','Billing Po Box',1,0,0,100,3,59,1,'V~O',1,null,'BAS')");
  $this->db->query("insert into field values (21,".$this->db->getUniqueID("field").",'ship_pobox','poshipads',1,'1','ship_pobox','Shipping Po Box',1,0,0,100,4,59,1,'V~O',1,null,'BAS')");
  
 //Block61
 $this->db->query("insert into field values (21,".$this->db->getUniqueID("field").",'description','crmentity',1,'19','description','Description',1,0,0,100,1,62,1,'V~O',1,null,'BAS')");

 //Block62
 $this->db->query("insert into field values (21,".$this->db->getUniqueID("field").",'terms_conditions','purchaseorder',1,'19','terms_conditions','Terms & Conditions',1,0,0,100,1,61,1,'V~O',1,null,'BAS')");

//Purchase Order Details -- END

//Sales Order Details -- START
 //Block63
 
$this->db->query("insert into field values (22,".$this->db->getUniqueID("field").",'subject','salesorder',1,'2','subject','Subject',1,0,0,100,1,63,1,'V~M',1,null,'BAS')");	
 $this->db->query("insert into field values (22,".$this->db->getUniqueID("field").",'potentialid','salesorder',1,'76','potential_id','Potential Name',1,0,0,100,2,63,1,'I~O',1,null,'BAS')");	
 $this->db->query("insert into field values (22,".$this->db->getUniqueID("field").",'customerno','salesorder',1,'1','customerno','Customer No',1,0,0,100,3,63,1,'V~O',1,null,'BAS')");
$this->db->query("insert into field values (22,".$this->db->getUniqueID("field").",'quoteid','salesorder',1,'78','quote_id','Quote Name',1,0,0,100,4,63,1,'I~O',1,null,'BAS')");	
$this->db->query("insert into field values (22,".$this->db->getUniqueID("field").",'purchaseorder','salesorder',1,'1','purchaseorder','Purchase Order',1,0,0,100,4,63,1,'V~O',1,null,'BAS')");	

 $this->db->query("insert into field values (22,".$this->db->getUniqueID("field").",'contactid','salesorder',1,'57','contact_id','Contact Name',1,0,0,100,6,63,1,'I~O',1,null,'BAS')");	
 $this->db->query("insert into field values (22,".$this->db->getUniqueID("field").",'duedate','salesorder',1,'5','duedate','Due Date',1,0,0,100,8,63,1,'D~O',1,null,'BAS')");
 $this->db->query("insert into field values (22,".$this->db->getUniqueID("field").",'carrier','salesorder',1,'15','carrier','Carrier',1,0,0,100,9,63,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (22,".$this->db->getUniqueID("field").",'pending','salesorder',1,'1','pending','Pending',1,0,0,100,10,63,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (22,".$this->db->getUniqueID("field").",'sostatus','salesorder',1,'15','sostatus','Status',1,0,0,100,11,63,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (22,".$this->db->getUniqueID("field").",'salestax','salesorder',1,'1','txtTax','Sales Tax',1,0,0,100,12,63,3,'N~O',1,null,'BAS')");
 $this->db->query("insert into field values (22,".$this->db->getUniqueID("field").",'adjustment','salesorder',1,'1','txtAdjustment','Sales Tax',1,0,0,100,12,63,3,'NN~O',1,null,'BAS')");
 $this->db->query("insert into field values (22,".$this->db->getUniqueID("field").",'salescommission','salesorder',1,'1','salescommission','Sales Commission',1,0,0,100,13,63,1,'N~O',1,null,'BAS')");
 $this->db->query("insert into field values (22,".$this->db->getUniqueID("field").",'exciseduty','salesorder',1,'1','exciseduty','Excise Duty',1,0,0,100,13,63,1,'N~O',1,null,'BAS')");
 $this->db->query("insert into field values (22,".$this->db->getUniqueID("field").",'total','salesorder',1,'1','hdnGrandTotal','Total',1,0,0,100,14,63,3,'N~O',1,null,'BAS')");
 $this->db->query("insert into field values (22,".$this->db->getUniqueID("field").",'subtotal','salesorder',1,'1','hdnSubTotal','Total',1,0,0,100,15,63,3,'N~O',1,null,'BAS')");
 $this->db->query("insert into field values (22,".$this->db->getUniqueID("field").",'accountid','salesorder',1,'73','account_id','Account Name',1,0,0,100,16,63,1,'I~M',1,null,'BAS')");
 $this->db->query("insert into field values (22,".$this->db->getUniqueID("field").",'smownerid','crmentity',1,'53','assigned_user_id','Assigned To',1,0,0,100,17,63,1,'V~M',1,null,'BAS')");
 $this->db->query("insert into field values (22,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'70','createdtime','Created Time',1,0,0,100,18,63,2,'T~O',1,null,'BAS')");
 $this->db->query("insert into field values (22,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'70','modifiedtime','Modified Time',1,0,0,100,19,63,2,'T~O',1,null,'BAS')");



 //Block 65

 $this->db->query("insert into field values (22,".$this->db->getUniqueID("field").",'bill_street','sobillads',1,'24','bill_street','Billing Address',1,0,0,100,1,65,1,'V~M',1,null,'BAS')");
 $this->db->query("insert into field values (22,".$this->db->getUniqueID("field").",'ship_street','soshipads',1,'24','ship_street','Shipping Address',1,0,0,100,2,65,1,'V~M',1,null,'BAS')");

 $this->db->query("insert into field values (22,".$this->db->getUniqueID("field").",'bill_city','sobillads',1,'1','bill_city','Billing City',1,0,0,100,5,65,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (22,".$this->db->getUniqueID("field").",'ship_city','soshipads',1,'1','ship_city','Shipping City',1,0,0,100,6,65,1,'V~O',1,null,'BAS')");

 $this->db->query("insert into field values (22,".$this->db->getUniqueID("field").",'bill_state','sobillads',1,'1','bill_state','Billing State',1,0,0,100,7,65,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (22,".$this->db->getUniqueID("field").",'ship_state','soshipads',1,'1','ship_state','Shipping State',1,0,0,100,8,65,1,'V~O',1,null,'BAS')");

 $this->db->query("insert into field values (22,".$this->db->getUniqueID("field").",'bill_code','sobillads',1,'1','bill_code','Billing Code',1,0,0,100,9,65,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (22,".$this->db->getUniqueID("field").",'ship_code','soshipads',1,'1','ship_code','Shipping Code',1,0,0,100,10,65,1,'V~O',1,null,'BAS')");


 $this->db->query("insert into field values (22,".$this->db->getUniqueID("field").",'bill_country','sobillads',1,'1','bill_country','Billing Country',1,0,0,100,11,65,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (22,".$this->db->getUniqueID("field").",'ship_country','soshipads',1,'1','ship_country','Shipping Country',1,0,0,100,12,65,1,'V~O',1,null,'BAS')");

 $this->db->query("insert into field values (22,".$this->db->getUniqueID("field").",'bill_pobox','sobillads',1,'1','bill_pobox','Billing Po Box',1,0,0,100,3,65,1,'V~O',1,null,'BAS')");
  $this->db->query("insert into field values (22,".$this->db->getUniqueID("field").",'ship_pobox','soshipads',1,'1','ship_pobox','Shipping Po Box',1,0,0,100,4,65,1,'V~O',1,null,'BAS')");
  
//Block67
 $this->db->query("insert into field values (22,".$this->db->getUniqueID("field").",'description','crmentity',1,'19','description','Description',1,0,0,100,1,68,1,'V~O',1,null,'BAS')");

 //Block68
 $this->db->query("insert into field values (22,".$this->db->getUniqueID("field").",'terms_conditions','salesorder',1,'19','terms_conditions','Terms & Conditions',1,0,0,100,1,67,1,'V~O',1,null,'BAS')");


//Sales Order Details -- END

//Invoice Details -- START
 //Block69

$this->db->query("insert into field values (23,".$this->db->getUniqueID("field").",'subject','invoice',1,'2','subject','Subject',1,0,0,100,1,69,1,'V~M',1,null,'BAS')");
 $this->db->query("insert into field values (23,".$this->db->getUniqueID("field").",'salesorderid','invoice',1,'80','salesorder_id','Sales Order',1,0,0,100,2,69,1,'I~O',1,null,'BAS')");	
 $this->db->query("insert into field values (23,".$this->db->getUniqueID("field").",'customerno','invoice',1,'1','customerno','Customer No',1,0,0,100,3,69,1,'V~O',1,null,'BAS')");	

 
//to include contact name field in Invoice-start
$this->db->query("insert into field values (23,".$this->db->getUniqueID("field").",'contactid','invoice',1,'57','contact_id','Contact Name',1,0,0,100,4,69,1,'I~O',1,null,'BAS')");
//end

$this->db->query("insert into field values (23,".$this->db->getUniqueID("field").",'invoicedate','invoice',1,'5','invoicedate','Invoice Date',1,0,0,100,5,69,1,'D~O',1,null,'BAS')");
 $this->db->query("insert into field values (23,".$this->db->getUniqueID("field").",'duedate','invoice',1,'5','duedate','Due Date',1,0,0,100,6,69,1,'D~O',1,null,'BAS')");
$this->db->query("insert into field values (23,".$this->db->getUniqueID("field").",'purchaseorder','invoice',1,'1','purchaseorder','Purchase Order',1,0,0,100,8,69,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (23,".$this->db->getUniqueID("field").",'salestax','invoice',1,'1','txtTax','Sales Tax',1,0,0,100,9,69,3,'N~O',1,null,'BAS')");
 $this->db->query("insert into field values (23,".$this->db->getUniqueID("field").",'adjustment','invoice',1,'1','txtAdjustment','Sales Tax',1,0,0,100,9,69,3,'NN~O',1,null,'BAS')");
 $this->db->query("insert into field values (23,".$this->db->getUniqueID("field").",'salescommission','invoice',1,'1','salescommission','Sales Commission',1,0,0,10,13,69,1,'N~O',1,null,'BAS')");
 $this->db->query("insert into field values (23,".$this->db->getUniqueID("field").",'exciseduty','invoice',1,'1','exciseduty','Excise Duty',1,0,0,100,11,69,1,'N~O',1,null,'BAS')");
 $this->db->query("insert into field values (23,".$this->db->getUniqueID("field").",'subtotal','invoice',1,'1','hdnSubTotal','Sub Total',1,0,0,100,12,69,3,'N~O',1,null,'BAS')");
 $this->db->query("insert into field values (23,".$this->db->getUniqueID("field").",'total','invoice',1,'1','hdnGrandTotal','Total',1,0,0,100,13,69,3,'N~O',1,null,'BAS')");
$this->db->query("insert into field values (23,".$this->db->getUniqueID("field").",'accountid','invoice',1,'73','account_id','Account Name',1,0,0,100,14,69,1,'I~M',1,null,'BAS')");
 $this->db->query("insert into field values (23,".$this->db->getUniqueID("field").",'invoicestatus','invoice',1,'15','invoicestatus','Status',1,0,0,100,15,69,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (23,".$this->db->getUniqueID("field").",'smownerid','crmentity',1,'53','assigned_user_id','Assigned To',1,0,0,100,16,69,1,'V~M',1,null,'BAS')");
 $this->db->query("insert into field values (23,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'70','createdtime','Created Time',1,0,0,100,17,69,2,'T~O',1,null,'BAS')");
 $this->db->query("insert into field values (23,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'70','modifiedtime','Modified Time',1,0,0,100,18,69,2,'T~O',1,null,'BAS')"); 

 //Block 71

$this->db->query("insert into field values (23,".$this->db->getUniqueID("field").",'bill_street','invoicebillads',1,'24','bill_street','Billing Address',1,0,0,100,1,71,1,'V~M',1,null,'BAS')");
 $this->db->query("insert into field values (23,".$this->db->getUniqueID("field").",'ship_street','invoiceshipads',1,'24','ship_street','Shipping Address',1,0,0,100,2,71,1,'V~M',1,null,'BAS')");

 $this->db->query("insert into field values (23,".$this->db->getUniqueID("field").",'bill_city','invoicebillads',1,'1','bill_city','Billing City',1,0,0,100,5,71,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (23,".$this->db->getUniqueID("field").",'ship_city','invoiceshipads',1,'1','ship_city','Shipping City',1,0,0,100,6,71,1,'V~O',1,null,'BAS')");

 $this->db->query("insert into field values (23,".$this->db->getUniqueID("field").",'bill_state','invoicebillads',1,'1','bill_state','Billing State',1,0,0,100,7,71,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (23,".$this->db->getUniqueID("field").",'ship_state','invoiceshipads',1,'1','ship_state','Shipping State',1,0,0,100,8,71,1,'V~O',1,null,'BAS')");

 $this->db->query("insert into field values (23,".$this->db->getUniqueID("field").",'bill_code','invoicebillads',1,'1','bill_code','Billing Code',1,0,0,100,9,71,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (23,".$this->db->getUniqueID("field").",'ship_code','invoiceshipads',1,'1','ship_code','Shipping Code',1,0,0,100,10,71,1,'V~O',1,null,'BAS')");


 $this->db->query("insert into field values (23,".$this->db->getUniqueID("field").",'bill_country','invoicebillads',1,'1','bill_country','Billing Country',1,0,0,100,11,71,1,'V~O',1,null,'BAS')");
 $this->db->query("insert into field values (23,".$this->db->getUniqueID("field").",'ship_country','invoiceshipads',1,'1','ship_country','Shipping Country',1,0,0,100,12,71,1,'V~O',1,null,'BAS')");

 $this->db->query("insert into field values (23,".$this->db->getUniqueID("field").",'bill_pobox','invoicebillads',1,'1','bill_pobox','Billing Po Box',1,0,0,100,3,71,1,'V~O',1,null,'BAS')");
  $this->db->query("insert into field values (23,".$this->db->getUniqueID("field").",'ship_pobox','invoiceshipads',1,'1','ship_pobox','Shipping Po Box',1,0,0,100,4,71,1,'V~O',1,null,'BAS')");

//Block73
 $this->db->query("insert into field values (23,".$this->db->getUniqueID("field").",'description','crmentity',1,'19','description','Description',1,0,0,100,1,74,1,'V~O',1,null,'BAS')");
 //Block74
 $this->db->query("insert into field values (23,".$this->db->getUniqueID("field").",'terms_conditions','invoice',1,'19','terms_conditions','Terms & Conditions',1,0,0,100,1,73,1,'V~O',1,null,'BAS')");


//Invoice Details -- END



                // Insert End
                

		//New Security Start
		//Inserting into profile table
		$this->db->query("insert into profile values ('".$profile1_id."','Administrator',null)");	
		$this->db->query("insert into profile values ('".$profile2_id."','Sales Profile',null)");
		$this->db->query("insert into profile values ('".$profile3_id."','Support Profile',null)");
		$this->db->query("insert into profile values ('".$profile4_id."','Guest Profile',null)");
		
		//Inserting into profile2gloabal permissions
		$this->db->query("insert into profile2globalpermissions values ('".$profile1_id."',1,0)");
		$this->db->query("insert into profile2globalpermissions values ('".$profile1_id."',2,0)");
		$this->db->query("insert into profile2globalpermissions values ('".$profile2_id."',1,1)");
		$this->db->query("insert into profile2globalpermissions values ('".$profile2_id."',2,1)");
		$this->db->query("insert into profile2globalpermissions values ('".$profile3_id."',1,1)");
		$this->db->query("insert into profile2globalpermissions values ('".$profile3_id."',2,1)");
		$this->db->query("insert into profile2globalpermissions values ('".$profile4_id."',1,1)");
		$this->db->query("insert into profile2globalpermissions values ('".$profile4_id."',2,1)");

		//Inserting into profile2tab
		$this->db->query("insert into profile2tab values (".$profile1_id.",1,0)");
		$this->db->query("insert into profile2tab values (".$profile1_id.",2,0)");
		$this->db->query("insert into profile2tab values (".$profile1_id.",3,0)");
		$this->db->query("insert into profile2tab values (".$profile1_id.",4,0)");
		$this->db->query("insert into profile2tab values (".$profile1_id.",6,0)");
		$this->db->query("insert into profile2tab values (".$profile1_id.",7,0)");
		$this->db->query("insert into profile2tab values (".$profile1_id.",8,0)");
		$this->db->query("insert into profile2tab values (".$profile1_id.",9,0)");
		$this->db->query("insert into profile2tab values (".$profile1_id.",10,0)");
		$this->db->query("insert into profile2tab values (".$profile1_id.",13,0)");
		$this->db->query("insert into profile2tab values (".$profile1_id.",14,0)");
		$this->db->query("insert into profile2tab values (".$profile1_id.",15,0)");
		$this->db->query("insert into profile2tab values (".$profile1_id.",16,0)");
		$this->db->query("insert into profile2tab values (".$profile1_id.",17,0)");
		$this->db->query("insert into profile2tab values (".$profile1_id.",18,0)");
		$this->db->query("insert into profile2tab values (".$profile1_id.",19,0)");
		$this->db->query("insert into profile2tab values (".$profile1_id.",20,0)");
		$this->db->query("insert into profile2tab values (".$profile1_id.",21,0)");
		$this->db->query("insert into profile2tab values (".$profile1_id.",22,0)");
		$this->db->query("insert into profile2tab values (".$profile1_id.",23,0)");
		$this->db->query("insert into profile2tab values (".$profile1_id.",24,0)");
		$this->db->query("insert into profile2tab values (".$profile1_id.",25,0)");
        $this->db->query("insert into profile2tab values (".$profile1_id.",26,0)");

		//Inserting into profile2tab
		$this->db->query("insert into profile2tab values (".$profile2_id.",1,0)");
		$this->db->query("insert into profile2tab values (".$profile2_id.",2,0)");
		$this->db->query("insert into profile2tab values (".$profile2_id.",3,0)");
		$this->db->query("insert into profile2tab values (".$profile2_id.",4,0)");
		$this->db->query("insert into profile2tab values (".$profile2_id.",6,0)");
		$this->db->query("insert into profile2tab values (".$profile2_id.",7,0)");
		$this->db->query("insert into profile2tab values (".$profile2_id.",8,0)");
		$this->db->query("insert into profile2tab values (".$profile2_id.",9,0)");
		$this->db->query("insert into profile2tab values (".$profile2_id.",10,0)");
		$this->db->query("insert into profile2tab values (".$profile2_id.",13,0)");
		$this->db->query("insert into profile2tab values (".$profile2_id.",14,0)");
		$this->db->query("insert into profile2tab values (".$profile2_id.",15,0)");
		$this->db->query("insert into profile2tab values (".$profile2_id.",16,0)");
		$this->db->query("insert into profile2tab values (".$profile2_id.",17,0)");
		$this->db->query("insert into profile2tab values (".$profile2_id.",18,0)");
		$this->db->query("insert into profile2tab values (".$profile2_id.",19,0)");
		$this->db->query("insert into profile2tab values (".$profile2_id.",20,0)");
		$this->db->query("insert into profile2tab values (".$profile2_id.",21,0)");
		$this->db->query("insert into profile2tab values (".$profile2_id.",22,0)");
		$this->db->query("insert into profile2tab values (".$profile2_id.",23,0)");
		$this->db->query("insert into profile2tab values (".$profile2_id.",24,0)");
		$this->db->query("insert into profile2tab values (".$profile2_id.",25,0)");
        $this->db->query("insert into profile2tab values (".$profile2_id.",26,0)");

		$this->db->query("insert into profile2tab values (".$profile3_id.",1,0)");
		$this->db->query("insert into profile2tab values (".$profile3_id.",2,0)");
		$this->db->query("insert into profile2tab values (".$profile3_id.",3,0)");
		$this->db->query("insert into profile2tab values (".$profile3_id.",4,0)");
		$this->db->query("insert into profile2tab values (".$profile3_id.",6,0)");
		$this->db->query("insert into profile2tab values (".$profile3_id.",7,0)");
		$this->db->query("insert into profile2tab values (".$profile3_id.",8,0)");
		$this->db->query("insert into profile2tab values (".$profile3_id.",9,0)");
		$this->db->query("insert into profile2tab values (".$profile3_id.",10,0)");
		$this->db->query("insert into profile2tab values (".$profile3_id.",13,0)");
		$this->db->query("insert into profile2tab values (".$profile3_id.",14,0)");
		$this->db->query("insert into profile2tab values (".$profile3_id.",15,0)");
		$this->db->query("insert into profile2tab values (".$profile3_id.",16,0)");
		$this->db->query("insert into profile2tab values (".$profile3_id.",17,0)");
		$this->db->query("insert into profile2tab values (".$profile3_id.",18,0)");
		$this->db->query("insert into profile2tab values (".$profile3_id.",19,0)");
		$this->db->query("insert into profile2tab values (".$profile3_id.",20,0)");
		$this->db->query("insert into profile2tab values (".$profile3_id.",21,0)");
		$this->db->query("insert into profile2tab values (".$profile3_id.",22,0)");
		$this->db->query("insert into profile2tab values (".$profile3_id.",23,0)");
		$this->db->query("insert into profile2tab values (".$profile3_id.",24,0)");
		$this->db->query("insert into profile2tab values (".$profile3_id.",25,0)");
        $this->db->query("insert into profile2tab values (".$profile3_id.",26,0)");
        

		$this->db->query("insert into profile2tab values (".$profile4_id.",1,0)");
		$this->db->query("insert into profile2tab values (".$profile4_id.",2,0)");
		$this->db->query("insert into profile2tab values (".$profile4_id.",3,0)");
		$this->db->query("insert into profile2tab values (".$profile4_id.",4,0)");
		$this->db->query("insert into profile2tab values (".$profile4_id.",6,0)");
		$this->db->query("insert into profile2tab values (".$profile4_id.",7,0)");
		$this->db->query("insert into profile2tab values (".$profile4_id.",8,0)");
		$this->db->query("insert into profile2tab values (".$profile4_id.",9,0)");
		$this->db->query("insert into profile2tab values (".$profile4_id.",10,0)");
		$this->db->query("insert into profile2tab values (".$profile4_id.",13,0)");
		$this->db->query("insert into profile2tab values (".$profile4_id.",14,0)");
		$this->db->query("insert into profile2tab values (".$profile4_id.",15,0)");
		$this->db->query("insert into profile2tab values (".$profile4_id.",16,0)");	
		$this->db->query("insert into profile2tab values (".$profile4_id.",17,0)");
		$this->db->query("insert into profile2tab values (".$profile4_id.",18,0)");
		$this->db->query("insert into profile2tab values (".$profile4_id.",19,0)");
		$this->db->query("insert into profile2tab values (".$profile4_id.",20,0)");
		$this->db->query("insert into profile2tab values (".$profile4_id.",21,0)");
		$this->db->query("insert into profile2tab values (".$profile4_id.",22,0)");
		$this->db->query("insert into profile2tab values (".$profile4_id.",23,0)");
		$this->db->query("insert into profile2tab values (".$profile4_id.",24,0)");
		$this->db->query("insert into profile2tab values (".$profile4_id.",25,0)");
		$this->db->query("insert into profile2tab values (".$profile4_id.",26,0)");
		//Inserting into profile2standardpermissions  Adminsitrator
		
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",2,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",2,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",2,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",2,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",2,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",4,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",4,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",4,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",4,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",4,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",6,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",6,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",6,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",6,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",6,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",7,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",7,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",7,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",7,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",7,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",8,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",8,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",8,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",8,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",8,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",9,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",9,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",9,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",9,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",9,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",10,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",10,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",10,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",10,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",10,4,0)");

		
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",13,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",13,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",13,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",13,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",13,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",14,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",14,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",14,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",14,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",14,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",15,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",15,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",15,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",15,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",15,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",16,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",16,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",16,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",16,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",16,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",18,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",18,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",18,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",18,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",18,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",19,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",19,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",19,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",19,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",19,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",20,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",20,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",20,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",20,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",20,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",21,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",21,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",21,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",21,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",21,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",22,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",22,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",22,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",22,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",22,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",23,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",23,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",23,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",23,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",23,4,0)");




        $this->db->query("insert into profile2standardpermissions values (".$profile1_id.",26,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",26,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",26,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",26,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",26,4,0)");


		//Insert into Profile 2 std permissions for Sales User  
		//Help Desk Create/Delete not allowed. Read-Only	

		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",2,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",2,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",2,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",2,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",2,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",4,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",4,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",4,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",4,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",4,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",6,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",6,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",6,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",6,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",6,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",7,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",7,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",7,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",7,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",7,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",8,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",8,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",8,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",8,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",8,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",9,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",9,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",9,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",9,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",9,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",10,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",10,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",10,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",10,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",10,4,0)");

		
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",13,0,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",13,1,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",13,2,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",13,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",13,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",14,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",14,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",14,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",14,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",14,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",15,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",15,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",15,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",15,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",15,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",16,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",16,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",16,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",16,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",16,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",18,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",18,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",18,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",18,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",18,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",19,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",19,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",19,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",19,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",19,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",20,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",20,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",20,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",20,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",20,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",21,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",21,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",21,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",21,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",21,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",22,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",22,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",22,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",22,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",22,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",23,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",23,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",23,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",23,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",23,4,0)");


        	$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",26,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",26,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",26,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",26,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",26,4,0)");

		//Inserting into profile2std for Support Profile
		// Potential is read-only

		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",2,0,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",2,1,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",2,2,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",2,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",2,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",4,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",4,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",4,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",4,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",4,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",6,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",6,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",6,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",6,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",6,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",7,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",7,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",7,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",7,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",7,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",8,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",8,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",8,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",8,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",8,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",9,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",9,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",9,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",9,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",9,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",10,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",10,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",10,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",10,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",10,4,0)");

		
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",13,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",13,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",13,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",13,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",13,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",14,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",14,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",14,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",14,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",14,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",15,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",15,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",15,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",15,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",15,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",16,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",16,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",16,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",16,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",16,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",18,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",18,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",18,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",18,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",18,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",19,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",19,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",19,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",19,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",19,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",20,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",20,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",20,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",20,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",20,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",21,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",21,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",21,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",21,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",21,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",22,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",22,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",22,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",22,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",22,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",23,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",23,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",23,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",23,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",23,4,0)");


        	$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",26,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",26,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",26,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",26,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",26,4,0)");
        
		//Inserting into profile2stdper for Profile Guest Profile
		//All Read-Only
		
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",2,0,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",2,1,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",2,2,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",2,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",2,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",4,0,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",4,1,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",4,2,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",4,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",4,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",6,0,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",6,1,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",6,2,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",6,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",6,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",7,0,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",7,1,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",7,2,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",7,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",7,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",8,0,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",8,1,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",8,2,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",8,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",8,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",9,0,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",9,1,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",9,2,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",9,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",9,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",10,0,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",10,1,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",10,2,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",10,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",10,4,0)");

		
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",13,0,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",13,1,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",13,2,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",13,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",13,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",14,0,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",14,1,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",14,2,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",14,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",14,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",15,0,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",15,1,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",15,2,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",15,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",15,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",16,0,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",16,1,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",16,2,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",16,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",16,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",18,0,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",18,1,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",18,2,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",18,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",18,4,0)");	
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",19,0,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",19,1,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",19,2,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",19,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",19,4,0)");	
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",20,0,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",20,1,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",20,2,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",20,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",20,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",21,0,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",21,1,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",21,2,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",21,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",21,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",22,0,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",22,1,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",22,2,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",22,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",22,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",23,0,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",23,1,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",23,2,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",23,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",23,4,0)");	


        $this->db->query("insert into profile2standardpermissions values (".$profile4_id.",26,0,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",26,1,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",26,2,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",26,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",26,4,0)");

		//Insert into role2profile
		$this->db->query("insert into role2profile values ('H".$role2_id."',".$profile1_id.")");
		$this->db->query("insert into role2profile values ('H".$role3_id."',".$profile2_id.")");
	
		//Insert into profile2field
			
                insertProfile2field($profile1_id);
                insertProfile2field($profile2_id);	
                insertProfile2field($profile3_id);	
                insertProfile2field($profile4_id);

		insert_def_org_field();	
		

		//Inserting into profile 2 utility Admin
                $this->db->query("insert into profile2utility values (".$profile1_id.",2,5,0)");
                $this->db->query("insert into profile2utility values (".$profile1_id.",2,6,0)");
                $this->db->query("insert into profile2utility values (".$profile1_id.",4,5,0)");
                $this->db->query("insert into profile2utility values (".$profile1_id.",4,6,0)");
                $this->db->query("insert into profile2utility values (".$profile1_id.",6,5,0)");
                $this->db->query("insert into profile2utility values (".$profile1_id.",6,6,0)");
                $this->db->query("insert into profile2utility values (".$profile1_id.",7,5,0)");
                $this->db->query("insert into profile2utility values (".$profile1_id.",7,6,0)");
                $this->db->query("insert into profile2utility values (".$profile1_id.",8,6,0)");
                //$this->db->query("insert into profile2utility values (".$profile1_id.",9,6,0)");
                $this->db->query("insert into profile2utility values (".$profile1_id.",10,6,0)");
		$this->db->query("insert into profile2utility values (".$profile1_id.",7,8,0)");
                $this->db->query("insert into profile2utility values (".$profile1_id.",6,8,0)");
                $this->db->query("insert into profile2utility values (".$profile1_id.",4,8,0)");
		$this->db->query("insert into profile2utility values (".$profile1_id.",14,5,0)");
                $this->db->query("insert into profile2utility values (".$profile1_id.",14,6,0)");
                $this->db->query("insert into profile2utility values (".$profile1_id.",4,7,0)");
                $this->db->query("insert into profile2utility values (".$profile1_id.",7,9,0)");

		//Inserting into profile2utility Sales Profile
		//Import Export Not Allowed.	
		$this->db->query("insert into profile2utility values (".$profile2_id.",2,5,1)");
                $this->db->query("insert into profile2utility values (".$profile2_id.",2,6,1)");
                $this->db->query("insert into profile2utility values (".$profile2_id.",4,5,1)");
                $this->db->query("insert into profile2utility values (".$profile2_id.",4,6,1)");
                $this->db->query("insert into profile2utility values (".$profile2_id.",6,5,1)");
                $this->db->query("insert into profile2utility values (".$profile2_id.",6,6,1)");
                $this->db->query("insert into profile2utility values (".$profile2_id.",7,5,1)");
                $this->db->query("insert into profile2utility values (".$profile2_id.",7,6,1)");
                $this->db->query("insert into profile2utility values (".$profile2_id.",8,6,1)");
                //$this->db->query("insert into profile2utility values (".$profile2_id.",9,6,1)");
                $this->db->query("insert into profile2utility values (".$profile2_id.",10,6,1)");
                $this->db->query("insert into profile2utility values (".$profile2_id.",7,8,0)");
                $this->db->query("insert into profile2utility values (".$profile2_id.",6,8,0)");
                $this->db->query("insert into profile2utility values (".$profile2_id.",4,8,0)");
		$this->db->query("insert into profile2utility values (".$profile2_id.",14,5,1)");
                $this->db->query("insert into profile2utility values (".$profile2_id.",14,6,1)");
		$this->db->query("insert into profile2utility values (".$profile2_id.",4,7,0)");
                $this->db->query("insert into profile2utility values (".$profile2_id.",7,9,0)");

		//Inserting into profile2utility Support Profile
		//Import Export Not Allowed.	
		$this->db->query("insert into profile2utility values (".$profile3_id.",2,5,1)");
                $this->db->query("insert into profile2utility values (".$profile3_id.",2,6,1)");
                $this->db->query("insert into profile2utility values (".$profile3_id.",4,5,1)");
                $this->db->query("insert into profile2utility values (".$profile3_id.",4,6,1)");
                $this->db->query("insert into profile2utility values (".$profile3_id.",6,5,1)");
                $this->db->query("insert into profile2utility values (".$profile3_id.",6,6,1)");
                $this->db->query("insert into profile2utility values (".$profile3_id.",7,5,1)");
                $this->db->query("insert into profile2utility values (".$profile3_id.",7,6,1)");
                $this->db->query("insert into profile2utility values (".$profile3_id.",8,6,1)");
                //$this->db->query("insert into profile2utility values (".$profile3_id.",9,6,1)");
                $this->db->query("insert into profile2utility values (".$profile3_id.",10,6,1)");
                $this->db->query("insert into profile2utility values (".$profile3_id.",7,8,0)");
                $this->db->query("insert into profile2utility values (".$profile3_id.",6,8,0)");
                $this->db->query("insert into profile2utility values (".$profile3_id.",4,8,0)");
		$this->db->query("insert into profile2utility values (".$profile3_id.",14,5,1)");
                $this->db->query("insert into profile2utility values (".$profile3_id.",14,6,1)");
		$this->db->query("insert into profile2utility values (".$profile3_id.",4,7,0)");
                $this->db->query("insert into profile2utility values (".$profile3_id.",7,9,0)");

		//Inserting into profile2utility Guest Profile Read-Only
		//Import Export BusinessCar Not Allowed.	
		$this->db->query("insert into profile2utility values (".$profile4_id.",2,5,1)");
                $this->db->query("insert into profile2utility values (".$profile4_id.",2,6,1)");
                $this->db->query("insert into profile2utility values (".$profile4_id.",4,5,1)");
                $this->db->query("insert into profile2utility values (".$profile4_id.",4,6,1)");
                $this->db->query("insert into profile2utility values (".$profile4_id.",6,5,1)");
                $this->db->query("insert into profile2utility values (".$profile4_id.",6,6,1)");
                $this->db->query("insert into profile2utility values (".$profile4_id.",7,5,1)");
                $this->db->query("insert into profile2utility values (".$profile4_id.",7,6,1)");
                $this->db->query("insert into profile2utility values (".$profile4_id.",8,6,1)");
                //$this->db->query("insert into profile2utility values (".$profile4_id.",9,6,1)");
                $this->db->query("insert into profile2utility values (".$profile4_id.",10,6,1)");
                $this->db->query("insert into profile2utility values (".$profile4_id.",7,8,1)");
                $this->db->query("insert into profile2utility values (".$profile4_id.",6,8,1)");
                $this->db->query("insert into profile2utility values (".$profile4_id.",4,8,1)");		
		$this->db->query("insert into profile2utility values (".$profile4_id.",14,5,1)");
                $this->db->query("insert into profile2utility values (".$profile4_id.",14,6,1)");
		$this->db->query("insert into profile2utility values (".$profile4_id.",4,7,0)");
                $this->db->query("insert into profile2utility values (".$profile4_id.",7,9,0)");

		//Inserting values into org share action mapping
                $this->db->query("insert into org_share_action_mapping values(0,'Public: Read Only')");
                $this->db->query("insert into org_share_action_mapping values(1,'Public: Read, Create/Edit')");
                $this->db->query("insert into org_share_action_mapping values(2,'Public: Read, Create/Edit, Delete')");
                $this->db->query("insert into org_share_action_mapping values(3,'Private')");

                $this->db->query("insert into org_share_action_mapping values(4,'Hide Details')");
                $this->db->query("insert into org_share_action_mapping values(5,'Hide Details and Add Events')");
                $this->db->query("insert into org_share_action_mapping values(6,'Show Details')");
                $this->db->query("insert into org_share_action_mapping values(7,'Show Details and Add Events')");


		//Inserting for all tabs
                $def_org_tabid= Array(2,4,6,7,9,10,13,16,20,21,22,23);

                foreach($def_org_tabid as $def_tabid)
                {
                        $this->db->query("insert into org_share_action2tab values(0,".$def_tabid.")");
                        $this->db->query("insert into org_share_action2tab values(1,".$def_tabid.")");
                        $this->db->query("insert into org_share_action2tab values(2,".$def_tabid.")");
                        $this->db->query("insert into org_share_action2tab values(3,".$def_tabid.")");
                }

                $this->db->query("insert into org_share_action2tab values(4,17)");
                $this->db->query("insert into org_share_action2tab values(5,17)");
                $this->db->query("insert into org_share_action2tab values(6,17)");
                $this->db->query("insert into org_share_action2tab values(7,17)");

		//Insert into default_org_sharingrule
               $this->db->query("insert into def_org_share values (".$this->db->getUniqueID('def_org_share').",2,2,0)");

               $this->db->query("insert into def_org_share values (".$this->db->getUniqueID('def_org_share').",4,2,2)");

               $this->db->query("insert into def_org_share values (".$this->db->getUniqueID('def_org_share').",6,2,0)");

               $this->db->query("insert into def_org_share values (".$this->db->getUniqueID('def_org_share').",7,2,0)");

               $this->db->query("insert into def_org_share values (".$this->db->getUniqueID('def_org_share').",9,3,1)");
               $this->db->query("insert into def_org_share values (".$this->db->getUniqueID('def_org_share').",10,2,0)");
               $this->db->query("insert into def_org_share values (".$this->db->getUniqueID('def_org_share').",13,2,0)");
               $this->db->query("insert into def_org_share values (".$this->db->getUniqueID('def_org_share').",16,3,2)");
               $this->db->query("insert into def_org_share values (".$this->db->getUniqueID('def_org_share').",17,7,0)");
               $this->db->query("insert into def_org_share values (".$this->db->getUniqueID('def_org_share').",20,2,0)");
               $this->db->query("insert into def_org_share values (".$this->db->getUniqueID('def_org_share').",21,2,0)");
               $this->db->query("insert into def_org_share values (".$this->db->getUniqueID('def_org_share').",22,2,0)");
               $this->db->query("insert into def_org_share values (".$this->db->getUniqueID('def_org_share').",23,2,0)");

		//Populating the DataShare Related Modules

		//Lead Related Module
		$this->db->query("insert into datashare_relatedmodules values (".$this->db->getUniqueID('datashare_relatedmodules').",7,10)");

		//Account Related Module
		$this->db->query("insert into datashare_relatedmodules values (".$this->db->getUniqueID('datashare_relatedmodules').",6,2)");
		$this->db->query("insert into datashare_relatedmodules values (".$this->db->getUniqueID('datashare_relatedmodules').",6,13)");
		$this->db->query("insert into datashare_relatedmodules values (".$this->db->getUniqueID('datashare_relatedmodules').",6,20)");
		$this->db->query("insert into datashare_relatedmodules values (".$this->db->getUniqueID('datashare_relatedmodules').",6,22)");
		$this->db->query("insert into datashare_relatedmodules values (".$this->db->getUniqueID('datashare_relatedmodules').",6,23)");
		$this->db->query("insert into datashare_relatedmodules values (".$this->db->getUniqueID('datashare_relatedmodules').",6,10)");

		//Potential Related Module
		$this->db->query("insert into datashare_relatedmodules values (".$this->db->getUniqueID('datashare_relatedmodules').",2,20)");
		$this->db->query("insert into datashare_relatedmodules values (".$this->db->getUniqueID('datashare_relatedmodules').",2,22)");

		//Quote Related Module
		$this->db->query("insert into datashare_relatedmodules values (".$this->db->getUniqueID('datashare_relatedmodules').",20,22)");

		//SO Related Module
		$this->db->query("insert into datashare_relatedmodules values (".$this->db->getUniqueID('datashare_relatedmodules').",22,23)");	


			
					
  // New Secutity End

                $table_name="groups";
                $table_name="users2group";
                $table_name="productcollaterals";
                $table_name="email_attachments";

         //insert into the notificationscheduler table

	//insert into related list table
	//Inserting for account related lists
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Accounts").",".getTabid("Potentials").",'get_opportunities',1,'Potentials',0)");	
		
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Accounts").",".getTabid("Contacts").",'get_contacts',2,'Contacts',0)");

	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Accounts").",".getTabid("Activities").",'get_activities',3,'Activities',0)");

	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Accounts").",".getTabid("HelpDesk").",'get_tickets',4,'HelpDesk',0)");
	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Accounts").",".getTabid("Activities").",'get_history',5,'History',0)");

	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Accounts").",0,'get_attachments',6,'Attachments',0)");

	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Accounts").",".getTabid("Quotes").",'get_quotes',7,'Quotes',0)");
	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Accounts").",".getTabid("Invoice").",'get_invoices',8,'Invoice',0)");
	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Accounts").",".getTabid("SalesOrder").",'get_salesorder',9,'Sales Order',0)");
	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Accounts").",".getTabid("Products").",'get_products',10,'Products',0)");

	//Inserting Lead Related Lists	

	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Leads").",".getTabid("Activities").",'get_activities',1,'Activities',0)");
	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Leads").",".getTabid("Emails").",'get_emails',2,'Emails',0)");
	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Leads").",".getTabid("Activities").",'get_history',3,'History',0)");
	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Leads").",0,'get_attachments',4,'Attachments',0)");
	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Leads").",".getTabid("Products").",'get_products',5,'Products',0)");

	//Inserting for contact related lists
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Contacts").",".getTabid("Potentials").",'get_opportunities',1,'Potentials',0)");	
		
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Contacts").",".getTabid("Activities").",'get_activities',2,'Activities',0)");

	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Contacts").",".getTabid("Emails").",'get_emails',3,'Emails',0)");

	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Contacts").",".getTabid("HelpDesk").",'get_tickets',4,'HelpDesk',0)");
	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Contacts").",".getTabid("Quotes").",'get_quotes',5,'Quotes',0)");
	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Contacts").",".getTabid("PurchaseOrder").",'get_purchase_orders',6,'Purchase Order',0)");
	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Contacts").",".getTabid("SalesOrder").",'get_salesorder',7,'Sales Order',0)");
	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Contacts").",".getTabid("Products").",'get_products',8,'Products',0)");

	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Contacts").",".getTabid("Activities").",'get_history',9,'History',0)");

	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Contacts").",0,'get_attachments',10,'Attachments',0)");

	//Inserting Potential Related Lists	

	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Potentials").",".getTabid("Activities").",'get_activities',1,'Activities',0)");

	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Potentials").",".getTabid("Contacts").",'get_contacts',2,'Contacts',0)");

	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Potentials").",".getTabid("Products").",'get_products',3,'History',0)");

	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Potentials").",0,'get_stage_history',4,'Sales Stage History',0)");
	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Potentials").",0,'get_attachments',5,'Attachments',0)");
	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Potentials").",".getTabid("Quotes").",'get_Quotes',6,'Quotes',0)");
	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Potentials").",".getTabid("SalesOrder").",'get_salesorder',7,'Sales Order',0)");
	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Potentials").",".getTabid("Activities").",'get_history',8,'History',0)");

		//Inserting Product Related Lists	

	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Products").",".getTabid("HelpDesk").",'get_tickets',1,'HelpDesk',0)");

	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Products").",".getTabid("Activities").",'get_activities',2,'Activities',0)");

	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Products").",0,'get_attachments',3,'Attachments',0)");
	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Products").",".getTabid("Quotes").",'get_quotes',4,'Quotes',0)");
	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Products").",".getTabid("PurchaseOrder").",'get_purchase_orders',5,'Purchase Order',0)");
	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Products").",".getTabid("SalesOrder").",'get_salesorder',6,'Sales Order',0)");
	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Products").",".getTabid("Invoice").",'get_invoices',7,'Invoice',0)");
	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Products").",".getTabid("PriceBooks").",'get_product_pricebooks',8,'PriceBooks',0)");
	
		//Inserting Emails Related Lists	

	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Emails").",".getTabid("Contacts").",'get_contacts',1,'Contacts',0)");

	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Emails").",0,'get_users',2,'Users',0)");

	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Emails").",0,'get_attachments',3,'Attachments',0)");

		//Inserting HelpDesk Related Lists
		
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("HelpDesk").",".getTabid("Activities").",'get_activities',1,'Activities',0)");

	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("HelpDesk").",0,'get_attachments',2,'Attachments',0)");

	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("PriceBooks").",14,'get_pricebook_products',2,'Products',0)");

        // Inserting Vendor Related Lists
        $this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Vendors").",14,'get_products',1,'Products',0)");

        $this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Vendors").",21,'get_purchase_orders',2,'Purchase Order',0)");

        $this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Vendors").",4,'get_contacts',3,'Contacts',0)");

	// Inserting Quotes Related Lists
	
        $this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Quotes").",".getTabid("Invoice").",'get_salesorder',1,'Sales Order',0)");
        
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Quotes").",9,'get_activities',2,'Activities',0)");
	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Quotes").",9,'get_history',3,'History',0)");

	// Inserting Purchase order Related Lists

	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("PurchaseOrder").",9,'get_activities',1,'Activities',0)");
	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("PurchaseOrder").",0,'get_attachments',2,'Attachments',0)");
	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("PurchaseOrder").",".getTabid("Activities").",'get_history',3,'History',0)");
	
	// Inserting Sales order Related Lists

	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("SalesOrder").",9,'get_activities',1,'Activities',0)");
	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("SalesOrder").",0,'get_attachments',2,'Attachments',0)");
	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("SalesOrder").",".getTabid("Invoice").",'get_invoices',3,'Invoice',0)");
	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("SalesOrder").",".getTabid("Activities").",'get_history',4,'History',0)");
	
	// Inserting Invoice Related Lists

	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Invoice").",9,'get_activities',1,'Activities',0)");
	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Invoice").",0,'get_attachments',2,'Attachments',0)");

	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Invoice").",".getTabid("Activities").",'get_history',3,'History',0)");

	// Inserting Activities Related Lists
	
	$this->db->query("insert into relatedlists values (".$this->db->getUniqueID('relatedlists').",".getTabid("Activities").",0,'get_users',1,'Users',0)");
	$this->db->query("insert into relatedlists values (".$this->db->getUniqueID('relatedlists').",".getTabid("Activities").",4,'get_contacts',2,'Contacts',0)");

	// Inserting Campaigns Related Lists

         $this->db->query("insert into relatedlists values (".$this->db->getUniqueID('relatedlists').",".getTabid("Campaigns").",".getTabid("Contacts").",'get_contacts',1,'Contacts',0)");
         $this->db->query("insert into relatedlists values (".$this->db->getUniqueID('relatedlists').",".getTabid("Campaigns").",".getTabid("Leads").",'get_leads',2,'Leads',0)");

               $this->db->query("insert into notificationscheduler(schedulednotificationid,schedulednotificationname,active,notificationsubject,notificationbody,label) values (".$this->db->getUniqueID("notificationscheduler").",'LBL_TASK_NOTIFICATION_DESCRITPION',1,'Task Delay Notification','Tasks delayed beyond 24 hrs ','LBL_TASK_NOTIFICATION')");


                $this->db->query("insert into notificationscheduler(schedulednotificationid,schedulednotificationname,active,notificationsubject,notificationbody,label) values (".$this->db->getUniqueID("notificationscheduler").",'LBL_BIG_DEAL_DESCRIPTION' ,1,'Big Deal notification','Success! A big deal has been won! ','LBL_BIG_DEAL')");


                $this->db->query("insert into notificationscheduler(schedulednotificationid,schedulednotificationname,active,notificationsubject,notificationbody,label) values (".$this->db->getUniqueID("notificationscheduler").",'LBL_TICKETS_DESCRIPTION',1,'Pending Tickets notification','Ticket pending please ','LBL_PENDING_TICKETS')");


                $this->db->query("insert into notificationscheduler(schedulednotificationid,schedulednotificationname,active,notificationsubject,notificationbody,label) values (".$this->db->getUniqueID("notificationscheduler").",'LBL_MANY_TICKETS_DESCRIPTION',1,'Too many tickets Notification','Too many tickets pending against this entity ','LBL_MANY_TICKETS')");


                $this->db->query("insert into notificationscheduler(schedulednotificationid,schedulednotificationname,active,notificationsubject,notificationbody,label) values (".$this->db->getUniqueID("notificationscheduler").",'LBL_START_DESCRIPTION' ,1,'Support Start Notification','Support starts please ','LBL_START_NOTIFICATION')");

                $this->db->query("insert into notificationscheduler(schedulednotificationid,schedulednotificationname,active,notificationsubject,notificationbody,label) values (".$this->db->getUniqueID("notificationscheduler").",'LBL_SUPPORT_DESCRIPTION',1,'Support ending please','Support Ending Notification','LBL_SUPPORT_NOTICIATION')");
		
 		$this->db->query("insert into notificationscheduler(schedulednotificationid,schedulednotificationname,active,notificationsubject,notificationbody,label) values (".$this->db->getUniqueID("notificationscheduler").",'LBL_ACTIVITY_REMINDER_DESCRIPTION' ,1,'Activity Reminder Notication','This is a reminder notification for the Activity','LBL_ACTIVITY_NOTIFICATION')");

		//Inserting Inventory Notifications
	$invoice_body = 'Dear {HANDLER},

The current stock of {PRODUCTNAME} in our warehouse is {CURRENTSTOCK}. Kindly procure required number of units as the stock level is below reorder level {REORDERLEVELVALUE}.

Please treat this information as Urgent as the invoice is already sent  to the customer.

Severity: Critical

Thanks,
{CURRENTUSER}';

		
               $this->db->query("insert into inventorynotification(notificationid,notificationname,notificationsubject,notificationbody,label) values (".$this->db->getUniqueID("inventorynotification").",'InvoiceNotification','{PRODUCTNAME} Stock Level is Low','".$invoice_body." ','InvoiceNotificationDescription')");

		$quote_body = 'Dear {HANDLER},

Quote is generated for {QUOTEQUANTITY} units of {PRODUCTNAME}. The current stock of {PRODUCTNAME} in our warehouse is {CURRENTSTOCK}. 

Severity: Minor

Thanks,
{CURRENTUSER}';	
		
		
               $this->db->query("insert into inventorynotification(notificationid,notificationname,notificationsubject,notificationbody,label) values (".$this->db->getUniqueID("inventorynotification").",'QuoteNotification','Quote given for {PRODUCTNAME}','".$quote_body." ','QuoteNotificationDescription')");

		$so_body = 'Dear {HANDLER},

SalesOrder is generated for {SOQUANTITY} units of {PRODUCTNAME}. The current stock of {PRODUCTNAME} in our warehouse is {CURRENTSTOCK}. 

Please treat this information  with priority as the sales order is already generated.

Severity: Major

Thanks,
{CURRENTUSER}';

		
               $this->db->query("insert into inventorynotification(notificationid,notificationname,notificationsubject,notificationbody,label) values (".$this->db->getUniqueID("inventorynotification").",'SalesOrderNotification','Sales Order generated for {PRODUCTNAME}','".$so_body." ','SalesOrderNotificationDescription')");

		//Insert into currency table
		$this->db->query("insert into currency_info values(".$this->db->getUniqueID("currency_info").",'U.S Dollar','USD','$',1,'Active','-11')");
                



//insert into email template table

	$body='
	Hello!

	On behalf of the vtiger team,  I am pleased to announce the release of vtiger crm4.2 . This is a feature packed release including the mass email template handling, custom view feature, reports feature and a host of other utilities. vtiger runs on all platforms.

	Notable Features of vtiger are :
	-Email Client Integration
	-Trouble Ticket Integration
	-Invoice Management Integration
	-Reports Integration
	-Portal Integration
	-Enhanced Word Plugin Support
	-Custom View Integration

	Known Issues:
	-ABCD
	-EFGH
	-IJKL
	-MNOP
	-QRST';

          $this->db->query("insert into emailtemplates(foldername,templatename,subject,description,body,deleted,templateid) values ('Public','Announcement for Release','Announcement for Release','Announcement of a release','".$body."',0,".$this->db->getUniqueID('emailtemplates').")");
	


$body='name
street,
city,
state,
 zip)
 
 Dear
 
 Please check the following invoices that are yet to be paid by you:
 
 No. Date      Amount
 1   1/1/01    $4000
 2   2/2//01   $5000
 3   3/3/01    $10000
 4   7/4/01    $23560
 
 Kindly let us know if there are any issues that you feel are pending to be discussed.
 We will be more than happy to give you a call.
 We would like to continue our business with you.
 
 Sincerely,
 name
 title';


               $this->db->query("insert into emailtemplates(foldername,templatename,subject,description,body,deleted,templateid) values ('Public','Pending Invoices','Invoices Pending','Payment Due','".$body."',0,".$this->db->getUniqueID('emailtemplates').")");





$body=' Dear

Your proposal on the project XYZW has been reviewed by us
and is acceptable in its entirety.

We are eagerly looking forward to this project
and are pleased about having the opportunity to work
together. We look forward to a long standing relationship
with your esteemed firm.

I would like to take this opportunity to invite you
to a game of golf on Wednesday morning 9am at the
Cuff Links Ground. We will be waiting for you in the
Executive Lounge.

Looking forward to seeing you there.

Sincerely,
name
title';
	       
               $this->db->query("insert into emailtemplates(foldername,templatename,subject,description,body,deleted,templateid) values ('Public','Acceptance Proposal','Acceptance Proposal','Acceptance of Proposal','".$body."',0,".$this->db->getUniqueID('emailtemplates').")");


$body= ' The undersigned hereby acknowledges receipt and delivery
of the goods.
The undersigned will release the payment subject to the goods being discovered not satisfactory.

Signed under seal this <date>

Sincerely,
name
title';
	       
               $this->db->query("insert into emailtemplates(foldername,templatename,subject,description,body,deleted,templateid) values ('Public','Good received acknowledgement','Goods received acknowledgement','Acknowledged Receipt of Goods','".$body."',0,".$this->db->getUniqueID('emailtemplates').")");


	       $body= ' Dear
	 We are in receipt of your order as contained in the
   purchase order form.We consider this to be final and binding on both sides.
If there be any exceptions noted, we shall consider them
only if the objection is received within ten days of receipt of
this notice.

Thank you for your patronage.
Sincerely,
name
title';


	       
               $this->db->query("insert into emailtemplates(foldername,templatename,subject,description,body,deleted,templateid) values ('Public','Accept Order','Accept Order','Acknowledgement/Acceptance of Order','".$body."',0,".$this->db->getUniqueID('emailtemplates').")");




$body='Dear

We are relocating our office to
11111,XYZDEF Cross,
UVWWX Circle
The telephone number for this new location is (101) 1212-1328.

Our Manufacturing Division will continue operations
at 3250 Lovedale Square Avenue, in Frankfurt.

We hope to keep in touch with you all.
Please update your addressbooks.


Thank You,
name
title';
	       
               $this->db->query("insert into emailtemplates(foldername,templatename,subject,description,body,deleted,templateid) values ('Public','Address Change','Change of Address','Address Change','".$body."',0,".$this->db->getUniqueID('emailtemplates').")");



$body='Dear

Thank you for extending us the opportunity to meet with
you and members of your staff.

I know that John Doe serviced your account
for many years and made many friends at your firm. He has personally
discussed with me the deep relationship that he had with your firm.
While his presence will be missed, I can promise that we will
continue to provide the fine service that was accorded by
John to your firm.

I was genuinely touched to receive such fine hospitality.

Thank you once again.

Sincerely,
name
title';


	       
               $this->db->query("insert into emailtemplates(foldername,templatename,subject,description,body,deleted,templateid) values ('Public','Follow Up','Follow Up','Follow Up of meeting','".$body."',0,".$this->db->getUniqueID('emailtemplates').")");



$body='Congratulations!

The numbers are in and I am proud to inform you that our
total sales for the previous quarter
amounts to $100,000,00.00!. This is the first time
we have exceeded the target by almost 30%.
We have also beat the previous quarter record by a
whopping 75%!

Let us meet at Smoking Joe for a drink in the evening!

C you all there guys!

Sincerely,
name
title';

	       
               $this->db->query("insert into emailtemplates(foldername,templatename,subject,description,body,deleted,templateid) values ('Public','Target Crossed!','Target Crossed!','Fantastic Sales Spree!','".$body."',0,".$this->db->getUniqueID('emailtemplates').")");

$body='
Dear

Thank you for your confidence in our ability to serve you.
We are glad to be given the chance to serve you.I look
forward to establishing a long term partnership with you.
Consider me as a friend.
Should any need arise,please do give us a call.

Sincerely,
name
title';

	       
               $this->db->query("insert into emailtemplates(foldername,templatename,subject,description,body,deleted,templateid) values ('Public','Thanks Note','Thanks Note','Note of thanks','".$body."',0,".$this->db->getUniqueID('emailtemplates').")");

		
	       //Insert into organizationdetails table 
	       $this->db->query("insert into organizationdetails(organizationame,address,city,state,country,code,phone,fax,website,logoname) values ('vtiger',' 40-41-42, Sivasundar Apartments, Flat D-II, Shastri Street, Velachery','Chennai','Tamil Nadu','India','600 042','+91-44-5202-1990','+91-44-5202-1990','www.vtiger.com','vtiger-crm-logo.jpg')");

  //Insert into inventory_tandc table
               $this->db->query("insert into inventory_tandc values('".$this->db->getUniqueID('inventory_tandc')."','Inventory','')");

	$this->db->query("insert into actionmapping values(0,'Save',0)");
	$this->db->query("insert into actionmapping values(1,'EditView',0)");
	$this->db->query("insert into actionmapping values(2,'Delete',0)");
	$this->db->query("insert into actionmapping values(3,'index',0)");
	$this->db->query("insert into actionmapping values(4,'DetailView',0)");
	$this->db->query("insert into actionmapping values(5,'Import',0)");
	$this->db->query("insert into actionmapping values(6,'Export',0)");
	$this->db->query("insert into actionmapping values(7,'AddBusinessCard',0)");
	$this->db->query("insert into actionmapping values(8,'Merge',0)");
	$this->db->query("insert into actionmapping values(1,'VendorEditView',0)");
	$this->db->query("insert into actionmapping values(4,'VendorDetailView',0)");
	$this->db->query("insert into actionmapping values(0,'SaveVendor',0)");
	$this->db->query("insert into actionmapping values(2,'DeleteVendor',0)");
	$this->db->query("insert into actionmapping values(1,'PriceBookEditView',0)");
	$this->db->query("insert into actionmapping values(4,'PriceBookDetailView',0)");
	$this->db->query("insert into actionmapping values(0,'SavePriceBook',0)");
	$this->db->query("insert into actionmapping values(2,'DeletePriceBook',0)");
	$this->db->query("insert into actionmapping values(9,'ConvertLead',0)");

	//Insert values for moduleowners table which contains the modules and their users. default user id admin - after 4.2 patch 2
	$module_array = Array('Potentials','Contacts','Accounts','Leads','Notes','Activities','Emails','HelpDesk','Products','Faq','Vendors','PriceBooks','Quotes','PurchaseOrder','SalesOrder','Invoice','Reports');
	foreach($module_array as $mod)
	{
		$this->db->query("insert into moduleowners values(".getTabid($mod).",1)");
	}
	//added by jeri for category view from db
	$this->db->query("insert into parenttab values
			(1,'My Home Page',1,0),
			(2,'Marketing',2,0),
			(3,'Sales',3,0),
			(4,'Support',4,0),
			(5,'Analytics',5,0),
			(6,'Inventory',6,0),
			(7,'Tools',7,0),
			(8,'Settings',8,0)
		    	");

	$this->db->query("insert into parenttabrel values
			(1,9,2),
			(1,17,3),
			(1,10,4),
    			(1,3,1),
		    	(3,7,1),
			(3,6,2),
			(3,4,3),
    			(3,2,4),
	    		(3,20,5),
	    		(3,22,6),
	    		(3,23,7),
	    		(3,14,8),
	    		(3,19,9),
	    		(3,8,10),
	   		(4,13,1),
	    		(4,15,2),
	    		(4,6,3),
	    		(4,4,4),
		   	(4,14,5),
			(4,8,6),
			(5,1,1),
			(5,25,2),
		    	(6,14,1),
	 	   	(6,18,2),
		    	(6,19,3),
		    	(6,21,4),
		    	(6,22,5),
		    	(6,20,6),
	    		(6,23,7),
		    	(7,24,1),
			(7,27,2),
	    		(7,8,3),
		    	(2,26,1)");
				

		//rss feeds

		$this->db->query("insert into rss values(1,'http://finance.yahoo.com/rss/headline?s=IBM','IBM Finblog',0,1,'IBM Finance')");	
		$this->db->query("insert into rss values(2,'http://finance.yahoo.com/rss/headline?s=HPQ','HP Blog',0,1,'HP Finance')");
		$this->db->query("insert into rss values(3,'http://finance.yahoo.com/rss/headline?s=GM','GM blog',0,0,'GM Finance')");	

		}
	
}
?>

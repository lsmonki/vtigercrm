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
require_once('include/utils.php');

// TabMenu shown in the header page.
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
		$profile1_id = $this->db->getUniqueID("profile");
		$profile2_id = $this->db->getUniqueID("profile");
		$profile3_id = $this->db->getUniqueID("profile");
		$profile4_id = $this->db->getUniqueID("profile");

                $this->db->query("insert into role values(".$role1_id.",'administrator','')");
		$this->db->query("insert into role values(".$role2_id.",'standard_user','')");
                
                $table_name="user2role";
                $table_name="role2tab";

 $this->db->query("INSERT INTO tab VALUES (3,'Home',0,1,'Home','','',1)");
 $this->db->query("INSERT INTO tab VALUES (7,'Leads',0,4,'Leads','','',1)");
 $this->db->query("INSERT INTO tab VALUES (6,'Accounts',0,5,'Accounts','','',1)");
 $this->db->query("INSERT INTO tab VALUES (4,'Contacts',0,6,'Contacts','','',1)");
 $this->db->query("INSERT INTO tab VALUES (2,'Potentials',0,7,'Potentials','','',1)");
 $this->db->query("INSERT INTO tab VALUES (8,'Notes',0,9,'Notes','','',1)");
 $this->db->query("INSERT INTO tab VALUES (9,'Activities',0,3,'Activities','','',1)");
 $this->db->query("INSERT INTO tab VALUES (10,'Emails',0,10,'Emails','','',1)");
 $this->db->query("INSERT INTO tab VALUES (13,'HelpDesk',0,11,'HelpDesk','','',1)");
 $this->db->query("INSERT INTO tab VALUES (14,'Products',0,8,'Products','','',1)");
 $this->db->query("INSERT INTO tab VALUES (1,'Dashboard',0,12,'Dashboards','','',1)");
 $this->db->query("INSERT INTO tab VALUES (15,'Faq',2,14,'Faq','','',1)");
 $this->db->query("INSERT INTO tab VALUES (16,'Events',2,13,'Events','','',1)");
 $this->db->query("INSERT INTO tab VALUES (17,'Calendar',0,2,'Calendar','','',1)");

//Account Details -- START
 //Block1
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'accountname','account',1,'2','accountname','Account Name',1,0,0,100,1,1,1,'V~M')");	
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'phone','account',1,'11','phone','Phone',1,0,0,100,2,1,1,'V~O')");	
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'website','account',1,'17','website','Website',1,0,0,100,3,1,1,'V~O')");	

 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'fax','account',1,'1','fax','Fax',1,0,0,100,4,1,1,'V~O')");	
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'tickersymbol','account',1,'1','tickersymbol','Ticker Symbol',1,0,0,100,5,1,1,'V~O')");	
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'otherphone','account',1,'11','otherphone','Other Phone',1,0,0,100,6,1,1,'V~O')");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'parentid','account',1,'51','account_id','Member Of',1,0,0,100,7,1,1,'I~O')");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'email1','account',1,'13','email1','Email',1,0,0,100,8,1,1,'V~O')");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'employees','account',1,'7','employees','Employees',1,0,0,100,9,1,1,'I~O')");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'email2','account',1,'13','email2','Other Email',1,0,0,100,10,1,1,'V~O')");

 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'ownership','account',1,'1','ownership','Ownership',1,0,0,100,11,1,1,'V~O')");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'rating','account',1,'1','rating','Rating',1,0,0,100,12,1,1,'V~O')");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'industry','account',1,'15','industry','industry',1,0,0,100,13,1,1,'V~O')");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'siccode','account',1,'1','siccode','SIC Code',1,0,0,100,14,1,1,'I~O')");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'account_type','account',1,'15','accounttype','Type',1,0,0,100,15,1,1,'V~O')");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'annualrevenue','account',1,'71','annual_revenue','Annual Revenue',1,0,0,100,16,1,1,'I~O')");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'smownerid','crmentity',1,'52','assigned_user_id','Assigned To',1,0,0,100,17,1,1,'V~M')");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'70','createdtime','Created Time',1,0,0,100,18,1,2,'T~O')");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'70','modifiedtime','Modified Time',1,0,0,100,18,1,2,'T~O')");

 //Block 2
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'street','accountbillads',1,'21','bill_street','Billing Address',1,0,0,100,1,2,1,'V~O')");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'street','accountshipads',1,'21','ship_street','Shipping Address',1,0,0,100,2,2,1,'V~O')");

 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'city','accountbillads',1,'1','bill_city','City',1,0,0,100,3,2,1,'V~O')");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'city','accountshipads',1,'1','ship_city','City',1,0,0,100,4,2,1,'V~O')");

 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'state','accountbillads',1,'1','bill_state','State',1,0,0,100,5,2,1,'V~O')");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'state','accountshipads',1,'1','ship_state','State',1,0,0,100,6,2,1,'V~O')");

 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'code','accountbillads',1,'1','bill_code','Code',1,0,0,100,7,2,1,'V~O')");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'code','accountshipads',1,'1','ship_code','Code',1,0,0,100,8,2,1,'V~O')");


 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'country','accountbillads',1,'1','bill_country','Country',1,0,0,100,9,2,1,'V~O')");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'country','accountshipads',1,'1','ship_country','Country',1,0,0,100,10,2,1,'V~O')");

 //Block3
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'description','crmentity',1,'19','description','Description',1,0,0,100,1,3,1,'V~O')");
//Account Details -- END

			
//Lead Details --- START

//Block1 -- Start
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'salutation','leaddetails',1,'55','salutationtype','Salutation',1,0,0,100,1,1,3,'V~O')");	
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'firstname','leaddetails',1,'55','firstname','First Name',1,0,0,100,2,1,1,'V~O')");	
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'phone','leadaddress',1,'11','phone','Phone',1,0,0,100,3,1,1,'V~O')");	

 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'lastname','leaddetails',1,'2','lastname','Last Name',1,0,0,100,4,1,1,'V~M')");	
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'mobile','leadaddress',1,'1','mobile','Mobile',1,0,0,100,5,1,1,'V~O')");	
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'company','leaddetails',1,'2','company','Company',1,0,0,100,6,1,1,'V~M')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'fax','leadaddress',1,'1','fax','Fax',1,0,0,100,7,1,1,'V~O')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'designation','leaddetails',1,'1','designation','Designation',1,0,0,100,8,1,1,'V~O')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'email','leaddetails',1,'13','email','Email',1,0,0,100,9,1,1,'V~O')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'leadsource','leaddetails',1,'15','leadsource','Lead Source',1,0,0,100,10,1,1,'V~O')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'website','leadsubdetails',1,'17','website','Website',1,0,0,100,11,1,1,'V~O')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'industry','leaddetails',1,'15','industry','Industry',1,0,0,100,12,1,1,'V~O')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'leadstatus','leaddetails',1,'15','leadstatus','Lead Status',1,0,0,100,13,1,1,'V~O')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'annualrevenue','leaddetails',1,'71','annualrevenue','Annual Revenue',1,0,0,100,14,1,1,'V~O')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'rating','leaddetails',1,'15','rating','Rating',1,0,0,100,15,1,1,'V~O')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'noofemployees','leaddetails',1,'1','noofemployees','No Of Employees',1,0,0,100,16,1,1,'V~O')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'smownerid','crmentity',1,'53','assigned_user_id','Assigned To',1,0,0,100,17,1,1,'V~M')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'yahooid','leaddetails',1,'13','yahooid','Yahoo Id',1,0,0,100,18,1,1,'V~O')");
$this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'70','createdtime','Created Time',1,0,0,100,19,1,2,'T~O')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'70','modifiedtime','Modified Time',1,0,0,100,20,1,2,'T~O')");
//Block1 -- End

//Block2 -- Start
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'lane','leadaddress',1,'21','lane','Street',1,0,0,100,1,2,1,'V~O')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'code','leadaddress',1,'1','code','Postal Code',1,0,0,100,2,2,1,'V~O')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'city','leadaddress',1,'1','city','City',1,0,0,100,3,2,1,'V~O')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'country','leadaddress',1,'1','country','Country',1,0,0,100,4,2,1,'V~O')");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'state','leadaddress',1,'1','state','State',1,0,0,100,5,2,1,'V~O')");
//Block2 --End

//Block3 -- Start
$this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'description','crmentity',1,'19','description','Description',1,0,0,100,1,3,1,'V~O')");
//Block3 -- End
//Lead Details -- END


//Contact Details -- START
//Block1 -- Start
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'salutation','contactdetails',1,'55','salutationtype','Salutation',1,0,0,100,1,1,3,'V~O')");	
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'firstname','contactdetails',1,'55','firstname','First Name',1,0,0,100,2,1,1,'V~O')");	
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'phone','contactdetails',1,'11','phone','Office Phone',1,0,0,100,3,1,1,'V~O')");	
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'lastname','contactdetails',1,'2','lastname','Last Name',1,0,0,100,4,1,1,'V~M')");	
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'mobile','contactdetails',1,'1','mobile','Mobile',1,0,0,100,5,1,1,'V~O')");	
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'accountid','contactdetails',1,'50','account_id','Account Name',1,0,0,100,6,1,1,'I~M')");	
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'homephone','contactsubdetails',1,'11','homephone','Home Phone',1,0,0,100,7,1,1,'V~O')");	
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'leadsource','contactsubdetails',1,'15','leadsource','Lead Source',1,0,0,100,8,1,1,'V~O')");	
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'otherphone','contactsubdetails',1,'11','otherphone','Phone',1,0,0,100,9,1,1,'V~O')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'title','contactdetails',1,'1','title','Title',1,0,0,100,10,1,1,'V~O')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'fax','contactdetails',1,'1','fax','Fax',1,0,0,100,11,1,1,'V~O')");	
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'department','contactdetails',1,'1','department','Department',1,0,0,100,12,1,1,'V~O')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'birthday','contactsubdetails',1,'5','birthday','Birthdate',1,0,0,100,14,1,1,'V~O')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'email','contactdetails',1,'13','email','Email',1,0,0,100,15,1,1,'V~O')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'reportsto','contactdetails',1,'57','contact_id','Reports To',1,0,0,100,16,1,1,'V~O')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'assistant','contactsubdetails',1,'1','assistant','Assistant',1,0,0,100,17,1,1,'V~O')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'yahooid','contactdetails',1,'13','yahooid','Yahoo Id',1,0,0,100,18,1,1,'V~O')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'assistantphone','contactsubdetails',1,'11','assistantphone','Assistant Phone',1,0,0,100,19,1,1,'V~O')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'donotcall','contactdetails',1,'56','donotcall','Do Not Call',1,0,0,100,20,1,1,'C~O')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'emailoptout','contactdetails',1,'56','emailoptout','Email Opt Out',1,0,0,100,21,1,1,'C~O')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'smownerid','crmentity',1,'52','assigned_user_id','Assigned To',1,0,0,100,22,1,1,'V~M')");
$this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'70','createdtime','Created Time',1,0,0,100,23,1,2,'T~O')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'70','modifiedtime','Modified Time',1,0,0,100,24,1,2,'T~O')");
//Block1 -- End

//Block 2 -- Start
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'mailingstreet','contactaddress',1,'21','mailingstreet','Mailing Street',1,0,0,100,1,2,1,'V~O')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'otherstreet','contactaddress',1,'21','otherstreet','Other Street',1,0,0,100,2,2,1,'V~O')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'mailingcity','contactaddress',1,'1','mailingcity','City',1,0,0,100,3,2,1,'V~O')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'othercity','contactaddress',1,'1','othercity','City',1,0,0,100,4,2,1,'V~O')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'mailingstate','contactaddress',1,'1','mailingstate','State',1,0,0,100,5,2,1,'V~O')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'otherstate','contactaddress',1,'1','otherstate','State',1,0,0,100,6,2,1,'V~O')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'mailingzip','contactaddress',1,'1','mailingzip','Zip',1,0,0,100,7,2,1,'V~O')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'otherzip','contactaddress',1,'1','otherzip','Zip',1,0,0,100,8,2,1,'V~O')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'mailingcountry','contactaddress',1,'1','mailingcountry','Country',1,0,0,100,9,2,1,'V~O')");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'othercountry','contactaddress',1,'1','othercountry','Country',1,0,0,100,10,2,1,'V~O')");
//Block2 -- End

//Block3 -- Start
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'description','crmentity',1,'19','description','Description',1,0,0,100,1,3,1,'V~O')");
//Block3 -- End
//Contact Details -- END


//Potential Details -- START
//Block1 -- Start
 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'potentialname','potential',1,'2','potentialname','Potential Name',1,0,0,100,1,1,1,'V~M')");	
 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'amount','potential',1,71,'amount','Amount',1,0,0,100,2,1,1,'N~O')");	
 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'accountid','potential',1,'50','account_id','Account Name',1,0,0,100,3,1,1,'V~M')");	
 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'closingdate','potential',1,'23','closingdate','Expected Close Date',1,0,0,100,5,1,1,'D~M')");	
 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'potentialtype','potential',1,'15','opportunity_type','Type',1,0,0,100,6,1,1,'V~O')");	
 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'nextstep','potential',1,'1','nextstep','Next Step',1,0,0,100,7,1,1,'V~O')");
 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'leadsource','potential',1,'15','leadsource','Lead Source',1,0,0,100,8,1,1,'V~O')");	
 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'sales_stage','potential',1,'16','sales_stage','Sales Stage',1,0,0,100,9,1,1,'V~O')");
 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'smownerid','crmentity',1,'52','assigned_user_id','Assigned To',1,0,0,100,10,1,1,'V~M')");
 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'probability','potential',1,'9','probability','Probability',1,0,0,100,11,1,1,'N~O')");
$this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'70','createdtime','Created Time',1,0,0,100,13,1,2,'T~O')");
 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'70','modifiedtime','Modified Time',1,0,0,100,14,1,2,'T~O')");
//Block1 -- End

//Block2 -- Start
 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'description','crmentity',1,'19','description','Description',1,0,0,100,1,2,1,'V~O')");
//Block2 -- End
//Potential Details -- END

//Ticket Details -- START
//Block1 -- Start
// $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'groupname','troubletickets',1,'54','groupname','Group',1,0,0,100,1,1,1)");
 $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'smownerid','crmentity',1,'53','assigned_user_id','Assigned To',1,0,0,100,2,1,1,'V~M')");
 // $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'crmid','seticketsrel',1,'59','parent_id','Product Name',1,0,0,100,3,1,1,'V~O')");
 $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'contact_id','troubletickets',1,'57','contact_id','Contact Name',1,0,0,100,4,1,1,'I~O')");
 $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'priority','troubletickets',1,'15','ticketpriorities','Priority',1,0,0,100,5,1,1,'V~O')");
 $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'status','troubletickets',1,'15','ticketstatus','Status',1,0,0,100,6,1,1,'V~O')");
 $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'category','troubletickets',1,'15','ticketcategories','Category',1,0,0,100,7,1,1,'V~O')");
 $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'update_log','troubletickets',1,'15','update_log','Update History',1,0,0,100,7,1,3,'V~O')");
$this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'70','createdtime','Created Time',1,0,0,100,8,1,2,'T~O')");
 $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'70','modifiedtime','Modified Time',1,0,0,100,9,1,2,'T~O')");
 $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'title','troubletickets',1,'22','ticket_title','Title',1,0,0,100,1,2,1,'V~M')");
 $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'description','troubletickets',1,'19','description','Description',1,0,0,100,1,3,1,'V~O')");
//Block1 -- End
//Ticket Details -- END

//Product Details -- START
//Block1 -- Start
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'productname','products',1,'2','productname','Product Name',1,0,0,100,1,1,1,'V~M')");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'productcode','products',1,'1','productcode','Product Code',1,0,0,100,2,1,1,'V~O')");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'discontinued','products',1,'56','discontinued','Product Active',1,0,0,100,3,1,1,'V~O')");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'commissionrate','products',1,'9','commissionrate','Commission Rate',1,0,0,100,4,1,1,'N~O')"); 
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'qty_per_unit','products',1,'1','qty_per_unit','Qty/Unit',1,0,0,100,5,1,1,'N~O')");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'unit_price','products',1,'71','unit_price','Unit Price',1,0,0,100,6,1,1,'N~O')"); 
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'manufacturer','products',1,'15','manufacturer','Manufacturer',1,0,0,100,7,1,1,'V~O')");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'productcategory','products',1,'15','productcategory','Product Category',1,0,0,100,8,1,1,'V~O')");	
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'start_date','products',1,'5','start_date','Support Start Date',1,0,0,100,8,1,1,'D~O')");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'expiry_date','products',1,'5','expiry_date','Support Expiry Date',1,0,0,100,9,1,1,'D~O~GE~start_date~Support Expiry Date')");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'purchase_date','products',1,'5','purchase_date','Purchase Date',1,0,0,100,10,1,1,'D~O')");
$this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'70','createdtime','Created Time',1,0,0,100,11,1,2,'T~O')");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'70','modifiedtime','Modified Time',1,0,0,100,12,1,2,'T~O')");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'product_description','products',1,'19','product_description','Description',1,0,0,100,13,2,1,'V~O')");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'crmid','seproductsrel',1,'66','parent_id','Related To',1,0,0,100,14,1,1,'I~O')");
//Block1 -- End
//Product Details -- END

//Note Details -- START
//Block1 -- Start
 $this->db->query("insert into field values (8,".$this->db->getUniqueID("field").",'contact_id','notes',1,'57','contact_id','Contact Name',1,0,0,100,1,1,1,'V~O')");
 $this->db->query("insert into field values (8,".$this->db->getUniqueID("field").",'crmid','senotesrel',1,'62','parent_id','Related To',1,0,0,100,2,1,1,'I~O')");
 $this->db->query("insert into field values (8,".$this->db->getUniqueID("field").",'title','notes',1,'2','title','Subject',1,0,0,100,3,1,1,'V~M')");
$this->db->query("insert into field values (8,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'70','createdtime','Created Time',1,0,0,100,4,1,2,'T~O')");
 $this->db->query("insert into field values (8,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'70','modifiedtime','Modified Time',1,0,0,100,5,1,2,'T~O')");
 $this->db->query("insert into field values (8,".$this->db->getUniqueID("field").",'filename','notes',1,'61','filename','Attachment',1,0,0,100,4,2,1,'V~O')");
 $this->db->query("insert into field values (8,".$this->db->getUniqueID("field").",'notecontent','notes',1,'19','notecontent','Note',1,0,0,100,5,3,1,'V~O')");
//Block1 -- End
//Note Details -- END

//Email Details -- START
//Block1 -- Start
	$this->db->query("insert into field values (10,".$this->db->getUniqueID("field").",'date_start','activity',1,'6','date_start','Date & Time Sent',1,0,0,100,1,1,1,'DT~M~time_start~Time Start')");
	$this->db->query("insert into field values (10,".$this->db->getUniqueID("field").",'semodule','activity',1,'2','parent_type','Sales Enity Module',1,0,0,100,2,1,3,'')");
	$this->db->query("insert into field values (10,".$this->db->getUniqueID("field").",'activitytype','activity',1,'2','activitytype','Activtiy Type',1,0,0,100,3,1,3,'V~O')");
	$this->db->query("insert into field values (10,".$this->db->getUniqueID("field").",'crmid','seactivityrel',1,'67','parent_id','Related To',1,0,0,100,4,1,1,'I~O')");
	$this->db->query("insert into field values (10,".$this->db->getUniqueID("field").",'smownerid','crmentity',1,'52','assigned_user_id','Assigned To',1,0,0,100,5,1,1,'V~M')");
	$this->db->query("insert into field values (10,".$this->db->getUniqueID("field").",'subject','activity',1,'2','subject','Subject',1,0,0,100,6,2,1,'V~M')");
	$this->db->query("insert into field values (10,".$this->db->getUniqueID("field").",'filename','emails',1,'61','filename','Attachment',1,0,0,100,7,3,1,'V~O')");
	 $this->db->query("insert into field values (10,".$this->db->getUniqueID("field").",'description','emails',1,'19','description','Description',1,0,0,100,8,4,1,'V~O')");
	 $this->db->query("insert into field values (10,".$this->db->getUniqueID("field").",'time_start','activity',1,'2','time_start','Time Start',1,0,0,100,9,1,3,'T~O')");
$this->db->query("insert into field values (10,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'70','createdtime','Created Time',1,0,0,100,10,1,2,'T~O')");
 $this->db->query("insert into field values (10,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'70','modifiedtime','Modified Time',1,0,0,100,11,1,2,'T~O')");
//Block1 -- End
//Email Details -- END


//Task Details --START
//Block1 -- Start
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'subject','activity',1,'2','subject','Subject',1,0,0,100,1,1,1,'V~M')");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'smownerid','crmentity',1,'53','assigned_user_id','Assigned To',1,0,0,100,2,1,1,'V~M')");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'date_start','activity',1,'6','date_start','Start Date & Time',1,0,0,100,3,1,1,'DT~M~time_start')");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'time_start','activity',1,'2','time_start','Time Start',1,0,0,100,4,1,3,'T~O')");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'due_date','activity',1,'5','due_date','Due Date',1,0,0,100,5,1,1,'D~M')");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'crmid','seactivityrel',1,'66','parent_id','Related To',1,0,0,100,7,1,1,'I~O')");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'contactid','cntactivityrel',1,'57','contact_id','Contact Name',1,0,0,100,8,1,1,'I~O')");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'status','activity',1,'15','taskstatus','Status',1,0,0,100,9,1,1,'V~O')");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'eventstatus','activity',1,'15','eventstatus','Status',1,0,0,100,9,1,1,'V~O')");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'priority','activity',1,'15','taskpriority','Priority',1,0,0,100,10,1,1,'V~O')");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'sendnotification','activity',1,'56','sendnotification','Send Notification',1,0,0,100,11,1,1,'C~O')");
$this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'70','createdtime','Created Time',1,0,0,100,14,1,2,'T~O')");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'70','modifiedtime','Modified Time',1,0,0,100,15,1,2,'T~O')");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'activitytype','activity',1,'15','activitytype','Activity Type',1,0,0,100,16,1,3,'V~O')");

 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'description','activity',1,'19','description','Description',1,0,0,100,1,2,1,'V~O')");


$this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'duration_hours','activity',1,'63','duration_hours','Duration',1,0,0,100,17,1,3,'T~O')");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'duration_minutes','activity',1,'15','duration_minutes','Duration Minutes',1,0,0,100,18,1,3,'T~O')");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'location','activity',1,'1','location','Location',1,0,0,100,19,1,3,'V~O')");

//Block1 -- End
//Task Details -- END


//Event Details --START
//Block1 -- Start
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'subject','activity',1,'2','subject','Subject',1,0,0,100,1,1,1,'V~M')");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'smownerid','crmentity',1,'53','assigned_user_id','Assigned To',1,0,0,100,2,1,1,'I~O')");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'date_start','activity',1,'6','date_start','Start Date & Time',1,0,0,100,3,1,1,'DT~M~time_start')");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'time_start','activity',1,'2','time_start','Time Start',1,0,0,100,4,1,3,'T~M')");
$this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'duration_hours','activity',1,'63','duration_hours','Duration',1,0,0,100,5,1,1,'I~M')");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'duration_minutes','activity',1,'15','duration_minutes','Duration Minutes',1,0,0,100,6,1,3,'O~O')");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'crmid','seactivityrel',1,'66','parent_id','Related To',1,0,0,100,7,1,1,'I~O')");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'contactid','cntactivityrel',1,'57','contact_id','Contact Name',1,0,0,100,8,1,1,'V~O')");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'eventstatus','activity',1,'15','eventstatus','Status',1,0,0,100,9,1,1,'V~O')");
//Priority not needed in events
# $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'priority','activity',1,'15','taskpriority','Priority',1,0,0,100,10,1,1,'V~O')");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'sendnotification','activity',1,'56','sendnotification','Send Notification',1,0,0,100,11,1,1,'C~O')");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'activitytype','activity',1,'15','activitytype','Activity Type',1,0,0,100,12,1,1,'V~O')");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'location','activity',1,'1','location','Location',1,0,0,100,13,1,1,'V~O')");
$this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'70','createdtime','Created Time',1,0,0,100,14,1,2,'T~O')");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'70','modifiedtime','Modified Time',1,0,0,100,15,1,2,'T~O')");

 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'description','activity',1,'19','description','Description',1,0,0,100,1,2,1,'V~O')");

//Block1 -- End
//Event Details -- END

//Faq Details -- START
//Block1 -- Start
 $this->db->query("insert into field values (15,".$this->db->getUniqueID("field").",'category','faq',1,'15','faqcategories','Category',1,0,0,100,1,1,1,'V~O')");
 $this->db->query("insert into field values (15,".$this->db->getUniqueID("field").",'crmid','sefaqrel',1,'62','parent_id','Related To',1,0,0,100,2,1,1,'I~O')");
 $this->db->query("insert into field values (15,".$this->db->getUniqueID("field").",'question','faq',1,'19','question','Question',1,0,0,100,1,2,1,'V~O')");
 $this->db->query("insert into field values (15,".$this->db->getUniqueID("field").",'answer','faq',1,'19','answer','Answer',1,0,0,100,1,3,1,'V~O')");
 $this->db->query("insert into field values (15,".$this->db->getUniqueID("field").",'comments','faq',1,'19','comments','Comments',1,0,0,100,1,4,1,'V~O')");
$this->db->query("insert into field values (15,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'70','createdtime','Created Time',1,0,0,100,3,1,2,'T~O')");
 $this->db->query("insert into field values (15,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'70','modifiedtime','Modified Time',1,0,0,100,4,1,2,'T~O')");
//Block1 -- End
//Ticket Details -- END

                // Insert End
                
                

		//New Security Start
		//Inserting into profile table
		$this->db->query("insert into profile values ('".$profile1_id."','Administrator')");	
		$this->db->query("insert into profile values ('".$profile2_id."','Sales Profile')");
		$this->db->query("insert into profile values ('".$profile3_id."','Support Profile')");
		$this->db->query("insert into profile values ('".$profile4_id."','Guest Profile')");
		
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
	

		//Inserting into profile2standardpermissions  Adminsitrator
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",1,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",1,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",1,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",1,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",1,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",2,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",2,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",2,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",2,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",2,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",3,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",3,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",3,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",3,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile1_id.",3,4,0)");

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




		//Insert into Profile 2 std permissions for Sales User  
		//Help Desk Create/Delete not allowed. Read-Only	


		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",1,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",1,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",1,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",1,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",1,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",2,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",2,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",2,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",2,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",2,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",3,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",3,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",3,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",3,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile2_id.",3,4,0)");

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


		//Inserting into profile2std for Support Profile
		// Potential is read-only
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",1,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",1,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",1,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",1,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",1,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",2,0,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",2,1,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",2,2,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",2,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",2,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",3,0,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",3,1,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",3,2,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",3,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile3_id.",3,4,0)");

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


		//Inserting into profile2stdper for Profile Guest Profile
		//All Read-Only
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",1,0,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",1,1,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",1,2,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",1,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",1,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",2,0,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",2,1,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",2,2,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",2,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",2,4,0)");

		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",3,0,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",3,1,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",3,2,1)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",3,3,0)");
		$this->db->query("insert into profile2standardpermissions values (".$profile4_id.",3,4,0)");

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

		//Insert into role2profile
		$this->db->query("insert into role2profile values (".$role1_id.",".$profile1_id.")");
		$this->db->query("insert into role2profile values (".$role2_id.",".$profile2_id.")");
	
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
                $this->db->query("insert into profile2utility values (".$profile1_id.",9,6,0)");
                $this->db->query("insert into profile2utility values (".$profile1_id.",10,6,0)");
		$this->db->query("insert into profile2utility values (".$profile1_id.",7,8,0)");
                $this->db->query("insert into profile2utility values (".$profile1_id.",6,8,0)");
                $this->db->query("insert into profile2utility values (".$profile1_id.",4,8,0)");

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
                $this->db->query("insert into profile2utility values (".$profile2_id.",9,6,1)");
                $this->db->query("insert into profile2utility values (".$profile2_id.",10,6,1)");
                $this->db->query("insert into profile2utility values (".$profile2_id.",7,8,0)");
                $this->db->query("insert into profile2utility values (".$profile2_id.",6,8,0)");
                $this->db->query("insert into profile2utility values (".$profile2_id.",4,8,0)");

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
                $this->db->query("insert into profile2utility values (".$profile3_id.",9,6,1)");
                $this->db->query("insert into profile2utility values (".$profile3_id.",10,6,1)");
                $this->db->query("insert into profile2utility values (".$profile3_id.",7,8,0)");
                $this->db->query("insert into profile2utility values (".$profile3_id.",6,8,0)");
                $this->db->query("insert into profile2utility values (".$profile3_id.",4,8,0)");

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
                $this->db->query("insert into profile2utility values (".$profile4_id.",9,6,1)");
                $this->db->query("insert into profile2utility values (".$profile4_id.",10,6,1)");
                $this->db->query("insert into profile2utility values (".$profile4_id.",7,8,1)");
                $this->db->query("insert into profile2utility values (".$profile4_id.",6,8,1)");
                $this->db->query("insert into profile2utility values (".$profile4_id.",4,8,1)");		

			
		//Insert into default_org_sharingrule
		$this->db->query("insert into def_org_share values (".$this->db->getUniqueID('def_org_share').",2,2)");
		
		$this->db->query("insert into def_org_share values (".$this->db->getUniqueID('def_org_share').",4,2)");

		$this->db->query("insert into def_org_share values (".$this->db->getUniqueID('def_org_share').",6,2)");

		$this->db->query("insert into def_org_share values (".$this->db->getUniqueID('def_org_share').",7,2)");

		$this->db->query("insert into def_org_share values (".$this->db->getUniqueID('def_org_share').",8,2)");
                $this->db->query("insert into def_org_share values (".$this->db->getUniqueID('def_org_share').",9,2)");
                $this->db->query("insert into def_org_share values (".$this->db->getUniqueID('def_org_share').",10,2)");
                $this->db->query("insert into def_org_share values (".$this->db->getUniqueID('def_org_share').",13,2)");
                $this->db->query("insert into def_org_share values (".$this->db->getUniqueID('def_org_share').",14,2)");
                $this->db->query("insert into def_org_share values (".$this->db->getUniqueID('def_org_share').",15,2)");
                $this->db->query("insert into def_org_share values (".$this->db->getUniqueID('def_org_share').",16,2)");			
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

	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Accounts").",".getTabid("Activities").",'get_activities',3,'Acivities',0)");

	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Accounts").",".getTabid("Activities").",'get_history',4,'History',0)");

	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Accounts").",0,'get_attachments',5,'Attachments',0)");

	//Inserting Lead Related Lists	

	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Leads").",".getTabid("Activities").",'get_activities',1,'Activities',0)");

	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Leads").",".getTabid("Emails").",'get_emails',2,'Emails',0)");

	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Leads").",".getTabid("Activities").",'get_history',3,'History',0)");

	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Leads").",0,'get_attachments',4,'Attachments',0)");

	//Inserting for contact related lists
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Contacts").",".getTabid("Potentials").",'get_opportunities',1,'Potentials',0)");	
		
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Contacts").",".getTabid("Activities").",'get_activities',2,'Activities',0)");

	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Contacts").",".getTabid("Emails").",'get_emails',3,'Emails',0)");

	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Contacts").",".getTabid("HelpDesk").",'get_tickets',4,'HelpDesk',0)");

	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Contacts").",".getTabid("Activities").",'get_history',5,'History',0)");

	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Contacts").",0,'get_attachments',6,'Attachments',0)");

	//Inserting Potential Related Lists	

	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Potentials").",".getTabid("Activities").",'get_activities',1,'Activities',0)");

	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Potentials").",".getTabid("Contacts").",'get_contacts',2,'Contacts',0)");

	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Potentials").",".getTabid("Products").",'get_products',3,'History',0)");

	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Potentials").",0,'get_attachments',4,'Attachments',0)");

		//Inserting Product Related Lists	

	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Products").",".getTabid("HelpDesk").",'get_tickets',1,'HelpDesk',0)");

	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Products").",".getTabid("Activities").",'get_activities',2,'Activities',0)");

	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Products").",0,'get_attachments',3,'Attachments',0)");

		//Inserting Emails Related Lists	

	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Emails").",".getTabid("Contacts").",'get_contacts',1,'Contacts',0)");

	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Emails").",0,'get_users',2,'Users',0)");

	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("Emails").",0,'get_attachments',3,'Attachments',0)");

		//Inserting HelpDesk Related Lists
		
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("HelpDesk").",".getTabid("Activities").",'get_activities',1,'Activities',0)");

	
	$this->db->query("insert into relatedlists values(".$this->db->getUniqueID('relatedlists').",".getTabid("HelpDesk").",0,'get_attachments',2,'Attachments',0)");




                 $this->db->query("insert into notificationscheduler(schedulednotificationid,schedulednotificationname,active,description,notificationsubject,notificationbody) values (".$this->db->getUniqueID("notificationscheduler").",'Notification stating tasks which are delayed beyond 24 hrs',1,'Delayed Task Notification','Task Delay Notification','Tasks delayed beyond 24 hrs')");


                 $this->db->query("insert into notificationscheduler(schedulednotificationid,schedulednotificationname,active,description,notificationsubject,notificationbody) values (".$this->db->getUniqueID("notificationscheduler").",'Big Deal Notification' ,1,'A big deal has been achieved','Big Deal notification','Success! A big deal has been won!')");


                 $this->db->query("insert into notificationscheduler(schedulednotificationid,schedulednotificationname,active,description,notificationsubject,notificationbody) values (".$this->db->getUniqueID("notificationscheduler").",'Notification for pending tickets',1,'Pending Tickets notification','Pending Tickets notification','Ticket pending please')");



                 $this->db->query("insert into notificationscheduler(schedulednotificationid,schedulednotificationname,active,description,notificationsubject,notificationbody) values (".$this->db->getUniqueID("notificationscheduler").",'Notifcation stating too many tickets are allocated to an entity',1,'Too many tickets Notification','Too many tickets Notification','Too many tickets pending against this entity    ')");


                 $this->db->query("insert into notificationscheduler(schedulednotificationid,schedulednotificationname,active,description,notificationsubject,notificationbody) values (".$this->db->getUniqueID("notificationscheduler").",'Support Starting Notification' ,1,'Your support starts today','Support Start Notification','Support starts please')");
                 
                 $this->db->query("insert into notificationscheduler(schedulednotificationid,schedulednotificationname,active,description,notificationsubject,notificationbody) values (".$this->db->getUniqueID("notificationscheduler").",'Support Ending Notification' ,1,'Support Ending Notification','Support ending please','Support Ending Notification')");

		//Insert into currency table
		$this->db->query("insert into currency_info values('U.S Dollar','USD','$')");
                 
	       	}

	function drop_tables () {
	}
	
	function get_summary_text()
	{
		return "$this->name";
	}
}
?>

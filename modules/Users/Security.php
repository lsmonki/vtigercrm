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
require_once('include/utils.php');

// TabMenu shown in the header page.
class Security extends SugarBean {
	var $table_name = "role";
		
	function Security() {
		$this->log = LoggerManager::getLogger('security');
		$this->db = new PearDatabase();
	}

	var $new_schema = true;

	function create_tables () {
		global $app_strings;
                //role table must have role name as the primary key		
		/*
		$query = 'CREATE TABLE '.$this->table_name.' ( ';
		$query .='name varchar(60) NOT NULL';
		$query .=',description  varchar(100)';
		$query .=',PRIMARY KEY (name) )'; 

		$this->log->info($query);
		mysql_query($query) or die($app_strings['ERR_CREATING_TABLE'].mysql_error());	
		*/
                $this->db->query("insert into role values('','administrator','')");
		$this->db->query("insert into role values('','standard_user','')");
                
		// Create the indexes
		//$this->create_index("create index idx_role_name on role (name)");




                

                $table_name="user2role";

                //user2role must have userid as the primary key
		/*
                $query = 'CREATE TABLE '.$table_name.' ( ';
		$query .='userid varchar(100) NOT NULL  ';
		$query .=', rolename varchar (50) NOT NULL';
		$query .=', PRIMARY KEY (userid) )'; 

		$this->log->info($query);
		mysql_query($query) or die($app_strings['ERR_CREATING_TABLE'].mysql_error());	
                // Create the indexes
		$this->create_index("create index idx_user2role_name on user2role(userid)");
		*/

                //	$this->db->query("insert into user2role values(1,1)");
                //$this->log->info($query);
                //$this->db->query($query);
                
                //primary key will be rolename and tabid
                $table_name="role2tab";
                
    		/*		
                $query = 'CREATE TABLE '.$table_name.' ( ';
		$query .='rolename varchar(100) NOT NULL';
                $query .=', tabid int NOT NULL';
		$query .=', module_permission tinyint(4) NOT NULL';
		$query .=', description varchar(100) NOT NULL';
		$query .=', PRIMARY KEY (rolename,tabid) )'; 


		$this->log->info($query);
		mysql_query($query) or die($app_strings['ERR_CREATING_TABLE'].mysql_error());	
		// Create the indexes
		$this->create_index("create index idx_role2tab_name on role2tab (rolename,tabid)");
		*/


		//Inserting entries into tab and field table


 $this->db->query("INSERT INTO tab VALUES (3,'Home',0,1,'Home','','',1)");	 
 $this->db->query("INSERT INTO tab VALUES (7,'Leads',0,2,'Leads','','',1)");	 
 $this->db->query("INSERT INTO tab VALUES (6,'Accounts',0,3,'Accounts','','',1)");
 $this->db->query("INSERT INTO tab VALUES (4,'Contacts',0,4,'Contacts','','',1)");	 
 $this->db->query("INSERT INTO tab VALUES (2,'Potentials',0,5,'Potentials','','',1)");
 $this->db->query("INSERT INTO tab VALUES (8,'Notes',0,6,'Notes','','',1)");	 
 $this->db->query("INSERT INTO tab VALUES (9,'Activities',0,7,'Activities','','',1)");	 
 $this->db->query("INSERT INTO tab VALUES (10,'Emails',0,8,'Emails','','',1)");
 $this->db->query("INSERT INTO tab VALUES (13,'HelpDesk',0,9,'HelpDesk','','',1)");	 
 $this->db->query("INSERT INTO tab VALUES (14,'Products',0,10,'Products','','',1)");	 
 $this->db->query("INSERT INTO tab VALUES (1,'Dashboard',0,11,'Dashboards','','',1)");	 
 $this->db->query("INSERT INTO tab VALUES (15,'Faq',2,12,'Faq','','',1)");
 $this->db->query("INSERT INTO tab VALUES (16,'Events',2,13,'Events','','',1)");	 
 $this->db->query("INSERT INTO tab VALUES (17,'Calendar',0,14,'Calendar','','',1)");

//Account Details -- START
 //Block1
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'accountname','account',1,'2','accountname','Account Name',1,0,0,100,1,1,1)");	
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'phone','account',1,'11','phone','Phone',1,0,0,100,2,1,1)");	
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'website','account',1,'17','website','Website',1,0,0,100,3,1,1)");	

 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'fax','account',1,'1','fax','Fax',1,0,0,100,4,1,1)");	
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'tickersymbol','account',1,'1','tickersymbol','Ticker Symbol',1,0,0,100,5,1,1)");	
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'otherphone','account',1,'11','otherphone','Other Phone',1,0,0,100,6,1,1)");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'parentid','account',1,'51','account_id','Member Of',1,0,0,100,7,1,1)");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'email1','account',1,'13','email1','Email',1,0,0,100,8,1,1)");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'employees','account',1,'7','employees','Employees',1,0,0,100,9,1,1)");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'email2','account',1,'13','email2','Other Email',1,0,0,100,10,1,1)");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'ownership','account',1,'1','ownership','Ownership',1,0,0,100,11,1,1)");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'rating','account',1,'1','rating','Rating',1,0,0,100,12,1,1)");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'industry','account',1,'15','industry','industry',1,0,0,100,13,1,1)");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'siccode','account',1,'1','siccode','SIC Code',1,0,0,100,14,1,1)");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'account_type','account',1,'15','accounttype','Type',1,0,0,100,15,1,1)");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'annualrevenue','account',1,'1','annual_revenue','Annual Revenue',1,0,0,100,16,1,1)");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'smownerid','crmentity',1,'52','assigned_user_id','Assigned To',1,0,0,100,17,1,1)");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'1','createdtime','Created Time',1,0,0,100,18,1,2)");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'1','modifiedtime','Modified Time',1,0,0,100,18,1,2)");

 //Block 2
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'street','accountbillads',1,'21','bill_street','Billing Address',1,0,0,100,1,2,1)");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'street','accountshipads',1,'21','ship_street','Shipping Address',1,0,0,100,2,2,1)");

 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'city','accountbillads',1,'1','bill_city','City',1,0,0,100,3,2,1)");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'city','accountshipads',1,'1','ship_city','City',1,0,0,100,4,2,1)");

 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'state','accountbillads',1,'1','bill_state','State',1,0,0,100,5,2,1)");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'state','accountshipads',1,'1','ship_state','State',1,0,0,100,6,2,1)");

 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'code','accountbillads',1,'1','bill_code','Code',1,0,0,100,7,2,1)");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'code','accountshipads',1,'1','ship_code','Code',1,0,0,100,8,2,1)");


 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'country','accountbillads',1,'1','bill_country','Country',1,0,0,100,9,2,1)");
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'country','accountshipads',1,'1','ship_country','Country',1,0,0,100,10,2,1)");

 //Block3
 $this->db->query("insert into field values (6,".$this->db->getUniqueID("field").",'description','crmentity',1,'19','description','Description',1,0,0,100,1,3,1)");
//Account Details -- END

			
//Lead Details --- START

//Block1 -- Start
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'salutation','leaddetails',1,'55','salutation','Salutation',1,0,0,100,1,1,3)");	
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'firstname','leaddetails',1,'55','firstname','First Name',1,0,0,100,2,1,1)");	
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'phone','leadaddress',1,'11','phone','Phone',1,0,0,100,3,1,1)");	

 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'lastname','leaddetails',1,'2','lastname','Last Name',1,0,0,100,4,1,1)");	
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'mobile','leadaddress',1,'1','mobile','Mobile',1,0,0,100,5,1,1)");	
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'company','leaddetails',1,'2','company','Company',1,0,0,100,6,1,1)");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'fax','leadaddress',1,'1','fax','Fax',1,0,0,100,7,1,1)");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'designation','leaddetails',1,'1','designation','Designation',1,0,0,100,8,1,1)");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'email','leaddetails',1,'13','email','Email',1,0,0,100,9,1,1)");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'leadsource','leaddetails',1,'15','leadsource','Lead Source',1,0,0,100,10,1,1)");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'website','leadsubdetails',1,'17','website','Website',1,0,0,100,11,1,1)");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'industry','leaddetails',1,'15','industry','Industry',1,0,0,100,12,1,1)");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'leadstatus','leaddetails',1,'15','leadstatus','Lead Status',1,0,0,100,13,1,1)");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'annualrevenue','leaddetails',1,'1','annualrevenue','Annual Revenue',1,0,0,100,14,1,1)");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'rating','leaddetails',1,'15','rating','Rating',1,0,0,100,15,1,1)");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'noofemployees','leaddetails',1,'1','noofemployees','No Of Employees',1,0,0,100,16,1,1)");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'smownerid','crmentity',1,'53','assigned_user_id','Assigned To',1,0,0,100,17,1,1)");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'yahooid','leaddetails',1,'13','yahooid','Yahoo Id',1,0,0,100,18,1,1)");
$this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'1','createdtime','Created Time',1,0,0,100,19,1,2)");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'1','modifiedtime','Modified Time',1,0,0,100,20,1,2)");
//Block1 -- End

//Block2 -- Start
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'lane','leadaddress',1,'21','lane','Street',1,0,0,100,1,2,1)");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'code','leadaddress',1,'21','code','Postal Code',1,0,0,100,2,2,1)");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'city','leadaddress',1,'1','city','City',1,0,0,100,3,2,1)");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'country','leadaddress',1,'1','country','Country',1,0,0,100,4,2,1)");
 $this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'state','leadaddress',1,'1','state','State',1,0,0,100,5,2,1)");
//Block2 --End

//Block3 -- Start
$this->db->query("insert into field values (7,".$this->db->getUniqueID("field").",'description','crmentity',1,'19','description','Description',1,0,0,100,1,3,1)");
//Block3 -- End
//Lead Details -- END


//Contact Details -- START
//Block1 -- Start
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'salutation','contactdetails',1,'55','salutation','Salutation',1,0,0,100,1,1,3)");	
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'firstname','contactdetails',1,'55','firstname','First Name',1,0,0,100,2,1,1)");	
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'phone','contactdetails',1,'11','phone','Office Phone',1,0,0,100,3,1,1)");	
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'lastname','contactdetails',1,'2','lastname','Last Name',1,0,0,100,4,1,1)");	
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'mobile','contactdetails',1,'1','mobile','Mobile',1,0,0,100,5,1,1)");	
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'accountid','contactdetails',1,'50','account_id','Account Name',1,0,0,100,6,1,1)");	
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'homephone','contactsubdetails',1,'11','homephone','Home Phone',1,0,0,100,7,1,1)");	
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'leadsource','contactsubdetails',1,'15','leadsource','Lead Source',1,0,0,100,8,1,1)");	
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'otherphone','contactsubdetails',1,'11','otherphone','Phone',1,0,0,100,9,1,1)");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'title','contactdetails',1,'1','title','Title',1,0,0,100,10,1,1)");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'fax','contactdetails',1,'1','fax','Fax',1,0,0,100,11,1,1)");	
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'department','contactdetails',1,'1','department','Department',1,0,0,100,12,1,1)");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'contactdetails',1,'13','email','Email',1,0,0,100,13,1,1)");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'birthday','contactsubdetails',1,'5','birthday','Birthdate',1,0,0,100,14,1,1)");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'email','contactdetails',1,'13','email','Email',1,0,0,100,15,1,1)");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'reportsto','contactdetails',1,'57','reportsto','Reports To',1,0,0,100,16,1,1)");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'assistant','contactsubdetails',1,'1','assistant','Assistant',1,0,0,100,17,1,1)");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'yahooid','contactdetails',1,'13','yahooid','Yahoo Id',1,0,0,100,18,1,1)");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'assistantphone','contactsubdetails',1,'11','assistantphone','Assistant Phone',1,0,0,100,19,1,1)");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'donotcall','contactdetails',1,'56','donotcall','Do Not Call',1,0,0,100,20,1,1)");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'emailoptout','contactdetails',1,'56','emailoptout','Email Opt Out',1,0,0,100,21,1,1)");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'smownerid','crmentity',1,'52','assigned_user_id','Assigned To',1,0,0,100,22,1,1)");
$this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'1','createdtime','Created Time',1,0,0,100,23,1,2)");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'1','modifiedtime','Modified Time',1,0,0,100,24,1,2)");
//Block1 -- End

//Block 2 -- Start
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'mailingstreet','contactaddress',1,'21','mailingstreet','Mailing Street',1,0,0,100,1,2,1)");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'otherstreet','contactaddress',1,'21','otherstreet','Other Street',1,0,0,100,2,2,1)");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'mailingcity','contactaddress',1,'1','mailingcity','City',1,0,0,100,3,2,1)");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'othercity','contactaddress',1,'1','othercity','City',1,0,0,100,4,2,1)");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'mailingstate','contactaddress',1,'1','mailingstate','State',1,0,0,100,5,2,1)");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'otherstate','contactaddress',1,'1','otherstate','State',1,0,0,100,6,2,1)");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'mailingzip','contactaddress',1,'1','mailingzip','Zip',1,0,0,100,7,2,1)");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'otherzip','contactaddress',1,'1','otherzip','Zip',1,0,0,100,8,2,1)");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'mailingcountry','contactaddress',1,'1','mailingcountry','Country',1,0,0,100,9,2,1)");
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'othercountry','contactaddress',1,'1','othercountry','Country',1,0,0,100,10,2,1)");
//Block2 -- End

//Block3 -- Start
 $this->db->query("insert into field values (4,".$this->db->getUniqueID("field").",'description','crmentity',1,'19','description','Description',1,0,0,100,1,3,1)");
//Block3 -- End
//Contact Details -- END


//Potential Details -- START
//Block1 -- Start
 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'potentialname','potential',1,'2','potentialname','Potential Name',1,0,0,100,1,1,1)");	
 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'amount','potential',1,1,'amount','Amount',1,0,0,100,2,1,1)");	
 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'accountid','potential',1,'50','account_id','Account Name',1,0,0,100,3,1,1)");	
 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'currency','potential',1,'15','currency','Currency',1,0,0,100,4,1,1)");	
 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'closingdate','potential',1,'6','closingdate','Expected Close Date',1,0,0,100,5,1,1)");	
 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'potentialtype','potential',1,'15','opportunity_type','Type',1,0,0,100,6,1,1)");	
 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'nextstep','potential',1,'1','nextstep','Next Step',1,0,0,100,7,1,1)");
 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'leadsource','potential',1,'15','leadsource','Lead Source',1,0,0,100,8,1,1)");	
 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'sales_stage','potential',1,'16','sales_stage','Sales Stage',1,0,0,100,9,1,1)");
 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'smownerid','crmentity',1,'52','assigned_user_id','Assigned To',1,0,0,100,10,1,1)");
 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'probability','potential',1,'9','probability','Probability',1,0,0,100,11,1,1)");
$this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'1','createdtime','Created Time',1,0,0,100,13,1,2)");
 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'1','modifiedtime','Modified Time',1,0,0,100,14,1,2)");
//Block1 -- End

//Block2 -- Start
 $this->db->query("insert into field values (2,".$this->db->getUniqueID("field").",'description','crmentity',1,'19','description','Description',1,0,0,100,1,2,1)");
//Block2 -- End
//Potential Details -- END

//Ticket Details -- START
//Block1 -- Start
 $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'groupname','troubletickets',1,'54','groupname','Group',1,0,0,100,1,1,1)");
 $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'smownerid','crmentity',1,'52','assigned_user_id','Assigned To',1,0,0,100,2,1,1)");
 $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'crmid','seticketsrel',1,'62','parent_id','Parent',1,0,0,100,3,1,1)");
 $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'contact_id','troubletickets',1,'57','contact_id','Contact Name',1,0,0,100,4,1,1)");
 $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'priority','troubletickets',1,'15','troubleticketpriorities','Priority',1,0,0,100,5,1,1)");
 $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'status','troubletickets',1,'15','troubleticketstatus','Status',1,0,0,100,6,1,1)");
 $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'category','troubletickets',1,'15','troubleticketcategories','Category',1,0,0,100,7,1,1)");
 $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'update_log','troubletickets',1,'15','update_log','Update History',1,0,0,100,7,1,3)");
$this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'1','createdtime','Created Time',1,0,0,100,8,1,2)");
 $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'1','modifiedtime','Modified Time',1,0,0,100,9,1,2)");
 $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'title','troubletickets',1,'22','title','Title',1,0,0,100,1,2,1)");
 $this->db->query("insert into field values (13,".$this->db->getUniqueID("field").",'description','troubletickets',1,'19','description','Description',1,0,0,100,1,3,1)");
//Block1 -- End
//Ticket Details -- END

//Product Details -- START
//Block1 -- Start
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'productname','products',1,'2','productname','Product Name',1,0,0,100,1,1,1)");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'productcode','products',1,'1','productcode','Product Code',1,0,0,100,2,1,1)");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'discontinued','products',1,'56','discontinued','Product Active',1,0,0,100,3,1,1)");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'commissionrate','products',1,'1','commissionrate','Commission Rate',1,0,0,100,4,1,1)"); 
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'qty_per_unit','products',1,'1','qty_per_unit','Qty/Unit',1,0,0,100,5,1,1)");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'unit_price','products',1,'1','unit_price','Unit Price',1,0,0,100,6,1,1)"); 
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'manufacturer','products',1,'15','manufacturer','Manufacturer',1,0,0,100,7,1,1)");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'productcategory','products',1,'15','productcategory','Product Category',1,0,0,100,8,1,1)");	
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'start_date','products',1,'5','start_date','Support Start Date',1,0,0,100,8,1,1)");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'expiry_date','products',1,'5','expiry_date','Support Expiry Date',1,0,0,100,9,1,1)");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'purchase_date','products',1,'5','purchase_date','Purchase Date',1,0,0,100,10,1,1)");
$this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'1','createdtime','Created Time',1,0,0,100,11,1,2)");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'1','modifiedtime','Modified Time',1,0,0,100,12,1,2)");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'product_description','products',1,'19','product_description','Description',1,0,0,100,13,2,1)");
 $this->db->query("insert into field values (14,".$this->db->getUniqueID("field").",'crmid','seproductsrel',1,'62','parent_id','Parent',1,0,0,100,14,1,1)");
//Block1 -- End
//Product Details -- END

//Note Details -- START
//Block1 -- Start
 $this->db->query("insert into field values (8,".$this->db->getUniqueID("field").",'contact_id','notes',1,'57','contact_id','Contact Name',1,0,0,100,1,1,1)");
 $this->db->query("insert into field values (8,".$this->db->getUniqueID("field").",'crmid','senotesrel',1,'62','parent_id','Parent',1,0,0,100,2,1,1)");
 $this->db->query("insert into field values (8,".$this->db->getUniqueID("field").",'title','notes',1,'2','title','Subject',1,0,0,100,3,1,1)");
$this->db->query("insert into field values (8,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'1','createdtime','Created Time',1,0,0,100,4,1,2)");
 $this->db->query("insert into field values (8,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'1','modifiedtime','Modified Time',1,0,0,100,5,1,2)");
 $this->db->query("insert into field values (8,".$this->db->getUniqueID("field").",'filename','notes',1,'61','filename','Attachment',1,0,0,100,4,2,1)");
 $this->db->query("insert into field values (8,".$this->db->getUniqueID("field").",'notecontent','notes',1,'19','notecontent','Note',1,0,0,100,5,3,1)");
//Block1 -- End
//Note Details -- END

//Email Details -- START
//Block1 -- Start
	$this->db->query("insert into field values (10,".$this->db->getUniqueID("field").",'date_start','activity',1,'6','date_start','Date & Time Sent',1,0,0,100,1,1,1)");
	$this->db->query("insert into field values (10,".$this->db->getUniqueID("field").",'semodule','activity',1,'2','parent_type','Sales Enity Module',1,0,0,100,2,1,3)");
	$this->db->query("insert into field values (10,".$this->db->getUniqueID("field").",'activitytype','activity',1,'2','activitytype','Activtiy Type',1,0,0,100,3,1,3)");
	$this->db->query("insert into field values (10,".$this->db->getUniqueID("field").",'crmid','seactivityrel',1,'62','parent_id','Parent',1,0,0,100,4,1,1)");
	$this->db->query("insert into field values (10,".$this->db->getUniqueID("field").",'smownerid','crmentity',1,'52','assigned_user_id','Assigned To',1,0,0,100,5,1,1)");
	$this->db->query("insert into field values (10,".$this->db->getUniqueID("field").",'subject','activity',1,'2','name','Subject',1,0,0,100,6,2,1)");
	$this->db->query("insert into field values (10,".$this->db->getUniqueID("field").",'filename','emails',1,'61','filename','Attachment',1,0,0,100,7,3,1)");
	 $this->db->query("insert into field values (10,".$this->db->getUniqueID("field").",'description','emails',1,'19','description','Description',1,0,0,100,8,4,1)");
	 $this->db->query("insert into field values (10,".$this->db->getUniqueID("field").",'time_start','activity',1,'2','time_start','Time Start',1,0,0,100,9,1,3)");
$this->db->query("insert into field values (10,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'1','createdtime','Created Time',1,0,0,100,10,1,2)");
 $this->db->query("insert into field values (10,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'1','modifiedtime','Modified Time',1,0,0,100,11,1,2)");
//Block1 -- End
//Email Details -- END


//Task Details --START
//Block1 -- Start
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'subject','activity',1,'2','subject','Subject',1,0,0,100,1,1,1)");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'smownerid','crmentity',1,'52','assigned_user_id','Assigned To',1,0,0,100,2,1,1)");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'date_start','activity',1,'6','date_start','Start Date & Time',1,0,0,100,3,1,1)");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'time_start','activity',1,'2','time_start','Time Start',1,0,0,100,4,1,3)");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'crmid','seactivityrel',1,'62','parent_id','Parent',1,0,0,100,7,1,1)");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'contactid','cntactivityrel',1,'57','contact_id','Contact Name',1,0,0,100,8,1,1)");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'status','activity',1,'15','taskstatus','Status',1,0,0,100,9,1,1)");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'priority','activity',1,'15','taskpriority','Priority',1,0,0,100,10,1,1)");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'sendnotification','activity',1,'56','sendnotification','Send Notification',1,0,0,100,11,1,1)");
$this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'1','createdtime','Created Time',1,0,0,100,14,1,2)");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'1','modifiedtime','Modified Time',1,0,0,100,15,1,2)");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'activitytype','activity',1,'15','activitytype','Activity Type',1,0,0,100,16,1,3)");

 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'description','activity',1,'19','description','Description',1,0,0,100,1,2,1)");


$this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'duration_hours','activity',1,'63','duration_hours','Duration',1,0,0,100,17,1,3)");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'duration_minutes','activity',1,'15','duration_minutes','Duration Minutes',1,0,0,100,18,1,3)");
 $this->db->query("insert into field values (9,".$this->db->getUniqueID("field").",'location','activity',1,'1','location','Location',1,0,0,100,19,1,3)");

//Block1 -- End
//Task Details -- END


//Event Details --START
//Block1 -- Start
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'subject','activity',1,'2','subject','Subject',1,0,0,100,1,1,1)");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'smownerid','crmentity',1,'52','assigned_user_id','Assigned To',1,0,0,100,2,1,1)");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'date_start','activity',1,'6','date_start','Start Date & Time',1,0,0,100,3,1,1)");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'time_start','activity',1,'2','time_start','Time Start',1,0,0,100,4,1,3)");
$this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'duration_hours','activity',1,'63','duration_hours','Duration',1,0,0,100,5,1,1)");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'duration_minutes','activity',1,'15','duration_minutes','Duration Minutes',1,0,0,100,6,1,3)");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'crmid','seactivityrel',1,'62','parent_id','Parent',1,0,0,100,7,1,1)");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'contactid','cntactivityrel',1,'57','contact_id','Contact Name',1,0,0,100,8,1,1)");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'status','activity',1,'15','taskstatus','Status',1,0,0,100,9,1,1)");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'priority','activity',1,'15','taskpriority','Priority',1,0,0,100,10,1,1)");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'sendnotification','activity',1,'56','sendnotification','Send Notification',1,0,0,100,11,1,1)");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'activitytype','activity',1,'15','activitytype','Activity Type',1,0,0,100,12,1,1)");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'location','activity',1,'1','location','Location',1,0,0,100,13,1,1)");
$this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'1','createdtime','Created Time',1,0,0,100,14,1,2)");
 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'1','modifiedtime','Modified Time',1,0,0,100,15,1,2)");

 $this->db->query("insert into field values (16,".$this->db->getUniqueID("field").",'description','activity',1,'19','description','Description',1,0,0,100,1,2,1)");

//Block1 -- End
//Event Details -- END

//Faq Details -- START
//Block1 -- Start
 $this->db->query("insert into field values (15,".$this->db->getUniqueID("field").",'category','faq',1,'15','faqcategories','Category',1,0,0,100,1,1,1)");
 $this->db->query("insert into field values (15,".$this->db->getUniqueID("field").",'crmid','sefaqrel',1,'62','parent_id','Parent',1,0,0,100,2,1,1)");
 $this->db->query("insert into field values (15,".$this->db->getUniqueID("field").",'question','faq',1,'19','question','Question',1,0,0,100,1,2,1)");
 $this->db->query("insert into field values (15,".$this->db->getUniqueID("field").",'answer','faq',1,'19','answer','Answer',1,0,0,100,1,3,1)");
 $this->db->query("insert into field values (15,".$this->db->getUniqueID("field").",'comments','faq',1,'19','comments','Comments',1,0,0,100,1,4,1)");
$this->db->query("insert into field values (15,".$this->db->getUniqueID("field").",'createdtime','crmentity',1,'1','createdtime','Created Time',1,0,0,100,3,1,2)");
 $this->db->query("insert into field values (15,".$this->db->getUniqueID("field").",'modifiedtime','crmentity',1,'1','modifiedtime','Modified Time',1,0,0,100,4,1,2)");
//Block1 -- End
//Ticket Details -- END

                // Insert Enddddddddddddddddddddddddddddddd
                
                $this->db->query("insert into role2tab(rolename,tabid,module_permission,description) values ('administrator',1,1,'')");
                $this->db->query("insert into role2tab(rolename,tabid,module_permission,description) values ('administrator',2,1,'')");
                $this->db->query("insert into role2tab(rolename,tabid,module_permission,description) values ('administrator',3,1,'')");
                $this->db->query("insert into role2tab(rolename,tabid,module_permission,description) values ('administrator',4,1,'')");
                $this->db->query("insert into role2tab(rolename,tabid,module_permission,description) values ('administrator',5,1,'')");
                $this->db->query("insert into role2tab(rolename,tabid,module_permission,description) values ('administrator',6,1,'')");
                $this->db->query("insert into role2tab(rolename,tabid,module_permission,description) values ('administrator',7,1,'')");
		$this->db->query("insert into role2tab(rolename,tabid,module_permission,description) values ('administrator',8,1,'')");

                $this->db->query("insert into role2tab(rolename,tabid,module_permission,description) values ('administrator',9,1,'')");
                $this->db->query("insert into role2tab(rolename,tabid,module_permission,description) values ('administrator',10,1,'')");
                $this->db->query("insert into role2tab(rolename,tabid,module_permission,description) values ('administrator',11,1,'')");
		$this->db->query("insert into role2tab(rolename,tabid,module_permission,description) values ('administrator',12,1,'')");
                $this->db->query("insert into role2tab(rolename,tabid,module_permission,description) values ('administrator',13,1,'')");
		
                $this->db->query("insert into role2tab(rolename,tabid,module_permission,description) values ('administrator',14,1,'')");
                $this->db->query("insert into role2tab(rolename,tabid,module_permission,description) values ('administrator',15,1,'')");

                $this->db->query("insert into role2tab(rolename,tabid,module_permission,description) values ('administrator',16,1,'')");
                $this->db->query("insert into role2tab(rolename,tabid,module_permission,description) values ('administrator',17,1,'')");



                
                $this->db->query("insert into role2tab(rolename,tabid,module_permission,description) values ('standard_user',1,1,'')");
                $this->db->query("insert into role2tab(rolename,tabid,module_permission,description) values ('standard_user',2,1,'')");
                $this->db->query("insert into role2tab(rolename,tabid,module_permission,description) values ('standard_user',3,1,'')");
                $this->db->query("insert into role2tab(rolename,tabid,module_permission,description) values ('standard_user',4,1,'')");
                $this->db->query("insert into role2tab(rolename,tabid,module_permission,description) values ('standard_user',5,1,'')");
                $this->db->query("insert into role2tab(rolename,tabid,module_permission,description) values ('standard_user',6,1,'')");
                $this->db->query("insert into role2tab(rolename,tabid,module_permission,description) values ('standard_user',7,1,'')");
                $this->db->query("insert into role2tab(rolename,tabid,module_permission,description) values ('standard_user',8,1,'')");
                $this->db->query("insert into role2tab(rolename,tabid,module_permission,description) values ('standard_user',9,1,'')");
                $this->db->query("insert into role2tab(rolename,tabid,module_permission,description) values ('standard_user',10,1,'')");
                $this->db->query("insert into role2tab(rolename,tabid,module_permission,description) values ('standard_user',11,1,'')");
                $this->db->query("insert into role2tab(rolename,tabid,module_permission,description) values ('standard_user',12,1,'')");
                $this->db->query("insert into role2tab(rolename,tabid,module_permission,description) values ('standard_user',13,1,'')");
                $this->db->query("insert into role2tab(rolename,tabid,module_permission,description) values ('standard_user',14,1,'')");
                $this->db->query("insert into role2tab(rolename,tabid,module_permission,description) values ('standard_user',15,1,'')");
                $this->db->query("insert into role2tab(rolename,tabid,module_permission,description) values ('standard_user',16,1,'')");
                $this->db->query("insert into role2tab(rolename,tabid,module_permission,description) values ('standard_user',17,1,'')");








		/* needs to updated in schema file -- srini
		$query="  CREATE TABLE `LeadGroupRelation` (  `id` int(11) NOT NULL auto_increment,  `leadid` varchar(50) NOT NULL default '',  `groupname` varchar(50) NOT NULL default '',  PRIMARY KEY  (`id`)) TYPE=InnoDB ";
                $this->log->info($query);
		mysql_query($query) or die($app_strings['ERR_CREATING_TABLE'].mysql_error());	
		// Create the indexes
		$this->create_index("create index idx_LeadGroupRelation_name on LeadGroupRelation (id)");
 
                $query="  CREATE TABLE `TaskGroupRelation` (  `id` int(11) NOT NULL auto_increment,  `taskid` varchar(50) NOT NULL default '',  `groupname` varchar(50) NOT NULL default '',  PRIMARY KEY  (`id`)) TYPE=InnoDB ";
                $this->log->info($query);
		mysql_query($query) or die($app_strings['ERR_CREATING_TABLE'].mysql_error());	
		// Create the indexes
		$this->create_index("create index idx_TaskGroupRelation_name on TaskGroupRelation (id)");
                

                $query="  CREATE TABLE `CallGroupRelation` (  `id` int(11) NOT NULL auto_increment,  `callid` varchar(50) NOT NULL default '',  `groupname` varchar(50) NOT NULL default '',  PRIMARY KEY  (`id`)) TYPE=InnoDB ";
                $this->log->info($query);
		mysql_query($query) or die($app_strings['ERR_CREATING_TABLE'].mysql_error());	
		// Create the indexes
		$this->create_index("create index idx_CallGroupRelation_name on  CallGroupRelation (id)");
		*/
                
               


                $table_name="role2action";
                
                //primary key will be rolename,tabid and actionname
                /*$query = 'CREATE TABLE '.$table_name.' ( ';
		$query .='rolename varchar(50)';
                $query .=', tabid int(11) NOT NULL ';
                $query .=', actionname varchar(100) NOT NULL';
		$query .=', action_permission tinyint(4) NOT NULL';
                $query .=', description varchar(100) NOT NULL';
		$query .=', PRIMARY KEY (rolename,tabid,actionname) )'; 


		$this->log->info($query);
		mysql_query($query) or die($app_strings['ERR_CREATING_TABLE'].mysql_error());	
		// Create the indexes
		$this->create_index("create index idx_role2action_name on role2action (rolename,tabid,actionname)");
                */
		
                

                
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',1,'index',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',1,'EditView',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',1,'Delete',1,'')");
                 $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',1,'Save',1,'')");

                

                
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',2,'index',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',2,'EditView',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',2,'Delete',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',2,'Save',1,'')");
                
                

                
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',3,'index',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',3,'EditView',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',3,'Delete',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',3,'Save',1,'')");

                
                
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',4,'index',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',4,'EditView',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',4,'Delete',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',4,'Save',1,'')");

                
                
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',5,'index',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',5,'EditView',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',5,'Delete',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',5,'Save',1,'')");

                
                
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',6,'index',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',6,'EditView',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',6,'Delete',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',6,'Save',1,'')");

                
                
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',7,'index',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',7,'EditView',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',7,'Delete',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',7,'Save',1,'')");

                

                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',8,'index',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',8,'EditView',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',8,'Delete',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',8,'Save',1,'')");

                
                
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',9,'index',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',9,'EditView',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',9,'Delete',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',9,'Save',1,'')");

                
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',10,'index',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',10,'EditView',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',10,'Delete',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',10,'Save',1,'')");

                


                
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',11,'index',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',11,'EditView',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',11,'Delete',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',11,'Save',1,'')");

                
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',12,'index',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',12,'EditView',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',12,'Delete',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',12,'Save',1,'')");

                
                
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',13,'index',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',13,'EditView',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',13,'Delete',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',13,'Save',1,'')");

 
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',14,'index',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',14,'CreateTicket',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',14,'SaveTicket',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',14,'DeleteTicket',1,'')");



 
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',15,'index',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',15,'EditView',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',15,'Delete',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',15,'Save',1,'')");




               //entries for the import features 
               $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',3,'fetchfile',1,'')");
               $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',4,'BusinessCard',1,'')");
               $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',4,'Import',1,'')");
               $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',5,'Import',1,'')");
               $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('administrator',6,'Import',1,'')");
                
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',1,'index',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',1,'EditView',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',1,'Delete',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',1,'Save',1,'')");
                






                
                
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',2,'index',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',2,'EditView',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',2,'Delete',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',2,'Save',1,'')");

                

                
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',3,'index',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',3,'EditView',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',3,'Delete',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',3,'Save',1,'')");

                
                
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',4,'index',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',4,'EditView',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',4,'Delete',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',4,'Save',1,'')");

                
                
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',5,'index',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',5,'EditView',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',5,'Delete',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',5,'Save',1,'')");

                
                
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',6,'index',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',6,'EditView',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',6,'Delete',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',6,'Save',1,'')");

                
                
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',7,'index',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',7,'EditView',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',7,'Delete',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',7,'Save',1,'')");

                

                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',8,'index',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',8,'EditView',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',8,'Delete',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',8,'Save',1,'')");

                
                
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',9,'index',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',9,'EditView',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',9,'Delete',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',9,'Save',1,'')");

                
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',10,'index',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',10,'EditView',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',10,'Delete',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',10,'Save',1,'')");

                


                
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',11,'index',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',11,'EditView',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',11,'Delete',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',11,'Save',1,'')");

                
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',12,'index',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',12,'EditView',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',12,'Delete',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',12,'Save',1,'')");

                
                
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',13,'index',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',13,'EditView',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',13,'Delete',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',13,'Save',1,'')");

                  
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',14,'index',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',14,'CreateTicket',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',14,'SaveTicket',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',14,'DeleteTicket',1,'')");

                

                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',15,'index',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',15,'EditView',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',15,'Delete',1,'')");
                $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',15,'Save',1,'')");

                


  //entries for the import features
               $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',3,'fetchfile',0,'')");
               $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',4,'BusinessCard',1,'')");
               $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',4,'Import',0,'')");
               $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',5,'Import',0,'')");
               $this->db->query("insert into role2action(rolename,tabid,actionname,action_permission,description) values ('standard_user',6,'Import',0,'')");


		//New Security Start
		//Inserting into profile table
		$this->db->query("insert into profile values ('','CEO')");	
		$this->db->query("insert into profile values ('','Sales Man')");
		//$this->db->query("insert into profile values ('','Sales Man')");
		//$this->db->query("insert into profile values ('','Standard Guest')");
		
		//Inserting into profile2tab
		$this->db->query("insert into profile2tab values (1,1,0)");
		$this->db->query("insert into profile2tab values (1,2,0)");
		$this->db->query("insert into profile2tab values (1,3,0)");
		$this->db->query("insert into profile2tab values (1,4,0)");
		$this->db->query("insert into profile2tab values (1,6,0)");
		$this->db->query("insert into profile2tab values (1,7,0)");
		$this->db->query("insert into profile2tab values (1,8,0)");
		$this->db->query("insert into profile2tab values (1,9,0)");
		$this->db->query("insert into profile2tab values (1,10,0)");
		$this->db->query("insert into profile2tab values (1,13,0)");
		$this->db->query("insert into profile2tab values (1,14,0)");
		$this->db->query("insert into profile2tab values (1,15,0)");
		$this->db->query("insert into profile2tab values (1,16,0)");
		$this->db->query("insert into profile2tab values (1,17,0)");

		//Inserting into profile2tab
		$this->db->query("insert into profile2tab values (2,1,0)");
		$this->db->query("insert into profile2tab values (2,2,0)");
		$this->db->query("insert into profile2tab values (2,3,0)");
		$this->db->query("insert into profile2tab values (2,4,0)");
		$this->db->query("insert into profile2tab values (2,6,0)");
		$this->db->query("insert into profile2tab values (2,7,0)");
		$this->db->query("insert into profile2tab values (2,8,0)");
		$this->db->query("insert into profile2tab values (2,9,0)");
		$this->db->query("insert into profile2tab values (2,10,0)");
		$this->db->query("insert into profile2tab values (2,13,0)");
		$this->db->query("insert into profile2tab values (2,14,0)");
		$this->db->query("insert into profile2tab values (2,15,0)");
		$this->db->query("insert into profile2tab values (2,16,0)");	
		$this->db->query("insert into profile2tab values (2,17,0)");	

		//Inserting into profile2standardpermissions
		$this->db->query("insert into profile2standardpermissions values (1,1,0,0)");
		$this->db->query("insert into profile2standardpermissions values (1,1,1,0)");
		$this->db->query("insert into profile2standardpermissions values (1,1,2,0)");
		$this->db->query("insert into profile2standardpermissions values (1,1,3,0)");
		$this->db->query("insert into profile2standardpermissions values (1,1,4,0)");

		$this->db->query("insert into profile2standardpermissions values (1,2,0,0)");
		$this->db->query("insert into profile2standardpermissions values (1,2,1,0)");
		$this->db->query("insert into profile2standardpermissions values (1,2,2,0)");
		$this->db->query("insert into profile2standardpermissions values (1,2,3,0)");
		$this->db->query("insert into profile2standardpermissions values (1,2,4,0)");

		$this->db->query("insert into profile2standardpermissions values (1,3,0,0)");
		$this->db->query("insert into profile2standardpermissions values (1,3,1,0)");
		$this->db->query("insert into profile2standardpermissions values (1,3,2,0)");
		$this->db->query("insert into profile2standardpermissions values (1,3,3,0)");
		$this->db->query("insert into profile2standardpermissions values (1,3,4,0)");

		$this->db->query("insert into profile2standardpermissions values (1,4,0,0)");
		$this->db->query("insert into profile2standardpermissions values (1,4,1,0)");
		$this->db->query("insert into profile2standardpermissions values (1,4,2,0)");
		$this->db->query("insert into profile2standardpermissions values (1,4,3,0)");
		$this->db->query("insert into profile2standardpermissions values (1,4,4,0)");

		$this->db->query("insert into profile2standardpermissions values (1,6,0,0)");
		$this->db->query("insert into profile2standardpermissions values (1,6,1,0)");
		$this->db->query("insert into profile2standardpermissions values (1,6,2,0)");
		$this->db->query("insert into profile2standardpermissions values (1,6,3,0)");
		$this->db->query("insert into profile2standardpermissions values (1,6,4,0)");

		$this->db->query("insert into profile2standardpermissions values (1,7,0,0)");
		$this->db->query("insert into profile2standardpermissions values (1,7,1,0)");
		$this->db->query("insert into profile2standardpermissions values (1,7,2,0)");
		$this->db->query("insert into profile2standardpermissions values (1,7,3,0)");
		$this->db->query("insert into profile2standardpermissions values (1,7,4,0)");

		$this->db->query("insert into profile2standardpermissions values (1,8,0,0)");
		$this->db->query("insert into profile2standardpermissions values (1,8,1,0)");
		$this->db->query("insert into profile2standardpermissions values (1,8,2,0)");
		$this->db->query("insert into profile2standardpermissions values (1,8,3,0)");
		$this->db->query("insert into profile2standardpermissions values (1,8,4,0)");

		$this->db->query("insert into profile2standardpermissions values (1,9,0,0)");
		$this->db->query("insert into profile2standardpermissions values (1,9,1,0)");
		$this->db->query("insert into profile2standardpermissions values (1,9,2,0)");
		$this->db->query("insert into profile2standardpermissions values (1,9,3,0)");
		$this->db->query("insert into profile2standardpermissions values (1,9,4,0)");

		$this->db->query("insert into profile2standardpermissions values (1,10,0,0)");
		$this->db->query("insert into profile2standardpermissions values (1,10,1,0)");
		$this->db->query("insert into profile2standardpermissions values (1,10,2,0)");
		$this->db->query("insert into profile2standardpermissions values (1,10,3,0)");
		$this->db->query("insert into profile2standardpermissions values (1,10,4,0)");

		
		$this->db->query("insert into profile2standardpermissions values (1,13,0,0)");
		$this->db->query("insert into profile2standardpermissions values (1,13,1,0)");
		$this->db->query("insert into profile2standardpermissions values (1,13,2,0)");
		$this->db->query("insert into profile2standardpermissions values (1,13,3,0)");
		$this->db->query("insert into profile2standardpermissions values (1,13,4,0)");

		$this->db->query("insert into profile2standardpermissions values (1,14,0,0)");
		$this->db->query("insert into profile2standardpermissions values (1,14,1,0)");
		$this->db->query("insert into profile2standardpermissions values (1,14,2,0)");
		$this->db->query("insert into profile2standardpermissions values (1,14,3,0)");
		$this->db->query("insert into profile2standardpermissions values (1,14,4,0)");

		$this->db->query("insert into profile2standardpermissions values (1,15,0,0)");
		$this->db->query("insert into profile2standardpermissions values (1,15,1,0)");
		$this->db->query("insert into profile2standardpermissions values (1,15,2,0)");
		$this->db->query("insert into profile2standardpermissions values (1,15,3,0)");
		$this->db->query("insert into profile2standardpermissions values (1,15,4,0)");

		$this->db->query("insert into profile2standardpermissions values (1,16,0,0)");
		$this->db->query("insert into profile2standardpermissions values (1,16,1,0)");
		$this->db->query("insert into profile2standardpermissions values (1,16,2,0)");
		$this->db->query("insert into profile2standardpermissions values (1,16,3,0)");
		$this->db->query("insert into profile2standardpermissions values (1,16,4,0)");







		$this->db->query("insert into profile2standardpermissions values (2,1,0,0)");
		$this->db->query("insert into profile2standardpermissions values (2,1,1,0)");
		$this->db->query("insert into profile2standardpermissions values (2,1,2,0)");
		$this->db->query("insert into profile2standardpermissions values (2,1,3,0)");
		$this->db->query("insert into profile2standardpermissions values (2,1,4,0)");

		$this->db->query("insert into profile2standardpermissions values (2,2,0,0)");
		$this->db->query("insert into profile2standardpermissions values (2,2,1,0)");
		$this->db->query("insert into profile2standardpermissions values (2,2,2,0)");
		$this->db->query("insert into profile2standardpermissions values (2,2,3,0)");
		$this->db->query("insert into profile2standardpermissions values (2,2,4,0)");

		$this->db->query("insert into profile2standardpermissions values (2,3,0,0)");
		$this->db->query("insert into profile2standardpermissions values (2,3,1,0)");
		$this->db->query("insert into profile2standardpermissions values (2,3,2,0)");
		$this->db->query("insert into profile2standardpermissions values (2,3,3,0)");
		$this->db->query("insert into profile2standardpermissions values (2,3,4,0)");

		$this->db->query("insert into profile2standardpermissions values (2,4,0,0)");
		$this->db->query("insert into profile2standardpermissions values (2,4,1,0)");
		$this->db->query("insert into profile2standardpermissions values (2,4,2,0)");
		$this->db->query("insert into profile2standardpermissions values (2,4,3,0)");
		$this->db->query("insert into profile2standardpermissions values (2,4,4,0)");

		$this->db->query("insert into profile2standardpermissions values (2,6,0,0)");
		$this->db->query("insert into profile2standardpermissions values (2,6,1,0)");
		$this->db->query("insert into profile2standardpermissions values (2,6,2,0)");
		$this->db->query("insert into profile2standardpermissions values (2,6,3,0)");
		$this->db->query("insert into profile2standardpermissions values (2,6,4,0)");

		$this->db->query("insert into profile2standardpermissions values (2,7,0,0)");
		$this->db->query("insert into profile2standardpermissions values (2,7,1,0)");
		$this->db->query("insert into profile2standardpermissions values (2,7,2,0)");
		$this->db->query("insert into profile2standardpermissions values (2,7,3,0)");
		$this->db->query("insert into profile2standardpermissions values (2,7,4,0)");

		$this->db->query("insert into profile2standardpermissions values (2,8,0,0)");
		$this->db->query("insert into profile2standardpermissions values (2,8,1,0)");
		$this->db->query("insert into profile2standardpermissions values (2,8,2,0)");
		$this->db->query("insert into profile2standardpermissions values (2,8,3,0)");
		$this->db->query("insert into profile2standardpermissions values (2,8,4,0)");

		$this->db->query("insert into profile2standardpermissions values (2,9,0,0)");
		$this->db->query("insert into profile2standardpermissions values (2,9,1,0)");
		$this->db->query("insert into profile2standardpermissions values (2,9,2,0)");
		$this->db->query("insert into profile2standardpermissions values (2,9,3,0)");
		$this->db->query("insert into profile2standardpermissions values (2,9,4,0)");

		$this->db->query("insert into profile2standardpermissions values (2,10,0,0)");
		$this->db->query("insert into profile2standardpermissions values (2,10,1,0)");
		$this->db->query("insert into profile2standardpermissions values (2,10,2,0)");
		$this->db->query("insert into profile2standardpermissions values (2,10,3,0)");
		$this->db->query("insert into profile2standardpermissions values (2,10,4,0)");

		
		$this->db->query("insert into profile2standardpermissions values (2,13,0,0)");
		$this->db->query("insert into profile2standardpermissions values (2,13,1,0)");
		$this->db->query("insert into profile2standardpermissions values (2,13,2,0)");
		$this->db->query("insert into profile2standardpermissions values (2,13,3,0)");
		$this->db->query("insert into profile2standardpermissions values (2,13,4,0)");

		$this->db->query("insert into profile2standardpermissions values (2,14,0,0)");
		$this->db->query("insert into profile2standardpermissions values (2,14,1,0)");
		$this->db->query("insert into profile2standardpermissions values (2,14,2,0)");
		$this->db->query("insert into profile2standardpermissions values (2,14,3,0)");
		$this->db->query("insert into profile2standardpermissions values (2,14,4,0)");

		$this->db->query("insert into profile2standardpermissions values (2,15,0,0)");
		$this->db->query("insert into profile2standardpermissions values (2,15,1,0)");
		$this->db->query("insert into profile2standardpermissions values (2,15,2,0)");
		$this->db->query("insert into profile2standardpermissions values (2,15,3,0)");
		$this->db->query("insert into profile2standardpermissions values (2,15,4,0)");

		$this->db->query("insert into profile2standardpermissions values (2,16,0,0)");
		$this->db->query("insert into profile2standardpermissions values (2,16,1,0)");
		$this->db->query("insert into profile2standardpermissions values (2,16,2,0)");
		$this->db->query("insert into profile2standardpermissions values (2,16,3,0)");
		$this->db->query("insert into profile2standardpermissions values (2,16,4,0)");


		
		/*
		$this->db->query("insert into profile2standardpermissions values (1,17,0,0)");
		$this->db->query("insert into profile2standardpermissions values (1,17,1,0)");
		$this->db->query("insert into profile2standardpermissions values (1,17,2,0)");
		$this->db->query("insert into profile2standardpermissions values (1,17,3,0)");
		*/	

		//Inserting into user2profile
		//$this->db->query("insert into user2profile values (1,1)");

		//Insert into role2profile
		$this->db->query("insert into role2profile values (1,1)");
		$this->db->query("insert into role2profile values (2,2)");
	
		//Insert into profile2field
			
                insertProfile2field(1);
                insertProfile2field(2);	
		

		//Inserting into profile 2 utility
                $this->db->query("insert into profile2utility values (1,2,5,0)");
                $this->db->query("insert into profile2utility values (1,2,6,0)");
                $this->db->query("insert into profile2utility values (1,4,5,0)");
                $this->db->query("insert into profile2utility values (1,4,6,0)");
                $this->db->query("insert into profile2utility values (1,6,5,0)");
                $this->db->query("insert into profile2utility values (1,6,6,0)");
                $this->db->query("insert into profile2utility values (1,7,5,0)");
                $this->db->query("insert into profile2utility values (1,7,6,0)");
                $this->db->query("insert into profile2utility values (1,8,6,0)");
                $this->db->query("insert into profile2utility values (1,9,6,0)");
                $this->db->query("insert into profile2utility values (1,10,6,0)");

		$this->db->query("insert into profile2utility values (2,2,5,0)");
                $this->db->query("insert into profile2utility values (2,2,6,0)");
                $this->db->query("insert into profile2utility values (2,4,5,0)");
                $this->db->query("insert into profile2utility values (2,4,6,0)");
                $this->db->query("insert into profile2utility values (2,6,5,0)");
                $this->db->query("insert into profile2utility values (2,6,6,0)");
                $this->db->query("insert into profile2utility values (2,7,5,0)");
                $this->db->query("insert into profile2utility values (2,7,6,0)");
                $this->db->query("insert into profile2utility values (2,8,6,0)");
                $this->db->query("insert into profile2utility values (2,9,6,0)");
                $this->db->query("insert into profile2utility values (2,10,6,0)");	

			
		//Insert into default_org_sharingrule
		$this->db->query("insert into default_org_sharingrule values ('',2,2)");
		
		$this->db->query("insert into default_org_sharingrule values ('',4,2)");

		$this->db->query("insert into default_org_sharingrule values ('',6,2)");

		$this->db->query("insert into default_org_sharingrule values ('',7,2)");

		$this->db->query("insert into default_org_sharingrule values ('',8,2)");
                $this->db->query("insert into default_org_sharingrule values ('',9,2)");
                $this->db->query("insert into default_org_sharingrule values ('',10,2)");
                $this->db->query("insert into default_org_sharingrule values ('',13,2)");
                $this->db->query("insert into default_org_sharingrule values ('',14,2)");
                $this->db->query("insert into default_org_sharingrule values ('',15,2)");
                $this->db->query("insert into default_org_sharingrule values ('',16,2)");			
  // New Secutity End



                //GROUPS TABLE
                $table_name="groups";
                            
                /*$query = 'CREATE TABLE '.$table_name.' ( ';
		$query .='name varchar(100) NOT NULL';
                $query .=', primary key(name)';
		$query .=', description TEXT';
		$query .=' )'; 


		$this->log->info($query);
		mysql_query($query) or die($app_strings['ERR_CREATING_TABLE'].mysql_error());	
		// Create the indexes
		$this->create_index("create index idx_groups_name on groups (name)");

		*/

		//users2group table 
                $table_name="users2group";
                  
      		/*		
                $query = 'CREATE TABLE '.$table_name.' ( ';
		$query .='groupname varchar(100) NOT NULL';
                $query .=', userid varchar(50)';
		$query .=', primary key(groupname,userid)';
		$query .=')'; 


		$this->log->info($query);
		mysql_query($query) or die($app_strings['ERR_CREATING_TABLE'].mysql_error());	
		// Create the indexes
		$this->create_index("create index idx_users2group on users2group (groupname,userid)");

		*/

 $table_name="productcollaterals";
 		/*
                $query = "create table " .$table_name ." ( productid int(11) NOT NULL ";
                $query .=",date_entered datetime NOT NULL default '0000-00-00 00:00:00'";
                $query .= ",parent_type varchar(50) NOT NULL default '',parent_id varchar(100) NOT NULL default '',data longblob NOT NULL,description tinytext,filename varchar(50) NOT NULL default '',filesize varchar(50) NOT NULL default '',filetype varchar(20) NOT NULL default '',PRIMARY KEY  (productid,filename))";
                $this->log->info($query);
                mysql_query($query) or die($app_strings['ERR_CREATING_TABLE'].mysql_error());
                $this->create_index("create index idx_collaterals_name on productcollaterals (productid,filename)");
*/

	 $table_name="email_attachments";
	 /*
                $query = "create table " .$table_name ." (date_entered datetime NOT NULL,parent_type varchar(100), parent_id varchar(100) NOT NULL,data longblob NOT NULL,filename varchar(50) NOT NULL,filesize varchar(50) NOT NULL,filetype varchar(20) NOT NULL,PRIMARY KEY (parent_id,filename ) )";
                $this->log->info($query);
                mysql_query($query) or die($app_strings['ERR_CREATING_TABLE'].mysql_error());
                $this->create_index("create index idx_email_attachments_name on email_attachments (parent_id,filename)");
*/

/*		$query = "create table wordtemplatestorage(filename varchar(100) NOT NULL,`module` varchar(30) NOT NULL,`date_entered` datetime NOT NULL default '0000-00-00 00:00:00',`parent_type` varchar(50) NOT NULL default '',`parent_id` varchar(100) NOT NULL default '',`data` longblob NOT NULL,`description` TEXT,`filesize` varchar(50) NOT NULL default '',`filetype` varchar(20) NOT NULL default '',PRIMARY KEY(filename))";
	 	$this->log->info($query);
                mysql_query($query) or die($app_strings['ERR_CREATING_TABLE'].mysql_error());
                $this->create_index("create index idx_wordtemplatestorage on wordtemplatestorage (filename)");

		$query="create table emailtemplatestorage (foldername varchar(100) NOT NULL default '',templatename varchar (100) NOT NULL,subject varchar(100) NOT NULL, description TEXT, body TEXT , PRIMARY KEY (foldername,templatename,subject))";

	 	$this->log->info($query);
                mysql_query($query) or die($app_strings['ERR_CREATING_TABLE'].mysql_error());
                $this->create_index("create index idx_emailtemplatestorage on emailtemplatestorage (foldername,templatename,subject)");














		*/


         //insert into the notificationscheduler table






         $this->db->query("insert into notificationscheduler(schedulednotificationid,schedulednotificationname,active,description,notificationsubject,notificationbody) values (".$this->db->getUniqueID("notificationscheduler").",User Creation Notification,1,'Notification when user gets created','New User Creation Notification','A new user account has been created')");
         
         $this->db->query("insert into notificationscheduler(schedulednotificationid,schedulednotificationname,active,description,notificationsubject,notificationbody) values (".$this->db->getUniqueID("notificationscheduler").",Lead Conversion Notification ,1,'Notification when a lead gets converted','Lead Conversion Notification','A Lead has been converted')");
         
         
                 $this->db->query("insert into notificationscheduler(schedulednotificationid,schedulednotificationname,active,description,notificationsubject,notificationbody) values (".$this->db->getUniqueID("notificationscheduler").",Notification when a task is assigned,1,'Task Assigned Notification','Task Assigned Notification','A task has been assigned to you')");


                 $this->db->query("insert into notificationscheduler(schedulednotificationid,schedulednotificationname,active,description,notificationsubject,notificationbody) values (".$this->db->getUniqueID("notificationscheduler").",Notification for joining a meeting,1,'Meeting notification','Meeting notification','You have been invited for a meeting')");



                 $this->db->query("insert into notificationscheduler(schedulednotificationid,schedulednotificationname,active,description,notificationsubject,notificationbody) values (".$this->db->getUniqueID("notificationscheduler").",Notifcation when a record/entity is delete,1,'Record Deletion Notification','Record Deletion Notification','A record has been deleted which was owned by you')");



                 
                 $this->db->query("insert into notificationscheduler(schedulednotificationid,schedulednotificationname,active,description,notificationsubject,notificationbody) values (".$this->db->getUniqueID("notificationscheduler").",Big Deal Notification ,1,'A big deal has been achieved','Big Deal notification','Success! A big deal has been won!')");
                 
                 
                 $this->db->query("insert into notificationscheduler(schedulednotificationid,schedulednotificationname,active,description,notificationsubject,notificationbody) values (".$this->db->getUniqueID("notificationscheduler").",Ticket Creation Notification ,1,'A ticket has been created','Ticket Creation Notification','A ticket has been created for recording the incident')");
                 
                 $this->db->query("insert into notificationscheduler(schedulednotificationid,schedulednotificationname,active,description,notificationsubject,notificationbody) values (".$this->db->getUniqueID("notificationscheduler").",Ticket Status Modification Notification ,1,'Ticket Status Modification Notification','A ticket status has been changed','This is to inform you that the status of the ticket has been changed')");
                 
                 $this->db->query("insert into notificationscheduler(schedulednotificationid,schedulednotificationname,active,description,notificationsubject,notificationbody) values (".$this->db->getUniqueID("notificationscheduler").",Ticket Closure Notification,1,'Ticket Closure Notification','Ticket Closure Notification','Ticket has been closed')");


	       	}

	function drop_tables () {

		/*
		$query = 'DROP TABLE IF EXISTS '.$this->table_name;

		$this->log->info($query);
                mysql_query($query);

                $table_name="user2role";
                $query = 'DROP TABLE IF EXISTS '.$table_name;
                                
		$this->log->info($query);
                mysql_query($query);

      $table_name="LeadGroupRelation";
                $query = 'DROP TABLE IF EXISTS '.$table_name;
                                
		$this->log->info($query);
                mysql_query($query);

                
                $table_name="TaskGroupRelation";
                $query = 'DROP TABLE IF EXISTS '.$table_name;
                                
		$this->log->info($query);
                mysql_query($query);


                
                $table_name="CallGroupRelation";
                $query = 'DROP TABLE IF EXISTS '.$table_name;
                                
		$this->log->info($query);
                mysql_query($query);


          
                
                $table_name="role2tab";
                $query = 'DROP TABLE IF EXISTS '.$table_name;
                                
		$this->log->info($query);
                mysql_query($query);

                
                $table_name="role2action";
                $query = 'DROP TABLE IF EXISTS '.$table_name;
                                
		$this->log->info($query);
                mysql_query($query);

		$table_name="groups";
                $query = 'DROP TABLE IF EXISTS '.$table_name;
                                
		$this->log->info($query);
                mysql_query($query);
	
		$table_name="users2group";
                $query = 'DROP TABLE IF EXISTS '.$table_name;
                                
		$this->log->info($query);
                mysql_query($query);
		
		$table_name="productcollaterals";
                $query = 'DROP TABLE IF EXISTS '.$table_name;
                                
		$this->log->info($query);
                mysql_query($query);
	
		$table_name="email_attachments";
                $query = 'DROP TABLE IF EXISTS '.$table_name;
                                
		$this->log->info($query);
                mysql_query($query);

		$table_name="wordtemplatestorage";
               $query = 'DROP TABLE IF EXISTS '.$table_name;
                                
		$this->log->info($query);
                mysql_query($query);

		$table_name="emailtemplatestorage";
               $query = 'DROP TABLE IF EXISTS '.$table_name;
                                
		$this->log->info($query);
                mysql_query($query);
*/

	
	}
	
	function get_summary_text()
	{
		return "$this->name";
	}
}
?>

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

class HelpDesk extends SugarBean {
	var $table_name = "troubletickets"; 
		
	function HelpDesk() {
		$this->log = LoggerManager::getLogger('helpdesk');
		$this->db = new PearDatabase();
	}

	var $new_schema = true;

	function create_tables () {
		global $app_strings;
	
	/*	
        $query = 'CREATE TABLE '.$this->table_name.' ( ';
		$query .='id int(11) NOT NULL auto_increment,groupname varchar (100),contact_id varchar(100),priority varchar(150),status varchar(150),parent_id varchar(100),parent_type varchar(25),category varchar(150),title varchar(255) NOT NULL ,description text,update_log text,version_id int(11),date_created datetime,date_modified datetime,assigned_user_id varchar(100) NOT NULL,deleted tinyint(1) NOT NULL default 0,PRIMARY KEY  (id))';
		$this->log->info($query);
		mysql_query($query) or die($app_strings['ERR_CREATING_TABLE'].mysql_error());	
		
     	$this->create_index("create index idx_troubletickets on troubletickets (id)");
		$table_name="products";

        $query = 'CREATE TABLE '.$table_name.' ( ';
		$query .='id int(11) NOT NULL auto_increment ';
		$query .=', productname varchar (50) NOT NULL';
		$query .=', category varchar (40),product_description text,qty_per_unit int (11) NOT NULL default 0, unit_price double ,weight double,pack_size int , cost_factor int ,commissionrate double , commissionmethod varchar(50), discontinued tinyint(1), deleted tinyint(1) NOT NULL default 0, PRIMARY KEY (id))'; 
		$this->log->info($query);
		mysql_query($query) or die($app_strings['ERR_CREATING_TABLE'].mysql_error());	
		$this->create_index("create index idx_products on products(id)");
               

	 
                //primary key will be rolename and tabid
                $table_name="faq";
                            
                $query = 'CREATE TABLE '.$table_name.' ( ';
		$query .='   id int(11) NOT NULL auto_increment,
                                question text NOT NULL default "",
                                answer text,
                                category varchar(100) NOT NULL,
                                author_id varchar(100) NOT NULL,
                                date_modified datetime NOT NULL,
                                comments text,
				deleted tinyint(1) NOT NULL default 0,
                                PRIMARY KEY  (id))';

		$this->log->info($query);
		mysql_query($query) or die($app_strings['ERR_CREATING_TABLE'].mysql_error());	
		// Create the indexes
		$this->create_index("create index idx_faq on faq (id)");

                $table_name="faqcategories";
                
                //primary key will be rolename,tabid and actionname
                $query = 'CREATE TABLE '.$table_name.' ( ';
		$query .='      category_name varchar(60) NOT NULL,
                                PRIMARY KEY  (category_name))';
		$this->log->info($query);
		mysql_query($query) or die($app_strings['ERR_CREATING_TABLE'].mysql_error());	*/
              
		$this->db->query("insert into faqcategories values('General')"); 


/*
  mysql_query(" CREATE TABLE troubleticketcategories (
                          category varchar(100) NOT NULL default '',
                          PRIMARY KEY  (category)
                        )")or die(mysql_errno() . " " . mysql_error());

		$this->log->info($query); */

	$this->db->query("insert into ticketcategories values('Big Problem')");
	$this->db->query("insert into ticketcategories values('Small Problem')");
	$this->db->query("insert into ticketcategories values('Other Problem')");
	
               

	/*mysql_query(" CREATE TABLE troubleticketpriorities (
                          priority varchar(100) NOT NULL default '',
                          PRIMARY KEY  (priority)
                          )")or die(mysql_errno() . " " . mysql_error());

		$this->log->info($query);*/
		
	$this->db->query("insert into ticketpriorities values('Low')");
	$this->db->query("insert into ticketpriorities values('Medium')");
	$this->db->query("insert into ticketpriorities values('High')");
	$this->db->query("insert into ticketpriorities values('Critical')");

        /*mysql_query(" CREATE TABLE troubleticketstatus (
                          status varchar(60) NOT NULL default '',
                          PRIMARY KEY  (status)
                        )")or die(mysql_errno() . " " . mysql_error());
		$this->log->info($query);*/

		
	$this->db->query("insert into ticketstatus values('Open')");
	$this->db->query("insert into ticketstatus values('In Progress')");
	$this->db->query("insert into ticketstatus values('Wait For Response')");
	$this->db->query("insert into ticketstatus values('Closed')");
	

    /*mysql_query(" CREATE TABLE troubleticketstracktime (
                        ticket_id int(11) not null,
                        supporter_id int(11) not null,
                        minutes int(11) default 0,
                        date_logged int(11) NOT NULL default 0
                )")or die(mysql_errno() . " " . mysql_error());
                $this->log->info($query);
                $this->create_index("create index idx_troubleticketstracktime on troubleticketstracktime (ticket_id)");*/

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

		*/

	}
	
	function get_summary_text()
	{
		return "$this->name";
	}
}
?>

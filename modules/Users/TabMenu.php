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

class TabMenu
{
  
  function getTabNames($permittedModuleList="")
  {
    
    $conn= new PearDatabase();
    if($permittedModuleList=="")
    {
      $sql="SELECT name from tabmenu where presence = 1 order by sequence";
    }
    else
    {
      $sql="SELECT name from tabmenu where id in (" .$permittedModuleList .") and presence = 1 order by sequence";
      //echo $sql;
    }
   
    $tabrow=$conn->query($sql);
    if(mysql_num_rows($tabrow) != 0)
    {
      while ($result = mysql_fetch_array($tabrow))
      {
        $tabmenu[]=$result['name'];
      }
    }
    return $tabmenu;
  }



}
// TabMenu shown in the header page.
class Tab extends SugarBean {
	var $log;
	var $db;

	// Stored fields
	var $id;
	var $name;
	var $presence;
	var $sequence;
	var $label;
	var $modifiedby;
	var $modifiedtime;
	var $customized;
	
	//var $default_task_name_values = array('Assemble catalogs', 'Make travel arrangements', 'Send a letter', 'Send contract', 'Send fax', 'Send a follow-up letter', 'Send literature', 'Send proposal', 'Send quote');

	var $table_name = "tabmenu";

	var $object_name = "Tab";

	var $column_fields = Array("id"
		, "name"
		, "presence"
		, "sequence"
		, "label"
		, "modifiedby"
		, "modifiedtime"
		, "customized"
		);

	// This is used to retrieve related fields from form posts.
	//var $additional_column_fields = Array('assigned_user_name', 'assigned_user_id', 'contact_name', 'contact_phone', 'contact_email', 'parent_name');		

	// This is the list of fields that are in the lists.
	//var $list_fields = Array('id', 'status', 'name', 'parent_type', 'parent_name', 'parent_id', 'date_due', 'contact_id', 'contact_name', 'assigned_user_name', 'assigned_user_id');
		
	function Tab() {
		$this->log = LoggerManager::getLogger('tab');
		$this->db = new PearDatabase();
	}

	var $new_schema = true;

	function create_tables () {
		global $app_strings;
		
		$query = 'CREATE TABLE '.$this->table_name.' ( ';
		$query .='id int(10) NOT NULL default \'0\' auto_increment';
		$query .=', name varchar(30) NOT NULL';
		$query .=', presence tinyint(1) NOT NULL default \'0\'';
		$query .=', sequence int(10) default NULL';
		$query .=', label varchar (30) NOT NULL default \'\'';
		$query .=', modifiedby varchar (50) default NULL';
		$query .=', modifiedtime bigint (20) default NULL'; 
		$query .=', customized tinyint(1) default NULL'; 
		$query .=', PRIMARY KEY (id)'; 
		$query .=', UNIQUE KEY name (name) )';  

		$this->log->info($query);
		
			
		mysql_query($query) or die($app_strings['ERR_CREATING_TABLE'].mysql_error());	
		
		//Populating TabMenu table
	
		mysql_query("insert into tabmenu values('','Home',1,1,'Home','','',0)");
		mysql_query("insert into tabmenu values('','Dashboard',1,2,'Dashboard','','',0)");
		mysql_query("insert into tabmenu values('','Leads',1,3,'Leads','','',0)");
		mysql_query("insert into tabmenu values('','Contacts',1,4,'Contacts','','',0)");
		mysql_query("insert into tabmenu values('','Accounts',1,5,'Accounts','','',0)");
		mysql_query("insert into tabmenu values('','Opportunities',1,6,'Opportunities','','',0)");
		mysql_query("insert into tabmenu values('','Cases',1,7,'Cases','','',0)");
		mysql_query("insert into tabmenu values('','Notes',1,8,'Notes','','',0)");
		mysql_query("insert into tabmenu values('','Calls',1,9,'Calls','','',0)");
		mysql_query("insert into tabmenu values('','Emails',1,10,'Emails','','',0)");
		mysql_query("insert into tabmenu values('','Meetings',1,11,'Meetings','','',0)");
		mysql_query("insert into tabmenu values('','Tasks',1,12,'Tasks','','',0)");
		mysql_query("insert into tabmenu values('','MessageBoard',1,13,'MessageBoard','','',0)");
		mysql_query("insert into tabmenu values('','HelpDesk',1,14,'HelpDesk','','',0)");
		mysql_query("insert into tabmenu values('','Products',1,15,'Products','','',0)");
		mysql_query("insert into tabmenu values('','Calendar',1,16,'Calendar','','',0)");
		mysql_query("delete from tabmenu where name = 'Cases'");
		//


		// Create the indexes
		$this->create_index("create index idx_tab_name on tabmenu (name)");
	}

	function drop_tables () {
		$query = 'DROP TABLE IF EXISTS '.$this->table_name;

		$this->log->info($query);
			
		mysql_query($query);

		//TODO Clint 4/27 - add exception handling logic here if the table can't be dropped.

	}
	
	function get_summary_text()
	{
		return "$this->name";
	}
}
?>

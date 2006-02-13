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

class TabMenu
{
  
  function getTabNames($permittedModuleList="")
  {
    
     global $adb;
     $conn= $adb;
    if($permittedModuleList=="")
    {
      $sql="SELECT name from tab where presence = 0 order by tabsequence";
    }
    else
    {
      $sql="SELECT name from tab where tabid in (" .$permittedModuleList .") and presence = 0 order by tabsequence";
      //echo $sql;
    }
   
    $tabrow=$conn->query($sql);    
    if($conn->num_rows($tabrow) != 0)
    {
      while ($result = $conn->fetch_array($tabrow))
      {
        $tabmenu[]=$result['name'];
      }
    }
    return $tabmenu;
  }



}
// TabMenu shown in the header page.
class Tab extends CRMEntity {
	var $log;
	var $db;

	// Stored fields
	var $tabid;
	var $name;
	var $presence;
	var $tabsequence;
	var $label;
	var $modifiedby;
	var $modifiedtime;
	var $customized;
	
	//var $default_task_name_values = array('Assemble catalogs', 'Make travel arrangements', 'Send a letter', 'Send contract', 'Send fax', 'Send a follow-up letter', 'Send literature', 'Send proposal', 'Send quote');

	var $table_name = "tab";

	var $object_name = "Tab";

	var $column_fields = Array("tabid"
		, "name"
		, "presence"
		, "tabsequence"
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
	}

	function drop_tables () {


	}
	
	function get_summary_text()
	{
		return "$this->name";
	}
}
?>

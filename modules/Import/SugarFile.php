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
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');

// Contact is used to store customer information.
class SugarFile extends SugarBean 
{
	var $log;
	var $db;

	// Stored fields
	var $id;
	var $name;
	var $content;
	var $deleted;
	var $date_entered;
	var $assigned_user_id;

	var $table_name = "files";
	var $object_name = "SugarFile";
	
	var $new_schema = true;

	var $column_fields = Array("id"
		,"name"
		,"content"
                ,"deleted"
                ,"date_entered"
                ,"assigned_user_id"
		);


	function SugarFile() 
	{
		$this->log = LoggerManager::getLogger('file');
		$this->db = new PearDatabase();
	}

	function create_tables () 
	{
		/*
		$query = 'CREATE TABLE '.$this->table_name.' ( ';
		$query .='id char(36) NOT NULL';
		$query .=', name char(36)';
		$query .=', content blob';
                $query .=', deleted bool NOT NULL default 0';
                $query .=', date_entered datetime NOT NULL';
                $query .=', assigned_user_id char(36)';
		$query .=', PRIMARY KEY ( ID ) )';

		
		
		
		$this->db->query($query,true,"Error creating table: ".$this->table_name. ":" );


		//TODO Clint 4/27 - add exception handling logic here if the table can't be created.
	
		// Create the indexes
                $this->create_index("create index idx_cont_owner_id_and_name on files (assigned_user_id, name, deleted)");
		*/
	}

	function drop_tables () 
	{
		/*
		$query = 'DROP TABLE IF EXISTS '.$this->table_name;

		
			
		$this->db->query($query);

		//TODO Clint 4/27 - add exception handling logic here if the table can't be dropped.
		*/

	}

	function delete_file ($owner_id,$name) 
	{
		$fields_arr = array(
				'assigned_user_id'=>$owner_id,
				'name'=>$name
				);

		$where_clause = $this->get_where($fields_arr);

		$query = "delete from ".$this->table_name." $where_clause";

		
			
		$this->db->query($query);

	}

	
	function save_file( $owner_id, $name, $content )
	{
		$this->delete_file( $owner_id, $name);
		$result = 1;
		$this->assigned_user_id = $owner_id;
		$this->name = $name;
		$this->content = $content;
		$this->save();
		return $result;
	}

	function get_file( $owner_id ,$name)
	{
		$fields_array = array(
				'assigned_user_id' => $owner_id
				,'name' => $name
				);
		$this->retrieve_by_string_fields($fields_array, false);
		return $this->content;
	}

        
	
	/**
	* This function retrieves a record of the appropriate type from the DB based on 
	* search criteria 
	* It fills in all of the fields from the DB into the object it was called on.
	* param $fields_array -  associative array:  array( field_name=>"field_value",..);
	*   the field_name is one of the columns in the database you want to match
	*   the field_value is the string you want to match (MUST BE STRING COLUMNS)
	* returns this - The object that it was called apon or null if record was not found.
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/


	function retrieve_by_string_fields($fields_array, $encode=true) 
	{ 
		$where_clause = $this->get_where($fields_array); 

		$query = "SELECT * FROM $this->table_name $where_clause"; 

		$this->log->debug("Retrieve $this->object_name: ".$query); 

		$result =$this->db->requireSingleResult($query,true,"Want only a single row"); 

		if(empty($result)) 
		{ 
			
			return null; 
		} 
		$row = $this->db->fetchByAssoc($result,-1, $encode); 
		foreach($this->column_fields as $field) 
		{ 
			if(isset($row[$field])) 
			{ 
				$this->$field = $row[$field]; 
			} 
		} 

		$this->fill_in_additional_detail_fields(); 
		return $this; 
	}
		
}


?>

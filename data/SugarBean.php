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
 * $Header:  vtiger_crm/data/SugarBean.php,v 1.1 2004/08/17 13:18:39 gjk Exp $
 * Description:  Defines the base class for all data entities used throughout the 
 * application.  The base class including its methods and variables is designed to 
 * be overloaded with module-specific methods and variables particular to the 
 * module's base entity class. 
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('database/DatabaseConnection.php');
require_once('data/Tracker.php');
require_once('include/utils.php');

class SugarBean
{
    /**
     * This method implements a generic insert and update logic for any SugarBean
     * This method only works for subclasses that implement the same variable names.
     * This method uses the presence of an id field that is not null to signify and update.
     * The id field should not be set otherwise.
     * todo - Add support for field type validation and encoding of parameters.
     */
	
	var $new_schema = false;

    function save() {
		$isUpdate = true;

		if(!isset($this->id) || $this->id == "")
		{
			$isUpdate = false;
		}

		$this->date_modified = date('YmdHis');
		if (isset($current_user)) $this->modified_user_id = $current_user->id;
		
		if($isUpdate)
		{
    		$query = "Update ";
		}
		else
		{
    		$this->date_entered = date('YmdHis');
			if($this->new_schema)
			{
				$this->id = create_guid();
			}
			$query = "INSERT into ";
		}
		// todo - add date modified to the list.

        // write out the SQL statement.
        $query .= $this->table_name." set ";

		$firstPass = 0;
		foreach($this->column_fields as $field)
		{
			// Do not write out the id field on the update statement.
			// We are not allowed to change ids.
			if($isUpdate && ('id' == $field))
				continue;

			// Only assign variables that have been set.
			if(isset($this->$field))
			{				
				// Try comparing this element with the head element.
				if(0 == $firstPass)
					$firstPass = 1;
				else
					$query = $query.", ";
	
				$query = $query.$field."='".(addslashes($this->$field))."'";
			}
		}

		if($isUpdate)
		{
			$query = $query." WHERE ID = '$this->id'";
			$this->log->info("Update $this->object_name: ".$query);
		}
		else
		{
        	$this->log->info("Insert: ".$query);
		}

		mysql_query($query)
			or die("MySQL error: ".mysql_error());

		// If this is not an update then store the id for later.
		if(!$isUpdate && !$this->new_schema)
	        $this->id = mysql_insert_id();
	        
	    // let subclasses save related field changes
	    $this->save_relationship_changes($isUpdate);
    }

    /** 
     * This function is a good location to save changes that have been made to a relationship.
     * This should be overriden in subclasses that have something to save.
     * param $is_update true if this save is an update.
     */
    function save_relationship_changes($is_update)
    {
    	
    }
    
    /**
     * This function retrieves a record of the appropriate type from the DB.
     * It fills in all of the fields from the DB into the object it was called on.
     * param $id - If ID is specified, it overrides the current value of $this->id.  If not specified the current value of $this->id will be used.
     * returns this - The object that it was called apon or null if exactly 1 record was not found.
     */
	function retrieve($id = -1) {
		if ($id == -1) {
			$id = $this->id;
		}

		$query = "SELECT * FROM $this->table_name WHERE ID = '$id'";
		$this->log->debug("Retrieve $this->object_name: ".$query);

		$result = mysql_query($query)
			or die("MySQL error: ".mysql_error());

		if(mysql_num_rows($result) != 1)
		{
			$this->log->fatal("Retrieving record by id $this->table_name:$id found ".mysql_num_rows($result)." rows");
			return null;
		}
				
		$row = mysql_fetch_assoc($result);

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

	/**
	 * This function returns a paged list of the current object type.  It is intended to allow for
	 * hopping back and forth through pages of data.  It only retrieves what is on the current page.
	 * This method must be called on a new instance.  It trashes the values of all the fields in the current one.
	 */
	function get_list($order_by = "", $where = "", $row_offset = 0) {
		$query = $this->create_list_query($order_by, $where);
		return $this->process_list_query($query, $row_offset);
	}

	/**
	 * This function returns a full (ie non-paged) list of the current object type.  
	 */
	function get_full_list($order_by = "", $where = "") {
		$this->log->debug("get_full_list:  order_by = '$order_by' and where = '$where'");
		$query = $this->create_list_query($order_by, $where);
		return $this->process_full_list_query($query);
	}

	function create_list_query($order_by, $where)
	{
		$query = "SELECT * FROM $this->table_name ";

		if($where != "")
			$query .= "where ($where) AND deleted=0";
		else
			$query .= "where deleted=0";

		if($order_by != "")
			$query .= " ORDER BY $order_by";

		return $query;
	}

	function process_list_query(&$query, &$row_offset)
	{
		global $list_max_entries_per_page;

		$this->log->debug("get_list: ".$query);
		$result = mysql_query($query);

		if(!$result)
		{
			$this->log->error("Error retrieving $this->object_name list: ".mysql_error()."<br/>Query: $query");
			die("MySQL error: ".mysql_error()."<br/>Query: ".$query);
		}

		$list = Array();

		$rows_found = @ mysql_num_rows($result);

		$this->log->debug("Found $rows_found ".$this->object_name."s");

		$previous_offset = $row_offset - $list_max_entries_per_page;
		$next_offset = $row_offset + $list_max_entries_per_page;

		if($rows_found != 0)
		{
			// seek to the current offset
			if(!mysql_data_seek($result, $row_offset))
			{
				$this->log->error("Error skipping to offset $row_offset".mysql_error()."<br/>Query: $query");
				die("Error skipping to offset $row_offset ".mysql_error()."<br/>Query: ".$query);
			}

			// We have some data.
			for($row_counter = 0; $row_counter < $list_max_entries_per_page && $row = mysql_fetch_array($result); $row_counter++)
			{
				foreach($this->list_fields as $field)
				{
					if (isset($row[$field])) {
						$this->$field = $row[$field];
						if(get_magic_quotes_gpc() == 1)
						{
							$this->$field = stripslashes($this->$field);
						}
						
						$this->log->debug("$this->object_name({$row['id']}): ".$field." = ".$this->$field);
					}
					else 
					{
						$this->$field = "";
					}
				}

				$this->fill_in_additional_list_fields();

				$list[] = $this;
			}
		}

		$response = Array();
		$response['list'] = $list;
		$response['row_count'] = $rows_found;
		$response['next_offset'] = $next_offset;
		$response['previous_offset'] = $previous_offset;

		return $response;
	}

	function process_full_list_query($query)
	{
		$this->log->debug("process_full_list_query: query is ".$query);
		$result = mysql_query($query);
		$this->log->debug("process_full_list_query: result is ".$result);

		if(!$result)
		{
			$this->log->error("Error retrieving $this->object_name list: ".mysql_error()."<br/>Query: $query");
			die("MySQL error: ".mysql_error()."<br/>Query: ".$query);
		}
		else {
			// We have some data.
			while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				foreach($this->list_fields as $field)
				{
					if (isset($row[$field])) {
						$this->$field = $row[$field];
						if(get_magic_quotes_gpc() == 1)
						{
							$this->$field = stripslashes($this->$field);
						}
						$this->log->debug("process_full_list: $this->object_name({$row['id']}): ".$field." = ".$this->$field);
					}
				}

				$this->fill_in_additional_list_fields();

				$list[] = $this;
			}
		}

		if (isset($list)) return $list;
		else return null;
	}
	
	/**
	 * Track the viewing of a detail record.  This leverages get_summary_text() which is object specific
	 * params $user_id - The user that is viewing the record.
	 */
	function track_view($user_id, $current_module)
	{
		$this->log->debug("About to call tracker (user_id, module_name, item_id)($user_id, $current_module, $this->id)");

		$tracker = new Tracker();
		$tracker->track_view($user_id, $current_module, $this->id, $this->get_summary_text());
	}

	/**
	 * return the summary text that should show up in the recent history list for this object.
	 */
	function get_summary_text()
	{
		return "Base Implementation.  Should be overridden.";
	}

	/**
	 * This is designed to be overridden and add specific fields to each record.  This allows the generic query to fill in
	 * the major fields, and then targetted queries to get related fields and add them to the record.  The contact's account for instance.
	 * This method is only used for populating extra fields in lists
	 */
	function fill_in_additional_list_fields()
	{
	}

	/**
	 * This is designed to be overridden and add specific fields to each record.  This allows the generic query to fill in
	 * the major fields, and then targetted queries to get related fields and add them to the record.  The contact's account for instance.
	 * This method is only used for populating extra fields in the detail form
	 */
	function fill_in_additional_detail_fields()
	{
	}

	/**
	 * This is a helper class that is used to quickly created indexes when createing tables
	 */
	function create_index($query)
	{
		$this->log->info($query);

		mysql_query($query) or die("Error creating index: ".mysql_error());
	}

	/** This function should be overridden in each module.  It marks an item as deleted.
	* If it is not overridden, then marking this type of item is not allowed
	*/
	function mark_deleted($id)
	{
		$query = "UPDATE $this->table_name set deleted=1 where id='$id'";
		mysql_query($query) or die("Error marking record deleted: ".mysql_error());

		$this->mark_relationships_deleted($id);

		// Take the item off of the recently viewed lists.
		$tracker = new Tracker();
		$tracker->delete_item_history($id);

	}

	/** This function deletes relationships to this object.  It should be overridden to handle the relationships of the specific object.
	* This function is called when the item itself is being deleted.  For instance, it is called on Contact when the contact is being deleted.
	*/
	function mark_relationships_deleted($id)
	{

	}

	/**
	 * This function is used to execute the query and create an array template objects from the resulting ids from the query.
	 * It is currently used for building sub-panel arrays.
	 * param $query - the query that should be executed to build the list
	 * param $template - The object that should be used to copy the records.
	 */
	function build_related_list(&$query, &$template)
	{

		$this->log->debug("Finding linked records $this->object_name: ".$query);

		$result = mysql_query($query)
			or die("MySQL error: ".mysql_error());

		$list = Array();

		while($row = mysql_fetch_assoc($result))
		{
			$template->retrieve($row['id']);

			// this copies the object into the array
			$list[] = $template;
		}

		return $list;
	}

	/**
	 */
	function build_related_list2(&$query, &$template, &$field_list)
	{

		$this->log->debug("Finding linked values $this->object_name: ".$query);

		$result = mysql_query($query)
			or die("MySQL error: ".mysql_error());

		$list = Array();

		while($row = mysql_fetch_assoc($result))
		{
			// Create a blank copy
			$copy = $template;
			
			foreach($field_list as $field)
			{
				// Copy the relevant fields
				$copy->$field = $row[$field];
				if(get_magic_quotes_gpc() == 1)
				{
					$copy->$field = stripslashes($copy->$field);
				}
			}	

			// this copies the object into the array
			$list[] = $copy;
		}

		return $list;
	}

	/* This is to allow subclasses to fill in row specific columns of a list view form */
	function list_view_pare_additional_sections(&$list_form)
	{
	}

	/* This function assigns all of the values into the template for the list view */
	function get_list_view_data()
	{
		$return_array = Array();
		
		foreach($this->list_fields as $field)
		{
			$return_array[strtoupper($field)] = $this->$field;
		}
		
		return $return_array;
	}
}

?>
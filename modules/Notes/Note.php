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
 * Contributor(s): ______________________________________..
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Notes/Note.php,v 1.15 2005/03/15 10:01:08 shaw Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('data/CRMEntity.php');
require_once('include/upload_file.php');

// Note is used to store customer information.
class Note extends CRMEntity {
	var $log;
	var $db;

	// Stored fields
	var $id;
        var $mode;

	var $notesid;
	var $description;
	var $name;
	var $filename;
	var $parent_type;
	var $parent_id;
	var $contact_id;

	var $parent_name;
	var $contact_name;
	var $contact_phone;
	var $contact_email;
	var $required_fields =  array("name"=>1);
	var $default_note_name_dom = array('Meeting notes', 'Reminder');

	var $table_name = "notes";
	var $tab_name = Array('crmentity','notes','senotesrel');
        var $tab_name_index = Array('crmentity'=>'crmid','notes'=>'notesid','senotesrel'=>'notesid');

  	var $module_id = "notesid";
	var $object_name = "Note";

	var $column_fields = Array();

        var $sortby_fields = Array('title','modifiedtime');		  

	// This is used to retrieve related fields from form posts.
	var $additional_column_fields = Array('', '', '', '');

	// This is the list of fields that are in the lists.
	var $list_fields = Array(
				'Subject'=>Array('notes'=>'title'),
				'Contact Name'=>Array('notes'=>'contact_id'),
				'Related to'=>Array('senotesrel'=>'crmid'),
				'File'=>Array('notes'=>'filename'),
				'Last Modified'=>Array('crmentity'=>'modifiedtime')
				);
	var $list_fields_name = Array(
					'Subject'=>'title',
					'Contact Name'=>'contact_id',
					'Related to'=>'crmid',
					'File'=>'filename',
					'Last Modified'=>'modifiedtime'
				     );	
	var $list_link_field= 'title';

	function Note() {
		$this->log = LoggerManager::getLogger('notes');
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Notes');
	}

	var $new_schema = true;

	function create_tables () {
	}

	function drop_tables () {
	}

	function get_summary_text()
	{
		return "$this->name";
	}

	function create_list_query(&$order_by, &$where)
	{
		$contact_required = ereg("contacts\.first_name", $where);

		if($contact_required)
		{
			$query = "SELECT notes.notesid, notes.title, notes.filename,  FROM contactdetailss, notes ";
			$where_auto = "notes.contact_id = contacts.id AND notes.deleted=0 AND contacts.deleted=0";
		}
		else
		{
			$query = 'SELECT notesid, title, filename  FROM notes ';
			$where_auto = "deleted=0";
		}

		if($where != "")
			$query .= "where $where AND ".$where_auto;
		else
			$query .= "where ".$where_auto;

		if($order_by != "")
			$query .= " ORDER BY $order_by";
		else
			$query .= " ORDER BY title";

		return $query;
	}




        function create_export_query(&$order_by, &$where)
        {
             $query = "SELECT
                                        notes.*,
                                        contactdetails.firstname,
                                        contactdetails.lastname
                                        FROM notes
                                        LEFT JOIN contactdetails ON notes.contact_id=contactdetails.contactid inner join crmentity on crmentity.crmid=notes.notesid and crmentity.deleted=0 ";
                return $query;
        }




	function fill_in_additional_list_fields()
	{
		$this->fill_in_additional_detail_fields();
	}

	function fill_in_additional_detail_fields()
	{
		# TODO:  Seems odd we need to clear out these values so that list views don't show the previous rows value if current value is blank
		$this->contact_name = '';
		$this->contact_phone = '';
		$this->contact_email = '';
		$this->parent_name = '';

		if (isset($this->contact_id) && $this->contact_id != '') {
			require_once("modules/Contacts/Contact.php");
			$contact = new Contact();
			$query = "SELECT first_name, last_name, phone_work, email1 from $contact->table_name where id = '$this->contact_id'";
			$result =$this->db->query($query,true," Error filling in additional detail fields: ");

			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);

			if($row != null)
			{
				$this->contact_name = return_name($row, 'first_name', 'last_name');
				if ($row['phone_work'] != '') $this->contact_phone = $row['phone_work'];
				else $this->contact_phone = '';
				if ($row['email1'] != '') $this->contact_email = $row['email1'];
				else $this->contact_email = '';
			}
		}

		if ($this->parent_type == "Opportunities") {
			require_once("modules/Opportunities/Opportunity.php");
			$parent = new Opportunity();
			$query = "SELECT name from $parent->table_name where id = '$this->parent_id'";
			$result =$this->db->query($query,true," Error filling in additional detail fields: ");

			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);

			if($row != null)
			{
				if ($row['name'] != '') stripslashes($this->parent_name = $row['name']);
			}
		}
		elseif ($this->parent_type == "Cases") {
			require_once("modules/Cases/Case.php");
			$parent = new aCase();
			$query = "SELECT name from $parent->table_name where id = '$this->parent_id'";
			$result =$this->db->query($query,true," Error filling in additional detail fields: ");

			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);

			if($row != null)
			{
				if ($row['name'] != '') $this->parent_name = stripslashes($row['name']);
			}
		}
		elseif ($this->parent_type == "Accounts") {
			require_once("modules/Accounts/Account.php");
			$parent = new Account();
			$query = "SELECT name from $parent->table_name where id = '$this->parent_id'";
			$result =$this->db->query($query,true," Error filling in additional detail fields: ");

			// Get the id and the name.
			$row = $this->db->fetchByAssoc($result);

			if($row != null)
			{
				if ($row['name'] != '') $this->parent_name = stripslashes($row['name']);
			}
		}
	}
	function get_list_view_data(){
		$note_fields = $this->get_list_view_array();
		global $app_list_strings, $focus, $action, $currentModule;
		$note_fields["DATE_MODIFIED"] = substr($note_fields["DATE_MODIFIED"], 0 , 10);
		if (isset($this->parent_type)) {
			$note_fields['PARENT_MODULE'] = $this->parent_type;
		}

		if (! isset($this->filename) || $this->filename != '') 
		{
                        $note_fields['FILENAME'] = $this->filename;
                        $note_fields['FILE_URL'] = UploadFile::get_url($this->filename,$this->id);
                }


		return $note_fields;
	}

}
?>

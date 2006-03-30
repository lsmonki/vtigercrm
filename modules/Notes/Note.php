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

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'modifiedtime';
	var $default_sort_order = 'ASC';

	function Note() {
		$this->log = LoggerManager::getLogger('notes');
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Notes');
	}

	var $new_schema = true;

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

}
?>

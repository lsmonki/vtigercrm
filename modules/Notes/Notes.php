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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Notes/Notes.php,v 1.15 2005/03/15 10:01:08 shaw Exp $
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
class Notes extends CRMEntity {
	
	var $log;
	var $db;

	var $default_note_name_dom = array('Meeting vtiger_notes', 'Reminder');

	var $tab_name = Array('vtiger_crmentity','vtiger_notes');
	var $tab_name_index = Array('vtiger_crmentity'=>'crmid','vtiger_notes'=>'notesid','vtiger_senotesrel'=>'notesid');

	var $column_fields = Array();

        var $sortby_fields = Array('title','modifiedtime','contact_id','filename','createdtime','lastname');		  

	// This is used to retrieve related vtiger_fields from form posts.
	var $additional_column_fields = Array('', '', '', '');

	// This is the list of vtiger_fields that are in the lists.
	var $list_fields = Array(
				'Subject'=>Array('notes'=>'notes_title'),
				'Contact Name'=>Array('contactdetails'=>'lastname'),
				'Related to'=>Array('senotesrel'=>'crmid'),
				'File'=>Array('notes'=>'filename'),
				'Last Modified'=>Array('crmentity'=>'modifiedtime')
				);
	var $list_fields_name = Array(
					'Subject'=>'notes_title',
					'Contact Name'=>'contact_id',
					'Related to'=>'crmid',
					'File'=>'filename',
					'Last Modified'=>'modifiedtime'
				     );	
	var $list_link_field= 'notes_title';

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'modifiedtime';
	var $default_sort_order = 'ASC';
	function Notes() {
		$this->log = LoggerManager::getLogger('notes');
		$this->log->debug("Entering Notes() method ...");
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Notes');
		$this->log->debug("Exiting Note method ...");
	}

	function save_module($module)
	{
		
		$insertion_mode = $this->mode;
		//inserting into vtiger_senotesrel
		if(isset($this->column_fields['parent_id']) && $this->column_fields['parent_id'] != '')
		{
			$this->insertIntoEntityTable('vtiger_senotesrel', $module);
		}
		elseif($this->column_fields['parent_id']=='' && $insertion_mode=="edit")
		{
			$this->deleteRelation('vtiger_senotesrel');
		}


		//Inserting into attachments table
		$this->insertIntoAttachment($this->id,'Notes');
				
	}


	/**
	 *      This function is used to add the vtiger_attachments. This will call the function uploadAndSaveFile which will upload the attachment into the server and save that attachment information in the database.
	 *      @param int $id  - entity id to which the vtiger_files to be uploaded
	 *      @param string $module  - the current module name
	*/
	function insertIntoAttachment($id,$module)
	{
		global $log, $adb;
		$log->debug("Entering into insertIntoAttachment($id,$module) method.");
		
		$file_saved = false;

		foreach($_FILES as $fileindex => $files)
		{
			if($files['name'] != '' && $files['size'] > 0)
			{
				$file_saved = $this->uploadAndSaveFile($id,$module,$files);
			}
		}

		$log->debug("Exiting from insertIntoAttachment($id,$module) method.");
	}


	/** Function to export the notes in CSV Format
	* @param reference variable - order by is passed when the query is executed
	* @param reference variable - where condition is passed when the query is executed
	* Returns Export Notes Query.
	*/
	function create_export_query(&$order_by, &$where)
	{
		global $log;
		$log->debug("Entering create_export_query(".$order_by.",". $where.") method ...");

		include("include/utils/ExportUtils.php");

		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Notes", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);

		$query = "SELECT $fields_list FROM vtiger_notes
				inner join vtiger_crmentity 
					on vtiger_crmentity.crmid=vtiger_notes.notesid 
				LEFT JOIN vtiger_senotesrel
					ON vtiger_senotesrel.notesid = vtiger_notes.notesid
				LEFT JOIN vtiger_contactdetails 
					ON vtiger_notes.contact_id=vtiger_contactdetails.contactid 

				LEFT JOIN vtiger_crmentity vtiger_crmentityRelatedTo
					ON vtiger_crmentityRelatedTo.crmid = vtiger_senotesrel.crmid
				
				LEFT JOIN vtiger_leaddetails vtiger_NoteRelatedToLead
					ON vtiger_NoteRelatedToLead.leadid = vtiger_senotesrel.crmid
				LEFT JOIN vtiger_account vtiger_NoteRelatedToAccount
					ON vtiger_NoteRelatedToAccount.accountid = vtiger_senotesrel.crmid
				LEFT JOIN vtiger_potential vtiger_NoteRelatedToPotential
					ON vtiger_NoteRelatedToPotential.potentialid = vtiger_senotesrel.crmid
				LEFT JOIN vtiger_products vtiger_NoteRelatedToProduct
					ON vtiger_NoteRelatedToProduct.productid = vtiger_senotesrel.crmid
				LEFT JOIN vtiger_invoice vtiger_NoteRelatedToInvoice
					ON vtiger_NoteRelatedToInvoice.invoiceid = vtiger_senotesrel.crmid
				LEFT JOIN vtiger_purchaseorder vtiger_NoteRelatedToPO
					ON vtiger_NoteRelatedToPO.purchaseorderid = vtiger_senotesrel.crmid
				LEFT JOIN vtiger_salesorder vtiger_NoteRelatedToSO
					ON vtiger_NoteRelatedToSO.salesorderid = vtiger_senotesrel.crmid

				WHERE vtiger_crmentity.deleted=0 

				AND ((vtiger_senotesrel.crmid IS NULL
					AND (vtiger_notes.contact_id = 0
					OR vtiger_notes.contact_id IS NULL))
					OR vtiger_senotesrel.crmid IN (".getReadEntityIds('Leads').")
					OR vtiger_senotesrel.crmid IN (".getReadEntityIds('Accounts').")
					OR vtiger_senotesrel.crmid IN (".getReadEntityIds('Potentials').")
					OR vtiger_senotesrel.crmid IN (".getReadEntityIds('Products').")
					OR vtiger_senotesrel.crmid IN (".getReadEntityIds('Invoice').")
					OR vtiger_senotesrel.crmid IN (".getReadEntityIds('PurchaseOrder').")
					OR vtiger_senotesrel.crmid IN (".getReadEntityIds('SalesOrder').")
					OR vtiger_notes.contact_id IN (".getReadEntityIds('Contacts').")) 

					";

		$log->debug("Exiting create_export_query method ...");
                return $query;
        }

}
?>

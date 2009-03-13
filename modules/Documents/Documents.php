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
class Documents extends CRMEntity {
	
	var $log;
	var $db;
	var $table_name = "vtiger_notes";
	var $table_index= 'notesid';
	var $default_note_name_dom = array('Meeting vtiger_notes', 'Reminder');

	var $tab_name = Array('vtiger_crmentity','vtiger_notes');
	var $tab_name_index = Array('vtiger_crmentity'=>'crmid','vtiger_notes'=>'notesid','vtiger_senotesrel'=>'notesid');
	
	var $column_fields = Array();

    var $sortby_fields = Array('title','modifiedtime','filename','createdtime','lastname','filedownloadcount','smownerid');		  

	// This is used to retrieve related vtiger_fields from form posts.
	var $additional_column_fields = Array('', '', '', '');

	// This is the list of vtiger_fields that are in the lists.
	var $list_fields = Array(
				'Title'=>Array('notes'=>'title'),
				'File Name'=>Array('notes'=>'filename'),
				'Assigned To' => Array('crmentity'=>'smownerid'),
				'Folder Name' => Array('attachmentsfolder'=>'foldername')
				);
	var $list_fields_name = Array(
					'Title'=>'notes_title',
					'File Name'=>'filename',
					'Assigned To'=>'assigned_user_id',
					'Folder Name' => 'folderid'
				     );	
				     
	var $search_fields = Array(
					'Title' => Array('notes'=>'notes_title'),
					'File Name' => Array('notes'=>'filename'),
					'Assigned To' => Array('crmentity'=>'smownerid'),
					'Folder Name' => Array('attachmentsfolder'=>'foldername')
		);
	
	var $search_fields_name = Array(
					'Title' => 'notes_title',
					'File Name' => 'filename',
					'Assigned To' => 'assigned_user_id',
					'Folder Name' => 'folderid'
	);				     
	var $list_link_field= 'notes_title';
	var $old_filename = '';
	//var $groupTable = Array('vtiger_notegrouprelation','notesid');

	var $mandatory_fields = Array('notes_title','createdtime' ,'modifiedtime','filename','filesize','filetype','filedownloadcount');
	
	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'title';
	var $default_sort_order = 'ASC';
	function Documents() {
		$this->log = LoggerManager::getLogger('notes');
		$this->log->debug("Entering Documents() method ...");
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Documents');
		$this->log->debug("Exiting Documents method ...");
	}

	function save_module($module)
	{
		global $log,$adb;
		$insertion_mode = $this->mode;
		if(isset($this->parentid) && $this->parentid != '')
			$relid =  $this->parentid;		
		//inserting into vtiger_senotesrel
		if(isset($relid) && $relid != '')
		{
			$this->insertintonotesrel($relid,$this->id);
		}
		$fieldname = $this->getFileTypeFieldName();
		if($_REQUEST[$fieldname."_locationtype"] == 'I' ){
				if($_FILES[$fieldname]['name'] != ''){
					$errCode=$_FILES[$fieldname]['error'];
						if($errCode == 0){
							foreach($_FILES as $fileindex => $files)
							{
								if($files['name'] != '' && $files['size'] > 0){
									$filename = $_FILES[$fieldname]['name'];
									$filename = from_html(preg_replace('/\s+/', '_', $filename));
									$filetype = $_FILES[$fieldname]['type'];
									$filesize = $_FILES[$fieldname]['size'];
									$filelocationtype = 'I';
								}
							}
					
						}
				}elseif($this->mode == 'edit') {
					$fileres = $adb->pquery("select filetype, filesize,filename,filedownloadcount,filelocationtype from vtiger_notes where notesid=?", array($this->id));
					if ($adb->num_rows($fileres) > 0) {
						$filename = $adb->query_result($fileres, 0, 'filename');
						$filetype = $adb->query_result($fileres, 0, 'filetype');
						$filesize = $adb->query_result($fileres, 0, 'filesize');
						$filedownloadcount = $adb->query_result($fileres, 0, 'filedownloadcount');
						$filelocationtype = $_REQUEST[$fieldname."_locationtype"];
					}
				}
			} 
			else{
				//$this->column_fields['filelocationtype'] = 'E';
				$filelocationtype = 'E';
				$filename = $_REQUEST[$fieldname];
				if(!(stripos($filename,'http://') === 0) && $filename != '') {
					$filename = 'http://'.$filename;
				}
				$filetype = '';
				$filesize = '';
				$filedownloadcount = '';
			}
			$query = "Update vtiger_notes set filename = ? ,filesize = ?, filetype = ? , filelocationtype = ? , filedownloadcount = ? where notesid = ?";
	 		$re=$adb->pquery($query,array($filename,$filesize,$filetype,$filelocationtype,$filedownloadcount,$this->id));
		//Inserting into attachments table
		$this->insertIntoAttachment($this->id,'Documents');
				
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
				$files['original_name'] = $_REQUEST[$fileindex.'_hidden'];
				$file_saved = $this->uploadAndSaveFile($id,$module,$files);
			}
		}

		$log->debug("Exiting from insertIntoAttachment($id,$module) method.");
	}

	/**    Function used to get the sort order for Documents listview
	*      @return string  $sorder - first check the $_REQUEST['sorder'] if request value is empty then check in the $_SESSION['NOTES_SORT_ORDER'] if this session value is empty then default sort order will be returned.
	*/
	function getSortOrder()
	{
		global $log;
		$log->debug("Entering getSortOrder() method ...");
		if(isset($_REQUEST['sorder']))
			$sorder = $_REQUEST['sorder'];
		else
			$sorder = (($_SESSION['NOTES_SORT_ORDER'] != '')?($_SESSION['NOTES_SORT_ORDER']):($this->default_sort_order));
		$log->debug("Exiting getSortOrder() method ...");
		return $sorder;
	}

	/**     Function used to get the order by value for Documents listview
	*       @return string  $order_by  - first check the $_REQUEST['order_by'] if request value is empty then check in the $_SESSION['NOTES_ORDER_BY'] if this session value is empty then default order by will be returned.
	*/
	function getOrderBy()
	{
		global $log;
		$log->debug("Entering getOrderBy() method ...");
		if (isset($_REQUEST['order_by']))
			$order_by = $_REQUEST['order_by'];
		else
			$order_by = (($_SESSION['NOTES_ORDER_BY'] != '')?($_SESSION['NOTES_ORDER_BY']):($this->default_order_by));
		$log->debug("Exiting getOrderBy method ...");
		return $order_by;
	}


	/** Function to export the notes in CSV Format
	* @param reference variable - where condition is passed when the query is executed
	* Returns Export Documents Query.
	*/
	function create_export_query($where)
	{
		global $log,$current_user;
		$log->debug("Entering create_export_query(". $where.") method ...");

		include("include/utils/ExportUtils.php");
		//To get the Permitted fields query and the permitted fields list
		$sql = getPermittedFieldsQuery("Documents", "detail_view");
		$fields_list = getFieldsListFromQuery($sql);
		
		$query = "SELECT $fields_list, vtiger_groups.groupname as 'Assigned To Group',case when (vtiger_users.user_name not like '') then vtiger_users.user_name else vtiger_groups.groupname end as user_name" .
				" FROM vtiger_notes
				inner join vtiger_crmentity 
					on vtiger_crmentity.crmid=vtiger_notes.notesid 
				LEFT JOIN vtiger_attachmentsfolder on vtiger_notes.folderid=vtiger_attachmentsfolder.folderid
				LEFT JOIN vtiger_users ON vtiger_crmentity.smownerid=vtiger_users.id " .
				" LEFT JOIN vtiger_groups ON vtiger_crmentity.smownerid=vtiger_groups.groupid "
				;
	
				$where_auto=" vtiger_crmentity.deleted=0"; 
				
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
		//we should add security check when the user has Private Access
		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[2] == 3)
		{
			//Added security check to get the permitted records only
			$query = $query." ".getListViewSecurityParameter("Documents");
		}

		if($where != "")
			$query .= "  WHERE ($where) AND ".$where_auto;
		else
			$query .= "  WHERE ".$where_auto;
		$log->debug("Exiting create_export_query method ...");
		        return $query;
	}	
	
	function del_create_def_folder($query)
	{
		global $adb;
		$dbQuery = $query." and vtiger_attachmentsfolder.folderid = 0";
		$dbresult = $adb->pquery($dbQuery,array());
		$noofnotes = $adb->num_rows($dbresult);
		if($noofnotes > 0)
		{
            $folderQuery = "select folderid from vtiger_attachmentsfolder";
            $folderresult = $adb->pquery($folderQuery,array());
            $noofdeffolders = $adb->num_rows($folderresult);
            if($noofdeffolders == 0)
            {
			    $insertQuery = "insert into vtiger_attachmentsfolder values (0,'Default','Contains all attachments for which a folder is not set',1,0)";
			    $insertresult = $adb->pquery($insertQuery,array());
            }
		}
		
	}
	
	function insertintonotesrel($relid,$id)
	{
		global $adb;
		$dbQuery = "insert into vtiger_senotesrel values ( ?, ? )";
		$dbresult = $adb->pquery($dbQuery,array($relid,$id));
	}
	
	/*function save_related_module($module, $crmid, $with_module, $with_crmid){
		global $log;
		$log->debug("indocument".$module.$crmid.$with_module.$with_crmid);
		if(isset($this->parentid) && $this->parentid != '')
			$relid =  $this->parentid;		
		//inserting into vtiger_senotesrel
		if(isset($relid) && $relid != '')
		{
			$this->insertintonotesrel($relid,$this->id);
		}
	}*/

	
	/*
	 * Function to get the primary query part of a report
	 * @param - $module Primary module name
	 * returns the query string formed on fetching the related data for report for primary module
	 */
	function generateReportsQuery($module){
	 			$moduletable = $this->table_name;
	 			$moduleindex = $this->tab_name_index[$moduletable];
	 				$query = "from $moduletable 
			        inner join vtiger_crmentity on vtiger_crmentity.crmid=$moduletable.$moduleindex
			        inner join vtiger_attachmentsfolder on vtiger_attachmentsfolder.folderid=$moduletable.folderid
					left join vtiger_groups as vtiger_groups".$module." on vtiger_groups".$module.".groupid = vtiger_crmentity.smownerid
		            left join vtiger_users as vtiger_users".$module." on vtiger_users".$module.".id = vtiger_crmentity.smownerid
					left join vtiger_groups on vtiger_groups.groupid = vtiger_crmentity.smownerid
		            left join vtiger_users on vtiger_users.id = vtiger_crmentity.smownerid";
		            return $query;
		            
	}
	
	/*
	 * Function to get the secondary query part of a report 
	 * @param - $module primary module name
	 * @param - $secmodule secondary module name
	 * returns the query string formed on fetching the related data for report for secondary module
	 */
	function generateReportsSecQuery($module,$secmodule){
		$tab = getRelationTables($module,$secmodule);
		
		foreach($tab as $key=>$value){
			$tables[]=$key;
			$fields[] = $value;
		}
		$tabname = $tables[0];
		$prifieldname = $fields[0][0];
		$secfieldname = $fields[0][1];
		$tmpname = $tabname."tmp".$secmodule;
		$condvalue = $tables[1].".".$fields[1];
	
		$query = " left join $tabname as $tmpname on $tmpname.$prifieldname = $condvalue  and $tmpname.$secfieldname IN (SELECT notesid from vtiger_notes INNER JOIN vtiger_crmentity ON vtiger_crmentity.deleted=0 AND vtiger_crmentity.crmid=vtiger_notes.notesid)";
		$query .=" left join vtiger_notes on vtiger_notes.notesid = $tmpname.$secfieldname 
				left join vtiger_crmentity as vtiger_crmentityDocuments on vtiger_crmentityDocuments.crmid=vtiger_notes.notesid and vtiger_crmentityDocuments.deleted=0 
		        left join vtiger_attachmentsfolder on vtiger_attachmentsfolder.folderid=vtiger_notes.folderid
				left join vtiger_groups as vtiger_groupsDocuments on vtiger_groupsDocuments.groupid = vtiger_crmentityDocuments.smownerid
				left join vtiger_users as vtiger_usersDocuments on vtiger_usersDocuments.id = vtiger_crmentityDocuments.smownerid"; 

		return $query;
	}

	/*
	 * Function to get the relation tables for related modules 
	 * @param - $secmodule secondary module name
	 * returns the array with table names and fieldnames storing relations between module and this module
	 */
	function setRelationTables($secmodule){
		$rel_tables = array();
		return $rel_tables[$secmodule];
	}
	
	// Function to unlink all the dependent entities of the given Entity by Id
	function unlinkDependencies($module, $id) {
		global $log;		
		/*//Backup Documents Related Records
		$se_q = 'SELECT crmid FROM vtiger_senotesrel WHERE notesid = ?';
		$se_res = $this->db->pquery($se_q, array($id));
		if ($this->db->num_rows($se_res) > 0) {
			for($k=0;$k < $this->db->num_rows($se_res);$k++)
			{
				$se_id = $this->db->query_result($se_res,$k,"crmid");
				$params = array($id, RB_RECORD_DELETED, 'vtiger_senotesrel', 'notesid', 'crmid', $se_id);
				$this->db->pquery('INSERT INTO vtiger_relatedlists_rb VALUES (?,?,?,?,?,?)', $params);
			}
		}
		$sql = 'DELETE FROM vtiger_senotesrel WHERE notesid = ?';
		$this->db->pquery($sql, array($id));*/
		
		parent::unlinkDependencies($module, $id);
	}
	
	// Function to unlink an entity with given Id from another entity
	function unlinkRelationship($id, $return_module, $return_id) {
		global $log;
		if(empty($return_module) || empty($return_id)) return;
		
		$sql = 'DELETE FROM vtiger_senotesrel WHERE notesid = ? AND crmid = ?';
		$this->db->pquery($sql, array($id, $return_id));
			
		$sql = 'DELETE FROM vtiger_crmentityrel WHERE (crmid=? AND relmodule=? AND relcrmid=?) OR (relcrmid=? AND module=? AND crmid=?)';
		$params = array($id, $return_module, $return_id, $id, $return_module, $return_id);
		$this->db->pquery($sql, $params);
	}


// Function to get fieldname for uitype 27 assuming that documents have only one file type field

	function getFileTypeFieldName(){
		global $adb,$log;
		$query = 'SELECT fieldname from vtiger_field where tabid = ? and uitype = ?';
		$tabid = getTabid('Documents');
		$res = $adb->pquery($query,array($tabid,27));
		$fieldname = $adb->query_result($res,0,'fieldname');
		return $fieldname;
		
	} 
}	
?>

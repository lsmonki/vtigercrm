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
 * $Header$
 * Description:  Defines the Account SugarBean Account entity with the necessary
 * methods and variables.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('data/CRMEntity.php');
require_once('include/utils/utils.php');

	global $empty_string;
// Faq is used to store vtiger_faq information.
class Faq extends CRMEntity {
	var $log;
	var $db;
	var $table_name = "vtiger_faq";
	var $tab_name = Array('vtiger_crmentity','vtiger_faq');
	var $tab_name_index = Array('vtiger_crmentity'=>'crmid','vtiger_faq'=>'id','vtiger_faqcomments'=>'faqid');
				
	var $entity_table = "vtiger_crmentity";
	
	var $column_fields = Array();
		
	var $non_mass_edit_fields = Array();

	var $sortby_fields = Array('question','category','id');		

	// This is the list of vtiger_fields that are in the lists.
	var $list_fields = Array(
				'FAQ Id'=>Array('faq'=>'id'),
				'Question'=>Array('faq'=>'question'),
				'Category'=>Array('faq'=>'category'),
				'Product Name'=>Array('faq'=>'product_id'), 
				'Created Time'=>Array('crmentity'=>'createdtime'), 
				'Modified Time'=>Array('crmentity'=>'modifiedtime') 
				);
	
	var $list_fields_name = Array(
				        'FAQ Id'=>'',
				        'Question'=>'question',
				        'Category'=>'faqcategories',
				        'Product Name'=>'product_id',
						'Created Time'=>'createdtime',
						'Modified Time'=>'modifiedtime' 
				      );
	var $list_link_field= 'question';

	var $search_fields = Array(
				'Account Name'=>Array('account'=>'accountname'),
				'City'=>Array('accountbillads'=>'bill_city'), 
				);
	
	var $search_fields_name = Array(
				        'Account Name'=>'accountname',
				        'City'=>'bill_city',
				      );

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'id';
	var $default_sort_order = 'DESC';

	/**	Constructor which will set the column_fields in this object
	 */
	function Faq() {
		$this->log =LoggerManager::getLogger('faq');
		$this->log->debug("Entering Faq() method ...");
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Faq');
		$this->log->debug("Exiting Faq method ...");
	}

	function save_module($module)
	{
		//Inserting into Faq comment table
		$this->insertIntoFAQCommentTable('vtiger_faqcomments', $module);
		
	}


	/** Function to insert values in vtiger_faqcomments table for the specified module,
  	  * @param $table_name -- table name:: Type varchar
  	  * @param $module -- module:: Type varchar
 	 */	
	function insertIntoFAQCommentTable($table_name, $module)
	{
		global $log;
		$log->info("in insertIntoFAQCommentTable  ".$table_name."    module is  ".$module);
        	global $adb;

        	$current_time = $adb->formatDate(date('Y-m-d H:i:s'), true);

		if($this->column_fields['comments'] != '')
			$comment = $this->column_fields['comments'];
		else
			$comment = $_REQUEST['comments'];

		if($comment != '')
		{
			$params = array('', $this->id, from_html($comment), $current_time);
			$sql = "insert into vtiger_faqcomments values(?, ?, ?, ?)";	
			$adb->pquery($sql, $params);
		}
	}	
	

	/**     Function to get the list of comments for the given FAQ id
         *      @param  int  $faqid - FAQ id
	 *      @return list $list - return the list of comments and comment informations as a html output where as these comments and comments informations will be formed in div tag.
        **/	
	function getFAQComments($faqid)
	{
		global $log, $default_charset;
		$log->debug("Entering getFAQComments(".$faqid.") method ...");
		global $mod_strings;
		$sql = "select * from vtiger_faqcomments where faqid=?";
		$result = $this->db->pquery($sql, array($faqid));
		$noofrows = $this->db->num_rows($result);

		//In ajax save we should not add this div
		if($_REQUEST['action'] != 'FaqAjax')
		{
			$list .= '<div id="comments_div" style="overflow: auto;height:200px;width:100%;">';
			$enddiv = '</div>';
		}

		for($i=0;$i<$noofrows;$i++)
		{
			$comment = $this->db->query_result($result,$i,'comments');
			$createdtime = $this->db->query_result($result,$i,'createdtime');
			if($comment != '')
			{
				//this div is to display the comment
				if($_REQUEST['action'] == 'FaqAjax') {
					$comment = htmlentities($comment, ENT_QUOTES, $default_charset);
				}
				$list .= '<div valign="top" style="width:99%;padding-top:10px;" class="dataField">'.make_clickable(nl2br($comment)).'</div>';
				
				//this div is to display the created time
				$list .= '<div valign="top" style="width:99%;border-bottom:1px dotted #CCCCCC;padding-bottom:5px;" class="dataLabel"><font color=darkred>'.$mod_strings['Created Time'];
				$list .= ' : '.$createdtime.'</font></div>';
			}
		}

		$list .= $enddiv;
		
		$log->debug("Exiting getFAQComments method ...");
		return $list;
	}

	function get_attachments($id)
	{
		global $log,$current_user;
		$log->debug("Entering get_attachments(".$id.") method ...");
		// Desc: Inserted crm2.createdtime, vtiger_notes.notecontent description, vtiger_users.user_name
		// Inserted inner join vtiger_users on crm2.smcreatorid= vtiger_users.id
		$tab_id=getTabid('Documents');
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
		require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3) {
			$sec_parameter=getListViewSecurityParameter('Documents');
		}
		
		$query = "select vtiger_notes.title,'Documents' ActivityType, vtiger_notes.filename,
					crm2.modifiedtime lastmodified,
					vtiger_notes.notesid crmid,
					vtiger_notes.notecontent description, vtiger_users.user_name
					from vtiger_notes
					inner join vtiger_senotesrel on vtiger_senotesrel.notesid= vtiger_notes.notesid
					inner join vtiger_crmentity on vtiger_crmentity.crmid= vtiger_senotesrel.crmid
					inner join vtiger_crmentity crm2 on crm2.crmid=vtiger_notes.notesid and crm2.deleted=0
					LEFT JOIN vtiger_notegrouprelation
						ON vtiger_notegrouprelation.notesid = vtiger_notes.notesid
					LEFT JOIN vtiger_groups
						ON vtiger_groups.groupname = vtiger_notegrouprelation.groupname			
					inner join vtiger_users on crm2.smownerid= vtiger_users.id
					where vtiger_crmentity.crmid=".$id;
		$query .= $sec_parameter;
		$log->debug("Exiting get_attachments method ...");
		return getAttachmentsAndNotes('Faq',$query,$id);
	}
}
?>

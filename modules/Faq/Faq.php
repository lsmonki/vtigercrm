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

// Faq is used to store faq information.
class Faq extends CRMEntity {
	var $log;
	var $db;

	// Stored fields
	var $id;
	var $mode;
	
	var $tab_name = Array('crmentity','faq','faqcomments');
	var $tab_name_index = Array('crmentity'=>'crmid','faq'=>'id','faqcomments'=>'faqid');
				
	var $entity_table = "crmentity";
	
	var $column_fields = Array();
		
	var $sortby_fields = Array('question','category','id');		

	// This is the list of fields that are in the lists.
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

	var $list_mode;
        var $popup_type;

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

	function Faq() {
		$this->log =LoggerManager::getLogger('account');
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Faq');
	}

	/**     Function to get the list of comments for the given FAQ id
         *      @param  int  $faqid - FAQ id
         *      @return list $list - the list of comments which are formed as boxed info with div tags.
        **/	
	function getFAQComments($faqid)
	{
		global $mod_strings;
		$sql = "select * from faqcomments where faqid=".$faqid;
		$result = $this->db->query($sql);
		$noofrows = $this->db->num_rows($result);

		if($noofrows == 0)
			return '';

		$list .= '<div style="overflow: scroll;height:150;width:100%;">';
		for($i=0;$i<$noofrows;$i++)
		{
			$comment = $this->db->query_result($result,$i,'comments');
			$createdtime = $this->db->query_result($result,$i,'createdtime');
			if($comment != '')
			{
				$list .= '<div valign="top" width="70%" class="dataField">&nbsp;&nbsp;'.$comment.'</div>';
				$list .= '<div valign="top" width="70%" class="dataLabel">'.$mod_strings['Created Time'];
				$list .= ' : '.$createdtime.'</div>';
			}
		}
		$list .= '</div>';
		return $list;
	}
	

}
?>

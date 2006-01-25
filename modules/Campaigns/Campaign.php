<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of txhe License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
require_once('data/CRMEntity.php');
require_once('include/utils/utils.php');


class Campaign extends CRMEntity {
	var $log;
	var $db;


	// Stored fields
	var $id;
	var $mode;

	var $tab_name = Array('crmentity','campaign');
	var $tab_name_index = Array('crmentity'=>'crmid','campaign'=>'campaignid');
	var $column_fields = Array();

	var $sortby_fields = Array('campaignname','smownerid','expectedcost');

	var $list_fields = Array(
	'Campaign ID'=>Array('crmentity'=>'crmid'),
	'Campaign Name'=>Array('campaign'=>'campaignname'),	  			
	'Expected Cost'=>Array('campaign'=>'expectedcost')
	);

	var $list_fields_name = Array(
	'Campaign ID'=>'',
	'Campaign Name'=>'campaignname',	  			
	'Expected Cost'=>'expectedcost');	  			

	var $list_link_field= 'campaignname';
			
	var $range_fields = Array(
	        'ticketid',
		'title',
        	'firstname',
	        'lastname',
        	'parent_id',
        	'productid',
        	'productname',
        	'priority',
        	'severity',
	        'status',
        	'category',
		'description',
		'solution',
		'modifiedtime',
		'createdtime'
		);

	//Added these variables which are used as default order by and sortorder in ListView
	var $default_order_by = 'crmid';
	var $default_sort_order = 'DESC';

	function Campaign() 
	{
		$this->log =LoggerManager::getLogger('campaign');
		$this->db = new PearDatabase();
		$this->column_fields = getColumnFields('Campaign');
	}

	function process_list_query($query)
	{
	  
   		$result =& $this->db->query($query,true,"Error retrieving $this->object_name list: ");
		$list = Array();
	        $rows_found =  $this->db->getRowCount($result);
        	if($rows_found != 0)
	        {
			$ticket = Array();
			for($index = 0 , $row = $this->db->fetchByAssoc($result, $index); $row && $index <$rows_found;$index++, $row = $this->db->fetchByAssoc($result, $index))
			{
		                foreach($this->range_fields as $columnName)
                		{
		                	if (isset($row[$columnName])) 
					{
			                	$ticket[$columnName] = $row[$columnName];
                    			}
		                       	else     
				        {   
		                        	$ticket[$columnName] = "";
			                }   
	     			}	
    		                $list[] = $ticket;
                	}
        	}   

		$response = Array();
	        $response['list'] = $list;
        	$response['row_count'] = $rows_found;
	        $response['next_offset'] = $next_offset;
        	$response['previous_offset'] = $previous_offset;

	        return $response;
	}

	/**	Function to get the HelpDesk field labels in caps letters without space
	 *	return array $mergeflds - array(	key => val	)    where   key=0,1,2..n & val = ASSIGNEDTO,RELATEDTO, .,etc
	**/
	function getColumnNames_Hd()
	{
		$sql1 = "select fieldlabel from field where tabid=13 and block <> 6 ";
		$result = $this->db->query($sql1);
		$numRows = $this->db->num_rows($result);
		for($i=0; $i < $numRows;$i++)
		{
			$custom_fields[$i] = $this->db->query_result($result,$i,"fieldlabel");
			$custom_fields[$i] = ereg_replace(" ","",$custom_fields[$i]);
			$custom_fields[$i] = strtoupper($custom_fields[$i]);
		}
		$mergeflds = $custom_fields;
		return $mergeflds;
	}

	/**     Function to get the list of comments for the given ticket id
	 *      @param  int  $ticketid - Ticket id
	 *      @return list $list - the list of comments which are formed as boxed info with div tags.
	**/
	function getCommentInformation($ticketid)
	{
		global $adb;
		global $mod_strings;
		$sql = "select * from ticketcomments where ticketid=".$ticketid;
		$result = $adb->query($sql);
		$noofrows = $adb->num_rows($result);

		if($noofrows == 0)
			return '';

		$list .= '<div style="overflow: scroll;height:200;width:100%;">';
		for($i=0;$i<$noofrows;$i++)
		{
			if($adb->query_result($result,$i,'comments') != '')
			{
				$list .= '<div valign="top" width="70%"class="dataField">';
				$list .= make_clickable(nl2br($adb->query_result($result,$i,'comments')));

				$list .= '</div><div valign="top" width="20%" class="dataLabel"><font color=darkred>';
				$list .= $mod_strings['LBL_AUTHOR'].' : ';
				if($adb->query_result($result,$i,'ownertype') == 'user')
					$list .= getUserName($adb->query_result($result,$i,'ownerid'));
				else
					$list .= $this->getCustomerName($ticketid);

				$list .= ' on '.$adb->query_result($result,$i,'createdtime').' &nbsp;';

				$list .= '</font></div>';
			}
		}
		$list .= '</div>';

		return $list;
	}

	/**     Function to get the Customer Name who has made comment to the ticket from the portal
	 *      @param  int    $id   - Ticket id
	 *      @return string $customername - The contact name
	**/
	function getCustomerName($id)
	{
        	global $adb;
	        $sql = "select * from portalinfo inner join troubletickets on troubletickets.parent_id = portalinfo.id where troubletickets.ticketid=".$id;
        	$result = $adb->query($sql);
	        $customername = $adb->query_result($result,0,'user_name');
        	return $customername;
	}


}
?>

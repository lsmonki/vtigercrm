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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/data/Tracker.php,v 1.15 2005/04/28 05:44:22 samk Exp $
 * Description:  Updates entries for the Last Viewed functionality tracking the
 * last viewed records on a per user basis.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');

/** This class is used to track the recently viewed items on a per user basis.
 * It is intended to be called by each module when rendering the detail form.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
*/
class Tracker {
    var $log;
    var $db;
    var $table_name = "tracker";

    // Tracker table
    var $column_fields = Array(
        "id",
        "user_id",
        "module_name",
        "item_id",
        "item_summary"
    );

    function Tracker()
    {
        $this->log = LoggerManager::getLogger('Tracker');
	//$this->db = new PearDatabase();
	global $adb;
        $this->db = $adb;
    }

    /**
     * Add this new item to the tracker table.  If there are too many items (global config for now)
     * then remove the oldest item.  If there is more than one extra item, log an error.
     * If the new item is the same as the most recent item then do not change the list
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
     */
    function track_view($user_id, $current_module, $item_id, $item_summary)
    {
      global $adb;
      $this->delete_history($user_id, $item_id);
      global $log;
$log->info("in  track view method ".$current_module);
        // Add a new item to the user's list

        $esc_item_id = addslashes($item_id);
        

         //get the first name and last name from the respective modules
          if($current_module =='Leads')
          {
            $query = 'select firstname,lastname from leaddetails where leadid=' .$item_id;
            $result = $this->db->query($query);
            $firstname = $adb->query_result($result,0,'firstname');
            $lastname =  $adb->query_result($result,0,'lastname');
            $item_summary = $lastname.' '.$firstname;
          }
          elseif ($current_module =='Accounts')
          {
            $query = 'select accountname from account where accountid=' .$item_id;
            $result = $this->db->query($query);
            $accountname = $adb->query_result($result,0,'accountname');
            $item_summary = $accountname;
            
          }
          elseif($current_module =='Contacts')
          {
            $query = 'select firstname,lastname from contactdetails where contactid=' .$item_id;
            $result = $this->db->query($query);
            $firstname = $adb->query_result($result,0,'firstname');
            $lastname =  $adb->query_result($result,0,'lastname');
            $item_summary = $lastname.' '.$firstname;
            
          }
          elseif($current_module =='Potentials')
          {
            $query = 'select potentialname from potential where potentialid=' .$item_id;
            $result = $this->db->query($query);
            $potentialname =  $adb->query_result($result,0,'potentialname');
            $item_summary = $potentialname;
          }
          elseif($current_module =='Notes')
          {
            $query = 'select title from notes where notesid=' .$item_id;
            $result = $this->db->query($query);
            $title = $adb->query_result($result,0,'title');
            $item_summary = $title;
            
          }
          elseif($current_module =='HelpDesk')
          {
            $query = 'select title from troubletickets where ticketid=' .$item_id;
            $result = $this->db->query($query);
            $title = $adb->query_result($result,0,'title');
            $item_summary = $title;
          }
          elseif($current_module =='Activities')
          {
            //$query = 'select name from calls where callid=' .$item_id;
	    $query = 'select subject from activity where activityid=' .$item_id;
            $result = $this->db->query($query);
            $name = $adb->query_result($result,0,'subject');
            $item_summary = $name;
          }
          elseif($current_module =='Emails')
          {
            //$query = 'select name from emails where emailid=' .$item_id;
	    $query = 'select subject from activity where activityid=' .$item_id;
            $result = $this->db->query($query);
            $name = $adb->query_result($result,0,'subject');
            $item_summary = $name;
          }
          elseif($current_module =='Products')
          {
            $query = 'select productname from products where productid=' .$item_id;
            $result = $this->db->query($query);
            $productname = $adb->query_result($result,0,'productname');
            $item_summary = $productname;
          }
          elseif($current_module =='Users')
          {
            $query = 'select first_name,last_name from users where id=' .$item_id;
            $result = $this->db->query($query);
            $firstname = $adb->query_result($result,0,'first_name');
            $lastname = $adb->query_result($result,0,'last_name');
            $item_summary = $lastname.' '.$firstname;
          }
	  elseif($current_module =='Invoice')
          {
            $query = 'select subject from invoice where invoiceid=' .$item_id;
            $result = $this->db->query($query);
            $invoice = $adb->query_result($result,0,'subject');
            $item_summary = $invoice;
          }
          elseif($current_module =='Quotes')
          {
            $query = 'select subject from quotes where quoteid=' .$item_id;
            $result = $this->db->query($query);
            $quote = $adb->query_result($result,0,'subject');
            $item_summary = $quote;
          }
	  elseif($current_module =='PurchaseOrder')
          {
            $query = 'select subject from purchaseorder where purchaseorderid=' .$item_id;
            $result = $this->db->query($query);
            $po = $adb->query_result($result,0,'subject');
            $item_summary = $po;
          }
	  elseif($current_module =='SalesOrder')
          {
            $query = 'select subject from salesorder where salesorderid=' .$item_id;
            $result = $this->db->query($query);
            $so = $adb->query_result($result,0,'subject');
            $item_summary = $so;
          }
	  elseif($current_module =='Vendor')
          {
            $query = 'select vendorname from vendor where vendorid=' .$item_id;
            $result = $this->db->query($query);
            $vendor = $adb->query_result($result,0,'vendorname');
            $item_summary = $vendor;
          }
	  elseif($current_module =='PriceBook')
          {
            $query = 'select bookname from pricebook where pricebookid=' .$item_id;
            $result = $this->db->query($query);
            $pb = $adb->query_result($result,0,'bookname');
            $item_summary = $pb;
          }	

	 
	 #if condition added to skip faq in last viewed history
	  if ($current_module != 'Faq')
	  {		
          $query = "INSERT into $this->table_name (user_id, module_name, item_id, item_summary) values ('$user_id', '$current_module', '$esc_item_id', ".$this->db->formatString($this->table_name,'item_summary',$item_summary).")";
          
          $this->log->info("Track Item View: ".$query);
          
          $this->db->query($query, true);
          
          
          $this->prune_history($user_id);
	  }
    }

    /**
     * param $user_id - The id of the user to retrive the history for
     * param $module_name - Filter the history to only return records from the specified module.  If not specified all records are returned
     * return - return the array of result set rows from the query.  All of the table fields are included
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
     */
    function get_recently_viewed($user_id, $module_name = "")
    {
    	if (empty($user_id)) {
    		return;
    	}

//        $query = "SELECT * from $this->table_name WHERE user_id='$user_id' ORDER BY id DESC";
	$query = "SELECT * from $this->table_name inner join crmentity on crmentity.crmid=tracker.item_id WHERE user_id='$user_id' and crmentity.deleted=0 ORDER BY id DESC";
        $this->log->debug("About to retrieve list: $query");
        $result = $this->db->query($query, true);
        $list = Array();
        while($row = $this->db->fetchByAssoc($result, -1, false))
        {
		//echo "while loppp";
		//echo '<BR>';


            // If the module was not specified or the module matches the module of the row, add the row to the list
            if($module_name == "" || $row[module_name] == $module_name)
            {
		//Adding Security check
		require_once('include/utils/utils.php');
		require_once('include/utils/UserInfoUtil.php');
		$entity_id = $row['item_id'];
		$module = $row['module_name'];
		//echo "module is ".$module."  id is      ".$entity_id;
		//echo '<BR>';
		if($module == "Users")
		{
			global $current_user;
			if(is_admin($current_user))
			{
				$per = 'yes';
			}	
		}
		else
		{
			
			$per = isPermitted($module,4,$entity_id);
			//echo "permission is".$per;
			//echo "<BR>";
			//echo "<BR>";
			
		}
		if($per == 'yes')
		{
            		$list[] = $row;
		}
            }
        }

        return $list;
    }



    /**
     * INTERNAL -- This method cleans out any entry for a record for a user.
     * It is used to remove old occurances of previously viewed items.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
     */
    function delete_history( $user_id, $item_id)
    {
        $query = "DELETE from $this->table_name WHERE user_id='$user_id' and item_id='$item_id'";
       $this->db->query($query, true);
    }

    /**
     * INTERNAL -- This method cleans out any entry for a record.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
     */
    function delete_item_history($item_id)
    {
        $query = "DELETE from $this->table_name WHERE item_id='$item_id'";
       $this->db->query($query, true);

    }

    /**
     * INTERNAL -- This function will clean out old history records for this user if necessary.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
     */
    function prune_history($user_id)
    {
        global $history_max_viewed;

        // Check to see if the number of items in the list is now greater than the config max.
        $query = "SELECT count(*) from $this->table_name WHERE user_id='$user_id'";

        $this->log->debug("About to verify history size: $query");

        $count = $this->db->getOne($query);


        $this->log->debug("history size: (current, max)($count, $history_max_viewed)");
        while($count > $history_max_viewed)
        {
            // delete the last one.  This assumes that entries are added one at a time.
            // we should never add a bunch of entries
            $query = "SELECT * from $this->table_name WHERE user_id='$user_id' ORDER BY id ASC";
            $this->log->debug("About to try and find oldest item: $query");
            $result =  $this->db->limitQuery($query,0,1);

            $oldest_item = $this->db->fetchByAssoc($result, -1, false);
            $query = "DELETE from $this->table_name WHERE id='{$oldest_item['id']}'";
            $this->log->debug("About to delete oldest item: ");

            $result = $this->db->query($query, true);


            $count--;
        }
    }

}
?>

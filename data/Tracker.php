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
 * Contributor(s): DrSlump <imontes@netxusfoundries.com>
 ********************************************************************************/

include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');

/** This class is used to track the recently viewed items on a per user basis.
 * It is intended to be called by each module when rendering the detail form.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): DrSlump <imontes@netxusfoundries.com>
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
        $this->db = $GLOBALS['adb'];
    }

    /**
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	 * All Rights Reserved.
	 * Contributor(s): DrSlump <imontes@netxusfoundries.com>
     */
    function track_view($user_id, $current_module, $item_id, $item_summary='')
    {
		global $adb;
		
		$modulesDetDesc = array(
			'Leads' 	=> "CONCAT(firstname,' ',lastname) as summary FROM leaddetails WHERE leadid=",
			'Accounts'	=> "accountname as summary FROM account WHERE accountid=",
			'Contacts'	=> "CONCAT(firstname,' ',lastname) as summary FROM contactdetails WHERE contactid=",
			'Potentials'=> "potentialname as summary FROM potential WHERE potentialid=",
			'Notes'		=> "title as summary FROM notes WHERE notesid=",
			'HelpDesk'	=> "title as summary FROM troubletickets WHERE ticketid=",
			"Activities"=> "subject as summary FROM activity WHERE activityid=",
			"Emails"	=> "subject as summary FROM activity WHERE activityid=",
			"Products"	=> "productname as summary FROM products WHERE productid=",
			"Users"		=> "CONCAT(first_name,' ',last_name) as summary FROM users WHERE id=",
		);
			
		if ($item_summary || isset($modulesDetDesc[$current_module])) {
			//remove this item from the tracker stack if it was there already
			$this->delete_history($user_id, $item_id);
				
			if (! $item_summary) {
				//fetch the item name
				$item_summary = $this->db->getOne('SELECT '.$modulesDetDesc[$current_module].$item_id);
			}
			
			//add the item to the tracker
			$sql = "INSERT INTO %s (user_id, module_name, item_id, item_summary) VALUES ('%s', '%s', '%s', '%s')";
			$sql = sprintf($sql, $this->table_name, $user_id, $current_module, $item_id, str_replace("'","\\'", $item_summary));
			$this->db->query($sql, true);
			
			//drop old items
	        $this->prune_history($user_id);
		}		
    }


    /**
     * param $user_id - The id of the user to retrive the history for
     * param $module_name - Filter the history to only return records from the specified module.  If not specified all records are returned
     * return - return the array of result set rows from the query.  All of the table fields are included
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
	 * All Rights Reserved.
	 * Contributor(s): DrSlump <imontes@netxusfoundries.com>
     */
    function get_recently_viewed($user_id, $module_name = "")
    {
		//Needed for Security check
		include_once('include/utils.php');
		include_once('modules/Users/UserInfoUtil.php');
		
		$sql = "SELECT tracker.item_id, tracker.module_name, tracker.item_summary FROM %s INNER JOIN crmentity ON crmentity.crmid=tracker.item_id WHERE tracker.user_id='%s' %s AND crmentity.deleted=0 ORDER BY tracker.id DESC";
		$sql = sprintf($sql, $this->table_name, $user_id, $module_name?"AND module_name='$module_name'":'');
		
        $this->log->debug("About to retrieve track list: $query");
        $result = $this->db->query($sql, true);
		
        $list = Array(); $num = 1;
        while ($row = $this->db->fetchByAssoc($result, -1, false)) {
			//Unfortunatly the Users module is no integrated with the permission
            //system, so we need to do a dirty check for it
			if($row['module_name'] == "Users") {
				$per = is_admin($GLOBALS['current_user'])?'yes':'no';
			} else {
				$per = isPermitted($row['module_name'], 4, $row['item_id']);
			}
			
			if($per == 'yes') {
				$list[] = $row;
				//check if we have got enough items already
				if (++$num > $GLOBALS['history_max_viewed']) break;
			}
		}
		
		return $list;
	}

	/**
	 * Returns an associative array with names of modules as keys and the
     * number times it appears in an user tracker. In other words, this
     * gives the popularity of a module for an user.
     *
     * @author: DrSlump <imontes@netxusfoundries.com
	 */	
	function get_popular_modules($user_id) {
		$sql = "SELECT  COUNT(*) as popularity, module_name FROM  `tracker` WHERE user_id = '%s' GROUP BY module_name ORDER BY popularity DESC";
		$rs = $this->db->query( sprintf($sql, $user_id) );
		$mods = array();
		while ($row = $this->db->fetchByAssoc($rs, -1, false)) {
			$mods[ $row['module_name'] ] = $row['popularity'];
		}
		return $mods;
	}

    /**
     * INTERNAL -- This method cleans out any entry for a record for a user.
     * It is used to remove old occurances of previously viewed items.
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 	 * All Rights Reserved.
	 * Contributor(s): DrSlump <imontes@netxusfoundries.com>
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
	 * Contributor(s): DrSlump <imontes@netxusfoundries.com>
     *
     * DrSlump:
     *   Now it uses a pseudo garbage collector, get_recently_viewed() now
     *   checks for $history_max_viewed so we don't need to do the cleanup
     *   on each insert
     */
    function prune_history($user_id)
    {
		//we have a 10% probability it'll be 0 and the cleanup is run
		if (! rand(0,10)) {
			
			//get all modules which have passed the maximum history for the given user
			$sql = "SELECT module_name FROM %s WHERE user_id=%s GROUP BY module_name HAVING COUNT(*) > %d";
			$rs = $this->db->query( sprintf($sql, $this->table_name, $user_id, $GLOBALS['history_max_viewed']) );
			$modules = array();
			while (list($module) = $this->db->getNextRow($rs)) {
				$modules[] = $module;
			}
			
			foreach ($mods as $module) {
				
				//we need to find the ID of the first tracker item over the
                //max history. This means we have to delete all the IDs smaller
                //than the guessed one.
                $sql = "SELECT id FROM %s WHERE user_id=%s AND module_name='%s' ORDER BY id DESC";
				$sql = sprintf($sql, $this->table_name, $user_id, $module);
				$rs = $this->db->limitQuery($sql, 1, $GLOBALS['history_max_viewed']);
				list($id) = $this->db->fetchByAssoc($rs, -1, false);
				
				$sql = "DELETE FROM %s WHERE user_id=%s AND module_name='%s' AND id < %s";
				$this->db->query( sprintf($sql, $this->table_name, $user_id, $module, $id) );
			}
		}		
    }

	function create_tables() {
		/*$query = 'CREATE TABLE '.$this->table_name.' (';
		$query = $query.'id int( 11 ) NOT NULL auto_increment';
		$query = $query.', user_id char(36)';
		$query = $query.', module_name char(25)';
		$query = $query.', item_id char(36)';
		$query = $query.', item_summary char(255)';
		$query = $query.', PRIMARY KEY ( ID ) )';



		$this->db->query($query);*/

	}

	function drop_tables () {
		/*$query = 'DROP TABLE IF EXISTS '.$this->table_name;

		$this->db->query($query);

		//TODO Clint 4/27 - add exception handling logic here if the table can't be dropped.
		*/

	}
}



?>

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

require_once('database/DatabaseConnection.php');
include_once('config.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once('data/SugarBean.php');
//include_once('modules/MessageBoard/includes/template.php');
include_once('modules/MessageBoard/mods/phpbb_fetch_all/posts.php');
include_once('modules/MessageBoard/mods/phpbb_fetch_all/common.php');
//include_once('modules/MessageBoard/includes/functions.php');
//include_once('modules/MessageBoard/includes/bbcode.php');
include_once('modules/MessageBoard/db/mysql.php');

class MessageBoard extends SugarBean {
	var $topic_title;	
	var $forum_name;
	var $username;
	var $topic_replies;
	var $post_time;
	var $table_name = "MessageBoard";
	var $object_name = "MessageBoard";
	var $new_schema = true;
	var $column_fields = Array(
		"topic_title",
		"forum_name",
		"username",
		"topic_replies",
		"post_time");

	var $list_fields = Array('topic_id','post_id','topic_title', 'forum_name', 'username', 'topic_replies' , 'post_time');

	function MessageBoard() {
		$this->log = LoggerManager::getLogger('messageboard');
		$this->db = new PearDatabase();
	}

	function create_tables () {
		$query = 'CREATE TABLE '.$this->table_name.' ( ';
		$query .='id char(36) NOT NULL';
		$query .=', date_entered datetime NOT NULL';
		$query .=', description text';
		$query .=', deleted bool NOT NULL default 0';
		$query .=', converted bool NOT NULL default 0';
		$query .=', PRIMARY KEY ( id ) )';

		$this->log->info($query);

		mysql_query($query);

	}

	function drop_tables () {
		$query = 'DROP TABLE IF EXISTS '.$this->table_name;

		$this->log->info($query);

		mysql_query($query);
	}

	// This method is used to provide backward compatibility with old data that was prefixed with http://
	// We now automatically prefix http://
	function remove_redundant_http()
	{
		if(eregi("http://", $this->website))
		{
			$this->website = substr($this->website, 7);
		}
	}

}



?>

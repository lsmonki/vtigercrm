<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
* 
 ********************************************************************************/

class FileStorage
{
var $table_name = "filestorage";

	function FileStorage() {
		$this->log = LoggerManager::getLogger('filestorage');
	}


function create_tables () 
{
		/*$query = 'CREATE TABLE '.$this->table_name.' ( ';
		$query .='fileid varchar(50) NOT NULL';
		$query .=', date_entered datetime NOT NULL';
		$query .=', parent_type varchar(50) NOT NULL';
		$query .=', parent_id varchar(100) NOT NULL';
		$query .=', data longblob NOT NULL';
		$query .=', description tinytext';
		$query .=', filename varchar(50) NOT NULL';
		$query .=', filesize varchar(50) NOT NULL';
		$query .=', filetype varchar(20) NOT NULL';
	        $query .=', PRIMARY KEY ( fileid ) )';

		$this->log->info($query);

		mysql_query($query);*/
}

	function drop_tables () {
		/*$query = 'DROP TABLE IF EXISTS '.$this->table_name;

		$this->log->info($query);

		mysql_query($query);

	// - add exception handling logic here if the table can't be dropped.*/

	}
}

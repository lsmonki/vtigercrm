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

require_once('include/logging.php');
	

class PearDatabase{
	var $database = null;
	var $dieOnError = false;
	var $dbType = null;
	var $dbHostName = null;
	var $dbName = null;
	var $dbOptions = null;
	var $userName=null;
	var $userPassword=null;
	var $query_time = 0;
	var $log = null;
	var $lastmysqlrow = -1;

	


	function setDieOnError($value){
		$this->dieOnError = $value;
	}
	
	function setDatabaseType($type){
		$this->dbType = $type;
	}
	
	function setUserName($name){
		$this->userName = $name;
	}
	
	function setOption($name, $value){
		if(isset($this->dbOptions))
			$this->dbOptions[$name] = $value;
		if(isset($this->database))
			$this->database->setOption($name, $value);
	}	
	
	function setUserPassword($pass){
		$this->userPassword = $pass;	
	}
	
	function setDatabaseName($db){
		$this->dbName = $db;	
	}
	
	function setDatabaseHost($host){
		$this->dbHostName = $host;	
	}
	
	function getDataSourceName(){
		return 	$this->dbType. "://".$this->userName.":".$this->userPassword."@". $this->dbHostName . "/". $this->dbName;
	}
	
	function checkError($msg='', $dieOnError=false){
		if($this->dbType == "mysql"){
			if (mysql_errno()){
			if($this->dieOnError || $dieOnError){
         	 	$this->log->fatal("MySQL error ".mysql_errno().": ".mysql_error());	
				die ($msg."MySQL error ".mysql_errno().": ".mysql_error());
				
			}else{
				$this->log->error("MySQL error ".mysql_errno().": ".mysql_error());	
			}
			return true;
			}
			return false;
		}	
			else{
				if(!isset($this->database)){
					$this->log->error("Database Is Not Connected");
					return true;
				}
				if(DB::isError($this->database)){
					
					if($this->dieOnError || $dieOnError){
						$this->log->fatal($msg.$this->database->getMessage());
						 die ($msg.$this->database->getMessage());	
					}else{
						$this->log->error($msg.$this->database->getMessage());		
					}
					return true;
				}
		}return false;
		
	}
	
	
	/**
	* @return void
	* @desc checks if a connection exists if it does not it closes the connection
	 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
	 * All Rights Reserved..
	 * Contributor(s): ______________________________________..
	*/
	function checkConnection(){
			if(!isset($this->database))
				$this->connect(false);
	}
	
	function query($sql, $dieOnError=false, $msg=''){
		$this->log->info('Query:' . $sql);
		$this->checkConnection();
		$this->query_time = microtime();
		if($this->dbType == "mysql"){
			$result =& mysql_query($sql);
			$this->lastmysqlrow = -1; 	
		}else{
			$result =& $this->database->query($sql);
		}
		$this->query_time = microtime() - $this->query_time;
		$this->log->info('Query Execution Time:'.$this->query_time);
		$this->checkError($msg.' Query Failed:' . $sql . '::', $dieOnError);	
		return $result;
	}
	
	function limitQuery($sql,$start,$count, $dieOnError=false, $msg=''){
		if($this->dbType == "mysql")
			 return $this->query("$sql LIMIT $start,$count", $dieOnError, $msg);
		$this->log->info('Limit Query:' . $sql. ' Start: ' .$start . ' count: ' . $count);
		$this->lastsql = $sql;
		
		$this->checkConnection();
		$this->query_time = microtime();
		$result =& $this->database->limitQuery($sql,$start, $count);
		$this->query_time = microtime() - $this->query_time;
		$this->log->info('Query Execution Time:'.$this->query_time);
		$this->checkError($msg.' Query Failed:' . $sql . '::', $dieOnError);	
		return $result;
	}
	
	function getOne($sql, $dieOnError=false, $msg=''){
		$this->log->info('Get One:' . $sql);
		$this->checkConnection();
		if($this->dbType == "mysql"){
			$queryresult =& $this->query($sql, $dieOnError, $msg);
			$result =& mysql_result($queryresult,0);
		}else{
			$result =& $this->database->getOne($sql);
		}
		$this->checkError($msg.' Get One Failed:' . $sql . '::', $dieOnError);	
		return $result;
	}

	function getFieldsArray(&$result)
	{
		$field_array = array();

		if(! isset($result) || empty($result))
		{
			return 0;
		}

		if($this->dbType == "mysql")
		{
			$i = 0;
			while ($i < mysql_num_fields($result)) 
			{
   				$meta = mysql_fetch_field($result, $i);

   				if (!$meta) 
				{
					return 0;
   				}
					
				array_push($field_array,$meta->name);

   				$i++;
			}
		}
		else
		{
			$arr = tableInfo($result);
			foreach ($arr as $index=>$subarr)
			{
				array_push($field_array,$subarr['name']);	
			}
	
		}

		return $field_array;
			
	}
	
	function getRowCount(&$result){
		if(isset($result) && !empty($result))
			if($this->dbType == "mysql"){
				return mysql_numrows($result);
			}else{
				 return $result->numRows();
			}
		return 0;
			
	}
	function getAffectedRowCount(&$result){
			if($this->dbType == "mysql"){
				return mysql_affected_rows();
			}
			else {
				return $result->affectedRows();
			}
		return 0;
			
	}
	function requireSingleResult($sql, $dieOnError=false,$msg='', $encode=true){
			$result = $this->query($sql, $dieOnError, $msg);
		
			if($this->getRowCount($result ) == 1)
				return to_html($result, $encode);
			$this->log->error('Rows Returned:'. $this->getRowCount($result) .' More than 1 row returned for '. $sql);
			return '';
	}
	
	
	
	function fetchByAssoc(&$result, $rowNum = -1, $encode=true){
		if(isset($result) && $rowNum < 0){
			if($this->dbType == "mysql"){
				$row = mysql_fetch_assoc($result);
				
				if($encode&& is_array($row))return array_map('to_html', $row);	
				return $row;
			}
			$row = $result->fetchRow(DB_FETCHMODE_ASSOC);	
		}
		if($this->dbType == "mysql"){
				if($this->getRowCount($result) > $rowNum){

					mysql_data_seek($result, $rowNum);	
				}
				$this->lastmysqlrow = $rowNum;
			
				$row = mysql_fetch_assoc($result);
				
				if($encode&& is_array($row))return array_map('to_html', $row);	
				return $row;
				
		}
		$row = $result->fetchRow(DB_FETCHMODE_ASSOC, $rowNum);
		if($encode)return array_map('to_html', $row);	
		return $row;
	}
	
	function getNextRow(&$result, $encode=true){
		if(isset($result)){
			$row = $result->fetchRow();
			if($encode&& is_array($row))return array_map('to_html', $row);	
				return $row;
			
		}
		return null;
	}
	
	
	function getQueryTime(){
		return $this->query_time;	
	}
	/*function execute($stmt, $data, $dieOnError=false, $msg=''){
		$this->log->info('Executing:'.$stmt);
		$this->checkConnection();
		$this->query_time = microtime();
		$prepared	= $this->database->prepare($stmt);
		$result = execute($stmt, $data);
		$this->query_time = microtime() - $this->query_time;
		//$this->log->info('Query Execution Time:'.$this->query_time);
		$this->checkError('Execute Failed:' . $stmt. '::', $dieOnError);
		return $result;	
	}*/
		
	
	
	function connect($dieOnError = false){
		global $dbconfigoption;
		if($this->dbType == "mysql" && $dbconfigoption['persistent'] == true){
			$this->database =@mysql_pconnect($this->dbHostName,$this->userName,$this->userPassword);
			@mysql_select_db($this->dbName) or die( "Unable to select database");				
			if(!$this->database){
				$this->connection = mysql_connect($this->dbHostName,$this->userName,$this->userPassword) or die("Could not connect to server ".$this->dbHostName." as ".$this->userName.".".mysql_error());
				if($this->connection == false && $dbconfigoption['persistent'] == true){
					$_SESSION['administrator_error'] = "<B>Severe Performance Degradation: Persistent Database Connections not working.  Please set \$dbconfigoption['persistent'] to false in your config.php file</B>";  			
				}	
			}
		}
		else $this->database = DB::connect($this->getDataSourceName(), $this->dbOptions);
		if($this->checkError('Could Not Connect:', $dieOnError))
			$this->log->info("connected to db");
			
	}
	function PearDatabase(){
			global $currentModule;
			$this->log =& LoggerManager::getLogger('PearDatabase_'. $currentModule);
			$this->resetSettings();
			
	}
	function resetSettings(){
		global $dbconfig, $dbconfigoption;
		$this->disconnect();
		$this->setDatabaseType($dbconfig['db_type']);
		$this->setUserName($dbconfig['db_user_name']);
		$this->setUserPassword($dbconfig['db_password']);
		$this->setDatabaseHost( $dbconfig['db_host_name']);
		$this->setDatabaseName($dbconfig['db_name']);
		$this->dbOptions = $dbconfigoption;
		if($this->dbType != "mysql"){
			require_once( 'DB.php' );	
		}
			
		
	}
	
function quote($string){
	global $dbconfig;
	if($dbconfig['db_type'] == 'mysql'){
		$string = mysql_escape_string($string);
	}else {$string = quoteSmart($string);}
	//$string = strtr($string, array('_' => '\_', '%'=>'\%'));
	return $string;
}


function disconnect() {
		if(isset($this->database)){
			if($this->dbType == "mysql"){
				mysql_close($this->database);
			}else{
				$this->database->disconnect();
			}
			unset($this->database);
		}
		
}
	
}
	

?>

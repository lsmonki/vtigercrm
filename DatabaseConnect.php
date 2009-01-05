<?php

include_once('adodb/adodb.inc.php');

$CONFIG = Array();
$CONFIG['hostname'] = $host;
$CONFIG['database'] = $dbname;
$CONFIG['username'] = $username;
$CONFIG['password'] = $passwd;
$CONFIG['dbtype'] = $dbtype;

$adb = new DatabaseConnect($CONFIG);

class DatabaseConnect {
	var $dieOnError = false;
	var $database;
	var $dbtype;

	function isMySQL() { return $this->dbtype=='mysql'; }
	function isPostgres() { return $this->dbtype=='pgsql'; }
 	function __construct($config) {
		$this->dbtype = $config['dbtype'];
		$this->database = ADONewConnection($this->dbtype);
		$this->database->PConnect(
			$config['hostname'], $config['username'],
			$config['password'], $config['database']);
	}

	function query($sql) {
		$result = $this->database->Execute($sql);
		//if(!$result && $this->dieOnError) die($sql." - ".$this->database->ErrorMsg());
		return $result;
	}

	function getUniqueID($seqname)
	{
		return $this->database->GenID($seqname."_seq",1);
	}

	function pquery($sql, $params) {
		$params = $this->flatten_array($params);
		$result = $this->database->Execute($sql,$params);
		//if(!$result && $this->dieOnError) die($sql." - ".$this->database->ErrorMsg());
		return $result;		
	}
	
	/**
	 * Flatten the composite array into single value.
	 * Example:
	 * $input = array(10, 20, array(30, 40), array('key1' => '50', 'key2'=>array(60), 70));
	 * returns array(10, 20, 30, 40, 50, 60, 70);
	 */
	function flatten_array($input, $output=null) {
		if($input == null) return null;
		if($output == null) $output = array();
		foreach($input as $value) {
			if(is_array($value)) {	
				$output = $this->flatten_array($value, $output);
			} else {	
				array_push($output, $value);
			}
		}
		return $output;
	}

	function query_result($result, $row, $column) {
		$rowdata = $this->fetch_array($result, $row);
		if(isset($this->showrow)) {
			$file = fopen('migtest.txt', 'a');
			fwrite($file, var_export($rowdata, true));
			fwrite($file, "CHECK FOR $column and " . $rowdata[$column]);
			fclose($file);
		}
		return $rowdata? $rowdata[$column] : false;
	}

	function fetch_array($result, $row=0) {
		$result->Move($row);
		if($result->EOF) return false;
		$rowdata = $result->FetchRow();		
		return $rowdata;
	}

	function num_rows($result) {
		if($result) return $result->RecordCount();
		return false;
	}

	function __destruct() {
		$this->database->disconnect();
		unset($this->database);
	}
	
	function sql_escape_string($str)
	{
		if($this->isMySql())
			$result_data = mysql_real_escape_string($str);
		elseif($this->isPostgres())
			$result_data = pg_escape_string($str);
			
		return $result_data;
	}
   
	function get_tables()
	{
		$result = & $this->database->MetaTables('TABLES');
		return $result;		
	}

}
?>
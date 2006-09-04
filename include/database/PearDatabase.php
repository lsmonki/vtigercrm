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
include('adodb/adodb.inc.php');
require_once("adodb/adodb-xmlschema.inc.php");

$log =& LoggerManager::getLogger('VT');

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
    var $enableSQLlog = false;

    function isMySQL() { return dbType=='mysql'; }
    function isOracle() { return dbType=='oci8'; }
    
    function println($msg)
    {
	require_once('include/logging.php');
	$log1 =& LoggerManager::getLogger('VT');
	if(is_array($msg))
	{
	    $log1->info("PearDatabse ->".print_r($msg,true));
	}
	else
	{
	    $log1->info("PearDatabase ->".$msg);
	}
	return $msg;
    }

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

    function startTransaction()
    {
	$this->checkConnection();
	$this->println("TRANS Started");
	$this->database->StartTrans();
    }

    function completeTransaction()
    {		
	if($this->database->HasFailedTrans()) 
	    $this->println("TRANS  Rolled Back");
	else
	    $this->println("TRANS  Commited");
	
	$this->database->CompleteTrans();
	$this->println("TRANS  Completed");
    }

/* ADODB converted	
 *    function checkError($msg='', $dieOnError=false)
 *    {
 *	if($this->dbType == "mysql")
 *	{
 *	    if (mysql_errno())
 *	    {
 *		if($this->dieOnError || $dieOnError)
 *		{
 *		    $this->log->fatal("MySQL error ".mysql_errno().": ".mysql_error());	
 *		    die ($msg."MySQL error ".mysql_errno().": ".mysql_error());
 *		} else {
 *		    $this->log->error("MySQL error ".mysql_errno().": ".mysql_error());	
 *		}
 *		return true;
 *	    }
 *	    return false;
 *	}	
 *	else
 *	{
 *	    if(!isset($this->database))
 *	    {
 *		$this->log->error("Database Is Not Connected");
 *		return true;
 *	    }
 *	    if(DB::isError($this->database))
 *	    {
 *		if($this->dieOnError || $dieOnError)
 *		{
 *		    $this->log->fatal($msg.$this->database->getMessage());
 *		    die ($msg.$this->database->getMessage());	
 *		} else {
 *		    $this->log->error($msg.$this->database->getMessage());		
 *		}
 *		return true;
 *	    }
 *	}
 *	return false;
 *   }
 */
    
    function checkError($msg='', $dieOnError=false)
    {
/*
 *	if($this->database->ErrorNo())
 *	{
 *	    if($this->dieOnError || $dieOnError)
 *	    {
 *		$this->println("ADODB error ".$this->database->ErrorNo());	
 *		die ($msg."ADODB error ".$this->database->ErrorNo());
 *	    } else {
 *		$this->log->error("MySQL error ".mysql_errno().": ".mysql_error());
 *	    }
 *	    return true;
 *	}
 */
	
	if($this->dieOnError || $dieOnError)
	{
	    $this->println("ADODB error ".$msg."->[".$this->database->ErrorNo()."]".$this->database->ErrorMsg());	
	    die ($msg."ADODB error ".$msg."->".$this->database->ErrorMsg());
	}
	else
	{
	    $this->println("ADODB error ".$msg."->[".$this->database->ErrorNo()."]".$this->database->ErrorMsg());
	}
	return false;
    }

    function change_key_case($arr)
    {
	return is_array($arr)?array_change_key_case($arr):$arr;
    }

    var $req_flist;	
    
    /**
    * @return void
    * @desc checks if a connection exists if it does not it closes the connection
     * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc..
     * All Rights Reserved..
     * Contributor(s): ______________________________________..
    */
    function checkConnection(){
	global $log;

	if(!isset($this->database))
	{
	    $this->println("TRANS creating new connection");
/*
 *	    $flist=get_included_files();
 *	    foreach($flist as $key=>$value)
 *	    {
 *		if(!strstr($value,'\\modules') && !strstr($value,'\\data'))
 *		unset($flist[$key]);
 *	    }
 *	    $this->println($flist);
 */
	    $this->connect(false);
	}
	else
	{
	    //$this->println("checkconnect using old connection");
	}
    }

/* ADODB converted	
 *    function query($sql, $dieOnError=false, $msg='')
 *    {
 *	$this->println("query ".$sql);
 *	$this->log->info('Query:' . $sql);
 *	$this->checkConnection();
 *	$this->query_time = microtime();
 *	if($this->dbType == "mysql")
 *	{
 *	    $result =& mysql_query($sql);
 *	    $this->lastmysqlrow = -1; 	
 *	} else {
 *	    $result =& $this->database->query($sql);
 *	}
 *	$this->query_time = microtime() - $this->query_time;
 *	$this->log->info('Query Execution Time:'.$this->query_time);
 *	$this->checkError($msg.' Query Failed:' . $sql . '::', $dieOnError);	
 *	return $result;
 *    }
 */

    function query($sql, $dieOnError=false, $msg='')
    {
	global $log;
	//$this->println("ADODB query ".$sql);		
	$log->debug('query being executed : '.$sql);
	$this->checkConnection();
	$result = & $this->database->Execute($sql);
	$this->lastmysqlrow = -1;
	if(!$result)$this->checkError($msg.' Query Failed:' . $sql . '::', $dieOnError);
	return $result;		
    }

    function getEmptyBlob()
    {
	//if(dbType=="oci8") return 'empty_blob()';
	//else return 'null';
	return 'null';
    }

    function updateBlob($tablename, $colname, $id, $data)	
    {
	$this->println("updateBlob t=".$tablename." c=".$colname." id=".$id);
	$this->checkConnection();
	$result = $this->database->UpdateBlob($tablename, $colname, $data, $id);
	$this->println("updateBlob t=".$tablename." c=".$colname." id=".$id." status=".$result);
	return $result;
    }

    function updateBlobFile($tablename, $colname, $id, $filename)	
    {
	$this->println("updateBlobFile t=".$tablename." c=".$colname." id=".$id." f=".$filename);
	$this->checkConnection();
	$result = $this->database->UpdateBlobFile($tablename, $colname, $filename, $id);
	$this->println("updateBlobFile t=".$tablename." c=".$colname." id=".$id." f=".$filename." status=".$result);
	return $result;
    }

/* ADODB converted
 *    function limitQuery($sql,$start,$count, $dieOnError=false, $msg='')
 *    {
 *	if($this->dbType == "mysql")
 *	    return $this->query("$sql LIMIT $start,$count", $dieOnError, $msg);
 *	$this->log->info('Limit Query:' . $sql. ' Start: ' .$start . ' count: ' . $count);
 *	$this->lastsql = $sql;
 *	
 *	$this->checkConnection();
 *	$this->query_time = microtime();
 *	$result =& $this->database->limitQuery($sql,$start, $count);
 *	$this->query_time = microtime() - $this->query_time;
 *	$this->log->info('Query Execution Time:'.$this->query_time);
 *	$this->checkError($msg.' Query Failed:' . $sql . '::', $dieOnError);	
 *	return $result;
 *    }
 */
    
    function limitQuery($sql,$start,$count, $dieOnError=false, $msg='')
    {
	global $log;
	//$this->println("ADODB limitQuery sql=".$sql." st=".$start." co=".$count);
	$log->debug(' limitQuery sql = '.$sql .' st = '.$start .' co = '.$count);
	$this->checkConnection();
	$result =& $this->database->SelectLimit($sql,$count,$start);
	if(!$result) $this->checkError($msg.' Limit Query Failed:' . $sql . '::', $dieOnError);
	return $result;		
    }
    
/* ADODB converted
 *   function getOne($sql, $dieOnError=false, $msg='')
 *   {
 *	$this->log->info('Get One:' . $sql);
 *	$this->checkConnection();
 *	if($this->dbType == "mysql"){
 *	    $queryresult =& $this->query($sql, $dieOnError, $msg);
 *	    $result =& mysql_result($queryresult,0);
 *	} else {
 *	    $result =& $this->database->getOne($sql);
 *	}
 *	$this->checkError($msg.' Get One Failed:' . $sql . '::', $dieOnError);	
 *	return $result;
 *    }
 */

    function getOne($sql, $dieOnError=false, $msg='')
    {
	$this->println("ADODB getOne sql=".$sql);
	$this->checkConnection();
	$result =& $this->database->GetOne($sql);
	if(!$result) $this->checkError($msg.' Get one Query Failed:' . $sql . '::', $dieOnError);
	return $result;		
    }

/* ADODB converted
 *    function getFieldsArray(&$result)
 *    {
 *	$field_array = array();
 *
 *	if(! isset($result) || empty($result))
 *	{
 *	    return 0;
 *	}
 *
 *	if($this->dbType == "mysql")
 *	{
 *	    $i = 0;
 *	    while ($i < mysql_num_fields($result)) 
 *	    {
 *		$meta = mysql_fetch_field($result, $i);
 *
 *		if (!$meta) 
 *		{
 *		    return 0;
 *		}
 *			
 *		array_push($field_array,$meta->name);
 *
 *		$i++;
 *	    }
 *	}
 *	else
 *	{
 *	    $arr = tableInfo($result);
 *	    foreach ($arr as $index=>$subarr)
 *	    {
 *		array_push($field_array,$subarr['name']);	
 *	    }
 *	}
 *
 *	return $field_array;
 *    }
 */

    function getFieldsArray(&$result)
    {
	//$this->println("ADODB getFieldsArray");
	$field_array = array();
	if(! isset($result) || empty($result))
	{
	    return 0;
	}

	$i = 0;
	$n = $result->FieldCount();
	while ($i < $n) 
	{
	    $meta = $result->FetchField($i);
	    if (!$meta) 
	    {
		return 0;
	    }
	    array_push($field_array,$meta->name);
	    $i++;
	}

	//$this->println($field_array);
	return $field_array;			
    }
    
/* ADODB Converted
 *    function getRowCount(&$result)
 *    {
 *	if(isset($result) && !empty($result))
 *	    if($this->dbType == "mysql"){
 *		return mysql_numrows($result);
 *	    } else {
 *		 return $result->numRows();
 *	    }
 *	return 0;
 *    }
 */
    
    function getRowCount(&$result){
	global $log;
	//$this->println("ADODB getRowCount");
	if(isset($result) && !empty($result))
	    $rows= $result->RecordCount();			
	//$this->println("ADODB getRowCount rows=".$rows);	
	//$log->debug('getRowCount rows= '.$rows);
	return $rows;			
    }

    /* ADODB newly added. replacement for mysql_num_rows */
    function num_rows(&$result)
    {
	return $this->getRowCount($result);
    }

    /* ADODB newly added. replacement form mysql_num_fields */
    function num_fields(&$result)
    {
	return $result->FieldCount();
    }

    /* ADODB newly added. replacement for mysql_fetch_array() */
    function fetch_array(&$result)
    {
	if($result->EOF)
	{
	    //$this->println("ADODB fetch_array return null");
	    return NULL;
	}		
	return $this->change_key_case($result->FetchRow());
    }

    /* ADODB newly added. replacement for mysql_result() */
    function query_result(&$result, $row, $col=0)
    {		
	//$this->println("ADODB query_result r=".$row." c=".$col);
	$result->Move($row);
	$rowdata = $this->change_key_case($result->FetchRow());
	//$this->println($rowdata);
	//Commented strip_selected_tags and added to_html function for HTML tags vulnerability
	//$coldata = strip_selected_tags($rowdata[$col],'script');
	$coldata = to_html($rowdata[$col]);
	//$this->println("ADODB query_result ". $coldata);
	return $coldata;
    }

/* ADODB Converted	
 *    function getAffectedRowCount(&$result)
 *    {
 *	if($this->dbType == "mysql"){
 *		return mysql_affected_rows();
 *	}
 *	else {
 *		return $result->affectedRows();
 *	}
 *	return 0;
 *    }
 */

    function getAffectedRowCount(&$result)
    {
	global $log;
	//$this->println("ADODB getAffectedRowCount");
	$log->debug('getAffectedRowCount');
	$rows =$this->database->Affected_Rows(); 
	//$this->println("ADODB getAffectedRowCount rows=".rows);
	$log->debug('getAffectedRowCount rows = '.$rows);
	return $rows;
    }

/* ADODB converted
 *    function requireSingleResult($sql, $dieOnError=false,$msg='', $encode=true){
 *	$result = $this->query($sql, $dieOnError, $msg);
 *
 *	if($this->getRowCount($result ) == 1)
 *	    return to_html($result, $encode);
 *	$this->log->error('Rows Returned:'. $this->getRowCount($result) .' More than 1 row returned for '. $sql);
 *	return '';
 *    }
 */

    function requireSingleResult($sql, $dieOnError=false,$msg='', $encode=true)
    {
	$result = $this->query($sql, $dieOnError, $msg);

	if($this->getRowCount($result ) == 1)				
	    return $result;
	$this->log->error('Rows Returned:'. $this->getRowCount($result) .' More than 1 row returned for '. $sql);
	return '';
    }
    

/* ADODB converted	
 *    function fetchByAssoc(&$result, $rowNum = -1, $encode=true)
 *    {
 *	if(isset($result) && $rowNum < 0)
 *	{
 *	    if($this->dbType == "mysql"){
 *		$row = mysql_fetch_assoc($result);
 *		
 *		if($encode&& is_array($row))
 *		    return array_map('to_html', $row);	
 *		return $row;
 *	    }
 *	    $row = $result->fetchRow(DB_FETCHMODE_ASSOC);	
 *	}
 *	if($this->dbType == "mysql"){
 *	    if($this->getRowCount($result) > $rowNum){
 *		mysql_data_seek($result, $rowNum);	
 *	    }
 *	    $this->lastmysqlrow = $rowNum;
 *    
 *	    $row = mysql_fetch_assoc($result);
 *	    
 *	    if($encode&& is_array($row))
 *		return array_map('to_html', $row);	
 *	    return $row;
 *	}
 *	$row = $result->fetchRow(DB_FETCHMODE_ASSOC, $rowNum);
 *	if($encode)
 *	    return array_map('to_html', $row);	
 *	return $row;
 *    }
 */

    function fetchByAssoc(&$result, $rowNum = -1, $encode=true)
    {
	//$this->println("ADODB fetchByAssoc ".$rowNum." fetch mode=".$adb->database->$ADODB_FETCH_MODE);
	if($result->EOF)
	{
	    $this->println("ADODB fetchByAssoc return null");
	    return NULL;
	}
	if(isset($result) && $rowNum < 0)
	{			
	    $row = $this->change_key_case($result->GetRowAssoc(false));			
	    $result->MoveNext();			
	    //print_r($row);
	    //$this->println("ADODB fetchByAssoc r< 0 isarray r=".is_array($row)." r1=".is_array($row[1]));			
	    //$this->println($row);
	    if($encode&& is_array($row))
		return array_map('to_html', $row);
	    //$this->println("ADODB fetchByAssoc r< 0 not array r1=".$row[1]);			
	    return $row;			
	}

	//$this->println("ADODB fetchByAssoc after if ".$rowNum);	
	
	if($this->getRowCount($result) > $rowNum)
	{
	    $result->Move($rowNum);				
	}

	$this->lastmysqlrow = $rowNum; //srini - think about this
	$row = $this->change_key_case($result->GetRowAssoc(false));		
	$result->MoveNext();
	//print_r($row);		
	$this->println($row);
			
	if($encode&& is_array($row))
	    return array_map('to_html', $row);	
	return $row;
    }
    
/* ADODB converted
 *    function getNextRow(&$result, $encode=true)
 *    {
 *	if(isset($result)){
 *	    $row = $result->fetchRow();
 *	    if($encode&& is_array($row))
 *		return array_map('to_html', $row);	
 *	    return $row;
 *	}
 *	return null;
 *    }
 */
    
    function getNextRow(&$result, $encode=true){
	global $log;

	//$this->println("ADODB getNextRow");
	$log->info('getNextRow');
	if(isset($result)){
	    $row = $this->change_key_case($result->FetchRow());
	    if($row && $encode&& is_array($row))
		return array_map('to_html', $row);	
	    return $row;
	}
	return null;
    }

    function fetch_row(&$result, $encode=true)
    {
	return $this->getNextRow($result);
    }

    function field_name(&$result, $col)
    {
	return $result->FetchField($col);
    }
    
    function getQueryTime(){
	return $this->query_time;	
    }

/*
 *    function execute($stmt, $data, $dieOnError=false, $msg=''){
 *	$this->log->info('Executing:'.$stmt);
 *	$this->checkConnection();
 *	$this->query_time = microtime();
 *	$prepared	= $this->database->prepare($stmt);
 *	$result = execute($stmt, $data);
 *	$this->query_time = microtime() - $this->query_time;
 *	//$this->log->info('Query Execution Time:'.$this->query_time);
 *	$this->checkError('Execute Failed:' . $stmt. '::', $dieOnError);
 *	return $result;	
 *    }
 */
	    
    
/* adodb converted
 *    function connect($dieOnError = false){
 *	$this->println("connect");
 *	global $dbconfigoption;
 *	if($this->dbType == "mysql" && $dbconfigoption['persistent'] == true){
 *	    $this->database =@mysql_pconnect($this->dbHostName,$this->userName,$this->userPassword);
 *	    @mysql_select_db($this->dbName) or die( "Unable to select database");				
 *	    if(!$this->database){
 *		$this->connection = mysql_connect($this->dbHostName,$this->userName,$this->userPassword) or die("Could not connect to server ".$this->dbHostName." as ".$this->userName.".".mysql_error());
 *		if($this->connection == false && $dbconfigoption['persistent'] == true){
 *		    $_SESSION['administrator_error'] = "<B>Severe Performance Degradation: Persistent Database Connections not working.  Please set \$dbconfigoption['persistent'] to false in your config.php file</B>";  			
 *		}	
 *	    }
 *	}
 *	else $this->database = DB::connect($this->getDataSourceName(), $this->dbOptions);
 *	if($this->checkError('Could Not Connect:', $dieOnError))
 *		$this->log->info("connected to db");
 *		
 *    }
 */
    
    function connect($dieOnError = false)
    {
	//$this->println("ADODB connect");
	global $dbconfigoption,$dbconfig;
	//$this->println("ADODB type=".$this->dbType." host=".$this->dbHostName." dbname=".$this->dbName." user=".$this->userName." password=".$this->userPassword);

/*
 *	$driver='mysql';
 *	$server='srinivasan';
 *	$user='root';
 *	$password='';
 *	$database='vtigercrm3_2';
 *
 *	$this->database = ADONewConnection($driver);
 *
 *	#$this->database->debug = true;
 *	$this->println("ADODB status=".$this->database->PConnect($server, $user, $password, $database));
 */

/*
 *	$this->dbHostName="srinivasan:1521";
 *	$this->userName="vt4";
 *	$this->userPassword="vt4";
 *	$this->dbName="srini";
 *	$this->dbType="oci8";
 */
	
	if(!isset($this->dbType))
	{
	    $this->println("ADODB Connect : DBType not specified");
	    return;
	}
	
	$this->database = ADONewConnection($this->dbType);
	//$this->database->debug = true;
	
	$this->database->PConnect($this->dbHostName, $this->userName, $this->userPassword, $this->dbName);
	$this->database->LogSQL($this->enableSQLlog);
	//$this->database->SetFetchMode(ADODB_FETCH_ASSOC); 
	//$this->println("ADODB type=".$this->dbType." host=".$this->dbHostName." dbname=".$this->dbName." user=".$this->userName." password=".$this->userPassword);		
    }

/*
 *    function PearDatabase(){			
 *	//$this->println("PearDatabase");
 *	global $currentModule;
 *	$this->log =& LoggerManager::getLogger('PearDatabase_'. $currentModule);
 *	$this->resetSettings();
 *   }
 *
 *   function resetSettings(){
 *	global $dbconfig, $dbconfigoption;
 *	$this->disconnect();
 *	$this->setDatabaseType($dbconfig['db_type']);
 *	$this->setUserName($dbconfig['db_username']);
 *	$this->setUserPassword($dbconfig['db_password']);
 *	$this->setDatabaseHost( $dbconfig['db_hostname']);
 *	$this->setDatabaseName($dbconfig['db_name']);
 *	$this->dbOptions = $dbconfigoption;
 *	$this->enableSQLlog = ($dbconfig['log_sql'] == true);
 *	//$this->println("resetSettings log=".$this->enableSQLlog);
 *	//$this->println($dbconfig);
 *	//if($this->dbType != "mysql"){
 *	//	require_once( 'DB.php' );	
 *	//}
 *   }
 */

    function PearDatabase($dbtype='',$host='',$dbname='',$username='',$passwd='')
    {
	//$this->println("PearDatabase");
	global $currentModule;
	$this->log =& LoggerManager::getLogger('PearDatabase_'. $currentModule);
	$this->resetSettings($dbtype,$host,$dbname,$username,$passwd);
    }

    function resetSettings($dbtype,$host,$dbname,$username,$passwd)
    {
	global $dbconfig, $dbconfigoption;
	    
	if($host == '')
	{
	    $this->disconnect();
	    $this->setDatabaseType($dbconfig['db_type']);
	    $this->setUserName($dbconfig['db_username']);
	    $this->setUserPassword($dbconfig['db_password']);
	    $this->setDatabaseHost( $dbconfig['db_hostname']);
	    $this->setDatabaseName($dbconfig['db_name']);
	    $this->dbOptions = $dbconfigoption;
	    if($dbconfig['log_sql'])
	    $this->enableSQLlog = ($dbconfig['log_sql'] == true);
	    //$this->println("resetSettings log=".$this->enableSQLlog);
	    //$this->println($dbconfig);
	    /*if($this->dbType != "mysql"){
		require_once( 'DB.php' );	
	    }*/
	}
	else
	{
	    $this->disconnect();
	    $this->setDatabaseType($dbtype);
	    $this->setDatabaseName($dbname);
	    $this->setUserName($username);
	    $this->setUserPassword($passwd);
	    $this->setDatabaseHost( $host);
	}
    }

    function quote($string){
	return $this->database->qstr($string);	
    }


/* ADODB converted
 *    function disconnect() {
 *	$this->println("disconnect");
 *	if(isset($this->database)){
 *	    if($this->dbType == "mysql"){
 *		mysql_close($this->database);
 *	    } else {
 *		$this->database->disconnect();
 *	    }
 *	    unset($this->database);
 *	}
 *    }
 */

    function disconnect() {
	$this->println("ADODB disconnect");
	if(isset($this->database)){
	    if($this->dbType == "mysql"){
		mysql_close($this->database);
	    } else {
		$this->database->disconnect();
	    }
	    unset($this->database);
	}
    }

    function setDebug($value)
    {
	$this->database->debug = $value;
    }


    // ADODB newly added methods
    function createTables($schemaFile, $dbHostName=false, $userName=false, $userPassword=false, $dbName=false, $dbType=false)
    {
	$this->println("ADODB createTables ".$schemaFile);
	if($dbHostName!=false) $this->dbHostName=$dbHostName;
	if($userName!=false) $this->userName=$userPassword;
	if($userPassword!=false) $this->userPassword=$userPassword;
	if($dbName!=false) $this->dbName=$dbName;
	if($dbType!=false) $this->dbType=$dbType;		

	//$db = ADONewConnection($this->dbType);
	$this->checkConnection();
	$db = $this->database;
	//$db->debug = true;

	//$this->println("ADODB createTables connect status=".$db->Connect($this->dbHostName, $this->userName, $this->userPassword, $this->dbName));
	$schema = new adoSchema( $db );
	//Debug Adodb XML Schema
	$sehema->XMLS_DEBUG = TRUE;
	//Debug Adodb
	$sehema->debug = true;
	$sql = $schema->ParseSchema( $schemaFile );

	$this->println("--------------Starting the table creation------------------");
	//$this->println($sql);

	//integer ExecuteSchema ([array $sqlArray = NULL], [boolean $continueOnErr = NULL])
	$result = $schema->ExecuteSchema( $sql, true );
	if($result)
	print $db->errorMsg();
	// needs to return in a decent way
	$this->println("ADODB createTables ".$schemaFile." status=".$result);
	return $result;
    }

    function createTable($tablename, $flds)
    {
	$this->println("ADODB createTable table=".$tablename." flds=".$flds);
	$this->checkConnection();
	//$dict = NewDataDictionary(ADONewConnection($this->dbType));
	$dict = NewDataDictionary($this->database);
	$sqlarray = $dict->CreateTableSQL($tablename, $flds);
	$result = $dict->ExecuteSQLArray($sqlarray);
	$this->println("ADODB createTable table=".$tablename." flds=".$flds." status=".$result);
	return $result;
    }

    function alterTable($tablename, $flds, $oper)
    {
	$this->println("ADODB alterTableTable table=".$tablename." flds=".$flds." oper=".$oper);
	//$dict = NewDataDictionary(ADONewConnection($this->dbType));
	$this->checkConnection();
	$dict = NewDataDictionary($this->database);
	//$sqlarray = new Array(); 
	
	if($oper == 'Add_Column')
	{
	    $sqlarray = $dict->AddColumnSQL($tablename, $flds);
	}
	else if($oper == 'Delete_Column')
	{
	    $sqlarray = $dict->DropColumnSQL($tablename, $flds);
	}

	$this->println("sqlarray");
	$this->println($sqlarray);

	$result = $dict->ExecuteSQLArray($sqlarray);

	$this->println("ADODB alterTableTable table=".$tablename." flds=".$flds." oper=".$oper." status=".$result);
	return $result;

    }

    function getColumnNames($tablename)
    {
	$this->println("ADODB getColumnNames table=".$tablename);	
	$this->checkConnection();
	$adoflds = $this->database->MetaColumns($tablename);
	//$colNames = new Array();
	$i=0;
	foreach($adoflds as $fld)
	{
	    $colNames[$i] = $fld->name;
	    $i++;
	}
	return $colNames;	
    }

    function formatString($tablename,$fldname, $str)
    {
	//$this->println("ADODB formatString table=".$tablename." fldname=".$fldname." str=".$str);
	$this->checkConnection();
	$adoflds = $this->database->MetaColumns($tablename);
	
	foreach ( $adoflds as $fld )
	{
	    //$this->println("ADODB formatString adofld =".$fld->name);
	    if(strcasecmp($fld->name,$fldname)==0)
	    {
		//$this->println("ADODB formatString fldname=".$fldname." fldtype =".$fld->type);

		$fldtype =strtoupper($fld->type); 	
		if(strcmp($fldtype,'CHAR')==0 || strcmp($fldtype,'VARCHAR') == 0 || strcmp($fldtype,'VARCHAR2') == 0 || strcmp($fldtype,'LONGTEXT')==0 || strcmp($fldtype,'TEXT')==0)
		{
		    //$this->println("ADODB return else normal");
		    return $this->database->Quote($str);
		}
		else if(strcmp($fldtype,'DATE') ==0 || strcmp($fldtype,'TIMESTAMP')==0)
		{
		    return $this->formatDate($str);
		}
		else
		{				
		    return $str;
		}
	    }
	}
	$this->println("format String Illegal field name ".$fldname);
	return $str;
    }

    function formatDate($datetime)
    {
	$this->checkConnection();
	//$db = ADONewConnection($this->dbType);
	$db = &$this->database;
	$date = $db->DBTimeStamp($datetime);
	//if($db->dbType=='mysql') return $this->quote($date);
	return $date;
    }

    function getDBDateString($datecolname)
    {
	$this->checkConnection();
	$db = &$this->database;
	$datestr = $db->SQLDate("Y-m-d, H:i:s" ,$datecolname);
	return $datestr;	
    }

    function getUniqueID($seqname)
    {
	$this->checkConnection();
	return $this->database->GenID($seqname."_seq",1);
    }
    function get_tables()
    {
	$this->checkConnection();
	$result = & $this->database->MetaTables('TABLES');
	$this->println($result);
	return $result;		
    }
} /* End of class */

$adb = new PearDatabase();
$adb->connect();
//$adb->database->setFetchMode(ADODB_FETCH_NUM);


?>

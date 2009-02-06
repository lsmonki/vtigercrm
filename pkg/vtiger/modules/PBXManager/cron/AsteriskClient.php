#!/usr/bin/php
<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
* ("License"); You may not use this file except in compliance with the License
* The Original Code is:  vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
********************************************************************************/

/**
 * this file will be run as a shell script (in linux) or a batch file (under windows).
 * the purpose of the file is to create a master socket which will be connecting to the asterisk server
 * and to keep it (the socket) alive all the time. 
 */

ini_set("include_path", "../../../");
require_once('modules/PBXManager/utils/AsteriskClass.php');
require_once('config.php');
require_once('include/utils/utils.php');
require_once('include/language/en_us.lang.php');

asteriskClient();

/**
 * this function defines the asterisk client
 */
function asteriskClient(){
	global $app_strings, $current_user;
	global $adb, $log;
	
	$data = getAsteriskInfo($adb);
	$server = $data['server'];
	$port = $data['port'];
	$username = $data['username'];
	$password = $data['password'];

	$errno = $errstr = NULL;
	$sock = @fsockopen($server, $port, $errno, $errstr, 1);
	stream_set_blocking($sock, false);
	if( $sock === false ) {
		echo "Socket cannot be created due to error: $errno:  $errstr\n";
		$log->debug("Socket cannot be created due to error:   $errno:  $errstr\n");
		exit(0);
	}else{
		echo "Date: ".date("d-m-Y")."\n";
		echo "Connecting to asterisk server.....\n";
		$log->debug("Connecting to asterisk server.....\n");
	}
	echo "Connected successfully\n\n\n";
	$asterisk = new Asterisk($sock, $server, $port);

	authorizeUser($username, $password, $asterisk);
		
	//keep looping continuosly to check if there are any calls
	while (true) {
		//check for outgoing calls
		$outgoing = checkOutgoingCalls($adb);
		if($outgoing === false){
			//no calls
		}else{
			//calls present :: so connect
			$asterisk->transfer($outgoing['from'],$outgoing['to']);
		}
		
		//check for incoming calls and insert in the database
		$incoming = handleIncomingCalls($asterisk, $adb);
	}
	fclose($sock);
	unset($sock);
}

/**
 * this function checks if there are any outgoing calls for the asterisk server
 * @param $adb - the peardatabase type object
 * @return 	array in the format array(from, to, extension, password) if call exists
 * 			false otherwise
 */
function checkOutgoingCalls($adb){
	$sql = "select * from vtiger_asteriskoutgoingcalls";
	$result = $adb->pquery($sql, array());
	
	if($adb->num_rows($result)>0){
		$call = array();
		$userid = $adb->query_result($result,0,"userid");
		$call['from'] = $adb->query_result($result,0,"from_number");
		$call['to'] = $adb->query_result($result,0,"to_number");
		
		$sql = "delete from vtiger_asteriskoutgoingcalls where userid=?";
		$result = $adb->pquery($sql, array($userid));
		
		$sql = "select * from vtiger_users where id = ?";
		$result = $adb->pquery($sql, array($userid));
		
		if($adb->num_rows($result)>0){
			$call['extension'] = $adb->query_result($result,0,"asterisk_extension");
			$call['password'] = $adb->query_result($result,0,"asterisk_password");
		}else{
			return false;
		}
		return $call;
	}else{
		return false;
	}
}

/**
 * this function checks if there are any incoming calls for the current user
 * if any call is found, it just inserts the values into the vtiger_asteriskincomingcalls table
 * 
 * @param $asterisk - the asterisk object
 * @param $adb - the peardatabase type object
 * @return	incoming call information if successful
 * 			false if unsuccessful
 */
function handleIncomingCalls($asterisk, $adb){
	$response = $asterisk->getAsteriskResponse();
	$callerNumber = "Unknown";
	$callerName = "Unknown";
	
	//event can be both newstate and newchannel :: this is an asterisk bug and can be found at
	//http://lists.digium.com/pipermail/asterisk-dev/2006-July/021565.html
	
	if(($response['Event'] == 'Newstate' || $response['Event'] == 'Newchannel') && $response['State'] == 'Ring'){
		//get the caller information
		if(!empty($response['CallerID'])){
			$callerNumber = $response['CallerID'];
		}elseif(!empty($response['CallerIDNum'])){
			$callerNumber = $response['CallerIDNum'];
		}
		if(!empty($response['CallerIDName'])){
			$callerName = $response['CallerIDName'];
		}
		while(true){
			$response = $asterisk->getAsteriskResponse();
			if(($response['Event'] == 'Newexten') && strstr($response['AppData'],"__DIALED_NUMBER")){
				$temp = array();
				if(strstr($response['Channel'], $callerNumber)){
					$temp = explode("/",$response['Channel']);
					$callerType = $temp[0];
				}
				$temp = explode("=",$response['AppData']);
				$extension = $temp[1];
				
				if(checkExtension($extension, $adb)){
					//insert into database
					$sql = "insert into vtiger_asteriskincomingcalls values (?,?,?,?)";
					$params = array($callerNumber, $callerName, $extension, $callerType);
					$adb->pquery($sql, $params);
					addToCallHistory($extension, $callerNumber, $extension, "incoming", $adb);
				}
			}elseif($response['Event'] == 'Hangup'){
				return true;
			}
		}
	}else{
		return false;
	}
}

/**
 * this function returns the asterisk server information
 * @param $adb - the peardatabase type object
 * @return array $data - contains the asterisk server and port information in the format array(server, port)
 */
function getAsteriskInfo($adb){
	global $log;
	$sql = "select * from vtiger_asterisk";
	$server = "";
	$port = "";	//hard-coded for now
	
	$result = $adb->pquery($sql, array());
	if($adb->num_rows($result)>0){
		$data = array();
		$data['server'] = $adb->query_result($result,0,"server");
		$data['port'] = $adb->query_result($result,0,"port");
		$data['username'] = $adb->query_result($result,0,"username");
		$data['password'] = $adb->query_result($result,0,"password");
		return $data;
	}else{
		$log->debug("Asterisk server settings not specified.\n".
			 		"Change the configuration from vtiger-> Settings-> Softphone Settings\n");
		return false;
	}
}

/**
 * this function takes a XML response and converts it to an array format
 * @param string $response - the xml response
 * @return the xml formatted into an array
 */
function getArray($xml){
	$lines = explode("\r\n", $xml);

	$response = array();
	foreach($lines as $line){
		list($key, $value) = explode(":", $line);
		$response[$key] = $value;
	}
	return $response;	
}

/**
 * this function will authorize the first user from the database that it finds
 * this is required as some user must be authenticated into the asterisk server to
 * receive the events that are being generated by asterisk
 * 
 * @param string $username - the asterisk username
 * @param string $password - the asterisk password
 * @param object $asterisk - asterisk type object
 */
function authorizeUser($username, $password, $asterisk){
	echo "Trying to login to asterisk\n";
	
	if(!empty($username) && !empty($password)){
		$asterisk->setUserInfo($username, $password);
		if( !$asterisk->authenticateUser() ) {
			echo "Cannot login to asterisk using\n
					User: $username\n
					Password: $password\n
					Please check your configuration details.\n";
			exit(0);	
		}else{
			echo "Logged in successfully to asterisk server\n\n";
			return true;
		}
	}else{
		return false;
	}
}

/**
 * this function checks if the given extension is a valid vtiger extension or not
 * if yes it returns true
 * if not it returns false
 * 
 * @param string $ext - the extension to be checked
 * @param object $adb - the peardatabase object
 */
function checkExtension($ext, $adb){
	$sql = "select * from vtiger_asteriskextensions where asterisk_extension='$ext'";
	$result = $adb->pquery($sql, array());
	
	if($adb->num_rows($result)>0){
		return true;
	}else{
		return false;
	}
}
?>

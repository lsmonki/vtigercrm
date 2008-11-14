<?php
	
	require_once("config.inc.php");
	require_once("include/HTTP_Session/Session.php");
	require_once('include/database/PearDatabase.php');
	require_once 'include/Webservices/Utils.php';
	require_once("modules/Users/Users.php");
	require_once("include/Webservices/State.php");
	require_once("include/Webservices/OperationManager.php");
	require_once("include/Webservices/SessionManager.php");
	require_once("include/Zend/Json.php");
	require_once("include/Webservices/VtigerCRMObject.php");
	require_once("include/Webservices/VtigerCRMObjectMeta.php");
	require_once("include/Webservices/DataTransform.php");
	require_once("include/Webservices/WebServiceError.php");
	require_once 'include/utils/utils.php';
	require_once 'include/utils/UserInfoUtil.php';
	require_once 'include/Webservices/ModuleTypes.php';
	
	$API_VERSION = "0.1";
	
	global $seclog,$log;
	$seclog =& LoggerManager::getLogger('SECURITY');
	$log =& LoggerManager::getLogger('webservice');
	
	function getParameter($ParameterArray, $paramName,$default=null){
		
		if (!get_magic_quotes_gpc()) {
			$param = addslashes($ParameterArray[$paramName]);
		} else {
			$param = $ParameterArray[$paramName];
		}
		
		if(!$param){
			$param = $default;
		}
		return $param;
	}
	
	$operationInput = array(
							"login"=>&$_POST,
							"retrieve"=>&$_GET,
							"create"=>&$_POST,
							"update"=>$_POST,
							"delete"=>&$_POST,
							"sync"=>&$_GET,
							"query"=>&$_GET,
							"logout"=>&$_POST,
							"listtypes"=>&$_GET,
							"getchallenge"=>&$_GET,
							"describeobject"=>&$_GET,
							"extendsession"=>&$_GET
						);
	
	function getRequestParamsArrayForOperation($operation){
		global $operationInput;
		return $operationInput[$operation];
	}
	
	function writeErrorOutput($operationManager, $error){
		
		$state = new State();
		$state->success = false;
		$state->error = $error;
		unset($state->result);
		$output = $operationManager->encode($state);
		echo $output;
		
	}
	
	function writeOutput($operationManager, $data){
		
		$state = new State();
		$state->success = true;
		$state->result = $data;
		unset($state->error);
		$output = $operationManager->encode($state);
		echo $output;
		
	}
	
	$operation = getParameter($_REQUEST, "operation");
	$operation = strtolower($operation);
	$format = getParameter($_REQUEST, "format","json");
	$sessionId = getParameter($_REQUEST,"sessionName");
	
	$sessionManager = new SessionManager();
	$operationManager = new OperationManager($format,$sessionManager);
	
	if(!$sessionId || strcasecmp($sessionId,"null")===0){
		$sessionId = null;
	}
	$adoptSession = false;
	if(strcasecmp($operation,"extendsession")===0){
		$sessionId = getParameter($_REQUEST,"PHPSESSID");
		$adoptSession = true;
	}
	$sid = $sessionManager->startSession($sessionId,$adoptSession);
	
	if(!$sessionId && !in_array($operation,$operationManager->getPreLoginOperations())){
		writeErrorOutput($operationManager,new WebServiceError(WebServiceErrorCode::$AUTHREQUIRED,"Authencation required"));
		return;
	}
	
	if(!$sid){
		writeErrorOutput($operationManager, $sessionManager->getError());
		return;
	}
	
	$userid = $sessionManager->get("authenticatedUserId");
	
	if($userid){
	
		$seed_user = new Users();
		$current_user = $seed_user->retrieveCurrentUserInfoFromFile($userid);
		
	}else{
		$current_user = null;
	}
	
	$input = getRequestParamsArrayForOperation($operation);
	
	$operationInput = $operationManager->sanitizeOperation($operation,$input);
	$includes = $operationManager->getOperationIncludes($operation);
	
	foreach($includes as $ind=>$path){
		require_once($path);
	}
	
	$rawOutput = $operationManager->runOperation($operation,$operationInput,$current_user);
	
	if(is_a($rawOutput,"WebServiceError")){
		writeErrorOutput($operationManager, $rawOutput);
	}else{
		writeOutput($operationManager, $rawOutput);
	}
	
	function getIdComponents($elementid){
		return explode("x",$elementid);
	}
	
	function getId($objId, $elemId){
		return $objId."x".$elemId;
	}
	
?>

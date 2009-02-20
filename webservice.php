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
	require_once 'include/Webservices/WebserviceField.php';
	require_once 'include/Webservices/EntityMeta.php';
	require_once 'include/Webservices/VtigerWebserviceObject.php';
	require_once("include/Webservices/VtigerCRMObject.php");
	require_once("include/Webservices/VtigerCRMObjectMeta.php");
	require_once("include/Webservices/DataTransform.php");
	require_once("include/Webservices/WebServiceError.php");
	require_once 'include/utils/CommonUtils.php';
	require_once 'include/utils/utils.php';
	require_once 'include/utils/UserInfoUtil.php';
	require_once 'include/Webservices/ModuleTypes.php';
	require_once 'include/utils/VtlibUtils.php';
	require_once('include/logging.php');
	require_once 'include/Webservices/WebserviceEntityOperation.php';
	require_once "include/language/$default_language.lang.php";
	
	$API_VERSION = "0.1";
	
	global $seclog,$log;
	$seclog =& LoggerManager::getLogger('SECURITY');
	$log =& LoggerManager::getLogger('webservice');
	
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
	
	$operation = vtws_getParameter($_REQUEST, "operation");
	$operation = strtolower($operation);
	$format = vtws_getParameter($_REQUEST, "format","json");
	$sessionId = vtws_getParameter($_REQUEST,"sessionName");
	
	$sessionManager = new SessionManager();
	$operationManager = new OperationManager($adb,$operation,$format,$sessionManager);
	
	try{
		if(!$sessionId || strcasecmp($sessionId,"null")===0){
			$sessionId = null;
		}
		
		$input = $operationManager->getOperationInput();
		$adoptSession = false;
		if(strcasecmp($operation,"extendsession")===0){
			if(isset($input['operation'])){
				$sessionId = vtws_getParameter($_REQUEST,"PHPSESSID");
				$adoptSession = true;
			}else{
				writeErrorOutput($operationManager,new WebServiceException(WebServiceErrorCode::$AUTHREQUIRED,"Authencation required"));
				return;
			}
		}
		$sid = $sessionManager->startSession($sessionId,$adoptSession);
		
		if(!$sessionId && !$operationManager->isPreLoginOperation()){
			writeErrorOutput($operationManager,new WebServiceException(WebServiceErrorCode::$AUTHREQUIRED,"Authencation required"));
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
		
		$operationInput = $operationManager->sanitizeOperation($input);
		$includes = $operationManager->getOperationIncludes();
		
		foreach($includes as $ind=>$path){
			require_once($path);
		}
		$rawOutput = $operationManager->runOperation($operationInput,$current_user);
		writeOutput($operationManager, $rawOutput);
	}catch(WebServiceException $e){
		writeErrorOutput($operationManager,$e);
	}catch(Exception $e){
		writeErrorOutput($operationManager, 
			new WebServiceException(WebServiceErrorCode::$INTERNALERROR,"Unknown Error while processing request"));
	}
?>

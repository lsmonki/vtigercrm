<?php
	require_once("config.inc.php");
	require_once("include/utils/utils.php");
	require_once 'webservice.php';

	function setBuiltIn($json){
		$json->useBuiltinEncoderDecoder = true;
	}
	
	class OperationManager{
		private $format;
		private $formatsData=array("json"=>array(
													"includePath"=>"include/Zend/Json.php",
													"class"=>"Zend_Json",
													"encodeMethod"=>"encode",
													"decodeMethod"=>"decode",
													"postCreate"=>"setBuiltIn"
												)
								);
		private $operationData = array(
										"create"=>array(
														"elementType"=>"String",
														"element"=>"encoded"
													),
										"update"=>array(
														"element"=>"encoded"
													),
										"login"=>array(
														"username"=>"String",
														"accessKey"=>"String"
													),
										"retrieve"=>array(
														"id"=>"String"
													),
										"delete"=>array(
														"id"=>"String"
													),
										"sync"=>array(
														"modifiedTime"=>"DateTime",
														"elementType"=>"String"
													),
										"query"=>array(
														"query"=>"String"
													),
										"logout"=>array(
														"sessionName"=>"String"
													),
										"listtypes"=>array(
													),
										"getchallenge"=>array(
														"username"=>"String"
													),
										"describeobject"=>array(
														"elementType"=>"String"
													)	
									);
		private $operationParameter = array(
											"create"=>array(
															"elementType",
															"element"
														),
											"update"=>array(
															"element"
														),
											"login"=>array(
															"username","accessKey"
														),
											"retrieve"=>array(
															"id"
														),
											"delete"=>array(
															"id"
														),
											"sync"=>array(
															"modifiedTime",
															"elementType"
														),
											"query"=>array(
															"query"
														),
											"logout"=>array(
															"sessionName"
														),
											"listtypes"=>array(
														),
											"getchallenge"=>array(
															"username"
														),
											"describeobject"=>array(
															"elementType"
														)
										);
		
		private $operationMeta = array(
										"login"=>array(
														"includes"=>array(
																		"webservices/login.php"
																	)
													),
										"retrieve"=>array(
															"includes"=>array(
																				"webservices/Retrieve.php"
																			)
													),
										"create"=>array(
															"includes"=>array(
																				"webservices/Create.php"
																			)
													),
										"update"=>array(
															"includes"=>array(
																				"webservices/Update.php"
																			)
													),
										"delete"=>array(
															"includes"=>array(
																				"webservices/Delete.php"
																			)
													),
										"sync"=>array(
														"includes"=>array(
																			"webservices/GetUpdates.php"
																		)
													),
										"query"=>array(
														"includes"=>array(
																			"webservices/Query.php"
																		)
													),
										"logout"=>array(
														"includes"=>array(
																			"webservices/logout.php"
																		)
													),
										"listtypes"=>array(
														"includes"=>array(
																			"webservices/ModuleTypes.php"
																		)
													),
										"getchallenge"=>array(
														"includes"=>array(
																			"webservices/AuthToken.php"
																		)
													),
										"describeobject"=>array(
														"includes"=>array(
																			"webservices/DescribeObject.php"
																		)
													)
													
									);
		private $operationHandle = array(
										"login"=>"vtws_login",
										"getchallenge"=>"vtws_getchallenge",
										"listtypes"=>"vtws_listtypes",
										"describeobject"=>"vtws_describeobject",
										"create"=>"vtws_create",
										"update"=>"vtws_update",
										"retrieve"=>"vtws_retrieve",
										"delete"=>"vtws_delete",
										"query"=>"vtws_query",
										"sync"=>"vtws_sync",
										"logout"=>"vtws_logout"
									);

		private $preLoginOperations = array("getchallenge","login");
		private $formatObjects ;
		private $inParamProcess ;
		private $sessionManager;
		
		function OperationManager($format, $sessionManager){
			
			$this->format = strtolower($format);
			$this->sessionManager = $sessionManager;
			$this->formatObjects = array();
			
			foreach($this->formatsData as $frmt=>$frmtData){
				require_once($frmtData["includePath"]);
				$instance = new $frmtData["class"]();
				$this->formatObjects[$frmt]["encode"] = array(&$instance,$frmtData["encodeMethod"]);
				$this->formatObjects[$frmt]["decode"] = array(&$instance,$frmtData["decodeMethod"]);
				if($frmtData["postCreate"]){
					call_user_func($frmtData["postCreate"],$instance);
				}
			}
			
			$this->inParamProcess = array();
			$this->inParamProcess["encoded"] = &$this->formatObjects[$this->format]["decode"];
			//$this->inParamProcess["id"] = array(&$this,"validateId");
		}
		
		function sanitizeOperation($operationName, $input){
			if($this->operationData[$operationName]){
				return $this->sanitizeInputForType($input, $this->operationData[$operationName],$this->operationParameter[$operationName]);
			}
			return false;
		}
		
		function sanitizeInputForType($input, $mapping,$ordering){
			
			$sanitizedInput = array();
			
			foreach($ordering as $ind=>$columnName){
				$type = $mapping[$columnName];
				$sanitizedInput[$columnName] = $this->handleType($type,$input[$columnName]);
			}
			return $sanitizedInput;
		}
		
		function handleType($type,$value){
			$result;
			$value = stripslashes($value);
			
			if($this->inParamProcess[$type]){
				$result = call_user_func($this->inParamProcess[$type],$value);
			}else{
				$result = $value;
			}
			return $result;
		}
		
		function runOperation($operation, $params,$user){
			global $app_strings,$API_VERSION;
			try{
				$app_strings = return_application_language($default_language);
				
				$operation = strtolower($operation);
				
				if(!isset($this->operationHandle[$operation]) || $this->operationHandle[$operation] ==null){
					return new WebServiceError(WebServiceErrorCode::$UNKNOWNOPERATION,"Unknown operation requested");
				}
				
				if(!in_array($operation,$this->preLoginOperations)){
					$params[] = $user;
					return call_user_func_array($this->operationHandle[$operation],$params);
				}else{
					
					$userDetails = call_user_func_array($this->operationHandle[$operation],$params);
					if(is_a($userDetails,"WebServiceError") || is_array($userDetails)){
						return $userDetails;
					}else{
						$this->sessionManager->set("authenticatedUserId", $userDetails->id);
						$crmObject = new VtigerCRMObject("Users");
						$userId = $crmObject->getIdFromComponents($crmObject->getModuleId(),$userDetails->id);
						$resp = array("sessionId"=>$this->sessionManager->getSessionId(),"userId"=>$userId,"version"=>$API_VERSION);
						return $resp;
					}
				}
			}catch(Exception $e){
				return new WebServiceError(WebServiceErrorCode::$INTERNALERROR,"Unknown Error while processing request");
			}
		}
		
		function encode($param){
			return call_user_func($this->formatObjects[$this->format]["encode"],$param);
		}
		
		function getOperationIncludes($operation){
			$includes = array();
			$operationData = $this->operationMeta[$operation];
			$includes = (!is_array($operationData["includes"]))? $includes: $operationData["includes"];
			return $includes;
		}
		
		function getPreLoginOperations(){
			return $this->preLoginOperations;
		}
		
	}
	
?>
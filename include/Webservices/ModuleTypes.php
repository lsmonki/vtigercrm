<?php
	
	function vtws_listtypes($user){
		try{
			global $adb,$log,$current_user;
			//get All the modules the current user is permitted to Access.
			$current_user = $user;
			$allModuleNames = getPermittedModuleNames();
			if(array_search('Calendar',$allModuleNames) !== false){
				array_push($allModuleNames,'Events');
			}
			//get All the CRM entity names.
			$webserviceEntities = vtws_getWebserviceEntities();
			$accessibleModules = array_values(array_intersect($webserviceEntities['module'],$allModuleNames));
			$entities = $webserviceEntities['entity'];
			$accessibleEntities = array();
			foreach($entities as $entity){
				$webserviceObject = VtigerWebserviceObject::fromName($adb,$entity);
				$handlerPath = $webserviceObject->getHandlerPath();
				$handlerClass = $webserviceObject->getHandlerClass();
				
				require_once $handlerPath;
				$handler = new $handlerClass($webserviceObject,$user,$adb,$log);
				$meta = $handler->getMeta();
				if($meta->hasAccess()===true){
					array_push($accessibleEntities,$entity);
				}
			}
		}catch(WebServiceException $exception){
			throw $exception;
		}catch(Exception $exception){
			throw new WebServiceException(WebServiceErrorCode::$DATABASEQUERYERROR,
											"An Database error occured while performing the operation");
		}
		return array("types"=>array_merge($accessibleModules,$accessibleEntities));
	}

?>
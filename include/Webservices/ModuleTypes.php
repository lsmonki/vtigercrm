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
		global $current_language;
		$oldCurrentLanguage = $current_language;
		$informationArray = array();
		foreach ($accessibleModules as $module) {
			$vtigerModule = ($module == 'Events')? 'Calendar':$module;
			$informationArray[$module] = array('isEntity'=>true,'label'=>getTranslatedString($module,$vtigerModule),
				'singular'=>getTranslatedString('SINGLE_'.$module,$vtigerModule));
		}
		global $default_language;
		require_once 'include/Webservices/language/'.$default_language.'.lang.php';
		foreach ($accessibleEntities as $entity) {
			$informationArray[$entity] = array('isEntity'=>false,'label'=>$app_strings[$entity],
				'singular'=>$app_strings['SINGLE_'.$entity]);
		}
		$current_language = $oldCurrentLanguage;
		return array("types"=>array_merge($accessibleModules,$accessibleEntities),'information'=>$informationArray);
	}

?>
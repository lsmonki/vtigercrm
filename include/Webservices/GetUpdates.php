<?php
	
	function vtws_sync($mtime,$elementType,$user){
			
		global $adb, $recordString,$modifiedTimeString;
		
		$ignoreModules = array("");
		$typed = true;
		$dformat = "Y-m-d H:i:s";
		
		$datetime = date($dformat, $mtime);
		
		$setypeArray = array();
		$setypeData = array();
		$setypeMeta = array();
		$setypeNoAccessArray = array();
		
		if(!isset($elementType) || $elementType=='' || $elementType==null){
			$typed=false;
		}
		
		$adb->startTransaction();
		$q= "select crmid,setype from vtiger_crmentity where modifiedtime >? and smownerid=? and deleted=0";
		$params = array($datetime,$user->id);
		if($typed){
			$q = $q." and setype=?";
			array_push($params,$elementType); 
		}
		
		$result = $adb->pquery($q, $params);
		
		do{
			if($arre){
				if((strpos($arre["setype"]," ")===FALSE || strpos($arre["setype"]," ")==="")){
					if((array_search($arre["setype"],$ignoreModules) === FALSE || 
							array_search($arre["setype"],$ignoreModules) === "")){
						$setypeArray[$arre["crmid"]] = $arre["setype"];
						
						if(!$setypeData[$arre["setype"]]){
							$setypeData[$arre["setype"]] = new VtigerCRMObject($arre["setype"],false);
							
							$setypeMeta[$arre["setype"]] = new VtigerCRMObjectMeta($setypeData[$arre["setype"]],$user);
							$setypeMeta[$arre["setype"]]->retrieveMeta();
						}
					}
				}
			}
			$arre = $adb->fetchByAssoc($result);
			
		}while($arre);
		
		$output = array();
		
		$output["updated"] = array();
		
		foreach($setypeArray as $key=>$val){
			
			$meta = $setypeMeta[$val];
			
			if(!$meta->hasAccess() || !$meta->hasWriteAccess() || !$meta->hasPermission(VtigerCRMObjectMeta::$RETRIEVE,$key)){
				if(!$setypeNoAccessArray[$val]){
					$setypeNoAccessArray[] = $val;
				}
				continue;
			}
			
			$setypeData[$val]->read($key);
			
			$output["updated"][] = DataTransform::filterAndSanitize($setypeData[$val]->getFields(),$meta);
		}
		
		$setypeArray = array();
		$setypeData = array();
		
		$q= "select crmid,setype,modifiedtime from vtiger_crmentity where modifiedtime >? and smownerid=? and deleted=1";
		$params = array($datetime,$user->id);
		if($typed){
			$q = $q." and setype=?";
			array_push($params,$elementType);
		}
		
		$result = $adb->pquery($q, $params);
		
		do{
			if($arre){
				if((strpos($arre["setype"]," ")===FALSE || strpos($arre["setype"]," ")==="")){
					if((array_search($arre["setype"],$ignoreModules) === FALSE || 
							array_search($arre["setype"],$ignoreModules) === "")){
						$setypeArray[$arre["crmid"]] = $arre["setype"];
						if(!$setypeData[$arre["setype"]]){
							$setypeData[$arre["setype"]] = new VtigerCRMObject($arre["setype"],false);
							
							$setypeMeta[$arre["setype"]] = new VtigerCRMObjectMeta($setypeData[$arre["setype"]],$user);
							$setypeMeta[$arre["setype"]]->retrieveMeta();
						}
					}
				}
			}
			$arre = $adb->fetchByAssoc($result);
			
		}while($arre);
		
		$output["deleted"] = array();
		
		foreach($setypeArray as $key=>$val){
			$meta = $setypeMeta[$val];
			
			if(!$meta->hasAccess() || !$meta->hasWriteAccess() /*|| !$meta->hasPermission(VtigerCRMObjectMeta::$RETRIEVE,$key)*/){
				if(!$setypeNoAccessArray[$val]){
					$setypeNoAccessArray[] = $val;
				}
				continue;
			}
			
			$output["deleted"][] = getId($meta->getObjectId(), $key);
		}
		
		$q= "select max(modifiedtime) as modifiedtime from vtiger_crmentity where modifiedtime >? and smownerid=?";
		$params = array($datetime,$user->id);
		if($typed){
			$q = $q." and setype=?";
			array_push($params,$elementType);
		}else if(sizeof($setypeNoAccessArray)>0){
			$q = $q." and setype not in ('".generateQuestionMarks($setypeNoAccessArray)."')";
			array_push($params,$setypeNoAccessArray);
		}
		
		$result = $adb->pquery($q, $params);
		$arre = $adb->fetchByAssoc($result);
		$modifiedtime = $arre['modifiedtime'];
		
		if(!$modifiedtime){
			$modifiedtime = $mtime;
		}else{
			$modifiedtime = vtws_getSeconds($modifiedtime);
		}
		if(is_string($modifiedtime)){
			$modifiedtime = intval($modifiedtime);
		}
		$output['lastModifiedTime'] = $modifiedtime;
		
		$error = $adb->hasFailedTransaction();
		$adb->completeTransaction();
		
		if($error){
			return new WebServiceError(WebServiceErrorCode::$DATABASEQUERYERROR,"Database error while performing required operation");
		}
		
		return $output;
	}
	
	function vtws_getSeconds($mtimeString){
		//TODO handle timezone and change time to gmt.
		return strtotime($mtimeString);
	}
	
?>

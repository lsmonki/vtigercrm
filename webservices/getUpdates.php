<?php
	require_once("webservices/VtigerCRMObject.php");
	require_once("webservices/VtigerCRMObjectMeta.php");
	require_once("webservices/DataTransform.php");
	require_once("webservices/WebServiceError.php");
	
	function sync($mtime,$elementType,$user){
			
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
		$q= "select crmid,setype from vtiger_crmentity where modifiedtime >'$datetime' and smownerid=$user->id and deleted=0";
		if($typed){
			$q = $q." and setype='$elementType'";
		}
		
		$result = $adb->query($q, false, "<br>Error: failed to get details for module<br>");
		
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
		
		$q= "select crmid,setype,modifiedtime from vtiger_crmentity where modifiedtime >'$datetime'  and smownerid=$user->id and deleted=1";
		if($typed){
			$q = $q." and setype='$elementType'";
		}
		
		$result = $adb->query($q, false, "<br>Error: failed to get details for module<br>");
		
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
		
		$q= "select max(modifiedtime) as modifiedtime from vtiger_crmentity where modifiedtime >'$datetime' and smownerid=$user->id and deleted=0";
		if($typed){
			$q = $q." and setype='$elementType'";
		}
		
		if(sizeof($setypeNoAccessArray)>0){
			$q = $q." and setype not in ('".implode("','",$setypeNoAccessArray)."')";
		}
		
		$result = $adb->query($q, false, "<br>Error: failed to get modified time<br>");
		$arre = $adb->fetchByAssoc($result);
		$modifiedtime = $arre['modifiedtime'];
		
		$q= "select max(modifiedtime) as modifiedtime from vtiger_crmentity where modifiedtime >'$datetime'  and smownerid=$user->id and deleted=1";
		if($typed){
			$q = $q." and setype='$elementType'";
		}
		
		if(sizeof($setypeNoAccessArray)>0){
			$q = $q." and setype not in ('".implode("','",$setypeNoAccessArray)."')";
		}
		
		$result = $adb->query($q, false, "<br>Error: failed to get modified time<br>");
		$arre = $adb->fetchByAssoc($result);
		if($arre['modifiedtime']>$modifiedtime){
			$modifiedtime = $arre['modifiedtime'];
		}
		
		if(!$modifiedtime){
			$modifiedtime = $mtime;
		}else{
			$modifiedtime = getSeconds($modifiedtime);
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
	
	function getSeconds($mtimeString){
		//TODO handle timezone and change time to gmt.
		return strtotime($mtimeString);
	}
	
?>

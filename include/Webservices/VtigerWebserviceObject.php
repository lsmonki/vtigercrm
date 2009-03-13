<?php

class VtigerWebserviceObject{
	
	private $id;
	private $name;
	private $handlerPath;
	private $handlerClass;
	
	private function VtigerWebserviceObject($entityId,$entityName,$handler_path,$handler_class){
		$this->id = $entityId;
		$this->name = $entityName;
		$this->handlerPath = $handler_path;
		$this->handlerClass = $handler_class;
	}
	
	static function fromName($adb,$entityName){
		
		$result = $adb->pquery("select * from vtiger_ws_entity where name=?",array($entityName));
		if($result){
			$rowCount = $adb->num_rows($result);
			if($rowCount === 1){
				$rowData = $adb->query_result_rowdata($result,0);
				return new VtigerWebserviceObject($rowData['id'],$rowData['name'],
						$rowData['handler_path'],$rowData['handler_class']);
			}
		}
		throw new WebServiceException(WebServiceErrorCode::$ACCESSDENIED,"Permission to perform the operation is denied for name");
	}
	
	static function fromId($adb,$entityId){
		
		$result = $adb->pquery("select * from vtiger_ws_entity where id=?",array($entityId));
		if($result){
			$rowCount = $adb->num_rows($result);
			if($rowCount === 1){
				$rowData = $adb->query_result_rowdata($result,0);
				return new VtigerWebserviceObject($rowData['id'],$rowData['name'],
						$rowData['handler_path'],$rowData['handler_class']);
			}
		}
		throw new WebServiceException(WebServiceErrorCode::$ACCESSDENIED,"Permission to perform the operation is denied for id");
	}
	
	static function fromQuery($adb,$query){
		$moduleRegex = "/[fF][rR][Oo][Mm]\s+([^\s;]+)/";
		$matches = array();
		$found = preg_match($moduleRegex,$query,$matches);
		if($found === 1){
			return VtigerWebserviceObject::fromName($adb,trim($matches[1]));
		}
		throw new WebServiceException(WebServiceErrorCode::$ACCESSDENIED,"Permission to perform the operation is denied for query");
	}
	
	public function getEntityName(){
		return $this->name;
	}
	
	public function getEntityId(){
		return $this->id;
	}
	
	public function getHandlerPath(){
		return $this->handlerPath;
	}
	
	public function getHandlerClass(){
		return $this->handlerClass;
	}
	
}
?>
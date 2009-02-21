<?php
//require_once('include/events/VTEntityData.inc');
require_once('include/Webservices/Utils.php');
require_once("include/Webservices/VtigerCRMObject.php");
require_once("include/Webservices/VtigerCRMObjectMeta.php");
require_once("include/Webservices/DataTransform.php");
require_once("include/Webservices/WebServiceError.php");
require_once 'include/Webservices/ModuleTypes.php';
require_once('include/Webservices/Create.php');
require_once 'include/Webservices/WebserviceField.php';
require_once 'include/Webservices/EntityMeta.php';
require_once 'include/Webservices/VtigerWebserviceObject.php';

require_once("modules/Users/Users.php");

class VTCreateTodoTask extends VTTask{
	public $executeImmediately = true;
	
	public function getFieldNames(){return array('todo', 'description', 'sendNotification', 'time', 'date', 'status', 'priority', 'days', 'direction', 'datefield', 'sendNotification');}
	
	function getAdmin(){
		$user = new Users();
		$user->retrieveCurrentUserInfoFromFile(1);
		global $current_user;
		$this->originalUser = $current_user;
		$current_user = $user;
		return $user;
	}
	
	public function doTask($entityData){
		global $adb, $current_user;
		$userId = $entityData->get('assigned_user_id');
		if($userId===null){
			$userId = 1;
		}
		if($userId===0){
			$groupId = getRecordOwnerId($entityData->getId());
			$userId = vtws_getWebserviceEntityId('Groups', $groupId);
		}else{
			$userId = vtws_getWebserviceEntityId('Users', $userId);
		}
		$baseDate = $entityData->get($this->datefield);
		preg_match('/\d\d\d\d-\d\d-\d\d/', $baseDate, $match);
		$baseDate = strtotime($match[0]);
		$date = strftime('%Y-%m-%d', $baseDate+$this->days*24*60*60*($this->directions=='Before'?-1:1));
		$fields = array(
			'activitytype'=>'Task',
			'description'=>$this->description,
			'subject'=>$this->todo,
			'taskpriority'=>$this->priority,
			'taskstatus'=>$this->status,
			'assigned_user_id'=>$userId,
			'time_start'=>$this->time,
			'sendnotification'=>$this->sendNotification!=''?1:0,
			'date_start'=>$date,
			'due_date'=>$date,
			'visibility'=>'all',
			'eventstatus'=>''
		);
		$moduleName = $entityData->getModuleName();
		$id = vtws_getWebserviceEntityId($moduleName, $entityData->getId());
		if($moduleName=='Contacts'){
			$fields['contact_id'] = $id;
		}else if(in_array($moduleName, 
			array('Leads', 'Accounts', 'Potentials', 'Quotes', 'PurchaseOrder', 
					'SalesOrder', 'Invoice', 'Campaigns', 'HelpDesk'))){
			$fields['parent_id'] = $id;
		}
		
		$adminUser = $this->getAdmin();
		vtws_create('Calendar', $fields, $adminUser);
		global $current_user;
		$current_user = $this->originalUser;
	}
}
?>

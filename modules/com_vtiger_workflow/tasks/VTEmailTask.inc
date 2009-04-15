<?php
require_once('modules/com_vtiger_workflow/VTEntityCache.inc');
require_once('modules/com_vtiger_workflow/VTWorkflowUtils.php');
class VTEmailTask extends VTTask{

	public function getFieldNames(){
		return array("subject", "content", "recepient");
	}
	public function doTask($entity){
		global $adb, $current_user;
		$util = new VTWorkflowUtils();

		$result = $adb->query("select email1, email2 from vtiger_users where id=1");
		$from_email = $adb->query_result($result,0,'email1');

		$admin = $util->adminUser();

		$entityCache = new VTEntityCache($admin);

		$et = new VTSimpleTemplate($this->recepient);
		$to_email = $et->render($entityCache, $entity->getId());
		
		$st = new VTSimpleTemplate($this->subject);
		$subject = $st->render($entityCache, $entity->getId());
		$ct = new VTSimpleTemplate($this->content);
		$content = $ct->render($entityCache, $entity->getId());
		send_mail($module,$to_email,$from_name,$from_email,$subject,$content);

		$util->revertUser();
	}
}
?>
<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
require_once('modules/com_vtiger_workflow/VTEntityCache.inc');
require_once('modules/com_vtiger_workflow/VTWorkflowUtils.php');
require_once('modules/com_vtiger_workflow/VTEmailRecipientsTemplate.inc');
require_once('modules/Emails/mail.php');
class VTEmailTask extends VTTask{
	// Sending email takes more time, this should be handled via queue all the time.
	public $executeImmediately = false;

	public function getFieldNames(){
		return array("subject", "content", "recepient", 'emailcc', 'emailbcc');
	}
	public function doTask($entity){
		global $adb, $current_user;
		$util = new VTWorkflowUtils();
		
		$from_userid = 1; // Default user-email = Administrator.
		// Send email as owner of the record (if not 
		$entityOwnerId = $entity->get('assigned_user_id');
		if ($entityOwnerId) {
			list ($moduleid, $from_userid) = explode('x', $entityOwnerId);
		}

		$result = $adb->pquery("select user_name, email1, email2 from vtiger_users where id=?", array($from_userid));
		$from_email = $adb->query_result($result,0,'email1');
		$from_name  = $adb->query_result($result,0,'user_name');

		$admin = $util->adminUser();
		$module = $entity->getModuleName();

		$entityCache = new VTEntityCache($admin);

		$et = new VTEmailRecipientsTemplate($this->recepient);
		$to_email = $et->render($entityCache, $entity->getId());
		$ecct = new VTEmailRecipientsTemplate($this->emailcc);
		$cc = $ecct->render($entityCache, $entity->getId());
		$ebcct = new VTEmailRecipientsTemplate($this->emailbcc);
		$bcc = $ebcct->render($entityCache, $entity->getId());
		if(strlen(trim($to_email, " \t\n,")) == 0 && strlen(trim($cc, " \t\n,")) == 0 &&
				strlen(trim($bcc, " \t\n,")) == 0) {
			return ;
		}

		$st = new VTSimpleTemplate($this->subject);
		$subject = $st->render($entityCache, $entity->getId());
		$ct = new VTSimpleTemplate($this->content);
		$content = $ct->render($entityCache, $entity->getId());

		//Storing the details of emails
		$entityIdDetails = vtws_getIdComponents($entity->getId());
		$entityId = $entityIdDetails[1];
		$moduleName = 'Emails';
		$userId = $current_user->id;
		$emailFocus = CRMEntity::getInstance($moduleName);
		$emailFieldValues = array(
				'assigned_user_id' => $userId,
				'subject' => $subject,
				'description' => $content,
				'from_email' => $from_email,
				'saved_toid' => $to_email,
				'ccmail' => $cc,
				'bccmail' => $bcc,
				'parent_id' => $entityId."@$userId|",
				'email_flag' => 'SENT',
				'activitytype' => $moduleName,
				'date_start' => date(getNewDisplayDate()),
				'mode' => '',
				'id' => ''
		);
		$emailFocus->column_fields = $emailFieldValues;
		$emailFocus->save($moduleName);

		//Including email tracking details
		global $site_URL, $application_unique_key;
		$emailId = $emailFocus->id;
		$trackURL = "$site_URL/modules/Emails/TrackAccess.php?record=$entityId&mailid=$emailId&app_key=$application_unique_key";
		$content = "<img src='$trackURL' alt='' width='1' height='1'>$content";

		if (stripos($content, '<img src="cid:logo" />')) {
			$logo = 1;
		}

		$status = send_mail($module, $to_email, $from_name, $from_email, $subject, $content, $cc, $bcc, '', '', $logo);

		if(!empty($emailId)) {
			$emailFocus->setEmailAccessCountValue($emailId);
		}
		if(!$status) {
			//If mail is not sent then removing the details about email
			$emailFocus->trash($moduleName, $emailId);
		}
		
		$util->revertUser();
	}
}
?>
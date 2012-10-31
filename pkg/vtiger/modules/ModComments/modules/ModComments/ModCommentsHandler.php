<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
require_once 'modules/Emails/mail.php';

class ModCommentsHandler extends VTEventHandler {

	function handleEvent($eventName, $data) {

		if($eventName == 'vtiger.entity.beforesave') {
			// Entity is about to be saved, take required action
		}

		if($eventName == 'vtiger.entity.aftersave') {
			// Entity has been saved, take next action
		}
	}
}


function CustomerCommentFromPortal($entityData) {
	$adb = PearDatabase::getInstance();

	$data = $entityData->getData();
	$customerWSId = $data['customer'];

	$relatedToWSId = $data['related_to'];
	$relatedToId = explode('x', $relatedToWSId);
	$moduleName = getSalesEntityType($relatedToId[1]);


	if($moduleName == 'HelpDesk' && !empty($customerWSId)) {
		$ownerIdInfo = getRecordOwnerId($relatedToId[1]);

		if(!empty($ownerIdInfo['Users'])) {
			$ownerId = $ownerIdInfo['Users'];
			$ownerName = getOwnerName($ownerId);
			$toEmail = getUserEmailId('id',$ownerId);
		}
		if(!empty($ownerIdInfo['Groups'])) {
			$ownerId = $ownerIdInfo['Groups'];
			$groupInfo = getGroupName($ownerId);
			$ownerName = $groupInfo[0];
			$toEmail = implode(',', getDefaultAssigneeEmailIds($ownerId));
		}

		$subject = getTranslatedString('LBL_RESPONDTO_TICKETID', $moduleName)."##". $relatedToId[1]."## ". getTranslatedString('LBL_CUSTOMER_PORTAL', $moduleName);
		$contents = getTranslatedString('Dear', $moduleName)." ".$ownerName.","."<br><br>"
					.getTranslatedString('LBL_CUSTOMER_COMMENTS', $moduleName)."<br><br>
					<b>".$data['commentcontent']."</b><br><br>"
					.getTranslatedString('LBL_RESPOND', $moduleName)."<br><br>"
					.getTranslatedString('LBL_REGARDS', $moduleName)."<br>"
					.getTranslatedString('LBL_SUPPORT_ADMIN', $moduleName);

		$customerId = explode('x', $customerWSId);

		$result = $adb->pquery("SELECT email FROM vtiger_contactdetails WHERE contactid=?", array($customerId[0]));
		$fromEmail = $adb->query_result($result,0,'email');

		send_mail('HelpDesk', $toEmail,'', $fromEmail, $subject, $contents);
	}
}

function TicketOwnerComments($entityData) {
	global $HELPDESK_SUPPORT_NAME, $HELPDESK_SUPPORT_EMAIL_ID, $PORTAL_URL;
	$adb = PearDatabase::getInstance();

	$wsParentId = $entityData->get('related_to');
	$parentIdParts = explode('x', $wsParentId);
	$parentId = $parentIdParts[1];
	$moduleName = getSalesEntityType($parentId);

	if($moduleName == 'HelpDesk') {
		$ticketFocus = CRMEntity::getInstance($moduleName);
		$ticketFocus->retrieve_entity_info($parentId, $moduleName);

		$subject = $ticketFocus->column_fields['ticket_no'] . ' [ '.getTranslatedString('LBL_TICKET_ID', $moduleName)
							.' : '.$parentId.' ] '.$ticketFocus->column_fields['ticket_title'];
		$bodySubject = getTranslatedString('Ticket No', $moduleName) .":" . $ticketFocus->column_fields['ticket_no']
							. "<br>" . getTranslatedString('LBL_TICKET_ID', $moduleName).' : '.$parentId.'<br> '
							.getTranslatedString('LBL_SUBJECT', $moduleName).$ticketFocus->column_fields['ticket_title'];

		$emailOptOut = 0;
		$ticketParentId = $ticketFocus->column_fields['parent_id'];
		//To get the emailoptout vtiger_field value and then decide whether send mail about the tickets or not
		if($ticketParentId != '') {
			$parentModule = getSalesEntityType($ticketParentId);
			if($parentModule == 'Contacts') {
				$result = $adb->pquery('SELECT email, emailoptout FROM vtiger_contactdetails WHERE contactid=?',
											array($ticketParentId));
				$emailOptOut = $adb->query_result($result,0,'emailoptout');
				$parentEmail = $contactMailId = $adb->query_result($result,0,'email');
				$displayValueArray = getEntityName($parentModule, $ticketParentId);
				if (!empty($displayValueArray)) {
					foreach ($displayValueArray as $key => $value) {
						$contactName = $value;
					}
				}
				$parentName = $contactName;
			}
			if($parentModule == 'Accounts') {
				$result = $adb->pquery("SELECT accountname, emailoptout, email1 FROM vtiger_account WHERE accountid=?",
											array($ticketParentId));
				$emailOptOut = $adb->query_result($result,0,'emailoptout');
				$parentEmail = $adb->query_result($result,0,'email1');
				$parentName = $adb->query_result($result,0,'accountname');
			}

			//added condition to check the emailoptout(this is for contacts and vtiger_accounts.)
			if($emailOptOut == 0) {
				$portalUrl = "<a href='".$PORTAL_URL."/index.php?module=HelpDesk&action=index&ticketid=".$parentId."&fun=detail'>".getTranslatedString('LBL_TICKET_DETAILS', $moduleName)."</a>";
				$contents = getTranslatedString('Dear', $moduleName) . " " . $parentName . ",<br><br>";
				$contents .= getTranslatedString('reply', $moduleName) . ' <b>' . $ticketFocus->column_fields['ticket_title']
						. '</b> ' . getTranslatedString('customer_portal', $moduleName);
				$contents .= getTranslatedString("link", $moduleName) . '<br>';
				$contents .= $portalUrl;
				$contents .= '<br><br>' . getTranslatedString("Thanks", $moduleName) . '<br><br>' . $HELPDESK_SUPPORT_NAME;

				$emailBody = $bodySubject.'<br><br>'.$contents;

				send_mail('HelpDesk', $parentEmail, $HELPDESK_SUPPORT_NAME, $HELPDESK_SUPPORT_EMAIL_ID, $subject, $emailBody);
			}
		}
	}
}
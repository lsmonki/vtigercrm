<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

vimport ('~~/include/Webservices/Query.php');

class Calendar_Feed_Action extends Vtiger_BasicAjax_Action {

	public function process(Vtiger_Request $request) {
		try {
			$result = array();

			$start = $request->get('start');
			$end   = $request->get('end');
			$type = $request->get('type');
			$userid = $request->get('userid');
			switch ($type) {
				case 'Events': $this->pullEvents($start, $end, $result, $request->get('cssClass'),$userid,$request->get('color')); break;
				case 'Tasks': $this->pullTasks($start, $end, $result, $request->get('cssClass')); break;
				case 'Potentials': $this->pullPotentials($start, $end, $result, $request->get('cssClass')); break;
				case 'Contacts':
							if($request->get('fieldname') == 'support_end_date') {
								$this->pullContactsBySupportEndDate($start, $end, $result, $request->get('cssClass'));
							}else{
								$this->pullContactsByBirthday($start, $end, $result, $request->get('cssClass'));
							}
							break;

				case 'Invoice': $this->pullInvoice($start, $end, $result, $request->get('cssClass')); break;
				case 'MultipleEvents' : $this->pullMultipleEvents($start,$end, $result,$request->get('mapping'));break;
				case 'Project': $this->pullProjects($start, $end, $result, $request->get('cssClass')); break;
				case 'ProjectTask': $this->pullProjectTasks($start, $end, $result, $request->get('cssClass')); break;
			}
			echo json_encode($result);
		} catch (Exception $ex) {
			echo $ex->getMessage();
		}
	}

	protected function queryForRecords($query, $onlymine=true) {
		$user = Users_Record_Model::getCurrentUserModel();
		if ($onlymine) {
			$userwsid = vtws_getWebserviceEntityId('Users', $user->getId());
			$query .= " AND assigned_user_id='{$userwsid}'";
		}
		// TODO take care of pulling 100+ records
		return vtws_query($query.';', $user);
	}

	protected function pullEvents($start, $end, &$result, $cssClass,$userid = false,$color = null) {
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$db = PearDatabase::getInstance();

		$moduleModel = Vtiger_Module_Model::getInstance('Events');
		if($userid){
			$focus = new Users();
			$focus->id = $userid;
			$focus->retrieve_entity_info($userid, 'Users');
			$user = Users_Record_Model::getInstanceFromUserObject($focus);
			$groupIds = Users_Record_Model::getUserGroups($userid);
			$userName = $user->getName();
			$queryGenerator = new QueryGenerator($moduleModel->get('name'), $user);
		}else{
			$queryGenerator = new QueryGenerator($moduleModel->get('name'), $currentUser);
		}

		$queryGenerator->setFields(array('subject','visibility','date_start','time_start','due_date','time_end','assigned_user_id','id'));
		$query = $queryGenerator->getQuery();

		$query.= " AND vtiger_activity.activitytype NOT IN ('Emails','Task') AND ";
		$query.= " ((date_start >= ? AND due_date < ?) OR ( due_date >= ?))";
		$params = array($start, $end, $start);

		if($userid){
			$query .= " AND (vtiger_crmentity.smownerid='$userid' ";
			if(!empty($groupIds)) {
				$query .= " OR vtiger_crmentity.smownerid IN (".generateQuestionMarks($groupIds).") ";
				$params = array_merge($params, $groupIds);
			}
			$query .= ")";
		} else {
			$query .= " AND vtiger_crmentity.smownerid='$currentUser->id'";
		}

		$queryResult = $db->pquery($query, $params);

		while($record = $db->fetchByAssoc($queryResult)){
			$item = array();
			$crmid = $record['activityid'];
			$visibility = $record['visibility'];
			$item['id'] = $crmid;
			$item['visibility'] = $visibility;
			if($visibility == 'Private' && $userid && $userid != $currentUser->getId()) {
				$item['title'] = $userName.' - '.vtranslate('Busy','Events').'*';
				$item['url']   = '';
			} else {
				$item['title'] = $record['subject'];
				$item['url']   = sprintf('index.php?module=Calendar&view=Detail&record=%s', $crmid);
			}

			$dateTimeFieldInstance = new DateTimeField($record['date_start'] . ' ' . $record['time_start']);
			$userDateTimeString = $dateTimeFieldInstance->getDisplayDateTimeValue($currentUser);
			$dateTimeComponents = explode(' ',$userDateTimeString);
			$dateComponent = $dateTimeComponents[0];
			//Conveting the date format in to Y-m-d . since full calendar expects in the same format
			$dataBaseDateFormatedString = DateTimeField::__convertToDBFormat($dateComponent, $currentUser->get('date_format'));
			$item['start'] = $dataBaseDateFormatedString.' '. $dateTimeComponents[1];

			$dateTimeFieldInstance = new DateTimeField($record['due_date'] . ' ' . $record['time_end']);
			$userDateTimeString = $dateTimeFieldInstance->getDisplayDateTimeValue($currentUser);
			$dateTimeComponents = explode(' ',$userDateTimeString);
			$dateComponent = $dateTimeComponents[0];
			//Conveting the date format in to Y-m-d . since full calendar expects in the same format
			$dataBaseDateFormatedString = DateTimeField::__convertToDBFormat($dateComponent, $currentUser->get('date_format'));
			$item['end']   =  $dataBaseDateFormatedString.' '. $dateTimeComponents[1];


			$item['className'] = $cssClass;
			$item['allDay'] = false;
			$item['color'] = $color;
			$result[] = $item;
			}
		}

	protected function pullMultipleEvents($start, $end, &$result, $data) {

		foreach ($data as $id=>$color) {
			$userEvents = array();
			$this->pullEvents($start, $end, $userEvents ,null,$id, $color);
			$result[$id] = $userEvents;
		}
	}

	protected function pullTasks($start, $end, &$result, $cssClass) {
		$user = Users_Record_Model::getCurrentUserModel();
		$db = PearDatabase::getInstance();

		$moduleModel = Vtiger_Module_Model::getInstance('Calendar');
		$queryGenerator = new QueryGenerator($moduleModel->get('name'), $user);

		$queryGenerator->setFields(array('subject','date_start','time_start','due_date','time_end','id'));
		$query = $queryGenerator->getQuery();

		$query.= " AND vtiger_activity.activitytype = 'Task' AND ";
		$query.= " ((date_start >= '$start' AND due_date < '$end') OR ( due_date >= '$start'))";
		$query.= " AND vtiger_crmentity.smownerid='{$user->getId()}'";

		$queryResult = $db->pquery($query, array());

		while($record = $db->fetchByAssoc($queryResult)){
			$item = array();
			$crmid = $record['activityid'];
			$item['title'] = $record['subject'];

			$dateTimeFieldInstance = new DateTimeField($record['date_start'] . ' ' . $record['time_start']);
			$userDateTimeString = $dateTimeFieldInstance->getDisplayDateTimeValue();
			$dateTimeComponents = explode(' ',$userDateTimeString);
			$dateComponent = $dateTimeComponents[0];
			//Conveting the date format in to Y-m-d . since full calendar expects in the same format
			$dataBaseDateFormatedString = DateTimeField::__convertToDBFormat($dateComponent, $user->get('date_format'));
			$item['start'] = $dataBaseDateFormatedString.' '. $dateTimeComponents[1];

			$item['end']   = $record['due_date'];
			$item['url']   = sprintf('index.php?module=Calendar&view=Detail&record=%s', $crmid);
			$item['className'] = $cssClass;
			$result[] = $item;
		}
	}

	protected function pullPotentials($start, $end, &$result, $cssClass) {
		$query = "SELECT potentialname,closingdate FROM Potentials";
		$query.= " WHERE closingdate >= '$start' AND closingdate <= '$end'";
		$records = $this->queryForRecords($query);
		foreach ($records as $record) {
			$item = array();
			list ($modid, $crmid) = vtws_getIdComponents($record['id']);
			$item['id'] = $crmid;
			$item['title'] = $record['potentialname'];
			$item['start'] = $record['closingdate'];
			$item['url']   = sprintf('index.php?module=Potentials&view=Detail&record=%s', $crmid);
			$item['className'] = $cssClass;
			$result[] = $item;
		}
	}

	protected function pullContacts($start, $end, &$result, $cssClass) {
		$this->pullContactsBySupportEndDate($start, $end, $result, $cssClass);
		$this->pullContactsByBirthday($start, $end, $result, $cssClass);
	}

	protected function pullContactsBySupportEndDate($start, $end, &$result, $cssClass) {
		$query = "SELECT firstname,lastname,support_end_date FROM Contacts";
		$query.= " WHERE support_end_date >= '$start' AND support_end_date <= '$end'";
		$records = $this->queryForRecords($query);
		foreach ($records as $record) {
			$item = array();
			list ($modid, $crmid) = vtws_getIdComponents($record['id']);
			$item['id'] = $crmid;
			$item['title'] = trim($record['firstname'] . ' ' . $record['lastname']);
			$item['start'] = $record['support_end_date'];
			$item['url']   = sprintf('index.php?module=Contacts&view=Detail&record=%s', $crmid);
			$item['className'] = $cssClass;
			$result[] = $item;
		}
	}

	protected  function pullContactsByBirthday($start, $end, &$result, $cssClass) {
		$db = PearDatabase::getInstance();
		$user = Users_Record_Model::getCurrentUserModel();
		$startDateComponents = split('-', $start);
		$endDateComponents = split('-', $end);

		$year = $startDateComponents[0];

		$query = "SELECT firstname,lastname,birthday,crmid FROM vtiger_contactdetails";
		$query.= " INNER JOIN vtiger_contactsubdetails ON vtiger_contactdetails.contactid = vtiger_contactsubdetails.contactsubscriptionid";
		$query.= " INNER JOIN vtiger_crmentity ON vtiger_contactdetails.contactid = vtiger_crmentity.crmid";
		$query.= " WHERE vtiger_crmentity.deleted=0 AND smownerid='{$user->getId()}' AND";
		$query.= " ((CONCAT('$year-', date_format(birthday,'%m-%d')) >= '$start'
						AND CONCAT('$year-', date_format(birthday,'%m-%d')) <= '$end')";

		$endDateYear = $endDateComponents[0];
		if ($year !== $endDateYear) {
			$query .= " OR
						(CONCAT('$endDateYear-', date_format(birthday,'%m-%d')) >= '$start'
							AND CONCAT('$endDateYear-', date_format(birthday,'%m-%d')) <= '$end')";
		}
		$query .= ")";

		$queryResult = $db->pquery($query, array());

		while($record = $db->fetchByAssoc($queryResult)){
			$item = array();
			$crmid = $record['crmid'];
			$recordDateTime = new DateTime($record['birthday']);

			$calendarYear = $year;
			if($recordDateTime->format('m') < $startDateComponents[1]) {
				$calendarYear = $endDateYear;
			}
			$recordDateTime->setDate($calendarYear, $recordDateTime->format('m'), $recordDateTime->format('d'));
			$item['id'] = $crmid;
			$item['title'] = trim($record['firstname'] . ' ' . $record['lastname']);
			$item['start'] = $recordDateTime->format('Y-m-d');
			$item['url']   = sprintf('index.php?module=Contacts&view=Detail&record=%s', $crmid);
			$item['className'] = $cssClass;
			$result[] = $item;
		}
	}

	protected function pullInvoice($start, $end, &$result, $cssClass) {
		$query = "SELECT subject,duedate FROM Invoice";
		$query.= " WHERE duedate >= '$start' AND duedate <= '$end'";
		$records = $this->queryForRecords($query);
		foreach ($records as $record) {
			$item = array();
			list ($modid, $crmid) = vtws_getIdComponents($record['id']);
			$item['id'] = $crmid;
			$item['title'] = $record['subject'];
			$item['start'] = $record['duedate'];
			$item['url']   = sprintf('index.php?module=Invoice&view=Detail&record=%s', $crmid);
			$item['className'] = $cssClass;
			$result[] = $item;
		}
	}

	/**
	 * Function to pull all the current user projects
	 * @param type $startdate
	 * @param type $actualenddate
	 * @param type $result
	 * @param type $cssClass
	 */
	protected function pullProjects($start, $end, &$result, $cssClass) {
		$db = PearDatabase::getInstance();
		$user = Users_Record_Model::getCurrentUserModel();

		$query = "SELECT projectname, startdate, targetenddate, crmid FROM vtiger_project";
		$query.= " INNER JOIN vtiger_crmentity ON vtiger_project.projectid = vtiger_crmentity.crmid";
		$query.= " WHERE vtiger_crmentity.deleted=0 AND smownerid='{$user->getId()}' AND ";
		$query.= " ((startdate >= '$start' AND targetenddate < '$end') OR ( targetenddate >= '$start'))";
		$queryResult = $db->pquery($query, array());

		while($record = $db->fetchByAssoc($queryResult)){
			$item = array();
			$crmid = $record['crmid'];
			$item['id'] = $crmid;
			$item['title'] = $record['projectname'];
			$item['start'] = $record['startdate'];
			$item['end'] = $record['targetenddate'];
			$item['url']   = sprintf('index.php?module=Project&view=Detail&record=%s', $crmid);
			$item['className'] = $cssClass;
			$result[] = $item;
		}
	}

	/**
	 * Function to pull all the current user porjecttasks
	 * @param type $startdate
	 * @param type $enddate
	 * @param type $result
	 * @param type $cssClass
	 */
	protected function pullProjectTasks($start, $end, &$result, $cssClass) {
		$db = PearDatabase::getInstance();
		$user = Users_Record_Model::getCurrentUserModel();

		$query = "SELECT projecttaskname, startdate, enddate, crmid FROM vtiger_projecttask";
		$query.= " INNER JOIN vtiger_crmentity ON vtiger_projecttask.projecttaskid = vtiger_crmentity.crmid";
		$query.= " WHERE vtiger_crmentity.deleted=0 AND smownerid='{$user->getId()}' AND ";
		$query.= " ((startdate >= '$start' AND enddate < '$end') OR ( enddate >= '$start'))";
		$queryResult = $db->pquery($query, array());

		while($record = $db->fetchByAssoc($queryResult)){
			$item = array();
			$crmid = $record['crmid'];
			$item['id'] = $crmid;
			$item['title'] = $record['projecttaskname'];
			$item['start'] = $record['startdate'];
			$item['end'] = $record['enddate'];
			$item['url']   = sprintf('index.php?module=ProjectTask&view=Detail&record=%s', $crmid);
			$item['className'] = $cssClass;
			$result[] = $item;
		}
	}

}
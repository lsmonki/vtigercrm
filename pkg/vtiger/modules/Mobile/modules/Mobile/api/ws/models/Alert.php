<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
abstract class Mobile_WS_AlertModel {
	
	var $alertid, $name, $moduleName, $refreshRate, $description;
	var $user;
	
	function __construct() {}
	
	function setUser($userInstance) {
		$this->user = $userInstance;
	}
	
	function getUser() {
		return $this->user;
	}
	
	function serializeToSend() {
		return array(
			'alertid' => (string)$this->alertid,
			'name' => $this->name,
			'moduleName' => $this->moduleName,
			'refreshRate'=> $this->refreshRate,
			'description'=> $this->description
		);
	}
	
 	abstract function query();
	abstract function queryParameters();
	
	function message() {
		return (string) $this->executeCount();
	}
	
	/*function execute() {
		global $adb;
		$result = $adb->pquery($this->query(), $this->queryParameters());
		return $result;
	}*/
	
	function executeCount() {
		global $adb;
		$result = $adb->pquery($this->countQuery(), $this->queryParameters());
		return $adb->query_result($result, 0, 'count');
	}
	
	// Function provided to enable sub-classes to over-ride in case required 
	protected function countQuery() {
		return mkCountQuery($this->query());
	}
	
	static function models() {
		$models = array();

		$models[] = new  Mobile_WS_AlertModel_NewTicketOfMine();
		$models[] = new Mobile_WS_AlertModel_IdleTicketsOfMine();
		$models[] = new Mobile_WS_AlertModel_PendingTicketsOfMine();
		
		$models[] = new Mobile_WS_AlertModel_PotentialsDueIn5Days();
		
		// Assign id for the models
		$alertid = 1;
		foreach($models as $model) {
			$model->alertid = $alertid++;
		}
		
		return $models;
	}
	
	static function modelWithId($alertid) {
		$models = self::models();
		foreach($models as $model) {
			if ($model->alertid == $alertid) return $model;
		}
		return false;
	}
}

/** Pending Ticket Alert */
class Mobile_WS_AlertModel_PendingTicketsOfMine extends Mobile_WS_AlertModel {
	function __construct() {
		parent::__construct();
		$this->name = 'Pending Ticket Alert';
		$this->moduleName = 'HelpDesk';
		$this->refreshRate= 1 * 60; // in minutes
		$this->description='Alert sent when ticket assigned is not yet closed';
	}
	
	function query() {
		$sql = "SELECT crmid FROM vtiger_troubletickets INNER JOIN 
				vtiger_crmentity ON vtiger_crmentity.crmid=vtiger_troubletickets.ticketid 
				WHERE vtiger_crmentity.deleted=0 AND vtiger_crmentity.smownerid=? AND 
				vtiger_troubletickets.status <> 'Closed'";
		return $sql;
	}
	
	function queryParameters() {
		return array($this->getUser()->id);
	}
}

/** Idle Ticket Alert */
class Mobile_WS_AlertModel_IdleTicketsOfMine extends Mobile_WS_AlertModel_PendingTicketsOfMine {
	function __construct() {
		parent::__construct();
		$this->name = 'Idle Ticket Alert';
		$this->moduleName = 'HelpDesk';
		$this->refreshRate= 1 * 60; // in minutes
		$this->description='Alert sent when ticket has not been updated in 24 hours';
	}
	
	function query() {
		$sql = parent::query();
		$sql .= " AND DATEDIFF(CURDATE(), vtiger_crmentity.modifiedtime) > 1";
		return $sql;
	}
}

/** New Ticket */
class Mobile_WS_AlertModel_NewTicketOfMine extends Mobile_WS_AlertModel_PendingTicketsOfMine {
	function __construct() {
		parent::__construct();
		$this->name = 'New Ticket Alert';
		$this->moduleName = 'HelpDesk';
		$this->refreshRate= 1 * 60; // in minutes
		$this->description='Alert sent when a ticket is assigned to you';
	}
	
	function query() {
		$sql = parent::query();
		$sql .= " ORDER BY crmid DESC LIMIT 1";
		return $sql;
	}
	
	function countQuery() {
		return str_replace("ORDER BY crmid DESC", "", $this->query());
	}
	
	function executeCount() {
		global $adb;
		$result = $adb->pquery($this->countQuery(), $this->queryParameters());
		return $adb->num_rows($result);
	}
}

/** Upcoming Opportunity */
class Mobile_WS_AlertModel_PotentialsDueIn5Days extends Mobile_WS_AlertModel {
	function __construct() {
		parent::__construct();
		$this->name = 'Upcoming Opportunity';
		$this->moduleName = 'Potentials';
		$this->refreshRate= 1 * 60; // in minutes
		$this->description='Alert sent when Potential Close Date is due before 5 days or less';
	}
	
	function query() {
		$sql = Mobile_WS_Utils::getModuleListQuery('Potentials', 
					"vtiger_potential.sales_stage not like 'Closed%' AND 
					DATEDIFF(vtiger_potential.closingdate, CURDATE()) <= 5"
				);
		return preg_replace("/^SELECT count\(\*\) as count(.*)/i", "SELECT crmid $1", mkCountQuery($sql));
	}
	
	function queryParameters() {
		return array();
	}
}


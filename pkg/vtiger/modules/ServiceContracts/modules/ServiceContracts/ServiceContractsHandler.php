<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
 
class ServiceContractsHandler extends VTEventHandler {

	function handleEvent($eventName, $entityData) {
		global $log, $adb;

		if($eventName == 'vtiger.entity.beforesave') {			
			$moduleName = $entityData->getModuleName();
			if ($moduleName == 'HelpDesk') {
				$ticketId = $entityData->getId();
				$oldstatus = '';
				if(isset($ticketId) && $ticketId != '') {
					$tktresult = $adb->pquery("select status from vtiger_troubletickets where ticketid=?", array($ticketId));
					if($adb->num_rows($tktresult) > 0) {
						$old_status = $adb->query_result($tktresult,0,"status");
					}
				}
				$entityData->old_status = $old_status;
			}
		}

		if($eventName == 'vtiger.entity.aftersave') {
			
			$moduleName = $entityData->getModuleName();
			
			// Update Used Units for the Service Contract, everytime the status of a ticket related to the Service Contract changes
			if ($moduleName == 'HelpDesk') {
				$ticketId = $entityData->getId();
				$data = $entityData->getData();
				if($data['ticketstatus'] != $entityData->old_status) {
					if(strtolower($data['ticketstatus']) == 'closed' || strtolower($entityData->old_status) == 'closed') {
						if (strtolower($entityData->old_status) == 'closed') {
							$op = '-';
						} else {
							$op = '+';
						}
						$contract_tktresult = $adb->pquery("SELECT crmid FROM vtiger_crmentityrel WHERE module = 'ServiceContracts'" .
								" AND relmodule = 'HelpDesk' AND relcrmid = ?", array($ticketId));
						$noOfTickets = $adb->num_rows($contract_tktresult);
						if($noOfTickets > 0) {
							for($i=0;$i<$noOfTickets;$i++) {
								$contract_id = $adb->query_result($contract_tktresult,$i,'crmid');
								$units = 0;
								$cont_res = $adb->pquery("SELECT used_units, total_units, tracking_unit FROM vtiger_servicecontracts WHERE servicecontractsid=?",array($contract_id));
								if($adb->num_rows($cont_res) > 0) {
									$tracking_unit = $adb->query_result($cont_res,0,'tracking_unit');
									if (strtolower($tracking_unit) == 'incidents') {
										$units = 1;
									} elseif (strtolower($tracking_unit) == 'days') {
										$units = 0;
										if(!empty($data['days'])) {
											$units = $data['days'];
										} elseif(!empty($data['hours'])) {
											$units = $data['hours'] / 24;
										} 						
									} elseif (strtolower($tracking_unit) == 'hours') {
										$units = 0;
										if(!empty($data['hours'])) {
											$units = $data['hours'];
										} elseif(!empty($data['days'])) {
											$units = $data['days'] * 24;
										} 
									}
								}
								$update_query = "UPDATE vtiger_servicecontracts SET used_units = used_units $op $units WHERE servicecontractsid = ?";
								$adb->pquery($update_query, array($contract_id));
							}
						}
					}
				}				
			}
			
			// Update the Planned Duration, Actual Duration, End Date and Progress based on other field values.			
			if ($moduleName == 'ServiceContracts') {				
				$contract_id = $entityData->getId();
				$data = $entityData->getData();
				
				$updateCols = array();
				$updateParams = array();
				
				// Calculate the Planned Duration based on Due date and Start date. (in days)
				if(!empty($data['due_date']) && !empty($data['start_date'])) {
					array_push($updateCols, "planned_duration= (TO_DAYS(due_date)-TO_DAYS(start_date)+1)");
				}
				// Update the End date if the status is Complete or if the Used Units reaches/exceeds Total Units
				if(empty($data['end_date']) && ($data['contract_status'] == 'Complete' || 
					(!empty($data['used_units']) && !empty($data['total_units']) && $data['used_units'] >= $data['total_units']))) {
					$data['end_date'] = date('Y-m-d');
					array_push($updateCols, 'end_date=?');
					array_push($updateParams, date('Y-m-d'));
				} elseif ($data['contract_status'] != 'Complete' && (empty($data['used_units']) || empty($data['total_units']) ||
					(!empty($data['used_units']) && !empty($data['total_units']) && $data['used_units'] < $data['total_units']))) {
					$data['end_date'] = null;
					array_push($updateCols, 'end_date=?');
					array_push($updateParams, null);					
				}
				// Calculate the Actual Duration based on End date and Start date. (in days)
				if(!empty($data['end_date']) && !empty($data['start_date'])) {
					array_push($updateCols, "actual_duration= (TO_DAYS(end_date)-TO_DAYS(start_date)+1)");
				} else {
					array_push($updateCols, "actual_duration= ''");					
				}
				// Update the Progress based on Used Units and Total Units (in percentage)
				if(!empty($data['used_units']) && !empty($data['total_units'])) {
					array_push($updateCols, 'progress=?');
					array_push($updateParams, floatval(($data['used_units'] * 100) / $data['total_units']));
				}
				
				if(count($updateCols) > 0) {
					$updateQuery = "UPDATE vtiger_servicecontracts SET ". implode(",", $updateCols) ." WHERE servicecontractsid = ?";
					array_push($updateParams, $contract_id);
					$adb->pquery($updateQuery, $updateParams);
				}
			}
		}
	}
}

?>

<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/
require_once 'modules/WSAPP/Handlers/vtigerCRMHandler.php';


class OutlookVtigerCRMHandler extends vtigerCRMHandler{
    
    public function translateReferenceFieldNamesToIds($entityRecords,$user){
        global $log;
        $entityRecordList = array();
        foreach($entityRecords as $index=>$record){
            $entityRecordList[$record['module']][$index] = $record;
        }
        foreach($entityRecordList as $module=>$records){
            $handler = vtws_getModuleHandlerFromName($module, $user);
            $meta = $handler->getMeta();
            $referenceFieldDetails = $meta->getReferenceFieldDetails();

            foreach($referenceFieldDetails as $referenceFieldName=>$referenceModuleDetails){
                $recordReferenceFieldNames = array();
                foreach($records as $index=>$recordDetails){
                    if(!empty($recordDetails[$referenceFieldName])) {
                    $recordReferenceFieldNames[$recordDetails['id']] = $recordDetails[$referenceFieldName];
                }
                }
                $entityNameIds = wsapp_getRecordEntityNameIds(array_values($recordReferenceFieldNames), $referenceModuleDetails, $user);
                foreach($records as $index=>$recordInfo){
                    if(!empty($entityNameIds[$recordInfo[$referenceFieldName]])){
                        $recordInfo[$referenceFieldName] = $entityNameIds[$recordInfo[$referenceFieldName]];
                    } else {
                        if($referenceFieldName == 'account_id'){
                            if($recordInfo[$referenceFieldName]!=NULL){
                                $element['accountname'] = $recordInfo[$referenceFieldName];
                                $element['assigned_user_id'] = vtws_getWebserviceEntityId('Users', $user->id);
                                $result = vtws_create('Accounts', $element, $user);
                                $entityNameIds = wsapp_getRecordEntityNameIds(array_values($recordReferenceFieldNames), $referenceModuleDetails, $user);
                                $recordInfo[$referenceFieldName] = $entityNameIds[$recordInfo[$referenceFieldName]];;
                            }
                        }
                        else{
                            $recordInfo[$referenceFieldName] = "";
                        }
                    }
                    $records[$index] = $recordInfo;
                }
            }
            $entityRecordList[$module] = $records;
        }

        $crmRecords = array();
        foreach($entityRecordList as $module=>$entityRecords){
            foreach($entityRecords as $index=>$record){
                $crmRecords[$index] = $record;
            }
        }
        return $crmRecords;
    }
    
}

?>
<?php

/* +**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * ********************************************************************************** */

class Mobile_WS_FetchModuleOwners extends Mobile_WS_Controller {

    function process(Mobile_API_Request $request) {
        $response = new Mobile_API_Response();
        $current_user = Users_Record_Model::getCurrentUserModel();
        $moduleName = $request->get('module');
        $users = array();
        $users['users'] = $this->getUsers($current_user, $moduleName);
        $response->setResult($users);
        $groups = $this->getGroups($current_user, $moduleName);
        $response->addToResult('groups', $groups);
        return $response;
    }

    function getUsers($current_user, $moduleName) {
        $users = $current_user->getAccessibleUsersForModule($moduleName);
        $userIds = array_keys($users);
        $usersList = array();
        $usersWSId = Mobile_WS_Utils::getEntityModuleWSId('Users');
        foreach ($userIds as $userId) {
            $userRecord = Users_Record_Model::getInstanceById($userId, 'Users');
            $usersList[] = array('value' => $usersWSId . 'x' . $userId,
                                 'label' => $userRecord->get("first_name") . ' ' . $userRecord->get('last_name')
                                );
        }
        return $usersList;
    }

    function getGroups($current_user, $moduleName) {
        $groups = $current_user->getAccessibleGroupForModule($moduleName);
        $groupIds = array_keys($groups);
        $groupsList = array();
        $groupsWSId = Mobile_WS_Utils::getEntityModuleWSId('Groups');
        foreach ($groupIds as $groupId) {
            $groupName = getGroupName($groupId);
            $groupsList[] = array('value' => $groupsWSId . 'x' . $groupId,
                                  'label' => $groupName[0]
                                 );
        }
        return $groupsList;
    }
}


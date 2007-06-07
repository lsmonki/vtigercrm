<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/

/** Class to retreive all the Parent Groups of the specified Group
 *
 */
class GetParentGroups { 

	var $parent_groups=Array();

	/** to get all the parent vtiger_groups of the specified group
	 * @params $groupId --> Group Id :: Type Integer
         * @returns updates the parent group in the varibale $parent_groups of the class
         */
	function getAllParentGroups($groupId)
	{
		global $adb,$log;
		$log->debug("Entering getAllParentGroups(".$groupid.") method...");
		$query="select groupid from vtiger_group2grouprel where containsgroupid=".$groupId;
		$adb->query($query);
		$result=$adb->query($query);
		$num_rows=$adb->num_rows($result);
		if($num_rows > 0)
		{
			for($i=0;$i<$num_rows;$i++)
			{
				$group_id=$adb->query_result($result,$i,'groupid');
				if(! in_array($group_id,$this->parent_groups))
				{
					$this->parent_groups[]=$group_id;
					$this->getAllParentGroups($group_id);
				}
			}
		}
		$log->debug("Exiting getAllParentGroups method...");
	}
}

?>

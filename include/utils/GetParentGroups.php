<?php
/*********************************************************************************
 * $Header$
 * Description:  Defines the Account SugarBean Account entity with the necessary
 * methods and variables.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
/** Class to retreive all the Parent Groups of the specified Group
 *
 */
class GetParentGroups { 

	var $parent_groups=Array();

	/** to get all the parent groups of the specified group
	 * @params $groupId --> Group Id :: Type Integer
         * @returns updates the parent group in the varibale $parent_groups of the class
         */
	function getAllParentGroups($groupId)
	{
		global $adb;
		$query="select groupid from group2grouprel where containsgroupid=".$groupId;
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

	}
}

?>

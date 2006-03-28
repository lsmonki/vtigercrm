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
require_once('include/utils/UserInfoUtil.php');
require_once('include/utils/GetParentGroups.php');

class GetUserGroups { 

	var $user_groups=Array();
	//var $userRole='';

	/** to get all the parent groups of the specified group
	 * @params $groupId --> Group Id :: Type Integer
         * @returns updates the parent group in the varibale $parent_groups of the class
         */
	function getAllUserGroups($userid)
	{
		global $adb;
		//Retreiving from the user2grouptable
		$query="select * from users2group where userid=".$userid;
		$result = $adb->query($query);
		$num_rows=$adb->num_rows($result);
		for($i=0;$i<$num_rows;$i++)
		{
			$now_group_id=$adb->query_result($result,$i,'groupid');
			if(! in_array($now_group_id,$this->user_groups))
			{
				$this->user_groups[]=$now_group_id;
					
			}
		}

		//Setting the User Role
		$userRole = fetchUserRole($userid);
		//Retreiving from the user2role
		$query="select * from group2role where roleid='".$userRole."'";
                $result = $adb->query($query);
                $num_rows=$adb->num_rows($result);
                for($i=0;$i<$num_rows;$i++)
                {
                        $now_group_id=$adb->query_result($result,$i,'groupid');
			if(! in_array($now_group_id,$this->user_groups))
			{
				$this->user_groups[]=$now_group_id;
					
			}
                }

		//Retreiving from the user2rs
		$parentRoles=getParentRole($userRole);
		$parentRolelist="(";
		foreach($parentRoles as $par_rol_id)
		{
			$parentRolelist .= "'".$par_rol_id."',";		
		}
		$parentRolelist .= "'".$userRole."')";
		$query="select * from group2rs where roleandsubid in".$parentRolelist;
                $result = $adb->query($query);
                $num_rows=$adb->num_rows($result);
                for($i=0;$i<$num_rows;$i++)
                {
                        $now_group_id=$adb->query_result($result,$i,'groupid');
 
			if(! in_array($now_group_id,$this->user_groups))
			{
				$this->user_groups[]=$now_group_id;
					
			}
                }
		foreach($this->user_groups as $grp_id)
		{
			$focus = new GetParentGroups();
			$focus->getAllParentGroups($grp_id);
			
			foreach($focus->parent_groups as $par_grp_id)
			{
				if(! in_array($par_grp_id,$this->user_groups))
				{
					$this->user_groups[]=$par_grp_id;
					
				}	
			}
								
		} 
		
	
	}

	
}

?>

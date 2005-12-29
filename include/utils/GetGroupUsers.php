<?php
/*********************************************************************************
 * $Header$
 * Description:  Defines the Account SugarBean Account entity with the necessary
 * methods and variables.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
/** Class to retreive all the users present in a group 
 *
 */
require_once('include/utils/UserInfoUtil.php');
require_once('include/utils/GetParentGroups.php');

class GetGroupUsers { 

	var $group_users=array();

	/** to get all the parent groups of the specified group
	 * @params $groupId --> Group Id :: Type Integer
         * @returns updates the parent group in the varibale $parent_groups of the class
         */
	function getAllUsersInGroup($groupid)
	{
		global $adb;
		//Retreiving from the user2grouptable
		$query="select * from users2group where groupid=".$groupid;
		$result = $adb->query($query);
		$num_rows=$adb->num_rows($result);
		for($i=0;$i<$num_rows;$i++)
		{
			$now_user_id=$adb->query_result($result,$i,'userid');
			if(! in_array($now_user_id,$this->group_users))
			{
				$this->group_users[]=$now_user_id;
					
			}
		}
		

		//Retreiving from the group2role
		$query="select * from group2role where groupid=".$groupid;
                $result = $adb->query($query);
                $num_rows=$adb->num_rows($result);
                for($i=0;$i<$num_rows;$i++)
                {
                        $now_role_id=$adb->query_result($result,$i,'roleid');
			$now_role_users=array();
			$now_role_users=getRoleUsers($now_role_id);
			
			foreach($now_role_users as $now_role_userid => $now_role_username)
			{
				if(! in_array($now_role_userid,$this->group_users))
				{
					$this->group_users[]=$now_role_userid;
					
				}
			}
			
                }

		//Retreiving from the group2rs
		$query="select * from group2rs where groupid=".$groupid;
                $result = $adb->query($query);
                $num_rows=$adb->num_rows($result);
                for($i=0;$i<$num_rows;$i++)
                {
                        $now_rs_id=$adb->query_result($result,$i,'roleandsubid');
			$now_rs_users=getRoleAndSubordinateUsers($now_rs_id);
			foreach($now_rs_users as $now_rs_userid => $now_rs_username)
			{	
				if(! in_array($now_rs_userid,$this->group_users))
				{
					$this->group_users[]=$now_rs_userid;
					
				}
			}
			
 
                }
		//Retreving from group2group
		$query="select * from group2grouprel where groupid=".$groupid;
                $result = $adb->query($query);
                $num_rows=$adb->num_rows($result);
                for($i=0;$i<$num_rows;$i++)
                {
			$now_grp_id=$adb->query_result($result,$i,'containsgroupid');
			$focus = new GetGroupUsers();
			$focus->getAllUsersInGroup($now_grp_id);
			$now_grp_users=$focus->group_users;
			foreach($focus->group_users as $temp_user_id)
			{	
				if(! in_array($temp_user_id,$this->group_users))
				{
					$this->group_users[]=$temp_user_id;
				}
			}
 
                }
		
	
	}

	
}

?>

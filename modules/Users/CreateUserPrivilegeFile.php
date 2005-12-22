<?php
require_once('config.php');
require_once('modules/Users/User.php');
require_once('modules/Users/UserInfoUtil.php');
require_once('include/utils.php');
require_once('include/utils/GetUserGroups.php');
require_once('include/utils/GetGroupUsers.php');


//$userid=1;

function createUserPrivilegesfile($userid)
{
	$handle=@fopen($root_directory.'user_privileges_'.$userid.'.php',"w+");

	if($handle)
	{
		$newbuf='';
		$newbuf .="<?php\n\n";
		$newbuf .="\n";		
		$newbuf .= "//This is the access privilege file\n";
		$user_focus= new User();
		$user_focus->retrieve($userid);
		if($user_focus->is_admin == 'on')
		{
			$newbuf .= "\$is_admin=true;\n";
			$newbuf .= "\n";
			$newbuf .= "?>";
			fputs($handle, $newbuf);
			fclose($handle);
			return;	
		}
		else
		{
			$newbuf .= "\$is_admin=false;\n";
			$newbuf .= "\n";
			
			$globalPermissionArr=getCombinedUserGlobalPermissions($userid);
			$tabsPermissionArr=getCombinedUserTabsPermissions($userid);
			$tabsPermissionArr=getCombinedUserTabsPermissions($userid);
			$actionPermissionArr=getCombinedUserActionPermissions($userid);
			$user_role=fetchUserRole($userid);
			$userGroupFocus=new GetUserGroups();
			$userGroupFocus->userRole=$user_role;
			$userGroupFocus->getAllUserGroups($userid);
			$subRoles=getRoleSubordinates($user_role);
			$subRoleAndUsers=getSubordinateRoleAndUsers($user_role);
			$def_org_share=getDefaultSharingAction();
			$parentRoles=getParentRole($user_role);

			
			$newbuf .= "\$current_user_roles='".$user_role."';\n";
			$newbuf .= "\n";
			$newbuf .= "\$current_user_profiles=".constructSingleArray(getUserProfile($userid)).";\n";
			$newbuf .= "\n";
			$newbuf .= "\$profileGlobalPermission=".constructArray($globalPermissionArr).";\n";
			$newbuf .="\n";		
			$newbuf .= "\$profileTabsPermission=".constructArray($tabsPermissionArr).";\n";
			$newbuf .="\n";		
			$newbuf .= "\$profileActionPermission=".constructTwoDimensionalArray($actionPermissionArr).";\n";
			$newbuf .="\n";		
			$newbuf .= "\$current_user_groups=".constructSingleArray($userGroupFocus->user_groups).";\n";
			$newbuf .="\n";		
			$newbuf .= "\$subordinate_roles=".constructSingleCharArray($subRoles).";\n";
			$newbuf .="\n";		
			$newbuf .= "\$parent_roles=".constructSingleCharArray($parentRoles).";\n";
			$newbuf .="\n";		
			$newbuf .= "\$subordinate_roles_users=".constructTwoDimensionalCharIntSingleArray($subRoleAndUsers).";\n";

			$newbuf .= "?>";
			fputs($handle, $newbuf);
			fclose($handle);
		}
	}
}


function createUserSharingPrivilegesfile($userid)
{
	global $adb;
	require_once('user_privileges_'.$userid.'.php');
	$handle=@fopen($root_directory.'sharing_privileges_'.$userid.'.php',"w+");

	if($handle)
	{
		$newbuf='';
		$newbuf .="<?php\n\n";
		$newbuf .="\n";		
		$newbuf .= "//This is the sharing access privilege file\n";
		$user_focus= new User();
		$user_focus->retrieve($userid);
		if($user_focus->is_admin == 'on')
		{
			$newbuf .= "\n";
			$newbuf .= "?>";
			fputs($handle, $newbuf);
			fclose($handle);
			return;	
		}
		else
		{

			//Constructig the Default Org Share Array
			$def_org_share=getAllDefaultSharingAction();
			$newbuf .= "\$profileGlobalPermission=".constructArray($def_org_share).";\n";
			$newbuf .= "\n";

			//Constructing Lead Sharing Rules
			$lead_share_per_array=getUserModuleSharingObjects("Leads",$userid,$def_org_share,$current_user_roles,$parent_roles,$current_user_groups);
			$lead_share_read_per=$lead_share_per_array['read'];
			$lead_share_write_per=$lead_share_per_array['write'];
			$newbuf .= "\$Leads_share_read_permission=array('ROLE'=>".constructTwoDimensionalCharIntSingleArray($lead_share_read_per['ROLE']).",'GROUP=>'".constructTwoDimensionalArray($lead_share_read_per['GROUP']).");\n";	
			$newbuf .= "\$Leads_share_write_permission=array('ROLE'=>".constructTwoDimensionalCharIntSingleArray($lead_share_write_per['ROLE']).",'GROUP=>'".constructTwoDimensionalArray($lead_share_write_per['GROUP']).");\n";	

			//Constructing Account Sharing Rules
			$account_share_per_array=getUserModuleSharingObjects("Accounts",$userid,$def_org_share,$current_user_roles,$parent_roles,$current_user_groups);
			$account_share_read_per=$account_share_per_array['read'];
			$account_share_write_per=$account_share_per_array['write'];
			$account_sharingrule_members=$account_share_per_array['sharingrules'];
			echo '<pre>';
			print_r($account_share_per_array['sharingrules']);
			echo '</pre>';
			$newbuf .= "\$Accounts_share_read_permission=array('ROLE'=>".constructTwoDimensionalCharIntSingleArray($account_share_read_per['ROLE']).",'GROUP=>'".constructTwoDimensionalArray($account_share_read_per['GROUP']).");\n";	
			$newbuf .= "\$Accounts_share_write_permission=array('ROLE'=>".constructTwoDimensionalCharIntSingleArray($account_share_write_per['ROLE']).",'GROUP=>'".constructTwoDimensionalArray($account_share_write_per['GROUP']).");\n";


			//Constructing the Account Related Module Sharing Array
			//$acct_related_pot=getRelatedModuleSharingArray("Accounts","Potentials",$account_sharingrule_members,$account_share_read_per,$account_share_write_per);

			//Constructing Potential Sharing Rules
			$pot_share_per_array=getUserModuleSharingObjects("Potentials",$userid,$def_org_share,$current_user_roles,$parent_roles,$current_user_groups);
			$pot_share_read_per=$pot_share_per_array['read'];
			$pot_share_write_per=$pot_share_per_array['write'];
			$newbuf .= "\$Potentials_share_read_permission=array('ROLE'=>".constructTwoDimensionalCharIntSingleArray($pot_share_read_per['ROLE']).",'GROUP=>'".constructTwoDimensionalArray($pot_share_read_per['GROUP']).");\n";	
			$newbuf .= "\$Potentials_share_write_permission=array('ROLE'=>".constructTwoDimensionalCharIntSingleArray($pot_share_write_per['ROLE']).",'GROUP=>'".constructTwoDimensionalArray($pot_share_write_per['GROUP']).");\n";


			//Constructing HelpDesk Sharing Rules
			$hd_share_per_array=getUserModuleSharingObjects("HelpDesk",$userid,$def_org_share,$current_user_roles,$parent_roles,$current_user_groups);
			$hd_share_read_per=$hd_share_per_array['read'];
			$hd_share_write_per=$hd_share_per_array['write'];
			$newbuf .= "\$HelpDesk_share_read_permission=array('ROLE'=>".constructTwoDimensionalCharIntSingleArray($hd_share_read_per['ROLE']).",'GROUP=>'".constructTwoDimensionalArray($hd_share_read_per['GROUP']).");\n";	
			$newbuf .= "\$HelpDesk_share_write_permission=array('ROLE'=>".constructTwoDimensionalCharIntSingleArray($hd_share_write_per['ROLE']).",'GROUP=>'".constructTwoDimensionalArray($hd_share_write_per['GROUP']).");\n";
	

			//Constructing Emails Sharing Rules
			$email_share_per_array=getUserModuleSharingObjects("Emails",$userid,$def_org_share,$current_user_roles,$parent_roles,$current_user_groups);
			$email_share_read_per=$email_share_per_array['read'];
			$email_share_write_per=$email_share_per_array['write'];
			$newbuf .= "\$Emails_share_read_permission=array('ROLE'=>".constructTwoDimensionalCharIntSingleArray($email_share_read_per['ROLE']).",'GROUP=>'".constructTwoDimensionalArray($email_share_read_per['GROUP']).");\n";	
			$newbuf .= "\$Emails_share_write_permission=array('ROLE'=>".constructTwoDimensionalCharIntSingleArray($email_share_write_per['ROLE']).",'GROUP=>'".constructTwoDimensionalArray($email_share_write_per['GROUP']).");\n";
	

			//Constructing Quotes Sharing Rules
			$quotes_share_per_array=getUserModuleSharingObjects("Quotes",$userid,$def_org_share,$current_user_roles,$parent_roles,$current_user_groups);
			$quotes_share_read_per=$quotes_share_per_array['read'];
			$quotes_share_write_per=$quotes_share_per_array['write'];
			$newbuf .= "\$Quotes_share_read_permission=array('ROLE'=>".constructTwoDimensionalCharIntSingleArray($quotes_share_read_per['ROLE']).",'GROUP=>'".constructTwoDimensionalArray($quotes_share_read_per['GROUP']).");\n";	
			$newbuf .= "\$Quotes_share_write_permission=array('ROLE'=>".constructTwoDimensionalCharIntSingleArray($quotes_share_write_per['ROLE']).",'GROUP=>'".constructTwoDimensionalArray($quotes_share_write_per['GROUP']).");\n";

			//Constructing Orders Sharing Rules
			$po_share_per_array=getUserModuleSharingObjects("Orders",$userid,$def_org_share,$current_user_roles,$parent_roles,$current_user_groups);
			$po_share_read_per=$po_share_per_array['read'];
			$po_share_write_per=$po_share_per_array['write'];
			$newbuf .= "\$Orders_share_read_permission=array('ROLE'=>".constructTwoDimensionalCharIntSingleArray($po_share_read_per['ROLE']).",'GROUP=>'".constructTwoDimensionalArray($po_share_read_per['GROUP']).");\n";	
			$newbuf .= "\$Orders_share_write_permission=array('ROLE'=>".constructTwoDimensionalCharIntSingleArray($po_share_write_per['ROLE']).",'GROUP=>'".constructTwoDimensionalArray($po_share_write_per['GROUP']).");\n";
	
			//Constructing Sales Order Sharing Rules
			$so_share_per_array=getUserModuleSharingObjects("SalesOrder",$userid,$def_org_share,$current_user_roles,$parent_roles,$current_user_groups);
			$so_share_read_per=$po_share_per_array['read'];
			$so_share_write_per=$po_share_per_array['write'];
			$newbuf .= "\$SalesOrder_share_read_permission=array('ROLE'=>".constructTwoDimensionalCharIntSingleArray($so_share_read_per['ROLE']).",'GROUP=>'".constructTwoDimensionalArray($so_share_read_per['GROUP']).");\n";	
			$newbuf .= "\$SalesOrder_share_write_permission=array('ROLE'=>".constructTwoDimensionalCharIntSingleArray($so_share_write_per['ROLE']).",'GROUP=>'".constructTwoDimensionalArray($so_share_write_per['GROUP']).");\n";
	
			//Constructing Invoice Sharing Rules
			$inv_share_per_array=getUserModuleSharingObjects("Invoice",$userid,$def_org_share,$current_user_roles,$parent_roles,$current_user_groups);
			$inv_share_read_per=$inv_share_per_array['read'];
			$inv_share_write_per=$inv_share_per_array['write'];
			$newbuf .= "\$Invoice_share_read_permission=array('ROLE'=>".constructTwoDimensionalCharIntSingleArray($inv_share_read_per['ROLE']).",'GROUP=>'".constructTwoDimensionalArray($inv_share_read_per['GROUP']).");\n";	
			$newbuf .= "\$Invoice_share_write_permission=array('ROLE'=>".constructTwoDimensionalCharIntSingleArray($inv_share_write_per['ROLE']).",'GROUP=>'".constructTwoDimensionalArray($inv_share_write_per['GROUP']).");\n";
	

			$newbuf .= "?>";
			fputs($handle, $newbuf);
			fclose($handle);
		}
	}
}
//print_r(getCombinedUserGlobalPermissions($userid));
function getUserModuleSharingObjects($module,$userid,$def_org_share,$current_user_roles,$parent_roles,$current_user_groups)
{
	global $adb;
	
	$mod_tabid=getTabid($module);

	$mod_share_permission;
	$mod_share_read_permission=Array();
	$mod_share_write_permission=Array();
	$share_id_members=Array();
	$share_id_groupmembers=Array();
	//If Sharing of leads is Private
	if($def_org_share[$mod_tabid] == 3 || $def_org_share[$mod_tabid] == 1)
	{
		$role_read_per=Array();
		$role_write_per=Array();
		$rs_read_per=Array();
		$rs_write_per=Array();
		$grp_read_per=Array();
		$grp_write_per=Array();
		//Retreiving from role to role
		$query="select datashare_role2role.* from datashare_role2role inner join datashare_module_rel on datashare_module_rel.shareid=datashare_role2role.shareid where datashare_module_rel.tabid=".$mod_tabid." and datashare_role2role.to_roleid='".$current_user_roles."'";
		$result=$adb->query($query);
		$num_rows=$adb->num_rows($result);
		for($i=0;$i<$num_rows;$i++)
		{
			$share_roleid=$adb->query_result($result,$i,'share_roleid');
			
			$shareid=$adb->query_result($result,$i,'shareid');
			$share_id_role_members=Array();
			$share_id_roles=Array();
			$share_id_roles[]=$share_roleid;
			$share_id_role_members['ROLE']=$share_id_roles;
			$share_id_members[$shareid]=$share_id_role_members;
	
			$share_permission=$adb->query_result($result,$i,'permission');
			if($share_permission == 1)
			{
				if($def_org_share[$mod_tabid] == 3)
				{	
					if(! array_key_exists($share_roleid,$role_read_per))
					{

						$share_role_users=getRoleUserIds($share_roleid);
						$role_read_per[$share_roleid]=$share_role_users;
					}
				}
				if(! array_key_exists($share_roleid,$role_write_per))
				{

					$share_role_users=getRoleUserIds($share_roleid);
					$role_write_per[$share_roleid]=$share_role_users;
				}
			}
			elseif($share_permission == 0 && $def_org_share[$mod_tabid] == 3)
			{
				if(! array_key_exists($share_roleid,$role_read_per))
				{

					$share_role_users=getRoleUserIds($share_roleid);
					$role_read_per[$share_roleid]=$share_role_users;
				}

			}

		}



		//Retreiving from role to rs
		$parRoleList = "(";
		foreach($parent_roles as $par_role_id)
		{
			$parRoleList .= "'".$par_role_id."',";		
		}
		$parRoleList .= "'".$current_user_roles."')";
		$query="select datashare_role2rs.* from datashare_role2rs inner join datashare_module_rel on datashare_module_rel.shareid=datashare_role2rs.shareid where datashare_module_rel.tabid=".$mod_tabid." and datashare_role2rs.to_roleandsubid in ".$parRoleList;
		$result=$adb->query($query);
		$num_rows=$adb->num_rows($result);
		for($i=0;$i<$num_rows;$i++)
		{
			$share_roleid=$adb->query_result($result,$i,'share_roleid');

			$shareid=$adb->query_result($result,$i,'shareid');
			$share_id_role_members=Array();
			$share_id_roles=Array();
			$share_id_roles[]=$share_roleid;
			$share_id_role_members['ROLE']=$share_id_roles;
			$share_id_members[$shareid]=$share_id_role_members;

			$share_permission=$adb->query_result($result,$i,'permission');
			if($share_permission == 1)
			{
				if($def_org_share[$mod_tabid] == 3)
				{	
					if(! array_key_exists($share_roleid,$role_read_per))
					{

						$share_role_users=getRoleUserIds($share_roleid);
						$role_read_per[$share_roleid]=$share_role_users;
					}
				}
				if(! array_key_exists($share_roleid,$role_write_per))
				{

					$share_role_users=getRoleUserIds($share_roleid);
					$role_write_per[$share_roleid]=$share_role_users;
				}
			}
			elseif($share_permission == 0 && $def_org_share[$mod_tabid] == 3)
			{
				if(! array_key_exists($share_roleid,$role_read_per))
				{

					$share_role_users=getRoleUserIds($share_roleid);
					$role_read_per[$share_roleid]=$share_role_users;
				}

			}

		}


		//Get roles from Role2Grp
		$grpIterator=false;
		$groupList = "(";
		foreach($current_user_groups as $grp_id)
		{
			if($grpIterator)
			{
				$groupList .= ",";
			}
			$groupList .= "'".$grp_id."'";
			$grpIterator=true;		
		}
		$groupList .= ")";

		$query="select datashare_role2group.* from datashare_role2group inner join datashare_module_rel on datashare_module_rel.shareid=datashare_role2group.shareid where datashare_module_rel.tabid=".$mod_tabid." and datashare_role2group.to_groupid in ".$groupList;
		$result=$adb->query($query);
		$num_rows=$adb->num_rows($result);
		for($i=0;$i<$num_rows;$i++)
		{
			$share_roleid=$adb->query_result($result,$i,'share_roleid');
			$shareid=$adb->query_result($result,$i,'shareid');
			$share_id_role_members=Array();
			$share_id_roles=Array();
			$share_id_roles[]=$share_roleid;
			$share_id_role_members['ROLE']=$share_id_roles;
			$share_id_members[$shareid]=$share_id_role_members;	

			$share_permission=$adb->query_result($result,$i,'permission');
			if($share_permission == 1)
			{
				if($def_org_share[$mod_tabid] == 3)
				{	
					if(! array_key_exists($share_roleid,$role_read_per))
					{

						$share_role_users=getRoleUserIds($share_roleid);
						$role_read_per[$share_roleid]=$share_role_users;
					}
				}
				if(! array_key_exists($share_roleid,$role_write_per))
				{

					$share_role_users=getRoleUserIds($share_roleid);
					$role_write_per[$share_roleid]=$share_role_users;
				}
			}
			elseif($share_permission == 0 && $def_org_share[$mod_tabid] == 3)
			{
				if(! array_key_exists($share_roleid,$role_read_per))
				{

					$share_role_users=getRoleUserIds($share_roleid);
					$role_read_per[$share_roleid]=$share_role_users;
				}

			}

		}



		//Retreiving from rs to role
		$query="select datashare_rs2role.* from datashare_rs2role inner join datashare_module_rel on datashare_module_rel.shareid=datashare_rs2role.shareid where datashare_module_rel.tabid=".$mod_tabid." and datashare_rs2role.to_roleid='".$current_user_roles."'";
		$result=$adb->query($query);
		$num_rows=$adb->num_rows($result);
		for($i=0;$i<$num_rows;$i++)
		{
			$share_rsid=$adb->query_result($result,$i,'share_roleandsubid');
			$share_roleids=getRoleAndSubordinatesRoleIds($share_rsid);
			$share_permission=$adb->query_result($result,$i,'permission');

			$shareid=$adb->query_result($result,$i,'shareid');
			$share_id_role_members=Array();
			$share_id_roles=Array();
			foreach($share_roleids as $share_roleid)
			{
				$share_id_roles[]=$share_roleid;
				

				if($share_permission == 1)
				{
					if($def_org_share[$mod_tabid] == 3)
					{	
						if(! array_key_exists($share_roleid,$role_read_per))
						{

							$share_role_users=getRoleUserIds($share_roleid);
							$role_read_per[$share_roleid]=$share_role_users;
						}
					}
					if(! array_key_exists($share_roleid,$role_write_per))
					{

						$share_role_users=getRoleUserIds($share_roleid);
						$role_write_per[$share_roleid]=$share_role_users;
					}
				}
				elseif($share_permission == 0 && $def_org_share[$mod_tabid] == 3)
				{
					if(! array_key_exists($share_roleid,$role_read_per))
					{

						$share_role_users=getRoleUserIds($share_roleid);
						$role_read_per[$share_roleid]=$share_role_users;
					}

				}
			}
			$share_id_role_members['ROLE']=$share_id_roles;
			$share_id_members[$shareid]=$share_id_role_members;

		}


		//Retreiving from rs to rs
		$parRoleList = "(";
		foreach($parent_roles as $par_role_id)
		{
			$parRoleList .= "'".$par_role_id."',";		
		}
		$parRoleList .= "'".$current_user_roles."')";
		$query="select datashare_rs2rs.* from datashare_rs2rs inner join datashare_module_rel on datashare_module_rel.shareid=datashare_rs2rs.shareid where datashare_module_rel.tabid=".$mod_tabid." and datashare_rs2rs.to_roleandsubid in ".$parRoleList;
		$result=$adb->query($query);
		$num_rows=$adb->num_rows($result);
		for($i=0;$i<$num_rows;$i++)
		{
			$share_rsid=$adb->query_result($result,$i,'share_roleandsubid');
			$share_roleids=getRoleAndSubordinatesRoleIds($share_rsid);
			$share_permission=$adb->query_result($result,$i,'permission');
		
			$shareid=$adb->query_result($result,$i,'shareid');
			$share_id_role_members=Array();
			$share_id_roles=Array();
			foreach($share_roleids as $share_roleid)
			{

				$share_id_roles[]=$share_roleid;

				if($share_permission == 1)
				{
					if($def_org_share[$mod_tabid] == 3)
					{	
						if(! array_key_exists($share_roleid,$role_read_per))
						{

							$share_role_users=getRoleUserIds($share_roleid);
							$role_read_per[$share_roleid]=$share_role_users;
						}
					}
					if(! array_key_exists($share_roleid,$role_write_per))
					{

						$share_role_users=getRoleUserIds($share_roleid);
						$role_write_per[$share_roleid]=$share_role_users;
					}
				}
				elseif($share_permission == 0 && $def_org_share[$mod_tabid] == 3)
				{
					if(! array_key_exists($share_roleid,$role_read_per))
					{

						$share_role_users=getRoleUserIds($share_roleid);
						$role_read_per[$share_roleid]=$share_role_users;
					}

				}
			}
			$share_id_role_members['ROLE']=$share_id_roles;
			$share_id_members[$shareid]=$share_id_role_members;	

		}


		//Get roles from Rs2Grp


		$query="select datashare_rs2grp.* from datashare_rs2grp inner join datashare_module_rel on datashare_module_rel.shareid=datashare_rs2grp.shareid where datashare_module_rel.tabid=".$mod_tabid." and datashare_rs2grp.to_groupid in ".$groupList;
		$result=$adb->query($query);
		$num_rows=$adb->num_rows($result);
		for($i=0;$i<$num_rows;$i++)
		{
			$share_rsid=$adb->query_result($result,$i,'share_roleandsubid');
			$share_roleids=getRoleAndSubordinatesRoleIds($share_rsid);
			$share_permission=$adb->query_result($result,$i,'permission');

			$shareid=$adb->query_result($result,$i,'shareid');
			$share_id_role_members=Array();
			$share_id_roles=Array();

			foreach($share_roleids as $share_roleid)
			{
				
				$share_id_roles[]=$share_roleid;
			
				if($share_permission == 1)
				{
					if($def_org_share[$mod_tabid] == 3)
					{	
						if(! array_key_exists($share_roleid,$role_read_per))
						{

							$share_role_users=getRoleUserIds($share_roleid);
							$role_read_per[$share_roleid]=$share_role_users;
						}
					}
					if(! array_key_exists($share_roleid,$role_write_per))
					{

						$share_role_users=getRoleUserIds($share_roleid);
						$role_write_per[$share_roleid]=$share_role_users;
					}
				}
				elseif($share_permission == 0 && $def_org_share[$mod_tabid] == 3)
				{
					if(! array_key_exists($share_roleid,$role_read_per))
					{

						$share_role_users=getRoleUserIds($share_roleid);
						$role_read_per[$share_roleid]=$share_role_users;
					}

				}
			}
			$share_id_role_members['ROLE']=$share_id_roles;
			$share_id_members[$shareid]=$share_id_role_members;



		}
		$mod_share_read_permission['ROLE']=$role_read_per;
		$mod_share_write_permission['ROLE']=$role_write_per;
		//echo '<BR>//////////////////////////<BR>';
		//print_r($mod_share_read_permission);
		//echo '<BR>//////////////////////////<BR>';
		//print_r($mod_share_write_permission);

		//Retreiving from the grp2role sharing
		$query="select datashare_grp2role.* from datashare_grp2role inner join datashare_module_rel on datashare_module_rel.shareid=datashare_grp2role.shareid where datashare_module_rel.tabid=".$mod_tabid." and datashare_grp2role.to_roleid='".$current_user_roles."'";
		$result=$adb->query($query);
		$num_rows=$adb->num_rows($result);
		for($i=0;$i<$num_rows;$i++)
		{
			$share_grpid=$adb->query_result($result,$i,'share_groupid');
			$share_permission=$adb->query_result($result,$i,'permission');

			$shareid=$adb->query_result($result,$i,'shareid');
			$share_id_grp_members=Array();
			$share_id_grps=Array();
			$share_id_grps[]=$share_grpid;
			$share_id_grp_members['GROUP']=$share_id_grps;
			$share_id_members[$shareid]=$share_id_grp_members;

			if($share_permission == 1)
			{
				if($def_org_share[$mod_tabid] == 3)
				{	
					if(! array_key_exists($share_grpid,$grp_read_per))
					{
						$focusGrpUsers = new GetGroupUsers();
						$focusGrpUsers->getAllUsersInGroup($share_grpid);
						$share_grp_users=$focusGrpUsers->group_users;
						$grp_read_per[$share_grpid]=$share_grp_users;
					}
				}
				if(! array_key_exists($share_grpid,$grp_write_per))
				{
					$focusGrpUsers = new GetGroupUsers();
					$focusGrpUsers->getAllUsersInGroup($share_grpid);
					$share_grp_users=$focusGrpUsers->group_users;
					$grp_write_per[$share_grpid]=$share_grp_users;

				}
			}
			elseif($share_permission == 0 && $def_org_share[$mod_tabid] == 3)
			{
				if(! array_key_exists($share_grpid,$grp_read_per))
				{
					$focusGrpUsers = new GetGroupUsers();
					$focusGrpUsers->getAllUsersInGroup($share_grpid);
					$share_grp_users=$focusGrpUsers->group_users;
					$grp_read_per[$share_grpid]=$share_grp_users;
				}

			}

		}

		//Retreiving from the grp2rs sharing


		$query="select datashare_grp2rs.* from datashare_grp2rs inner join datashare_module_rel on datashare_module_rel.shareid=datashare_grp2rs.shareid where datashare_module_rel.tabid=".$mod_tabid." and datashare_grp2rs.to_roleandsubid in ".$parRoleList;
		$result=$adb->query($query);
		$num_rows=$adb->num_rows($result);
		for($i=0;$i<$num_rows;$i++)
		{
			$share_grpid=$adb->query_result($result,$i,'share_groupid');
			$share_permission=$adb->query_result($result,$i,'permission');

			$shareid=$adb->query_result($result,$i,'shareid');
			$share_id_grp_members=Array();
			$share_id_grps=Array();
			$share_id_grps[]=$share_grpid;
			$share_id_grp_members['GROUP']=$share_id_grps;
			$share_id_members[$shareid]=$share_id_grp_members;

			if($share_permission == 1)
			{
				if($def_org_share[$mod_tabid] == 3)
				{	
					if(! array_key_exists($share_grpid,$grp_read_per))
					{
						$focusGrpUsers = new GetGroupUsers();
						$focusGrpUsers->getAllUsersInGroup($share_grpid);
						$share_grp_users=$focusGrpUsers->group_users;
						$grp_read_per[$share_grpid]=$share_grp_users;
					}
				}
				if(! array_key_exists($share_grpid,$grp_write_per))
				{
					$focusGrpUsers = new GetGroupUsers();
					$focusGrpUsers->getAllUsersInGroup($share_grpid);
					$share_grp_users=$focusGrpUsers->group_users;
					$grp_write_per[$share_grpid]=$share_grp_users;

				}
			}
			elseif($share_permission == 0 && $def_org_share[$mod_tabid] == 3)
			{
				if(! array_key_exists($share_grpid,$grp_read_per))
				{
					$focusGrpUsers = new GetGroupUsers();
					$focusGrpUsers->getAllUsersInGroup($share_grpid);
					$share_grp_users=$focusGrpUsers->group_users;
					$grp_read_per[$share_grpid]=$share_grp_users;
				}

			}

		}

		//Retreiving from the grp2grp sharing

		$query="select datashare_grp2grp.* from datashare_grp2grp inner join datashare_module_rel on datashare_module_rel.shareid=datashare_grp2grp.shareid where datashare_module_rel.tabid=".$mod_tabid." and datashare_grp2grp.to_groupid in ".$groupList;
		$result=$adb->query($query);
		$num_rows=$adb->num_rows($result);
		for($i=0;$i<$num_rows;$i++)
		{
			$share_grpid=$adb->query_result($result,$i,'share_groupid');
			$share_permission=$adb->query_result($result,$i,'permission');
		
			$shareid=$adb->query_result($result,$i,'shareid');
			$share_id_grp_members=Array();
			$share_id_grps=Array();
			$share_id_grps[]=$share_grpid;
			$share_id_grp_members['GROUP']=$share_id_grps;
			$share_id_members[$shareid]=$share_id_grp_members;

			if($share_permission == 1)
			{
				if($def_org_share[$mod_tabid] == 3)
				{	
					if(! array_key_exists($share_grpid,$grp_read_per))
					{
						$focusGrpUsers = new GetGroupUsers();
						$focusGrpUsers->getAllUsersInGroup($share_grpid);
						$share_grp_users=$focusGrpUsers->group_users;
						$grp_read_per[$share_grpid]=$share_grp_users;
					}
				}
				if(! array_key_exists($share_grpid,$grp_write_per))
				{
					$focusGrpUsers = new GetGroupUsers();
					$focusGrpUsers->getAllUsersInGroup($share_grpid);
					$share_grp_users=$focusGrpUsers->group_users;
					$grp_write_per[$share_grpid]=$share_grp_users;

				}
			}
			elseif($share_permission == 0 && $def_org_share[$mod_tabid] == 3)
			{
				if(! array_key_exists($share_grpid,$grp_read_per))
				{
					$focusGrpUsers = new GetGroupUsers();
					$focusGrpUsers->getAllUsersInGroup($share_grpid);
					$share_grp_users=$focusGrpUsers->group_users;
					$grp_read_per[$share_grpid]=$share_grp_users;
				}

			}

		}
		$mod_share_read_permission['GROUP']=$grp_read_per;
		$mod_share_write_permission['GROUP']=$grp_write_per;	
	}
	$mod_share_permission['read']=$mod_share_read_permission;
	$mod_share_permission['write']=$mod_share_write_permission;
	$mod_share_permission['sharingrules']=$share_id_members;	
	return $mod_share_permission;
}

/*
function getRelatedModuleSharingArray($par_mod,$share_mod,$account_sharingrule_members,$account_share_read_per,$account_share_write_per)
{
	global $adb;
	foreach($account_sharingrule_members as $sharingid => $sharingInfoArr)
	{
		
	}
}
*/
function constructArray($var)
{
	if (is_array($var))
	{
       		$code = 'array(';
       		foreach ($var as $key => $value)
		{
           		$code .= $key.'=>'.$value.',';
       		}
       		$code .= ')';
       		return $code;
   	}
}

function constructSingleArray($var)
{
	if (is_array($var))
	{
       		$code = 'array(';
       		foreach ($var as $value)
		{
           		$code .= $value.',';
       		}
       		$code .= ')';
       		return $code;
   	}
}

function constructSingleCharArray($var)
{
	if (is_array($var))
	{
       		$code = "array(";
       		foreach ($var as $value)
		{
           		$code .="'".$value."',";
       		}
       		$code .= ")";
       		return $code;
   	}
}

function constructTwoDimensionalArray($var)
{
	if (is_array($var))
	{
       		$code = 'array(';
       		foreach ($var as $key => $secarr)
		{
           		$code .= $key.'=>array(';
			foreach($secarr as $seckey => $secvalue)
			{
				$code .= $seckey.'=>'.$secvalue.',';
			}
			$code .= '),';
       		}
       		$code .= ')';
       		return $code;
   	}
}


function constructTwoDimensionalCharIntSingleArray($var)
{
	if (is_array($var))
	{
       		$code = "array(";
       		foreach ($var as $key => $secarr)
		{
           		$code .= "'".$key."'=>array(";
			foreach($secarr as $seckey => $secvalue)
			{
				$code .= $seckey.",";
			}
			$code .= "),";
       		}
       		$code .= ")";
       		return $code;
   	}
}
?>

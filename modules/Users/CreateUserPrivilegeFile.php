<?php
require_once('config.php');
require_once('modules/Users/User.php');
require_once('modules/Users/UserInfoUtil.php');
require_once('include/utils.php');


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

			
			$newbuf .= "\$current_user_roles=".fetchUserRole($userid).";\n";
			$newbuf .= "\n";
			$newbuf .= "\$current_user_profiles=".constructSingleArray(getUserProfile($userid)).";\n";
			$newbuf .= "\n";
			$newbuf .= "\$profileGlobalPermission=".constructArray($globalPermissionArr).";\n";
			$newbuf .="\n";		
			$newbuf .= "\$profileTabsPermission=".constructArray($tabsPermissionArr).";\n";
			$newbuf .="\n";		
			$newbuf .= "\$profileActionPermission=".constructTwoDimensionalArray($actionPermissionArr).";\n";
			$newbuf .= "?>";
			fputs($handle, $newbuf);
			fclose($handle);
		}
	}
}
//print_r(getCombinedUserGlobalPermissions($userid));
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
			$code .= ')';
       		}
       		$code .= ')';
       		return $code;
   	}
}

?>

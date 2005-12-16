<?php
require_once('XTemplate/xtpl.php');
require_once('include/utils/UserInfoUtil.php');
require_once('include/utils/utils.php');

global $app_strings;
global $mod_strings;
global $current_user;
global $adb;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
$profileId=$_REQUEST['profileid'];
$profileName=getProfileName($profileId);

$xtpl=new XTemplate ('modules/Users/profilePrivileges.html');
$secondaryModule='';
$mode='';
$output ='';
$output1 ='';
$xtpl->assign("PROFILEID", $profileId);
$xtpl->assign("PROFILE_NAME", $profileName);

//Initially setting the secondary selected tab
if(isset($_REQUEST['secmodule']) && $_REQUEST['secmodule'] != '')
{
	$secondaryModule=$_REQUEST['secmodule'];
	$mode=$_REQUEST['mode'];

}
else
{
	
	$secondaryModule='global_priv';
	$mode='view'; 
	
}

if($secondaryModule == 'global_priv')
{
		$xtpl->assign("GLOBAL_PRIV_CLASS", 'prvPrfSelectedTab');
		$xtpl->assign("TAB_PRIV_CLASS", 'prvPrfUnSelectedTab');
		$xtpl->assign("STAND_PRIV_CLASS", 'prvPrfUnSelectedTab');
		$xtpl->assign("UTIL_PRIV_CLASS", 'prvPrfUnSelectedTab');
		$xtpl->assign("FIELD_PRIV_CLASS", 'prvPrfUnSelectedTab');

		if($mode == 'view')
		{
			$edit_save='<a href="index.php?module=Users&action=profilePrivileges&mode=edit&secmodule=global_priv&profileid='.$profileId.'">Edit Privileges</a>';
			$xtpl->assign("EDIT_SAVE", $edit_save);

			$global_per_arry = getProfileGlobalPermission($profileId);
			$view_all_per = $global_per_arry[1];
			$edit_all_per = $global_per_arry[2];

			$output .= '<tr>';
                        $output .= '<td width=80%>View All</td>';
                        $output .= '<td width=20%>'.getGlobalDisplayValue($view_all_per,1).'</td>';
                        $output .= '</tr>';
			$output .='<tr><td colspan=4 style="border-top:1px dashed #ebebeb"></td></tr>';

			$output1 .= '<tr>';
                        $output1 .= '<td width=80%>Edit All</td>';
                        $output1 .= '<td width=20%>'.getGlobalDisplayValue($edit_all_per,2).'</td>';
                        $output1 .= '</tr>';
			$output1 .='<tr><td colspan=4 style="border-top:1px dashed #ebebeb"></td></tr>';

			$xtpl->assign("OUTPUT", $output);
			$xtpl->assign("OUTPUT1", $output1);
				
		}
		elseif($mode == 'edit')
		{
			$edit_save .= '<input type="hidden" name="module" value="Users">';
        		$edit_save .= '<input type="hidden" name="profileid" value="'.$profileId.'">';
		        $edit_save .= '<input type="hidden" name="action" value="UpdateProfileChanges">';
		        $edit_save .= '<input type="hidden" name="secmodule" value="global_priv">';	
		        $edit_save .= '<input type="hidden" name="mode" value="save">';
		        $edit_save .= '<input title="Save" accessKey="S" class="button" type="submit" name="Save" value="Save">';
			$xtpl->assign("EDIT_SAVE", $edit_save);

			$global_per_arry = getProfileGlobalPermission($profileId);
			//print_r($global_per_arry);
			$view_all_per = $global_per_arry[1];
			$edit_all_per = $global_per_arry[2];

			$output .= '<tr>';
                        $output .= '<td width=80%>View All</td>';
                        $output .= '<td width=20%>'.getGlobalDisplayOutput($view_all_per,1).'</td>';
                        $output .= '</tr>';
			$output .='<tr><td colspan=4 style="border-top:1px dashed #ebebeb"></td></tr>';

			$output1 .= '<tr>';
                        $output1 .= '<td width=80%>Edit All</td>';
                        $output1 .= '<td width=20%>'.getGlobalDisplayOutput($edit_all_per,2).'</td>';
                        $output1 .= '</tr>';
			$output1 .='<tr><td colspan=4 style="border-top:1px dashed #ebebeb"></td></tr>';

			$xtpl->assign("OUTPUT", $output);
			$xtpl->assign("OUTPUT1", $output1);	
				
					
	
		}
		


}
elseif($secondaryModule == 'stand_priv')
{
		$xtpl->assign("GLOBAL_PRIV_CLASS", 'prvPrfUnSelectedTab');
		$xtpl->assign("TAB_PRIV_CLASS", 'prvPrfUnSelectedTab');
		$xtpl->assign("STAND_PRIV_CLASS", 'prvPrfSelectedTab');
		$xtpl->assign("UTIL_PRIV_CLASS", 'prvPrfUnSelectedTab');
		$xtpl->assign("FIELD_PRIV_CLASS", 'prvPrfUnSelectedTab');

		if($mode == 'view')
		{
			//Updating the Edit Save Option
			$edit_save='<a href="index.php?module=Users&action=profilePrivileges&mode=edit&secmodule=stand_priv&profileid='.$profileId.'">Edit Privileges</a>';
			$xtpl->assign("EDIT_SAVE", $edit_save);	

			$output1 .= '<tr>';
			$output1 .= '<td width=33%>Create/Edit</td>';
			$output1 .= '<td width=33%>Delete</td>';
			$output1 .= '<td width=34%>View</td>';
			$output1 .= '</tr>';
			$output1 .='<tr><td colspan=4 style="border-top:1px dashed #ebebeb"></td></tr>';
			$output .= '<tr>';
			$output .= '<td width=80%>Entity</td>';
			$output .= '<td width=20%></td>';
			$output .= '</tr>';
			$output .='<tr><td colspan=4 style="border-top:1px dashed #ebebeb"></td></tr>';
			$act_perr_arry = getTabsActionPermission($profileId);	
			foreach($act_perr_arry as $tabid=>$action_array)
			{
				$entity_name = getTabname($tabid);
				//Create/Edit Permission
				$tab_create_per_id = $action_array['1'];
				$tab_create_per = getDisplayValue($tab_create_per_id,$tabid,'1');
				//Delete Permission
				$tab_delete_per_id = $action_array['2'];
				$tab_delete_per = getDisplayValue($tab_delete_per_id,$tabid,'2');
				//View Permission
				$tab_view_per_id = $action_array['4'];
				$tab_view_per = getDisplayValue($tab_view_per_id,$tabid,'4');

				$output1 .= '<tr>';
				$output1 .= '<td width=33%>'.$tab_create_per.'</td>';
				$output1 .= '<td width=33%>'.$tab_delete_per.'</td>';
				$output1 .= '<td width=34%>'.$tab_view_per.'</td>';
				$output1 .= '</tr>';
				$output1 .='<tr><td colspan=4 style="border-top:1px dashed #ebebeb"></td></tr>';
				$output .= '<tr>';
				$output .= '<td width=80%>'.$entity_name.'</td>';
				$output .= '<td width=20%></td>';
				$output .= '</tr>';
				$output .='<tr><td colspan=4 style="border-top:1px dashed #ebebeb"></td></tr>';
			}
				
	
		}
		if($mode == 'edit')
		{
			$edit_save .= '<input type="hidden" name="module" value="Users">';
                        $edit_save .= '<input type="hidden" name="profileid" value="'.$profileId.'">';
                        $edit_save .= '<input type="hidden" name="action" value="UpdateProfileChanges">';
                        $edit_save .= '<input type="hidden" name="secmodule" value="stand_priv">';
                        $edit_save .= '<input type="hidden" name="mode" value="save">';
	                $edit_save .= '<input title="Save" accessKey="S" class="button" type="submit" name="Save" value="Save">';
                        $xtpl->assign("EDIT_SAVE", $edit_save);
			

			$output1 .= '<tr>';
			$output1 .= '<td width=33%>Create/Edit</td>';
			$output1 .= '<td width=33%>Delete</td>';
			$output1 .= '<td width=34%>View</td>';
			$output1 .= '</tr>';
			$output1 .='<tr><td colspan=4 style="border-top:1px dashed #ebebeb"></td></tr>';
			$output .= '<tr>';
			$output .= '<td width=80%>Entity</td>';
			$output .= '<td width=20%></td>';
			$output .= '</tr>';
			$output .='<tr><td colspan=4 style="border-top:1px dashed #ebebeb"></td></tr>';
			$act_perr_arry = getTabsActionPermission($profileId);	
			foreach($act_perr_arry as $tabid=>$action_array)
			{
				$entity_name = getTabname($tabid);
				//Create/Edit Permission
				$tab_create_per_id = $action_array['1'];
				$tab_create_per = getDisplayOutput($tab_create_per_id,$tabid,'1');
				//Delete Permission
				$tab_delete_per_id = $action_array['2'];
				$tab_delete_per = getDisplayOutput($tab_delete_per_id,$tabid,'2');
				//View Permission
				$tab_view_per_id = $action_array['4'];
				$tab_view_per = getDisplayOutput($tab_view_per_id,$tabid,'4');

				$output1 .= '<tr>';
				$output1 .= '<td width=33%>'.$tab_create_per.'</td>';
				$output1 .= '<td width=33%>'.$tab_delete_per.'</td>';
				$output1 .= '<td width=34%>'.$tab_view_per.'</td>';
				$output1 .= '</tr>';
				$output1 .='<tr><td colspan=4 style="border-top:1px dashed #ebebeb"></td></tr>';
				$output .= '<tr>';
				$output .= '<td width=80%>'.$entity_name.'</td>';
				$output .= '<td width=20%></td>';
				$output .= '</tr>';
				$output .='<tr><td colspan=4 style="border-top:1px dashed #ebebeb"></td></tr>';
			}
				
	
		}
		$xtpl->assign("OUTPUT", $output);
		$xtpl->assign("OUTPUT1", $output1);

}
elseif($secondaryModule == 'tab_priv')
{
		$xtpl->assign("GLOBAL_PRIV_CLASS", 'prvPrfUnSelectedTab');
		$xtpl->assign("TAB_PRIV_CLASS", 'prvPrfSelectedTab');
		$xtpl->assign("STAND_PRIV_CLASS", 'prvPrfUnSelectedTab');
		$xtpl->assign("UTIL_PRIV_CLASS", 'prvPrfUnSelectedTab');
		$xtpl->assign("FIELD_PRIV_CLASS", 'prvPrfUnSelectedTab');

		if($mode == 'view')
		{
			$edit_save='<a href="index.php?module=Users&action=profilePrivileges&mode=edit&secmodule=tab_priv&profileid='.$profileId.'">Edit Privileges</a>';
                        $xtpl->assign("EDIT_SAVE", $edit_save);
			
			$tab_perr_array = getTabsPermission($profileId);
			$no_of_tabs =  sizeof($tab_perr_array);
			$i=1;
		        foreach($tab_perr_array as $tabid=>$tab_perr)
        		{
				$entity_name = getTabname($tabid);
				$tab_allow_per_id = $tab_perr_array[$tabid];
		                $tab_allow_per = getDisplayValue($tab_allow_per_id,$tabid,'');	

				if ($i%2==0)
				{
					$output1 .= '<tr>';
	                        	$output1 .= '<td width=80%>'.$entity_name.'</td>';
	        	                $output1 .= '<td width=20%>'.$tab_allow_per.'</td>';
 	  	             	        $output1 .= '</tr>';
					$output1 .='<tr><td colspan=4 style="border-top:1px dashed #ebebeb"></td></tr>';
				}
				else
				{
					$output .= '<tr>';
	                        	$output .= '<td width=80%>'.$entity_name.'</td>';
	        	                $output .= '<td width=20%>'.$tab_allow_per.'</td>';
 	  	             	        $output .= '</tr>';
					$output .='<tr><td colspan=4 style="border-top:1px dashed #ebebeb"></td></tr>';
				}
				$i++;	
			}
			$xtpl->assign("OUTPUT", $output);
			$xtpl->assign("OUTPUT1", $output1);	
	
		}
		if($mode == 'edit')
		{
			$edit_save .= '<input type="hidden" name="module" value="Users">';
                        $edit_save .= '<input type="hidden" name="profileid" value="'.$profileId.'">';
                        $edit_save .= '<input type="hidden" name="action" value="UpdateProfileChanges">';
                        $edit_save .= '<input type="hidden" name="secmodule" value="tab_priv">';
                        $edit_save .= '<input type="hidden" name="mode" value="save">';
	                $edit_save .= '<input title="Save" accessKey="S" class="button" type="submit" name="Save" value="Save">';
                        $xtpl->assign("EDIT_SAVE", $edit_save);

			$tab_perr_array = getTabsPermission($profileId);
			$no_of_tabs =  sizeof($tab_perr_array);
			$i=1;
		        foreach($tab_perr_array as $tabid=>$tab_perr)
        		{
				$entity_name = getTabname($tabid);
				$tab_allow_per_id = $tab_perr_array[$tabid];
		                $tab_allow_per = getDisplayOutput($tab_allow_per_id,$tabid,'');	

				if ($i%2==0)
				{
					$output1 .= '<tr>';
	                        	$output1 .= '<td width=80%>'.$entity_name.'</td>';
	        	                $output1 .= '<td width=20%>'.$tab_allow_per.'</td>';
 	  	             	        $output1 .= '</tr>';
					$output1 .='<tr><td colspan=4 style="border-top:1px dashed #ebebeb"></td></tr>';
				}
				else
				{
					$output .= '<tr>';
	                        	$output .= '<td width=80%>'.$entity_name.'</td>';
	        	                $output .= '<td width=20%>'.$tab_allow_per.'</td>';
 	  	             	        $output .= '</tr>';
					$output .='<tr><td colspan=4 style="border-top:1px dashed #ebebeb"></td></tr>';
				}
				$i++;	
			}
			$xtpl->assign("OUTPUT", $output);
			$xtpl->assign("OUTPUT1", $output1);	
	
		}


}
elseif($secondaryModule == 'util_priv')
{
	$xtpl->assign("GLOBAL_PRIV_CLASS", 'prvPrfUnSelectedTab');
	$xtpl->assign("TAB_PRIV_CLASS", 'prvPrfUnSelectedTab');
	$xtpl->assign("STAND_PRIV_CLASS", 'prvPrfUnSelectedTab');
	$xtpl->assign("UTIL_PRIV_CLASS", 'prvPrfSelectedTab');
	$xtpl->assign("FIELD_PRIV_CLASS", 'prvPrfUnSelectedTab');
	$i=1;
	if($mode == 'view')
	{

		$edit_save='<a href="index.php?module=Users&action=profilePrivileges&mode=edit&secmodule=util_priv&profileid='.$profileId.'">Edit Privileges</a>';
                $xtpl->assign("EDIT_SAVE", $edit_save);

		$act_utility_arry = getTabsUtilityActionPermission($profileId);

		foreach($act_utility_arry as $tabid=>$action_array)
		{

			$entity_name = getTabname($tabid);

			$output .= '<tr>';
			$output .= '<td width=80%><b>'.$entity_name.'</b></td>';
			$output .= '<td width=20%></td>';
			$output .= '</tr>';
			$output .='<tr><td colspan=4 style="border-top:1px dashed #ebebeb"></td></tr>';

			$output1 .= '<tr>';
			$output1 .= '<td width=80%></td>';
			$output1 .= '<td width=20%></td>';
			$output1 .= '</tr>';
			$output1 .='<tr><td colspan=4 style="border-top:1px dashed #ebebeb"></td></tr>';

		
			$k=1;
			$no_of_actions=sizeof($action_array);
			foreach($action_array as $action_id=>$act_per)
			{
				

				$action_name = getActionName($action_id);
				$tab_util_act_per = $action_array[$action_id];
				$tab_util_per = getDisplayValue($tab_util_act_per,$tabid,$action_id);

				

				if($k%2 == 0)
				{
					$output1 .= '<tr>';
					$output1 .= '<td width=80%>'.$action_name.'</td>';
					$output1 .= '<td width=20%>'.$tab_util_per.'</td>';
					$output1 .= '</tr>';
					$output1 .='<tr><td colspan=4 style="border-top:1px dashed #ebebeb"></td></tr>';
					

				} 
				else
				{
				
					$output .= '<tr>';
					$output .= '<td width=80%>'.$action_name.'</td>';
					$output .= '<td width=20%>'.$tab_util_per.'</td>';
					$output .= '</tr>';
					$output .='<tr><td colspan=4 style="border-top:1px dashed #ebebeb"></td></tr>';
					if($k == $no_of_actions)
					{
						$output1 .= '<tr>';
						$output1 .= '<td width=80%></td>';
						$output1 .= '<td width=20%></td>';
						$output1 .= '</tr>';
						$output1 .='<tr><td colspan=4 style="border-top:1px dashed #ebebeb"></td></tr>';
					}
				}
				$k++;	




			}

		}




		

	}
	elseif($mode == 'edit')
	{
		$edit_save .= '<input type="hidden" name="module" value="Users">';
                $edit_save .= '<input type="hidden" name="profileid" value="'.$profileId.'">';
                $edit_save .= '<input type="hidden" name="action" value="UpdateProfileChanges">';
                $edit_save .= '<input type="hidden" name="secmodule" value="util_priv">';
                $edit_save .= '<input type="hidden" name="mode" value="save">';
	        $edit_save .= '<input title="Save" accessKey="S" class="button" type="submit" name="Save" value="Save">';
                        $xtpl->assign("EDIT_SAVE", $edit_save);	

		$act_utility_arry = getTabsUtilityActionPermission($profileId);

		foreach($act_utility_arry as $tabid=>$action_array)
		{

			$entity_name = getTabname($tabid);

			$output .= '<tr>';
			$output .= '<td width=80%><b>'.$entity_name.'</b></td>';
			$output .= '<td width=20%></td>';
			$output .= '</tr>';
			$output .='<tr><td colspan=4 style="border-top:1px dashed #ebebeb"></td></tr>';

			$output1 .= '<tr>';
			$output1 .= '<td width=80%></td>';
			$output1 .= '<td width=20%></td>';
			$output1 .= '</tr>';
			$output1 .='<tr><td colspan=4 style="border-top:1px dashed #ebebeb"></td></tr>';

		
			$k=1;
			$no_of_actions=sizeof($action_array);
			foreach($action_array as $action_id=>$act_per)
			{
				

				$action_name = getActionName($action_id);
				$tab_util_act_per = $action_array[$action_id];
				$tab_util_per = getDisplayOutput($tab_util_act_per,$tabid,$action_id);

				

				if($k%2 == 0)
				{
					$output1 .= '<tr>';
					$output1 .= '<td width=80%>'.$action_name.'</td>';
					$output1 .= '<td width=20%>'.$tab_util_per.'</td>';
					$output1 .= '</tr>';
					$output1 .='<tr><td colspan=4 style="border-top:1px dashed #ebebeb"></td></tr>';
					

				} 
				else
				{
				
					$output .= '<tr>';
					$output .= '<td width=80%>'.$action_name.'</td>';
					$output .= '<td width=20%>'.$tab_util_per.'</td>';
					$output .= '</tr>';
					$output .='<tr><td colspan=4 style="border-top:1px dashed #ebebeb"></td></tr>';
					if($k == $no_of_actions)
					{
						$output1 .= '<tr>';
						$output1 .= '<td width=80%></td>';
						$output1 .= '<td width=20%></td>';
						$output1 .= '</tr>';
						$output1 .='<tr><td colspan=4 style="border-top:1px dashed #ebebeb"></td></tr>';
					}
				}
				$k++;	

			}
		}

	}
	$xtpl->assign("OUTPUT", $output);
	$xtpl->assign("OUTPUT1", $output1);

}
elseif($secondaryModule == 'field_priv')
{
		$xtpl->assign("GLOBAL_PRIV_CLASS", 'prvPrfUnSelectedTab');
		$xtpl->assign("TAB_PRIV_CLASS", 'prvPrfUnSelectedTab');
		$xtpl->assign("STAND_PRIV_CLASS", 'prvPrfUnSelectedTab');
		$xtpl->assign("UTIL_PRIV_CLASS", 'prvPrfUnSelectedTab');
		$xtpl->assign("FIELD_PRIV_CLASS", 'prvPrfSelectedTab');
		if($mode == 'list')
		{
			$modArr= Array('Leads'=>'LBL_LEAD_FIELD_ACCESS',
					'Accounts'=>'LBL_ACCOUNT_FIELD_ACCESS',
					'Contacts'=>'LBL_CONTACT_FIELD_ACCESS',
					'Potentials'=>'LBL_OPPORTUNITY_FIELD_ACCESS',
					'HelpDesk'=>'LBL_HELPDESK_FIELD_ACCESS',
					'Products'=>'LBL_PRODUCT_FIELD_ACCESS',
					'Notes'=>'LBL_NOTE_FIELD_ACCESS',
					'Emails'=>'LBL_EMAIL_FIELD_ACCESS',
					'Activities'=>'LBL_TASK_FIELD_ACCESS',
					'Events'=>'LBL_EVENT_FIELD_ACCESS',
					'Vendor'=>'LBL_VENDOR_FIELD_ACCESS',
					'PriceBook'=>'LBL_PB_FIELD_ACCESS',
					'Quotes'=>'LBL_QUOTE_FIELD_ACCESS',
					'PurchaseOrder'=>'LBL_PO_FIELD_ACCESS',
					'SalesOrder'=>'LBL_SO_FIELD_ACCESS',
					'Invoice'=>'LBL_INVOICE_FIELD_ACCESS'
					);

			$no_of_mod=sizeof($modArr);
			for($i=0;$i<$no_of_mod; $i++)
			{
				$fldModule=key($modArr);
				$lang_str=$modArr[$fldModule];	

				$output .= '<tr>';
	                        $output .= '<td width=80%><a href="index.php?module=Users&action=profilePrivileges&mode=view&secmodule=field_priv&profileid='.$profileId.'&fld_module='.$fldModule.'"><img src="'.$image_path.'/bullet.gif" align="absmiddle" border="0" hspace="5">'.$mod_strings[$lang_str].'</a></td>';
        	                $output .= '<td width=20%></td>';
                	        $output .= '</tr>';
				$output .='<tr><td colspan=4 style="border-top:1px dashed #ebebeb"></td></tr>';
				$i++;
				next($modArr);
				$fldModule=key($modArr);
				$lang_str=$modArr[$fldModule];

				$output1 .= '<tr>';
        	                $output1 .= '<td width=80%><a href="index.php?module=Users&action=profilePrivileges&mode=view&secmodule=field_priv&profileid='.$profileId.'&fld_module='.$fldModule.'"><img src="'.$image_path.'/bullet.gif" align="absmiddle" border="0" hspace="5">'.$mod_strings[$lang_str].'</a></td>';
                	        $output1 .= '<td width=20%></td>';
                        	$output1 .= '</tr>';
				$output1 .='<tr><td colspan=4 style="border-top:1px dashed #ebebeb"></td></tr>';
				next($modArr);
			}
			
				
		}
		elseif($mode=='view')
		{
			
			$fld_module=$_REQUEST['fld_module'];
			$xtpl->assign("FIELDMODULE", '&nbsp;&nbsp;<b>- '.$fld_module.' Field Access</b>');	
			$edit_save='<a href="index.php?module=Users&action=profilePrivileges&mode=edit&secmodule=field_priv&profileid='.$profileId.'&fld_module='.$fld_module.'">Edit Privileges</a>';
                        $xtpl->assign("EDIT_SAVE", $edit_save);

			$fieldListResult = getProfile2FieldList($fld_module, $profileId);
			$noofrows = $adb->num_rows($fieldListResult);
			for($i=0; $i<$noofrows; $i++)
			{
				$fldLabel= $adb->query_result($fieldListResult,$i,"fieldlabel");
				if($adb->query_result($fieldListResult,$i,"visible") == 0)
       			        {
                        		$visible = "<img src=".$image_path."/yes.gif>";
                		}
                		else
                		{
		                        $visible = "<img src=".$image_path."/no.gif>";
                		}

				if(($i+1)%2 == 0)
				{
					$output1 .= '<tr>';
	                	        $output1 .= '<td width=80%>'.$fldLabel.'</td>';
		                        $output1 .= '<td width=20%>'.$visible.'</td>';
        		                $output1 .= '</tr>';
					$output1 .='<tr><td colspan=4 style="border-top:1px dashed #ebebeb"></td></tr>';
				}
				else
				{
					$output .= '<tr>';
		                        $output .= '<td width=80%>'.$fldLabel.'</td>';
        			        $output .= '<td width=20%>'.$visible.'</td>';
                	        	$output .= '</tr>';
					$output .='<tr><td colspan=4 style="border-top:1px dashed #ebebeb"></td></tr>';
				}
			}
			
	
		}
		elseif($mode=='edit')
		{
			$fld_module=$_REQUEST['fld_module'];
			$xtpl->assign("FIELDMODULE", '&nbsp;&nbsp;<b>- '.$fld_module.' Field Access</b>');	
			$edit_save .= '<input type="hidden" name="module" value="Users">';
	                $edit_save .= '<input type="hidden" name="profileid" value="'.$profileId.'">';
        	        $edit_save .= '<input type="hidden" name="action" value="UpdateProfileChanges">';
                	$edit_save .= '<input type="hidden" name="secmodule" value="field_priv">';
                	$edit_save .= '<input type="hidden" name="fld_module" value="'.$fld_module.'">';
	                $edit_save .= '<input type="hidden" name="mode" value="save">';
		        $edit_save .= '<input title="Save" accessKey="S" class="button" type="submit" name="Save" value="Save">';
                        $xtpl->assign("EDIT_SAVE", $edit_save);	
			
			$fieldListResult = getProfile2FieldList($fld_module, $profileId);
			$noofrows = $adb->num_rows($fieldListResult);
			for($i=0; $i<$noofrows; $i++)
			{
				$fldLabel= $adb->query_result($fieldListResult,$i,"fieldlabel");
				$uitype = $adb->query_result($fieldListResult,$i,"uitype");
		                $mandatory = '';
                		$readonly = '';

				if($uitype == 2 || $uitype == 51 || $uitype == 6 || $uitype == 22 || $uitype == 73 || $uitype == 24 || $uitype == 81 || $uitype == 50 || $uitype == 23 || $uitype == 16)
                		{
                        		$mandatory = '<font color="red">*</font>';
		                        $readonly = 'disabled';
        	        	}	
				if($adb->query_result($fieldListResult,$i,"visible") == 0)
       			        {
					$visible = "checked";
                		}
                		else
                		{
					$visible = "";
                		}

				if(($i+1)%2 == 0)
				{
					$output1 .= '<tr>';
	                	        $output1 .= '<td width=80%>'.$mandatory.' '.$fldLabel.'</td>';
		                        $output1 .= '<td width=20%><input type="checkbox" name="'.$adb->query_result($fieldListResult,$i,"fieldid").'" '.$visible.' '.$readonly.'></td>';
        		                $output1 .= '</tr>';
					$output1 .='<tr><td colspan=4 style="border-top:1px dashed #ebebeb"></td></tr>';
				}
				else
				{
					$output .= '<tr>';
					$output .= '<td width=80%>'.$mandatory.' '.$fldLabel.'</td>';
		                        $output .= '<td width=20%><input type="checkbox" name="'.$adb->query_result($fieldListResult,$i,"fieldid").'" '.$visible.' '.$readonly.'></td>';
                	        	$output .= '</tr>';
					$output .='<tr><td colspan=4 style="border-top:1px dashed #ebebeb"></td></tr>';
				}
			}
			
	
		}
		$xtpl->assign("OUTPUT", $output);
		$xtpl->assign("OUTPUT1", $output1);
}

$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);

$xtpl->parse("main");

$xtpl->out("main");


function getGlobalDisplayValue($id,$actionid)
{
        global $image_path;
        if($id == '')
        {
                $value = '&nbsp;';
        }
        elseif($id == 0)
        {
                $value = '<img src="'.$image_path.'yes.gif">';
        }
        elseif($id == 1)
        {
                $value = '<img src="'.$image_path.'no.gif">';
        }
        else
        {
                $value = '&nbsp;';
        }

        return $value;

}

function getGlobalDisplayOutput($id,$actionid)
{
        if($actionid == '1')
        {
                $name = 'view_all';
        }
        elseif($actionid == '2')
        {

                $name = 'edit_all';
        }

        if($id == '')
        {
                $value = '';
        }
        elseif($id == 0)
        {
                $value = '<input type="checkbox" name="'.$name.'" checked>';
        }
        elseif($id == 1)
        {
                $value = '<input type="checkbox" name="'.$name.'">';
        }
        return $value;

}

function getDisplayValue($id)
{
        global $image_path;

        if($id == '')
        {
                $value = '&nbsp;';
        }
        elseif($id == 0)
        {
                $value = '<img src="'.$image_path.'yes.gif">';
        }
        elseif($id == 1)
        {
                $value = '<img src="'.$image_path.'no.gif">';
        }
        else
        {
                $value = '&nbsp;';
        }
        return $value;

}

function getDisplayOutput($id,$tabid,$actionid)
{
        if($actionid == '')
        {
                $name = $tabid.'_tab';
        }
        else
        {
                $temp_name = getActionname($actionid);
                $name = $tabid.'_'.$temp_name;
        }



        if($id == '')
        {
                $value = '';
        }
        elseif($id == 0)
        {
                $value = '<input type="checkbox" name="'.$name.'" checked>';
        }
        elseif($id == 1)
        {
                $value = '<input type="checkbox" name="'.$name.'">';
        }
        return $value;

}

?>

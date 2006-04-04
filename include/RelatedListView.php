<?php
require_once('include/utils/UserInfoUtil.php');
require_once("include/utils/utils.php");

function GetRelatedList($module,$relatedmodule,$focus,$query,$button,$returnset,$edit_val='',$del_val='')
{

	require_once('Smarty_setup.php');
	require_once("data/Tracker.php");
	require_once('include/database/PearDatabase.php');

	global $adb;
	global $app_strings;
	global $current_language;

	$current_module_strings = return_module_language($current_language, $module);

	global $list_max_entries_per_page;
	global $urlPrefix;

	$log = LoggerManager::getLogger('account_list');

	global $currentModule;
	global $theme;
	global $theme_path;
	global $theme_path;
	global $mod_strings;
	// focus_list is the means of passing data to a ListView.
	global $focus_list;
	$smarty = new vtigerCRM_Smarty;
	if (!isset($where)) $where = "";

	if (isset($_REQUEST['order_by'])) $order_by = $_REQUEST['order_by'];

	$button = '<table cellspacing=0 cellpadding=2><tr><td>'.$button.'</td></tr></table>';

	// Added to have Purchase Order as form Title
	if($relatedmodule == 'Orders') 
	{
		$smarty->assign('ADDBUTTON',get_form_header($app_strings['PurchaseOrder'],$button, false));
	}
	else
	{
		$smarty->assign('ADDBUTTON',get_form_header($app_strings[$relatedmodule],$button, false));
	}

	require_once('themes/'.$theme.'/layout_utils.php');
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	$smarty->assign("MOD", $mod_strings);
	$smarty->assign("APP", $app_strings);
	$smarty->assign("IMAGE_PATH",$image_path);
	$smarty->assign("MODULE",$relatedmodule);


	//Retreive the list from Database
	//$query = getListQuery("Accounts");

		//echo '<BR>*****************'.$relatedmodule.' ***************';
	//Appending the security parameter
	if($relatedmodule != 'Notes' && $relatedmodule != 'Products' && $relatedmodule != 'Faq' && $relatedmodule != 'PriceBook' && $relatedmodule != 'Vendors') //Security fix by Don
	{
		global $current_user;
		require('user_privileges/user_privileges_'.$current_user->id.'.php');
        	require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
		$tab_id=getTabid($relatedmodule);
		if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
        	{
        		$sec_parameter=getListViewSecurityParameter($relatedmodule);
                	$query .= ' '.$sec_parameter;

        	}
	}
	


	/*
	global $others_permission_id;
	$rel_tab_id = getTabid($relatedmodule);
	$defSharingPermissionData = $_SESSION['defaultaction_sharing_permission_set'];
	$others_rel_permission_id = $defSharingPermissionData[$rel_tab_id];
	if($others_rel_permission_id == 3 && $relatedmodule != 'Notes' && $relatedmodule != 'Products' && $relatedmodule != 'Faq' && $relatedmodule != 'PriceBook') //Security fix by Don
	{
		$query .= " and crmentity.smownerid in(".$current_user->id .",0)";
	}
	*/

	if(isset($where) && $where != '')
	{
		$query .= ' and '.$where;
	}

	//Appending the group by for Jaguar/Don
	if($relatedmodule == 'Activities')
	{
		$query .= ' group by crmentity.crmid';
	}


	//$url_qry = getURLstring($focus);

	if(isset($order_by) && $order_by != '')
	{
		$query .= ' ORDER BY '.$order_by;
		$url_qry .="&order_by=".$order_by;
	}
	$list_result = $adb->query($query);
	//Retreiving the no of rows
	$noofrows = $adb->num_rows($list_result);

	//Retreiving the start value from request
	if(isset($_REQUEST['start']) && $_REQUEST['start'] != '')
	{
		$start = $_REQUEST['start'];
	}
	else
	{

		$start = 1;
	}
	//Retreive the Navigation array
	$navigation_array = getNavigationValues($start, $noofrows, $list_max_entries_per_page);

	//Retreive the List View Table Header
	if($noofrows == 0)
	{
		$smarty->assign('NOENTRIES',$app_strings['LBL_NONE_SCHEDULED']);
	}
	else
	{
		$listview_header = getListViewHeader($focus,$relatedmodule,'','','','relatedlist');//"Accounts");
		if ($noofrows > 15)
		{
			$smarty->assign('SCROLLSTART','<div style="overflow:auto;height:315px;width:100%;">');
			$smarty->assign('SCROLLSTOP','</div>');
		}
		$smarty->assign("LISTHEADER", $listview_header);

		if($module == 'PriceBook' && $relatedmodule == 'Products')
		{
			$listview_entries = getListViewEntries($focus,$relatedmodule,$list_result,$navigation_array,'relatedlist',$returnset,$edit_val,$del_val);
		}
		if($module == 'Products' && $relatedmodule == 'PriceBook')
		{
			$listview_entries = getListViewEntries($focus,$relatedmodule,$list_result,$navigation_array,'relatedlist',$returnset,'EditListPrice','DeletePriceBookProductRel');
		}
		elseif($relatedmodule == 'SalesOrder')
		{
			$listview_entries = getListViewEntries($focus,$relatedmodule,$list_result,$navigation_array,'relatedlist',$returnset,'SalesOrderEditView','DeleteSalesOrder');
		}else
		{
			$listview_entries = getListViewEntries($focus,$relatedmodule,$list_result,$navigation_array,'relatedlist',$returnset);
		}
		$related_entries = array('header'=>$listview_header,'entries'=>$listview_entries);
		$navigationOutput = getTableHeaderNavigation($navigation_array, $url_qry,$relatedmodule);
		return $related_entries;
	}
}

function getAttachmentsAndNotes($parentmodule,$query,$id,$sid='')
{
	global $theme;

	$list = '<script>
		function confirmdelete(url)
		{
			if(confirm("Are you sure?"))
			{
				document.location.href=url;
			}
		}
	</script>';
	echo $list;

	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	require_once ($theme_path."layout_utils.php");

	global $adb;
	global $mod_strings;
	global $app_strings;

	$result=$adb->query($query);
	$noofrows = $adb->num_rows($result);

	$header[] = $app_strings['LBL_CREATED'];
	$header[] = $app_strings['LBL_SUBJECT'];
	$header[] = $app_strings['LBL_DESCRIPTION'];
	$header[] = $app_strings['LBL_ATTACHMENTS'];
	$header[] = $app_strings['LBL_TYPE'];		
	$header[] = $app_strings['LBL_ACTION'];	

	while($row = $adb->fetch_array($result))
	{
		$entries = Array();
		if($row['activitytype'] == 'Notes')
		{
			$module = 'Notes';
			$editaction = 'EditView';
			$deleteaction = 'Delete';
		}
		elseif($row['activitytype'] == 'Attachments')
		{
			$module = 'uploads';
			$editaction = 'upload';
			$deleteaction = 'deleteattachments';
		}

		if($row['createdtime'] != '0000-00-00 00:00:00')
		{
			$created_arr = explode(" ",getDisplayDate($row['createdtime']));
			$created_date = $created_arr[0];
			$created_time = substr($created_arr[1],0,5);
		}
		else
		{
			$created_date = '';
			$created_time = '';
		}

		$entries[] = $created_date;

		if($module == 'Notes')
		{
			$entries[] = '<a href="index.php?module='.$module.'&action=DetailView&return_module='.$parentmodule.'&return_action='.$return_action.'&record='.$row["crmid"].'&filename='.$row['filename'].'&fileid='.$row['attachmentsid'].'&return_id='.$_REQUEST["record"].'">'.$row['title'].'</a>';
		}
		elseif($module == 'uploads')
		{
			$entries[] = "";
		}

		$entries[] = nl2br($row['description']); 
		$attachmentname = ltrim($row['filename'],$id.'_');//explode('_',$row['filename'],2);

		$entries[] = '<a href="index.php?module=uploads&action=downloadfile&entityid='.$id.'&fileid='.$row['attachmentsid'].'">'.$attachmentname.'</a>';

		$entries[] = $row['activitytype'];	

		$del_param = 'index.php?module='.$module.'&action='.$deleteaction.'&return_module='.$parentmodule.'&return_action='.$_REQUEST['action'].'&record='.$row["crmid"].'&filename='.$row['filename'].'&return_id='.$_REQUEST["record"];

		if($module == 'Notes')
		{
			$edit_param = 'index.php?module='.$module.'&action='.$editaction.'&return_module='.$parentmodule.'&return_action='.$_REQUEST['action'].'&record='.$row["crmid"].'&filename='.$row['filename'].'&fileid='.$row['attachmentsid'].'&return_id='.$_REQUEST["record"];

			$entries[] .= '<a href="'.$edit_param.'">'.$app_strings['LNK_EDIT'].'</a> | <a href="javascript:;" onclick=confirmdelete("'.$del_param.'")>'.$app_strings['LNK_DELETE'].'</a>';
		}
		else
		{
			$entries[] = '<a href="javascript:;" onclick=confirmdelete("'.$del_param.'")>'.$app_strings['LNK_DELETE'].'</a>';
		}
		$entries_list[] = $entries;
	}

	if($entries_list !='')
		$return_data = array('header'=>$header,'entries'=>$entries_list);
	return $return_data;

}

function getHistory($parentmodule,$query,$id)
{
	$parentaction = $_REQUEST['action'];
	global $theme;
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	require_once ($theme_path."layout_utils.php");

	global $adb;
	global $mod_strings;
	global $app_strings;

	//Appending the security parameter
	global $others_permission_id;
	global $current_user;
	$rel_tab_id = getTabid("Activities");

	global $current_user;
        require('user_privileges/user_privileges_'.$current_user->id.'.php');
        require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
        $tab_id=getTabid('Activities');
       if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
       {
       		$sec_parameter=getListViewSecurityParameter('Activities');
                $query .= ' '.$sec_parameter;

        }
	$result=$adb->query($query);
	$noofrows = $adb->num_rows($result);
	
	$button .= '<table cellspacing=0 cellpadding=2><tr><td>';
	$button .= '</td></tr></table>';

	if($noofrows == 0)
	{
	}
	else
	{
		$list .= '<table border="0" cellpadding="0" cellspacing="0" class="FormBorder" width="100%" >';
		$list .= '<tr class="ModuleListTitle" height=20>';

// Armando Lüscher 15.07.2005 -> §scrollableTables
// Desc: class="blackLine" deleted because of vertical line in title <tr>

		$class_black="";
		if($noofrows<=15)
		{
			$class_black='class="blackLine"';	
			$colspan = 'colspan=2';
		}

		$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td>';
		$list .= '<td width="90" '.$colspan.' class="moduleListTitle" style="padding:0px 3px 0px 3px;" noWrap>'; // Armando Lüscher 26.09.2005 -> §visibleDescription -> Desc: Changed width from 25% to 90, inserted noWrap

		$colspan = ($noofrows<=15)?'colspan="3"':''; // Armando Lüscher 26.09.2005 -> §visibleDescription -> Desc: Inserted
		$list .= $app_strings['LBL_CREATED'].'</td>'; // Armando Lüscher 26.09.2005 -> §visibleDescription -> Desc: Changed LBL_SUBJECT to LBL_CREATED
		$header[] = $app_strings['LBL_CREATED'];
		$list .= '<td WIDTH="1" '.$class_black.'><IMG SRC="themes/'.$theme.'/images/blank.gif"></td>';
		$list .= '<td '.$colspan.' width="30%" class="moduleListTitle" style="padding:0px 3px 0px 3px;" noWrap>'; // Armando Lüscher 26.09.2005 -> §visibleDescription -> Desc: Changed width from 10% to 30%, inserted '.$colspan.' noWrap
	
		$list .= $app_strings['LBL_SUBJECT'].'</td>'; // Armando Lüscher 26.09.2005 -> §visibleDescription -> Desc: Changed LBL_STATUS to LBL_SUBJECT
		$header[] = $app_strings['LBL_SUBJECT'];
		$list .= '<td WIDTH="1" '.$class_black.'><IMG SRC="themes/'.$theme.'/images/blank.gif"></td>';
		$list .= '<td width="70%" class="moduleListTitle" style="padding:0px 3px 0px 3px;" noWrap>'; // Armando Lüscher 26.09.2005 -> §visibleDescription -> Desc: Changed width from 18% to 70%, inserted noWrap

		$list .= $app_strings['LBL_DESCRIPTION'].'</td>'; // Armando Lüscher 26.09.2005 -> §visibleDescription -> Desc: Changed LBL_LIST_CONTACT_NAME to LBL_DESCRIPTION
		$header[] = $app_strings['LBL_DESCRIPTION'];
		$list .= '<td WIDTH="1" '.$class_black.'><IMG SRC="themes/'.$theme.'/images/blank.gif"></td>';
		$list .= '<td width="80" class="moduleListTitle" style="padding:0px 3px 0px 3px;" noWrap>'; // Armando Lüscher 26.09.2005 -> §visibleDescription -> Desc: Changed width from 18% to 80, inserted noWrap

		$list .= $app_strings['LBL_ACTION'].'</td>'; // Armando Lüscher 26.09.2005 -> §visibleDescription -> Desc: Changed LBL_RELATED_TO to LBL_ACTION
		$header[] = $app_strings['LBL_TIME'];
		$header[] = $app_strings['LBL_ACTION'];
		$header[] = $app_strings['LBL_RELATED_TO'];
		$header[] = $app_strings['LBL_ASSIGNED_TO'];
		$list .= '<td WIDTH="1" '.$class_black.'><IMG SRC="themes/'.$theme.'/images/blank.gif"></td>';
/* // Armando Lüscher 26.09.2005 -> §visibleDescription -> Desc: Commented out because this is not used for the title row
*/
		$list .= '</td>';
		$colspan = 9;
		if($noofrows>15)
		{
			$list .= '<td style="width:20px">&nbsp;&nbsp&nbsp;&nbsp;</td>';
			$colspan = 11;
		}
		$list .= '</tr>';
	
		$list .= '<tr><td COLSPAN="'.$colspan.'" class="blackLine"><IMG SRC="themes/'.$theme.'/images//blank.gif"></td></tr>';

// begin: Armando Lüscher 14.07.2005 -> §scrollableTables
// Desc: 'X'
//			 Insert new table with 1 cell where all entries are in a new table.
//			 This cell will be scrollable when too many entries exist
		$list .= ($noofrows>15) ? '<tr><td colspan="'.$colspan.'"><div style="overflow:auto;height:315px;width:100%;"><table cellspacing="0" cellpadding="0" border="0" width="100%">':'';
// end: Armando Lüscher 14.07.2005 -> §scrollableTables

		$i=1;
		while($row = $adb->fetch_array($result))
		{
			$entries = Array();
			if($row['activitytype'] == 'Task')
			{
				$activitymode = 'Task';
				$icon = 'Tasks.gif';
				$status = $row['status'];
			}
			elseif($row['activitytype'] == 'Call' || $row['activitytype'] == 'Meeting')
			{
				$activitymode = 'Events';
				$icon = 'Activities.gif';
				$status = $row['eventstatus'];
			}
			if ($i%2==0)
				$trowclass = 'evenListRow';
			else
				$trowclass = 'oddListRow';
	
			$created_arr = explode(" ",getDisplayDate($row['createdtime']));
			$created_date = $created_arr[0];
			$created_time = substr($created_arr[1],0,5);

			$list .= '<tr class="'. $trowclass.'">';
			$entries[] = $created_date;	
			$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td>';
			$list .= '<td colspan="2" valign="top" class="visibleDescriptionLink" width="90" style="padding:0px 3px 0px 3px;" noWrap>'.$created_date.'</td>'; // Armando Lüscher 26.09.2005 -> §visibleDescription -> Desc: Changed width from 4% to 90, inserted colspan="2" align="right" valign="top" class="visibleDescriptionLink" style="padding:0px 3px 0px 3px;" noWrap, replaced <IMG SRC="'.$image_path.'/'.$icon.'"> with $created_date

			$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td>';
			$list .= '<td valign="top" colspan="3" width="30%" height="21" class="visibleDescriptionLink" style="padding:0px 3px 0px 3px;">'; // Armando Lüscher 26.09.2005 -> §visibleDescription -> Desc: Changed width from 25% to 30%, inserted colspan="3" valign="top" class="visibleDescriptionLink"
			$list .= '<a href="index.php?module=Activities&action=DetailView&return_module='.$parentmodule.'&return_action=DetailView&record='.$row["activityid"] .'&activity_mode='.$activitymode.'&return_id='.$_REQUEST['record'].'" title="'.$row['description'].'">'.$row['subject'].'</a></td>';
			$entries[] = $row['subject'];
			$list .= '</td>';
	
			$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td>';
			$list .= '<td valign="top" rowspan="2" width="70%" height="21" class="visibleDescription" style="padding:0px 3px 0px 3px;">'; // Armando Lüscher 26.09.2005 -> §visibleDescription -> Desc: Changed width from 10% to 70%, inserted rowspan="2" valign="top" class="visibleDescription"
			$entries[] = nl2br($row['description']);
			$list .= nl2br($row['description']); // Armando Lüscher 26.09.2005 -> §visibleDescription -> Desc: Replaced $status with nl2br($row['description'])
			$list .= '</td>';

			if($row['firstname'] != 'NULL')	
				$contactname = $row['firstname'].' ';
			if($ros['lastname'] != 'NULL')
				$contactname .= $row['lastname'];

			$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
			$list .= '<td valign="top" width="80" height="21" style="padding:0px 3px 0px 3px;" noWrap>'; // Armando Lüscher 26.09.2005 -> §visibleDescription -> Desc: Changed width from 18% to 80, inserted valign="top" noWrap
			// Armando Lüscher 26.09.2005 -> §visibleDescription -> Desc: This if-statement replaces the line above
			if(isPermitted("Activities",1,$row["activityid"]) == 'yes')
			{
				$list .= '<a href="index.php?module=Activities&action=EditView&return_module='.$parentmodule.'&return_action='.$parentaction.'&activity_mode='.$activitymode.'&record='.$row["activityid"].'&return_id='.$_REQUEST["record"].'">'.$app_strings['LNK_EDIT'].'</a>';
			
			}
			$list .= '</td>';

			// begin: Armando Lüscher 26.09.2005 -> §visibleDescription
			// Desc: Inserted because entries are displayed on 2 rows
			$list .= '</tr><tr class="'.$trowclass.'">';
			// end: Armando Lüscher 26.09.2005 -> §visibleDescription 

			$parentname = getRelatedTo('Activities',$result,$i-1);

			$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td>';
			
			// begin: Armando Lüscher 26.09.2005 -> §visibleDescription
			// Desc: Added
			$list .= '<td valign="top" width="20" style="padding:0px 0px 0px 10px;">';
			$list .= '<IMG SRC="'.$image_path.'/'.$icon.'">';
			$list .= '</td>';
			// end: Armando Lüscher 26.09.2005 -> §visibleDescription
	
			$list .= '<td align="right" valign="top" width="70" style="padding:0px 3px 0px 3px;">'; // Armando Lüscher 26.09.2005 -> §visibleDescription -> Desc: Changed width from 18% to 70, inserted align="right" valign="top"
			$list .= $created_time; // Armando Lüscher 26.09.2005 -> §visibleDescription -> Desc: Replaced $parentname with $created_time
			$list .= '</td>';	
			$entries[] = $created_time;
	
			$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td>';
			$list .= '<td valign="top" width="8%" style="padding:0px 3px 0px 3px;">'; // Armando Lüscher 26.09.2005 -> §visibleDescription -> Desc: Changed width from 15% to 8%, inserted valign="top"
			$list .= $status; // Armando Lüscher 26.09.2005 -> §visibleDescription -> Desc: Replaced $modifiedtime with $status
			$entries[] = $status;
			$list .= '</td>'; // Armando Lüscher 26.09.2005 -> §visibleDescription -> Desc: Inserted

//			$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
			$list .= '<td valign="top" width="18%" style="padding:0px 3px 0px 3px;">'; // Armando Lüscher 26.09.2005 -> §visibleDescription -> Desc: Changed width from 10% to 18%, inserted valign="top"
			$entries[] = $parentname;
			$list .= $parentname; // Armando Lüscher 26.09.2005 -> §visibleDescription -> Desc: Inserted
			$list .= '</td>'; // Armando Lüscher 26.09.2005 -> §visibleDescription -> Desc: Inserted
			
			// begin: Armando Lüscher 26.09.2005 -> §visibleDescription
			// Desc: Added
			$list .= '<td valign="top" width="4%" style="padding:0px 3px 0px 3px;">';
			if($row['user_name']==NULL && $row['groupname']!=NULL)
			{
				$list .= $row['groupname'];
				$entries[] = $row['groupname'];
			}
			else
			{
				$list .= $row['user_name'];
 				$entries[] = $row['user_name'];
				
			}
			$list .= '</td>';
			$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td>';
			
			// the description is in this space
			
			$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td>';
			$list .= '<td valign="top" width="80" style="padding:0px 3px 0px 3px;">';
			if(isPermitted("Activities",2,$row["activityid"]) == 'yes')
			{
				$list .= '<a href="index.php?module=Activities&action=Delete&return_module='.$parentmodule.'&return_action='.$parentaction.'&record='.$row["activityid"].'&return_id='.$_REQUEST["record"].'">'.$app_strings['LNK_DELETE'].'</a>';
			}
			$list .= '</td>';
			
			$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';

			$list .= '</tr>';

			$list .= '<tr width="'.$colspan.'"><td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif"></td></tr>';

			$i++;
			$entries_list[] = $entries;
		}

// begin: Armando Lüscher 14.07.2005 -> §scrollableTables
// Desc: Close table from 
		$list .= ($noofrows>15) ? '</table></div></td></tr>':'';
// end: Armando Lüscher 14.07.2005 -> §scrollableTables

		$list .= '<tr><td colspan="14" class="blackLine"></td></tr>';

		$list .= '</table>';
		$return_data = array('header'=>$header,'entries'=>$entries_list);
		return $return_data; 
	}
}

function getPriceBookRelatedProducts($query,$focus,$returnset='')
{
	require_once('Smarty_setup.php');
	global $adb;
	global $app_strings;
	global $mod_strings;
	global $current_language;
	$current_module_strings = return_module_language($current_language, 'PriceBook');

	global $list_max_entries_per_page;
	global $urlPrefix;


	global $theme;
	$pricebook_id = $_REQUEST['record'];
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	require_once($theme_path.'layout_utils.php');
	$list_result = 	$adb->query($query);
	$num_rows = $adb->num_rows($list_result);
	$smarty = new vtigerCRM_Smarty;
	$smarty->assign("MOD", $mod_strings);
	$smarty->assign("APP", $app_strings);
	$smarty->assign("IMAGE_PATH",$image_path);
	$other_text = '<table width="100%" border="0" cellpadding="1" cellspacing="0">
	<form name="selectproduct" method="POST">
	<tr>
	<input name="action" type="hidden" value="AddProductsToPriceBook">
	<input name="module" type="hidden" value="Products">
	<input name="return_module" type="hidden" value="PriceBooks">
	<input name="return_action" type="hidden" value="DetailView">
	<input name="pricebook_id" type="hidden" value="'.$_REQUEST["record"].'">';

        $other_text .='<td><input title="Select Products" accessyKey="F" class="button" onclick="this.form.action.value=\'AddProductsToPriceBook\';this.form.module.value=\'Products\';this.form.return_module.value=\'PriceBooks\';this.form.return_action.value=\'DetailView\'" type="submit" name="button" value="'.$app_strings["LBL_SELECT_PRODUCT_BUTTON_LABEL"].'"></td>';
		$other_text .='</tr></table>';

//Retreive the list from Database
$list_result = $adb->query($query);
$num_rows = $adb->num_rows($list_result);

//Retreive the List View Table Header

// Armando Lüscher 15.07.2005 -> §scrollableTables
// Desc: class="blackLine" deleted because of vertical line in title <tr>

//		$list .= $app_strings['LBL_ICON'].'Icon</td>';
		$class_black="";
		if($num_rows<15)
		{
			$class_black='class="blackLine"';	
		}

$header=array();
$header[]=$mod_strings['LBL_LIST_PRODUCT_NAME'];
$header[]=$mod_strings['LBL_PRODUCT_CODE'];
$header[]=$mod_strings['LBL_PRODUCT_UNIT_PRICE'];
$header[]=$mod_strings['LBL_PB_LIST_PRICE'];
$header[]=$mod_strings['LBL_ACTION'];

$smarty->assign("LISTHEADER", $list_header);

// begin: Armando Lüscher 14.07.2005 -> §scrollableTables
// Desc: 'X'
//			 Insert new table with 1 cell where all entries are in a new table.
//			 This cell will be scrollable when too many entries exist
		$list_body .= ($num_rows>15) ? '<tr><td colspan="12"><div style="overflow:auto;height:315px;width:100%;"><table cellspacing="0" cellpadding="0" border="0" width="100%">':'';
// end: Armando Lüscher 14.07.2005 -> §scrollableTablEs

for($i=0; $i<$num_rows; $i++)
{
	$entity_id = $adb->query_result($list_result,$i,"crmid");

		$unit_price = 	$adb->query_result($list_result,$i,"unit_price");
		$listprice = $adb->query_result($list_result,$i,"listprice");
		$field_name=$entity_id."_listprice";
		$entries = Array();
		$entries[] = $adb->query_result($list_result,$i,"productname");
		$entries[] = $adb->query_result($list_result,$i,"productcode");
		$entries[] = $unit_price;
		$entries[] = $listprice;
		$entries[] = '<a href="index.php?module=Products&action=EditListPrice&record='.$entity_id.'&pricebook_id='.$pricebook_id.'&listprice='.$listprice.'">edit</a>&nbsp;|&nbsp;<a href="index.php?module=Products&action=DeletePriceBookProductRel'.$returnset.'&record='.$entity_id.'&pricebook_id='.$pricebook_id.'">del</a>';
	$list_body .='<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="'.$image_path.'blank.gif"></td></tr>';
		$entries_list[] = $entries;
}
		if($num_rows>0)
		{
			$return_data = array('header'=>$header,'entries'=>$entries_list);
			return $return_data; 
		}
}

?>

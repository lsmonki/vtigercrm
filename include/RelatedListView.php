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


require_once('include/utils/UserInfoUtil.php');
require_once("include/utils/utils.php");
require_once("include/ListView/ListViewSession.php");

/** Function to get related list entries in detailed array format
  * @param $module -- modulename:: Type string
  * @param $relatedmodule -- relatedmodule:: Type string
  * @param $focus -- focus:: Type object
  * @param $query -- query:: Type string
  * @param $button -- buttons:: Type string
  * @param $returnset -- returnset:: Type string
  * @param $id -- id:: Type string
  * @param $edit_val -- edit value:: Type string
  * @param $del_val -- delete value:: Type string
  * @returns $related_entries -- related entires:: Type string array
  *
  */

function GetRelatedList($module,$relatedmodule,$focus,$query,$button,$returnset,$id='',$edit_val='',$del_val='')
{
	$log = LoggerManager::getLogger('account_list');
	$log->debug("Entering GetRelatedList(".$module.",".$relatedmodule.",".get_class($focus).",".$query.",".$button.",".$returnset.",".$edit_val.",".$del_val.") method ...");

	require_once('Smarty_setup.php');
	require_once("data/Tracker.php");
	require_once('include/database/PearDatabase.php');

	global $adb;
	global $app_strings;
	global $current_language;

	$current_module_strings = return_module_language($current_language, $module);

	global $list_max_entries_per_page;
	global $urlPrefix;


	global $currentModule;
	global $theme;
	global $theme_path;
	global $theme_path;
	global $mod_strings;
	// focus_list is the means of passing data to a ListView.
	global $focus_list;
	$smarty = new vtigerCRM_Smarty;
	if (!isset($where)) $where = "";
	
	
	$button = '<table cellspacing=0 cellpadding=2><tr><td>'.$button.'</td></tr></table>';

	// Added to have Purchase Order as form Title
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
	

	if(isset($where) && $where != '')
	{
		$query .= ' and '.$where;
	}
	
	if(!$_SESSION['rlvs'][$module][$relatedmodule])
	{
		$modObj = new ListViewSession();
		$modObj->sortby = $focus->default_order_by;
		$modObj->sorder = $focus->default_sort_order;
		$_SESSION['rlvs'][$module][$relatedmodule] = get_object_vars($modObj);
	}
	if(isset($_REQUEST['relmodule']) && ($_REQUEST['relmodule'] == $relatedmodule))
	{	
		if(method_exists($focus,getSortOrder))
		$sorder = $focus->getSortOrder();
		if(method_exists($focus,getOrderBy))
		$order_by = $focus->getOrderBy();

		if(isset($order_by) && $order_by != '')
		{
			$_SESSION['rlvs'][$module][$relatedmodule]['sorder'] = $sorder;
			$_SESSION['rlvs'][$module][$relatedmodule]['sortby'] = $order_by;
		}

	}
	elseif($_SESSION['rlvs'][$module][$relatedmodule])
	{
		$sorder = $_SESSION['rlvs'][$module][$relatedmodule]['sorder'];
		$order_by = $_SESSION['rlvs'][$module][$relatedmodule]['sortby'];
	}
	else
	{
		$order_by = $focus->default_order_by;
		$sorder = $focus->default_sort_order;
	}
		//Added by Don for AssignedTo ordering issue in Related Lists
	$query_order_by = $order_by;
	if($order_by == 'smownerid')
	{
		$query_order_by = "case when (vtiger_users.user_name not like '') then vtiger_users.user_name else vtiger_groups.groupname end ";
	}
	if($relatedmodule == "Calendar")
		$query .= ' GROUP BY vtiger_activity.activityid ORDER BY '.$query_order_by.' '.$sorder;
	else
		$query .= ' ORDER BY '.$query_order_by.' '.$sorder;		

	$url_qry .="&order_by=".$order_by."&sorder=".$sorder;
	//Added for PHP version less than 5
	if (!function_exists("stripos"))
	{
		function stripos($query,$needle)
		{
			return strpos(strtolower($query),strtolower($needle));
		}
	}
	
	//Retreiving the no of rows
	if($relatedmodule == "Calendar")//for calendar related list, count will increase when we have multiple contacts relationship for single activity
	{
		$count_query = "select count(*) as count, vtiger_activity.activitytype ".substr($query, stripos($query,'from'),strlen($query));
		$count_result = $adb->query($count_query);
		$noofrows = $adb->num_rows($count_result); 
	}
	else
	{
		$count_query = "select count(*) as count ".substr($query, stripos($query,'from'),strlen($query));
		$count_result = $adb->query(substr($count_query, stripos($count_query,'select'),stripos($count_query,'ORDER BY')));

		if($adb->num_rows($count_result) > 1)
			$noofrows = $adb->num_rows($count_result);
		else
			$noofrows =$adb->query_result($count_result,0,"count");
	}

	//Setting Listview session object while sorting/pagination
	if(isset($_REQUEST['relmodule']) && $_REQUEST['relmodule']!='' && $_REQUEST['relmodule'] == $relatedmodule)
	{
		$relmodule = $_REQUEST['relmodule'];
		if($_SESSION['rlvs'][$module][$relmodule])
		{
			setSessionVar($_SESSION['rlvs'][$module][$relmodule],$noofrows,$list_max_entries_per_page,$module,$relmodule);
		}
	}
	$start = $_SESSION['rlvs'][$module][$relatedmodule]['start'];
	$navigation_array = getNavigationValues($start, $noofrows, $list_max_entries_per_page);
	
	$start_rec = $navigation_array['start'];
	$end_rec = $navigation_array['end_val'];

	//limiting the query
	if ($start_rec ==0) 
		$limit_start_rec = 0;
	else
		$limit_start_rec = $start_rec -1;

	if( $adb->dbType == "pgsql")
 	    $list_result = $adb->pquery($query. " OFFSET $limit_start_rec LIMIT $list_max_entries_per_page", array());
 	else
 	    $list_result = $adb->pquery($query. " LIMIT $limit_start_rec, $list_max_entries_per_page", array());	

	//Retreive the List View Table Header
	if($noofrows == 0)
	{
		$smarty->assign('NOENTRIES',$app_strings['LBL_NONE_SCHEDULED']);
	}
	else
	{
		$id = $_REQUEST['record'];
		$listview_header = getListViewHeader($focus,$relatedmodule,'',$sorder,$order_by,$id,'',$module);//"Accounts");
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

		$navigationOutput = Array();
		$navigationOutput[] = $app_strings[LBL_SHOWING]." " .$start_rec." - ".$end_rec." " .$app_strings[LBL_LIST_OF] ." ".$noofrows;
		$module_rel = $module.'&relmodule='.$relatedmodule.'&record='.$id;
		$navigationOutput[] = getRelatedTableHeaderNavigation($navigation_array, $url_qry,$module_rel);
		$related_entries = array('header'=>$listview_header,'entries'=>$listview_entries,'navigation'=>$navigationOutput);

		$log->debug("Exiting GetRelatedList method ...");
		return $related_entries;
	}
}

/** Function to get related list entries in detailed array format
  * @param $parentmodule -- parentmodulename:: Type string
  * @param $query -- query:: Type string
  * @param $id -- id:: Type string
  * @returns $entries_list -- entries list:: Type string array
  *
  */

function getAttachmentsAndNotes($parentmodule,$query,$id,$sid='')
{
	global $log;
	$log->debug("Entering getAttachmentsAndNotes(".$parentmodule.",".$query.",".$id.",".$sid.") method ...");
	global $theme;

	$list = '<script>
		function confirmdelete(url)
		{
			if(confirm("'.$app_strings['ARE_YOU_SURE'].'"))
			{
				document.location.href=url;
			}
		}
	</script>';

	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";

	global $adb,$current_user;
	global $mod_strings;
	global $app_strings, $listview_max_textlength;
	
	$result=$adb->query($query);
	$noofrows = $adb->num_rows($result);

	$header[] = $app_strings['LBL_CREATED'];
	$header[] = $app_strings['LBL_TITLE'];
	$header[] = $app_strings['LBL_DESCRIPTION'];
	$header[] = $app_strings['LBL_ATTACHMENTS'];
	$header[] = $app_strings['LBL_TYPE'];		
	$header[] = $app_strings['LBL_ACTION'];	
	
	while($row = $adb->fetch_array($result))
	{
		if($row['activitytype'] == 'Attachments') {
			$query1="select setype,createdtime from vtiger_crmentity where crmid=?";
			$params1 = array($row['attachmentsid']);
		} else {
			$query1="select setype,createdtime from vtiger_crmentity where crmid=?";
			$params1 = array($row['crmid']);
		}

		$query1 .=" order by createdtime desc";
		$res=$adb->pquery($query1, $params1);
		$num_rows = $adb->num_rows($res);
		for($i=0; $i<$num_rows; $i++)
		{
			$setype = $adb->query_result($res,$i,'setype');
			$createdtime = $adb->query_result($res,$i,'createdtime');
		}

		if(($setype != "Products Image") && ($setype != "Contacts Image")) 
		{
			$entries = Array();
			if(trim($row['activitytype']) == 'Notes')
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
				$created_arr = explode(" ",getDisplayDate($createdtime));
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
				$entries[] = '<a href="index.php?module='.$module.'&action=DetailView&return_module='.$parentmodule.'&return_action='.$return_action.'&record='.$row["crmid"].'&filename='.$row['filename'].'&fileid='.$row['attachmentsid'].'&return_id='.$_REQUEST["record"].'&parenttab='.$_REQUEST["parenttab"].'">'.textlength_check($row['title']).'</a>';
			}
			elseif($module == 'uploads')
			{
				$entries[] = $row['title'];
			}
			if((getFieldVisibilityPermission('Notes', $current_user->id, 'notecontent') == '0') || $row['activitytype'] == 'Attachments')
			{
				$row['description'] = preg_replace("/(<\/?)(\w+)([^>]*>)/i","",$row['description']);
				if(strlen($row['description']) > $listview_max_textlength)
				{
					$row['description'] = substr($row['description'],0,$listview_max_textlength).'...';
				}
				$entries[] = nl2br($row['description']); 
			}
			else
				$entries[]=" <font color ='red' >" .$app_strings['LBL_NOT_ACCESSIBLE']."</font>";

			$attachmentname = $row['filename'];//explode('_',$row['filename'],2);

			if((getFieldVisibilityPermission('Notes', $current_user->id, 'filename') == '0') || $row['activitytype'] == 'Attachments')
				$entries[] = '<a href="index.php?module=uploads&action=downloadfile&entityid='.$id.'&fileid='.$row['attachmentsid'].'">'.textlength_check($attachmentname).'</a>';
			else
				$entries[]='';

			$entries[] = $row['activitytype'];	
			$del_param = 'index.php?module='.$module.'&action='.$deleteaction.'&return_module='.$parentmodule.'&return_action='.$_REQUEST['action'].'&record='.$row["crmid"].'&return_id='.$_REQUEST["record"].'&parenttab='.$_REQUEST["parenttab"];

			if($module == 'Notes')
			{
				$edit_param = 'index.php?module='.$module.'&action='.$editaction.'&return_module='.$parentmodule.'&return_action='.$_REQUEST['action'].'&record='.$row["crmid"].'&filename='.$row['filename'].'&fileid='.$row['attachmentsid'].'&return_id='.$_REQUEST["record"].'&parenttab='.$_REQUEST["parenttab"];

				$entries[] .= '<a href="'.$edit_param.'">'.$app_strings['LNK_EDIT'].'</a> | <a href=\'javascript:confirmdelete("'.$del_param.'")\'>'.$app_strings['LNK_DELETE'].'</a>';
			}
			else
			{
				$entries[] = '<a href=\'javascript:confirmdelete("'.$del_param.'")\'>'.$app_strings['LNK_DELETE'].'</a>';
			}
			$entries_list[] = $entries;
		}
	}

	if($entries_list !='')
		$return_data = array('header'=>$header,'entries'=>$entries_list);
	$log->debug("Exiting getAttachmentsAndNotes method ...");
	return $return_data;

}

/** Function to get related list entries in detailed array format
  * @param $parentmodule -- parentmodulename:: Type string
  * @param $query -- query:: Type string
  * @param $id -- id:: Type string
  * @returns $return_data -- return data:: Type string array
  *
  */

function getHistory($parentmodule,$query,$id)
{
	global $log;
	$log->debug("Entering getHistory(".$parentmodule.",".$query.",".$id.") method ...");
	$parentaction = $_REQUEST['action'];
	global $theme;
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";

	global $adb;
	global $mod_strings;
	global $app_strings;

	//Appending the security parameter
	global $current_user;
	$rel_tab_id = getTabid("Calendar");

	global $current_user;
        require('user_privileges/user_privileges_'.$current_user->id.'.php');
        require('user_privileges/sharing_privileges_'.$current_user->id.'.php');
        $tab_id=getTabid('Calendar');
       if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 && $defaultOrgSharingPermission[$tab_id] == 3)
       {
       		$sec_parameter=getListViewSecurityParameter('Calendar');
                $query .= ' '.$sec_parameter;

        }
	$result=$adb->query($query);
	$noofrows = $adb->num_rows($result);

	if($noofrows == 0)
	{
		//There is no entries for history
	}
	else
	{
		//Form the header columns
		$header[] = $app_strings['LBL_TYPE'];
		$header[] = $app_strings['LBL_SUBJECT'];
		$header[] = $app_strings['LBL_RELATED_TO'];
		$header[] = $app_strings['LBL_START_DATE']." & ".$app_strings['LBL_TIME'];
		$header[] = $app_strings['LBL_END_DATE']." & ".$app_strings['LBL_TIME'];
		//$header[] = $app_strings['LBL_DESCRIPTION'];
		$header[] = $app_strings['LBL_STATUS'];
		$header[] = $app_strings['LBL_ASSIGNED_TO'];

		$i=1;
		while($row = $adb->fetch_array($result))
		{
			$entries = Array();
			if($row['activitytype'] == 'Task')
			{
				$activitymode = 'Task';
				$icon = 'Tasks.gif';
				$status = $row['status'];
				$status = $app_strings[$status];
			}
			elseif($row['activitytype'] == 'Call' || $row['activitytype'] == 'Meeting')
			{
				$activitymode = 'Events';
				$icon = 'Activities.gif';
				$status = $row['eventstatus'];
				$status = $app_strings[$status]; 
			}
	
	            	$typeofactivity = $row['activitytype'];
 	            	$typeofactivity = $app_strings[$typeofactivity];
			$entries[] = $typeofactivity;

			$activity = '<a href="index.php?module=Calendar&action=DetailView&return_module='.$parentmodule.'&return_action=DetailView&record='.$row["activityid"] .'&activity_mode='.$activitymode.'&return_id='.$_REQUEST['record'].'&parenttab='.$_REQUEST['parenttab'].'">'.$row['subject'].'</a></td>';
			$entries[] = $activity;
	
			$parentname = getRelatedTo('Calendar',$result,$i-1);
			$entries[] = $parentname;
			
		       $entries[] = getDisplayDate($row['date_start'])."   ".$row['time_start'];
 	               $entries[] = getDisplayDate($row['due_date'])."   ".$row['time_end'];         
		
			//$entries[] = nl2br($row['description']);

			$entries[] = $status;

			if($row['user_name']==NULL && $row['groupname']!=NULL)
			{
				$entries[] = $row['groupname'];
			}
			else
			{
 				$entries[] = $row['user_name'];
				
			}
			
			$i++;
			$entries_list[] = $entries;
		}
	
		$return_data = array('header'=>$header,'entries'=>$entries_list);
		$log->debug("Exiting getHistory method ...");
		return $return_data; 
	}
}

/**	Function to display the Products which are related to the PriceBook
 *	@param string $query - query to get the list of products which are related to the current PriceBook
 *	@param object $focus - PriceBook object which contains all the information of the current PriceBook
 *	@param string $returnset - return_module, return_action and return_id which are sequenced with & to pass to the URL which is optional
 *	return array $return_data which will be formed like array('header'=>$header,'entries'=>$entries_list) where as $header contains all the header columns and $entries_list will contain all the Product entries
 */
function getPriceBookRelatedProducts($query,$focus,$returnset='')
{
	global $log;
	$log->debug("Entering getPriceBookRelatedProducts(".$query.",".get_class($focus).",".$returnset.") method ...");

	global $adb;
	global $app_strings;
	global $mod_strings;
	global $current_language,$current_user;
	$current_module_strings = return_module_language($current_language, 'PriceBook');

	global $list_max_entries_per_page;
	global $urlPrefix;

	global $theme;
	$pricebook_id = $_REQUEST['record'];
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";

	//Retreive the list from Database
	$list_result = $adb->query($query);
	$num_rows = $adb->num_rows($list_result);

	$header=array();
	$header[]=$mod_strings['LBL_LIST_PRODUCT_NAME'];
	if(getFieldVisibilityPermission('Products', $current_user->id, 'productcode') == '0')
		$header[]=$mod_strings['LBL_PRODUCT_CODE'];
	if(getFieldVisibilityPermission('Products', $current_user->id, 'unit_price') == '0')
		$header[]=$mod_strings['LBL_PRODUCT_UNIT_PRICE'];
	$header[]=$mod_strings['LBL_PB_LIST_PRICE'];
	if(isPermitted("PriceBooks","EditView","") == 'yes' || isPermitted("PriceBooks","Delete","") == 'yes')
		$header[]=$mod_strings['LBL_ACTION'];
	

	for($i=0; $i<$num_rows; $i++)
	{
		$entity_id = $adb->query_result($list_result,$i,"crmid");
		$unit_price = 	$adb->query_result($list_result,$i,"unit_price");
		$listprice = $adb->query_result($list_result,$i,"listprice");
		$field_name=$entity_id."_listprice";
		
		$entries = Array();
		$entries[] = textlength_check($adb->query_result($list_result,$i,"productname"));
		if(getFieldVisibilityPermission('Products', $current_user->id, 'productcode') == '0')
			$entries[] = $adb->query_result($list_result,$i,"productcode");
		if(getFieldVisibilityPermission('Products', $current_user->id, 'unit_price') == '0')
			$entries[] = $unit_price;

		$entries[] = $listprice;
		$action = "";
		if(isPermitted("PriceBooks","EditView","") == 'yes')
			$action .= '<img style="cursor:pointer;" src="'.$image_path.'editfield.gif" border="0" onClick="fnvshobj(this,\'editlistprice\'),editProductListPrice(\''.$entity_id.'\',\''.$pricebook_id.'\',\''.$listprice.'\')" alt="'.$app_strings["LBL_EDIT_BUTTON"].'" title="'.$app_strings["LBL_EDIT_BUTTON"].'"/>';
		if(isPermitted("PriceBooks","Delete","") == 'yes')
		{		
			if($action != "")
				$action .= '&nbsp;|&nbsp;';
			$action .= '<img src="'.$image_path.'delete.gif" onclick="if(confirm(\''.$app_strings['ARE_YOU_SURE'].'\')) deletePriceBookProductRel('.$entity_id.','.$pricebook_id.');" alt="'.$app_strings["LBL_DELETE"].'" title="'.$app_strings["LBL_DELETE"].'" style="cursor:pointer;" border="0">';	
		}
		if($action != "")		
			$entries[] = $action;
		$entries_list[] = $entries;
	}
	if($num_rows>0)
	{
		$return_data = array('header'=>$header,'entries'=>$entries_list);

		$log->debug("Exiting getPriceBookRelatedProducts method ...");
		return $return_data; 
	}
}
function CheckFieldPermission($fieldname,$module)
{
	global $current_user,$adb;

 require('user_privileges/user_privileges_'.$current_user->id.'.php');
 if($fieldname == '' || $module == '')
	 return "false";

	$tab_id = getTabid($module);
	if($is_admin==false && $profileGlobalPermission[1] == 1 && $profileGlobalPermission[2] == 1 )
	{
	 $profileList = getCurrentUserProfileList();
	 $sql1= "SELECT fieldname FROM vtiger_field INNER JOIN vtiger_profile2field ON vtiger_profile2field.fieldid=vtiger_field.fieldid INNER JOIN vtiger_def_org_field ON vtiger_def_org_field.fieldid=vtiger_field.fieldid WHERE vtiger_field.tabid=? AND fieldname=? AND vtiger_field.displaytype IN (1,2,3,4) AND vtiger_profile2field.visible=0 AND vtiger_def_org_field.visible=0 AND vtiger_profile2field.profileid IN (". generateQuestionMarks($profileList) .") GROUP BY vtiger_field.fieldid ORDER BY block,sequence";
	$result1= $adb->pquery($sql1, array(getTabid($module), $fieldname, $profileList));
	$permission = ($adb->num_rows($result1) > 0) ? "true" : "false";
 }else
 {
	$permission = "true";
 }
 return $permission;
}

function CheckColumnPermission($tablename, $columnname, $module)
{
	global $adb;
	
	$res = $adb->pquery("select fieldname from vtiger_field where tablename=? and columnname=?", array($tablename, $columnname));
	$fieldname = $adb->query_result($res, 0, 'fieldname');
	return CheckFieldPermission($fieldname, $module);
}	
?>

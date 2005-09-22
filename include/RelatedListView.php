<?php
require_once('modules/Users/UserInfoUtil.php');
require_once("include/utils.php");

function GetRelatedList($module,$relatedmodule,$focus,$query,$button,$returnset,$edit_val='',$del_val='')
{

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('include/database/PearDatabase.php');

global $adb;
global $app_strings;
global $current_language;

$mod_dir=getModuleDirName($module);
$current_module_strings = return_module_language($current_language, $mod_dir);

global $list_max_entries_per_page;
global $urlPrefix;

$log = LoggerManager::getLogger('account_list');

global $currentModule;
global $theme;
global $theme_path;
global $theme_path;

// focus_list is the means of passing data to a ListView.
global $focus_list;

if (!isset($where)) $where = "";

if (isset($_REQUEST['order_by'])) $order_by = $_REQUEST['order_by'];

//if($module == 'Potentials')
//	$focus = new Potential();

echo '<br><br>';

$button = '<table cellspacing=0 cellpadding=2><tr><td>'.$button.'</td></tr></table>';

// Added to have Purchase Order as form Title
if($relatedmodule == 'Orders') 
{
	echo get_form_header($app_strings['PurchaseOrder'],$button, false);
}
else
{
	echo get_form_header($app_strings[$relatedmodule],$button, false);
}

$xtpl=new XTemplate ('include/RelatedListView.html');
require_once('themes/'.$theme.'/layout_utils.php');
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("IMAGE_PATH",$image_path);

//Retreive the list from Database
//$query = getListQuery("Accounts");

//Appending the security parameter
global $others_permission_id;
global $current_user;
$rel_tab_id = getTabid($relatedmodule);
$defSharingPermissionData = $_SESSION['defaultaction_sharing_permission_set'];
$others_rel_permission_id = $defSharingPermissionData[$rel_tab_id];
if($others_rel_permission_id == 3 && $relatedmodule != 'Notes' && $relatedmodule != 'Products' && $relatedmodule != 'Faq' && $relatedmodule != 'PriceBook') //Security fix by Don
{
	 $query .= " and crmentity.smownerid in(".$current_user->id .",0)";
}

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
	echo $app_strings['LBL_NONE_SCHEDULED'];
}
else
{
	$listview_header = getListViewHeader($focus,$relatedmodule,'','','','relatedlist');//"Accounts");
	if ($noofrows > 15)
	{
		$xtpl->assign('SCROLLSTART','<div style="overflow:auto;height:315px;width:100%;">');
		$xtpl->assign('SCROLLSTOP','</div>');
	}
	$xtpl->assign("LISTHEADER", $listview_header);

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

	//$listview_entries = getListViewEntries1($focus,"Accounts",$list_result,$navigation_array);
	$xtpl->assign("LISTENTITY", $listview_entries);
	$xtpl->assign("SELECT_SCRIPT", $view_script);
	$navigationOutput = getTableHeaderNavigation($navigation_array, $url_qry,$relatedmodule);
	//echo $navigationOutput;

	//$xtpl->assign("NAVIGATION", $navigationOutput);

	$xtpl->parse("main");
	$xtpl->out("main");
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

	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	require_once ($theme_path."layout_utils.php");

	global $adb;
	global $mod_strings;
	global $app_strings;

	$result=$adb->query($query);
	$noofrows = $adb->num_rows($result);
	if($sid=='salesorderid')
	{
		$return_action = "SalesOrderDetailView";
	}
	else
	{
		$return_action = "DetailView";
	}
	$button .= '<table cellspacing=0 cellpadding=2><tr><td>';
	$button .= '<input type="hidden" name="fileid">';
	$button .= '<input title="New Attachment" accessyKey="F" class="button" onclick="this.form.action.value=\'upload\';this.form.module.value=\'uploads\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_ATTACHMENT'].'">&nbsp;';

        if(isPermitted("Notes",1,"") == 'yes')
        {
	
		$button .= '<input title="New Notes" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\''.$return_action.'\';this.form.module.value=\'Notes\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_NOTE'].'">&nbsp;';
	}
	$button .= '</td></tr></table>';
	

echo '<br><br>';
echo get_form_header($app_strings['LBL_ATTACHMENT_AND_NOTES'],$button, false);

if($noofrows == 0)
{
	echo $app_strings['LBL_NONE_SCHEDULED'];
}
else
{
// begin: Armando Lüscher 15.07.2005 -> §scrollableTables
// Desc: class="blackLine" deleted because of vertical line in title <tr>
	$list .= '<table border="0" cellpadding="0" cellspacing="0" class="FormBorder" width="100%">';
	$list .= '<tr class="ModuleListTitle" height=20>';

	$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
	$list .= '<td width="30%" class="moduleListTitle">';

	$class_black="";
	if($noofrows<15)
	{
		$class_black='class="blackLine"';
	}
	$list .= $app_strings['LBL_TITLE_OR_DESCRIPTION'].'</td>';
	$list .= '<td WIDTH="1" '.$class_black .'><IMG SRC="themes/'.$theme.'/images/blank.gif">';
	$list .= '<td width="10%" class="moduleListTitle">';

	$list .= $app_strings['LBL_ENTITY_TYPE'].'</td>';
	$list .= '<td WIDTH="1" '.$class_black .'><IMG SRC="themes/'.$theme.'/images/blank.gif">';
	$list .= '<td width="15%" class="moduleListTitle">';

	$list .= $app_strings['LBL_FILENAME'].'</td>';
	$list .= '<td WIDTH="1" '.$class_black .'><IMG SRC="themes/'.$theme.'/images/blank.gif">';
	$list .= '<td width="15%" class="moduleListTitle">';

	$list .= $app_strings['LBL_TYPE'].'</td>';
	$list .= '<td WIDTH="1" '.$class_black .'><IMG SRC="themes/'.$theme.'/images/blank.gif">';
	$list .= '<td width="20%" class="moduleListTitle">';

	$list .= $app_strings['LBL_LAST_MODIFIED'].'</td>';
	$list .= '<td WIDTH="1" '.$class_black .'><IMG SRC="themes/'.$theme.'/images/blank.gif">';
	$list .= '<td width="10%" class="moduleListTitle" height="21">';

	$list .= $app_strings['LBL_ACTION'].'</td>';
	$list .= '<td WIDTH="1" '.$class_black .'><IMG SRC="themes/'.$theme.'/images/blank.gif">';
	$list .= '<td class="moduleListTitle">';

	$list .= '</td>';
	if($noofrows>15)
	{
		$list .= '<td style="width:20px">&nbsp;&nbsp&nbsp;&nbsp;</td>';
	}

	$list .= '</tr>';
	$list .= '<tr><td COLSPAN="12" class="blackLine"><IMG SRC="themes/'.$theme.'/images//blank.gif"></td></tr>';
//	$list .= '</table>';
// end: Armando Lüscher 15.07.2005 -> scrollableTables

// begin: Armando Lüscher 14.07.2005 -> §scrollableTables
// Desc: 'Y'
//			 Insert new table with 1 cell where all entries are in a new table.
//			 This cell will be scrollable when too many entries exist
	$list .= ($noofrows>15) ? '<tr><td colspan="20"><div style="overflow:auto;height:315px;width:100%;"><table cellspacing="0" cellpadding="0" border="0" width="100%">':'';
// end: Armando Lüscher 14.07.2005 -> §scrollableTables

	$i=1;
	while($row = $adb->fetch_array($result))
	{
        	if($row[1] == 'Notes')
	        {
        	        $module = 'Notes';
                	$editaction = 'EditView';
	                $deleteaction = 'Delete';
        	}
	        elseif($row[1] == 'Attachments')
	        {
	                $module = 'uploads';
	                $editaction = 'upload';
	                $deleteaction = 'deleteattachments';
	        }

		if ($i%2==0)
			$trowclass = 'evenListRow';
		else
			$trowclass = 'oddListRow';

		$list .= '<tr class="'. $trowclass.'">';

		$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';

		if($module == 'Notes')
			$list .= '<td width="30%"><a href="index.php?module='.$module.'&action=DetailView&return_module='.$returnmodule.'&return_action='.$returnaction.'&record='.$row["crmid"] .'&return_id='.$_REQUEST['record'].'">'.$row[0].'</td>';
		elseif($module == 'uploads')
			$list .= '<td width="30%">'.$row[0].'</td>';

		$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
		$list .= '<td width="10%" height="21" style="padding:0px 3px 0px 3px;">';
		$list .= $row[1];
		$list .= '</td>';

		$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
		$list .= '<td width="15%" height="21" style="padding:0px 3px 0px 3px;">';
		$list .= '<a href = "index.php?module=uploads&action=downloadfile&return_module=Accounts&activity_type='.$row[1].'&fileid='.$row[5].'&filename='.$row[2].'">'.$row[2].'</a>';
		$list .= '</td>';

		$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
		$list .= '<td width="15%" height="21" style="padding:0px 3px 0px 3px;">';
		$list .= $row[3];
		$list .= '</td>';

		$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
		$list .= '<td width="20%" height="21" style="padding:0px 3px 0px 3px;">';

		if($row[4] != '0000-00-00 00:00:00')
			$list .= $row[4];
		else
                        $list .= '';

		$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
		$list .= '<td width="10%" height="21" style="padding:0px 3px 0px 3px;">';

		if($row[1] == 'Notes')
			$list .= '<a href="index.php?module='.$module.'&action='.$editaction.'&return_module='.$parentmodule.'&return_action='.$return_action.'&record='.$row["crmid"].'&filename='.$row[2].'&fileid='.$row['attachmentsid'].'&return_id='.$_REQUEST["record"].'">'.$app_strings['LNK_EDIT'].'</a>  |  ';
//		$list .= '<a href="index.php?module='.$module.'&action='.$deleteaction.'&return_module='.$parentmodule.'&return_action=DetailView&record='.$row["crmid"].'&filename='.$row[2].'&return_id='.$_REQUEST["record"].'">'.$app_strings['LNK_DELETE'].'</a>';
		$del_param = 'index.php?module='.$module.'&action='.$deleteaction.'&return_module='.$parentmodule.'&return_action='.$return_action.'&record='.$row["crmid"].'&filename='.$row[2].'&return_id='.$_REQUEST["record"];
                $list .= '<a href="javascript:confirmdelete(\''.$del_param.'\')">'.$app_strings['LNK_DELETE'].'</a>';

		$list .= '</td>';

		$list .= '</tr>';
		$i++;
	}
	
// begin: Armando Lüscher 14.07.2005 -> §scrollableTables
// Desc: Close table from 'Y'
		$list .= ($noofrows>15) ? '</table></div></td></tr>':'';
// end: Armando Lüscher 14.07.2005 -> scrollableTables

	$list .= '<tr><td COLSPAN="12" class="blackLine"><IMG SRC="themes/'.$theme.'/images//blank.gif"></td></tr>';
	$list .= '</table>';
	echo $list;

}
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
	$defSharingPermissionData = $_SESSION['defaultaction_sharing_permission_set'];
	$others_rel_permission_id = $defSharingPermissionData[$rel_tab_id];
	if($others_rel_permission_id == 3) //Security fix by Don
	{
         	$query .= " and crmentity.smownerid in(".$current_user->id .",0)";
	}

	$result=$adb->query($query);
	$noofrows = $adb->num_rows($result);
	
	$button .= '<table cellspacing=0 cellpadding=2><tr><td>';
	$button .= '</td></tr></table>';

	echo '<br><br>';
	echo get_form_header($app_strings['LBL_HISTORY'],'', false);

	if($noofrows == 0)
	{
		echo $app_strings['LBL_NONE_SCHEDULED'];
	}
	else
	{
		$list .= '<table border="0" cellpadding="0" cellspacing="0" class="FormBorder" width="100%" >';
		$list .= '<tr class="ModuleListTitle" height=20>';

		$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
		$list .= '<td width="4%" class="moduleListTitle"></td>';

// Armando Lüscher 15.07.2005 -> §scrollableTables
// Desc: class="blackLine" deleted because of vertical line in title <tr>

//		$list .= $app_strings['LBL_ICON'].'Icon</td>';
		$class_black="";
		if($noofrows<15)
		{
			$class_black='class="blackLine"';	
		}

		$list .= '<td WIDTH="1" '.$class_black.'><IMG SRC="themes/'.$theme.'/images/blank.gif">';
		$list .= '<td width="25%" class="moduleListTitle" style="padding:0px 3px 0px 3px;">';
	
		$list .= $app_strings['LBL_SUBJECT'].'</td>';
		$list .= '<td WIDTH="1" '.$class_black.'><IMG SRC="themes/'.$theme.'/images/blank.gif">';
		$list .= '<td width="10%" class="moduleListTitle" style="padding:0px 3px 0px 3px;">';
	
		$list .= $app_strings['LBL_STATUS'].'</td>';
		$list .= '<td WIDTH="1" '.$class_black.'><IMG SRC="themes/'.$theme.'/images/blank.gif">';
		$list .= '<td width="18%" class="moduleListTitle" style="padding:0px 3px 0px 3px;">';
	
		$list .= $app_strings['LBL_LIST_CONTACT_NAME'].'</td>';
		$list .= '<td WIDTH="1" '.$class_black.'><IMG SRC="themes/'.$theme.'/images/blank.gif">';
		$list .= '<td width="18%" class="moduleListTitle" style="padding:0px 3px 0px 3px;">';

		$list .= $app_strings['LBL_RELATED_TO'].'</td>';
		$list .= '<td WIDTH="1" '.$class_black.'><IMG SRC="themes/'.$theme.'/images/blank.gif">';
		$list .= '<td width="15%" class="moduleListTitle" style="padding:0px 3px 0px 3px;">';
	
		$list .= $app_strings['LBL_LAST_MODIFIED'].'</td>';
		$list .= '<td WIDTH="1" '.$class_black.'><IMG SRC="themes/'.$theme.'/images/blank.gif">';
		$list .= '<td width="10%" class="moduleListTitle" height="21" style="padding:0px 3px 0px 3px;">';

		$list .= $app_strings['LBL_ACTION'].'</td>';
		$list .= '<td WIDTH="1" '.$class_black.'><IMG SRC="themes/'.$theme.'/images/blank.gif">';
		$list .= '<td class="moduleListTitle">';

		$list .= '</td>';
		if($noofrows>15)
		{
			$list .= '<td style="width:20px">&nbsp;&nbsp&nbsp;&nbsp;</td>';
		}
		$list .= '</tr>';
	
		$list .= '<tr><td COLSPAN="14" class="blackLine"><IMG SRC="themes/'.$theme.'/images//blank.gif"></td></tr>';

// begin: Armando Lüscher 14.07.2005 -> §scrollableTables
// Desc: 'X'
//			 Insert new table with 1 cell where all entries are in a new table.
//			 This cell will be scrollable when too many entries exist
		$list .= ($noofrows>15) ? '<tr><td colspan="21"><div style="overflow:auto;height:315px;width:100%;"><table cellspacing="0" cellpadding="0" border="0" width="100%">':'';
// end: Armando Lüscher 14.07.2005 -> §scrollableTablEs

		$i=1;
		while($row = $adb->fetch_array($result))
		{
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
	
			$list .= '<tr class="'. $trowclass.'">';
	
			$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
			$list .= '<td width="4%"><IMG SRC="'.$image_path.'/'.$icon.'"></td>';

			$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
			$list .= '<td width="25%" height="21" style="padding:0px 3px 0px 3px;">';
			$list .= '<a href="index.php?module=Activities&action=DetailView&return_module='.$parentmodule.'&return_action=DetailView&record='.$row["activityid"] .'&activity_mode='.$activitymode.'&return_id='.$_REQUEST['record'].'" title="'.$row['description'].'">'.$row['subject'].'</a></td>';
			$list .= '</td>';
	
			$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
			$list .= '<td width="10%" height="21" style="padding:0px 3px 0px 3px;">';
			$list .= $status;
			$list .= '</td>';

			if($row['firstname'] != 'NULL')	
				$contactname = $row['firstname'].' ';
			if($ros['lastname'] != 'NULL')
				$contactname .= $row['lastname'];

			$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
			$list .= '<td width="18%" height="21" style="padding:0px 3px 0px 3px;">';
			$list .= '<a href="index.php?module=Contacts&action=DetailView&return_module='.$parentmodule.'&return_action=DetailView&record='.$row["contactid"].'&return_id='.$_REQUEST['record'].'">'.$contactname;
			$list .= '</td>';

			$parentname = getRelatedTo('Activities',$result,$i-1);

			$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
			$list .= '<td width="18%" height="21" style="padding:0px 3px 0px 3px;">';
			$list .= $parentname;
			$list .= '</td>';
	
			$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
			$list .= '<td width="15%" height="21" style="padding:0px 3px 0px 3px;">';
			$modifiedtime = getDisplayDate($row['modifiedtime']);
			$list .= $modifiedtime;
	
			$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
			$list .= '<td width="10%" height="21" style="padding:0px 3px 0px 3px;">';

			if(isPermitted("Activities",1,$row["activityid"]) == 'yes')
                	{
	
				$list .= '<a href="index.php?module=Activities&action=EditView&return_module='.$parentmodule.'&return_action='.$parentaction.'&activity_mode='.$activitymode.'&record='.$row["activityid"].'&return_id='.$_REQUEST["record"].'">'.$app_strings['LNK_EDIT'].'</a>  |  ';
			}
	
			if(isPermitted("Activities",2,$row["activityid"]) == 'yes')
                	{
				$list .= '<a href="index.php?module=Activities&action=Delete&return_module='.$parentmodule.'&return_action='.$parentaction.'&record='.$row["activityid"].'&return_id='.$_REQUEST["record"].'">'.$app_strings['LNK_DELETE'].'</a>';
			}
	
			$list .= '</td>';
			
			$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';

			$list .= '</tr>';
			$i++;
		}

// begin: Armando Lüscher 14.07.2005 -> §scrollableTables
// Desc: Close table from 
		$list .= ($noofrows>15) ? '</table></div></td></tr>':'';
// end: Armando Lüscher 14.07.2005 -> §scrollableTables

		$list .= '<tr><td colspan="14" class="blackLine"></td></tr>';

		$list .= '</table>';
		echo $list;
	}
}

function getPriceBookRelatedProducts($query,$focus,$returnset='')
{
	global $adb;
	global $app_strings;
	global $mod_strings;
	global $current_language;
	$current_module_strings = return_module_language($current_language, 'Products');

	global $list_max_entries_per_page;
	global $urlPrefix;


	global $theme;
	$pricebook_id = $_REQUEST['record'];
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	require_once($theme_path.'layout_utils.php');
	$list_result = 	$adb->query($query);
	$num_rows = $adb->num_rows($list_result);
	$xtpl=new XTemplate('include/RelatedListView.html');
	$xtpl->assign("MOD", $mod_strings);
	$xtpl->assign("APP", $app_strings);
	$xtpl->assign("IMAGE_PATH",$image_path);
	echo '<BR>';
	$other_text = '<table width="100%" border="0" cellpadding="1" cellspacing="0">
	<form name="selectproduct" method="POST">
	<tr>
	<input name="action" type="hidden" value="AddProductsToPriceBook">
	<input name="module" type="hidden" value="Products">
	<input name="return_module" type="hidden" value="Products">
	<input name="return_action" type="hidden" value="PriceBookDetailView">
	<input name="pricebook_id" type="hidden" value="'.$_REQUEST["record"].'">';

        $other_text .='<td><input title="Select Products" accessyKey="F" class="button" onclick="this.form.action.value=\'AddProductsToPriceBook\';this.form.module.value=\'Products\';this.form.return_module.value=\'Products\';this.form.return_action.value=\'PriceBookDetailView\'" type="submit" name="button" value="'.$app_strings["LBL_SELECT_PRODUCT_BUTTON_LABEL"].'"></td>';
		$other_text .='</tr></table>';

//Retreive the list from Database
echo get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'], $other_text, false );


//echo $list_query;
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

$list_header = '';
$list_header .= '<tr class="moduleListTitle" height=20>';
$list_header .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
$list_header .= '<td width="40%" class="moduleListTitle" height="21" style="padding:0px 3px 0px 3px;">'.$mod_strings['LBL_LIST_PRODUCT_NAME'].'</td>';
$list_header .='<td WIDTH="1" '.$class_black.' NOWRAP><IMG SRC="{IMAGE_PATH}blank.gif"></td>';
$list_header .= '<td width="15%" class="moduleListTitle" height="21" style="padding:0px 3px 0px 3px;">'.$mod_strings['LBL_PRODUCT_CODE'].'</td>';
$list_header .='<td WIDTH="1" '.$class_black.' NOWRAP><IMG SRC="{IMAGE_PATH}blank.gif"></td>';
$list_header .= '<td width="15%" class="moduleListTitle" height="21" style="padding:0px 3px 0px 3px;">'.$mod_strings['LBL_PRODUCT_UNIT_PRICE'].'</td>';
$list_header .='<td WIDTH="1" '.$class_black.' NOWRAP><IMG SRC="{IMAGE_PATH}blank.gif"></td>';
$list_header .= '<td width="15%" class="moduleListTitle" height="21" style="padding:0px 3px 0px 3px;">'.$mod_strings['LBL_PB_LIST_PRICE'].'</td>';
$list_header .='<td WIDTH="1" '.$class_black.' NOWRAP><IMG SRC="{IMAGE_PATH}blank.gif"></td>';
$list_header .= '<td width="15%" class="moduleListTitle" height="21" style="padding:0px 3px 0px 3px;">Edit|Del</td>';
$list_header .='<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="{IMAGE_PATH}blank.gif"></td>';

$list_header .= '</tr>';

$xtpl->assign("LISTHEADER", $list_header);

$list_body ='';
// begin: Armando Lüscher 14.07.2005 -> §scrollableTables
// Desc: 'X'
//			 Insert new table with 1 cell where all entries are in a new table.
//			 This cell will be scrollable when too many entries exist
		$list_body .= ($num_rows>15) ? '<tr><td colspan="12"><div style="overflow:auto;height:315px;width:100%;"><table cellspacing="0" cellpadding="0" border="0" width="100%">':'';
// end: Armando Lüscher 14.07.2005 -> §scrollableTablEs

for($i=0; $i<$num_rows; $i++)
{
	$entity_id = $adb->query_result($list_result,$i,"crmid");
		if (($i%2)==0)
			$list_body .= '<tr height=20 class=evenListRow>';
		else
			$list_body .= '<tr height=20 class=oddListRow>';

		$unit_price = 	$adb->query_result($list_result,$i,"unit_price");
		$listprice = $adb->query_result($list_result,$i,"listprice");
		$field_name=$entity_id."_listprice";

		$list_body .= '<td WIDTH="1" class="blackLine"><IMG SRC="'.$image_path.'blank.gif"></td>';
		$list_body .= '<td width="40%" height="21" style="padding:0px 3px 0px 3px;">'.$adb->query_result($list_result,$i,"productname").'</td>';
		$list_body .='<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="'.$image_path.'blank.gif"></td>';
		$list_body .= '<td width="15%" height="21" style="padding:0px 3px 0px 3px;">'.$adb->query_result($list_result,$i,"productcode").'</td>';
		$list_body .='<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="'.$image_path.'blank.gif"></td>';
		$list_body .= '<td width="15%" height="21" style="padding:0px 3px 0px 3px;">'.$unit_price.'</td>';
		$list_body .='<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="'.$image_path.'blank.gif"></td>';
		$list_body .= '<td width="15%" height="21" style="padding:0px 3px 0px 3px;">'.$listprice.'</td>';
		$list_body .='<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="'.$image_path.'blank.gif"></td>';
		$list_body .= '<td width="15%" height="21" style="padding:0px 3px 0px 3px;"><a href="index.php?module=Products&action=EditListPrice&record='.$entity_id.'&pricebook_id='.$pricebook_id.'&listprice='.$listprice.'">edit</a>&nbsp;|&nbsp;<a href="index.php?module=Products&action=DeletePriceBookProductRel'.$returnset.'&record='.$entity_id.'&pricebook_id='.$pricebook_id.'">del</a></td>';
	$list_body .='<td WIDTH="1" class="blackLine" NOWRAP><IMG SRC="'.$image_path.'blank.gif"></td></tr>';
	
}
// begin: Armando Lüscher 14.07.2005 -> §scrollableTables
// Desc: Close table from 
		$list_body .= ($num_rows>15) ? '</table></div></td></tr>':'';
// end: Armando Lüscher 14.07.2005 -> §scrollableTables

	$list_body .= '<tr><td COLSPAN="12" class="blackLine"><IMG SRC="themes/'.$theme.'/images//blank.gif"></td></tr>';

//$listview_entries = getListViewEntries($focus,"Products",$list_result,$navigation_array);

$xtpl->assign("LISTENTITY", $list_body);

$xtpl->parse("main");
$xtpl->out("main");	

}

//echo '</form>';
?>

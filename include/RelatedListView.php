<?php
require_once('modules/Users/UserInfoUtil.php');

function GetRelatedList($module,$relatedmodule,$focus,$query,$button,$returnset)
{

require_once('XTemplate/xtpl.php');
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

// focus_list is the means of passing data to a ListView.
global $focus_list;

if (!isset($where)) $where = "";

if (isset($_REQUEST['order_by'])) $order_by = $_REQUEST['order_by'];

//if($module == 'Potentials')
//	$focus = new Potential();

echo '<br><br>';

$button = '<table cellspacing=0 cellpadding=2><tr><td>'.$button.'</td></tr></table>';

echo get_form_header($relatedmodule,$button, false);
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
if($others_rel_permission_id == 3 && $module != 'Notes' && $module != 'Products' && $module != 'Faq')
{
	 $query .= " and crmentity.smownerid in(".$current_user->id .",0)";
}

if(isset($where) && $where != '')
{
        $query .= ' and '.$where;
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
	echo 'None Scheduled';
}
else
{
	$listview_header = getListViewHeader($focus,$relatedmodule,'','','','relatedlist');//"Accounts");
	$xtpl->assign("LISTHEADER", $listview_header);

	$listview_entries = getListViewEntries($focus,$relatedmodule,$list_result,$navigation_array,'relatedlist',$returnset);
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

function getAttachmentsAndNotes($parentmodule,$query,$id)
{
	global $theme;
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	require_once ($theme_path."layout_utils.php");

	global $adb;
	global $mod_strings;
	global $app_strings;

	$result=$adb->query($query);
	$noofrows = $adb->num_rows($result);
	
	$button .= '<table cellspacing=0 cellpadding=2><tr><td>';
	$button .= '<input type="hidden" name="fileid">';
	$button .= '<input title="New Attachment" accessyKey="F" class="button" onclick="this.form.action.value=\'upload\';this.form.module.value=\'uploads\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_ATTACHMENT'].'">&nbsp;';

        if(isPermitted("Notes",1,"") == 'yes')
        {
	
		$button .= '<input title="New Notes" accessyKey="F" class="button" onclick="this.form.action.value=\'EditView\';this.form.return_action.value=\'DetailView\';this.form.module.value=\'Notes\'" type="submit" name="button" value="'.$app_strings['LBL_NEW_NOTE'].'">&nbsp;';
	}
	$button .= '</td></tr></table>';
	

echo '<br><br>';
echo get_form_header($app_strings['LBL_ATTACHMENT_AND_NOTES'],$button, false);

if($noofrows == 0)
{
	echo 'None Scheduled';
}
else
{
	$list .= '<table border="0" cellpadding="0" cellspacing="0" class="FormBorder" width="100%">';
	$list .= '<tr class="ModuleListTitle" height=20>';

	$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
	$list .= '<td class="moduleListTitle">';

	$list .= $app_strings['LBL_TITLE'].'</td>';
	$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
	$list .= '<td width="%" class="moduleListTitle">';

	$list .= $app_strings['LBL_ENTITY_TYPE'].'</td>';
	$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
	$list .= '<td width="%" class="moduleListTitle">';

	$list .= $app_strings['LBL_FILENAME'].'</td>';
	$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
	$list .= '<td width="%" class="moduleListTitle">';

	$list .= $app_strings['LBL_TYPE'].'</td>';
	$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
	$list .= '<td width="%" class="moduleListTitle">';

	$list .= $app_strings['LBL_LAST_MODIFIED'].'</td>';
	$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
	$list .= '<td class="moduleListTitle" height="21">';

	$list .= $app_strings['LBL_ACTION'].'</td>';
	$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
	$list .= '<td width="%" class="moduleListTitle">';

	$list .= '</td>';
	$list .= '</tr>';

	$list .= '<tr><td COLSPAN="12" class="blackLine"><IMG SRC="themes/'.$theme.'/images//blank.gif"></td></tr>';

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
		$list .= '<td width="30%"><a href="index.php?module='.$module.'&action=DetailView&return_module='.$returnmodule.'&return_action='.$returnaction.'&record='.$row["crmid"] .'&return_id='.$_REQUEST['record'].'">'.$row[0].'</td>';

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
		$list .= $row[4];

		$list .= '<td WIDTH="1" class="blackLine"><IMG SRC="themes/'.$theme.'/images/blank.gif">';
		$list .= '<td width="10%" height="21" style="padding:0px 3px 0px 3px;">';

		if($row[1] == 'Notes')
			$list .= '<a href="index.php?module='.$module.'&action='.$editaction.'&return_module='.$parentmodule.'&return_action=DetailView&record='.$row["crmid"].'&filename='.$row[2].'&fileid='.$row['attachmentsid'].'&return_id='.$_REQUEST["record"].'">'.$app_strings['LNK_EDIT'].'</a>  |  ';
		$list .= '<a href="index.php?module='.$module.'&action='.$deleteaction.'&return_module='.$parentmodule.'&return_action=DetailView&record='.$row["crmid"].'&filename='.$row[2].'&return_id='.$_REQUEST["record"].'">'.$app_strings['LNK_DELETE'].'</a>';

		$list .= '</td>';

		$list .= '</tr>';
		$i++;
	}

	$list .= '</table>';
	echo $list;

}
}
//echo '</form>';
?>

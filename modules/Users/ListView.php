<?
require_once('include/utils/utils.php');
global $app_strings;
global $list_max_entries_per_page;

$log = LoggerManager::getLogger('user_list');

global $mod_strings;
global $currentModule;
global $theme;
global $current_language;
$mod_strings = return_module_language($current_language,'Users');
$category = getParentTab();
$focus = new User();
$smarty = new vtigerCRM_Smarty;
$no_of_users=UserCount();
//Retreiving the start value from request
if(isset($_REQUEST['start']) && $_REQUEST['start'] != '')
{
	        $start = $_REQUEST['start'];

			    //added to remain the navigation when sort
				    $url_string = "&start=".$_REQUEST['start'];
}
else
{
	        $start = 1;
}
//Retreive the Navigation array
$navigation_array = getNavigationValues($start, $no_of_users['user'], '10');
$start_rec = $navigation_array['start'];
$end_rec = $navigation_array['end_val'];
$record_string= $app_strings[LBL_SHOWING]." " .$start_rec." - ".$end_rec." " .$app_strings[LBL_LIST_OF] ." ".$no_of_users['user'];
$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"Administration","index",'');
$smarty->assign("MOD", return_module_language($current_language,'Settings'));
$smarty->assign("CMOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("CATEGORY",$category);
$smarty->assign("LIST_HEADER",$focus->getUserListViewHeader());
$smarty->assign("LIST_ENTRIES",$focus->getUserListViewEntries($navigation_array));
$smarty->assign("USER_COUNT",$no_of_users);
$smarty->assign("RECORD_COUNTS", $record_string);
$smarty->assign("NAVIGATION", $navigationOutput);
$smarty->assign("USER_IMAGES",getUserImageNames());
$smarty->display("UserListView.tpl");
?>

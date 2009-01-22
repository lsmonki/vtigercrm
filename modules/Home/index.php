<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Home/index.php,v 1.28 2005/04/20 06:57:47 samk Exp $
 * Description:  Main file for the Home module.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
require_once('include/home.php');
require_once('Smarty_setup.php');
require_once('modules/Home/HomeBlock.php');
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once('include/database/PearDatabase.php');
require_once('include/utils/UserInfoUtil.php');
require_once('include/utils/CommonUtils.php');
require_once('include/freetag/freetag.class.php');
global $app_strings;
global $app_list_strings;
global $mod_strings;
$smarty = new vtigerCRM_Smarty;
$homeObj=new Homestuff;
$smarty->assign("MOD",$mod_strings);
$smarty->assign("APP",$app_strings);
$smarty->assign("THEME", $theme);
$_REQUEST['search_form'] = 'false';
$_REQUEST['query'] = 'true';
$_REQUEST['status'] = 'In Progress--Not Started';
$_REQUEST['current_user_only'] = 'On';

$task_title = $mod_strings['LBL_OPEN_TASKS'];

// MWC Home Order Sorting functions given by mike
global $adb;
global $current_user;

$query = "SELECT vtiger_users.homeorder FROM vtiger_users WHERE id=?";
$result =& $adb->pquery($query, array($current_user->id), true,"Error getting home order");
$row = $adb->fetchByAssoc($result);

if($row != null)
{
	$home_section_order = $row['homeorder'];
}
if( count($home_section_order) < 1 )
{
	$home_section_order = array("HDB","ALVT","PLVT","QLTQ","CVLVT","HLT","OLV","GRT","OLTSO","ILTI","MNL","OLTPO","LTFAQ");
}

// To better performance, let us prefetch the module active info earlier
vtlib_prefetchModuleActiveInfo(); 
// END

$query="select name,tabid from vtiger_tab where tabid in(select distinct(tabid) from vtiger_field where tabid <> 29 and tabid <> 16 and tabid <>10 and tabid <> 37) order by name";
$result=$adb->query($query);

for($i=0;$i<$adb->num_rows($result);$i++)
{
	$modName=$adb->query_result($result,$i,'name');
	//Security check done by Don
	if(isPermitted($modName,'DetailView') == 'yes' && vtlib_isModuleActive($modName))
	{
		$modulenamearr[]=array($adb->query_result($result,$i,'tabid'),$modName);
	}	
}
		
	if(isPermitted('Calendar','index') == "yes" && vtlib_isModuleActive('Calendar'))
	{
		$activities = Array();
                include("modules/Calendar/OpenListView.php") ;
		$smarty->assign("VIEWID", getCvIdOfAll("Calendar"));
                $activities[] = getPendingActivities(0,"today");
                //$activities[] = getPendingActivities(0,"all");
                $activities[] = getPendingActivities(1,"today");
                //$activities[] = getPendingActivities(1,"all");
	}
//Security Check done for RSS and Dashboards
$allow_rss='no';
$allow_dashbd='no';
if(isPermitted('Rss','DetailView') == 'yes' && vtlib_isModuleActive('Rss'))
{
	$allow_rss='yes';
}	
if(isPermitted('Dashboard','DetailView') == 'yes' && vtlib_isModuleActive('Dashboard'))
{
	$allow_dashbd='yes';
}

//Query to check if the proxy server is configured
$qry="select * from vtiger_systems where server_type='proxy'";
$res=$adb->query($qry);
//Proxy server configuration to be handled
//if($adb->num_rows($res)==0)
//{
//	$smarty->assign("CONFIGPROXY","Proxy Server is not configured. <a href=index.php?module=Settings&action=ProxyServerConfig&parenttab=Settings target=_blank>Click Here</a>! to configure.");			
//}

$homedetails = $homeObj->getHomePageFrame();
$maxdiv = sizeof($homedetails)-1;

$smarty->assign("ACTIVITIES", $activities);
$smarty->assign("MAXLEN",$maxdiv);
$smarty->assign("ALLOW_RSS",$allow_rss);
$smarty->assign("ALLOW_DASH",$allow_dashbd);
$smarty->assign("HOMEFRAME",$homedetails);
$smarty->assign("MODULE_NAME",$modulenamearr);

global $current_language;

global $current_user;
$user_name = $current_user->column_fields[user_name];
$current_module_strings = return_module_language($current_language, 'Calendar');

$t=Date("Ymd");
//echo '<pre>';print_r($home_values); echo '</pre>'; 
$buttoncheck['Calendar'] = isPermitted('Calendar','index');
$smarty->assign("CHECK",$buttoncheck);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("APP",$app_strings);
$smarty->assign("MOD",$mod_strings);
$smarty->assign("MODULE",'Home');
$smarty->assign("CATEGORY",getParenttab('Home'));
$smarty->assign("HOMEDETAILS",$home_values);
$smarty->assign("HOMEDEFAULTVIEW",DefHomeView());
$smarty->assign("ACTIVITIES",$activities);
$smarty->assign("CURRENTUSER",$user_name);
$freetag = new freetag();
$smarty->assign("ALL_TAG",$freetag->get_tag_cloud_html("",$current_user->id));
$smarty->assign("NOTEBOOK_CONTENTS",getNotebookContents());

$smarty->display("Home/Homestuff.tpl");

/**
 * this function returns the notebook contents from the database
 * if there are no contents for a given user it creates a test content
 * @return - contents of the notebook for a user
 */
function getNotebookContents(){
	global $adb, $current_user;
	$contents = "Double-click here to edit ";
	
	$sql = "select * from vtiger_notebook_contents where userid=".$current_user->id;
	$result = $adb->query($sql);
	
	if($adb->num_rows($result)>0){
		$contents = $adb->query_result($result,0,"contents");
	}else{
		$sql = "insert into vtiger_notebook_contents values (?,?)";
		$adb->pquery($sql, array($current_user->id, $contents));
	}
	return $contents;
}
?>

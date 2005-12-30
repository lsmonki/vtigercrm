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
 * $Header$
 * Description:  Contains a variety of utility functions used to display UI
 * components such as form headers and footers.  Intended to be modified on a per
 * theme basis.
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once("include/utils/utils.php");


global $currentModule;
global $moduleList;
global $theme;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

global $app_strings;

$module_path="modules/".$currentModule."/";
require_once("include/Menu.php");
global $module_menu;

require_once("include/clock/Clock.php");
require_once("include/calculator/Calc.php");

$xtpl=new XTemplate ($theme_path."header.html");

/* Forum Display
$displayForums = $_REQUEST['forumDisplay'];
if($displayForums == "")
{
	$displayForums = true;
}
if($displayForums == "true")
$xtpl->assign("FORUM", "<script language=\"JavaScript\" type=\"text/javascript\" src=\"http://www.vtiger.com/forums/vtcrm_topicsanywhere.php?mode=show&f=uMSwyLDMsNyw5&n=5&jlp=y&a=y&s=y&l=y&m=y&h='a\'s\'m\&b=non&lpd=0&lpi=y&ch=30&cl=style.css\"></script><br>");
*/
$xtpl->assign("APP", $app_strings);
if(isset($app_strings['LBL_CHARSET']))
{
	$xtpl->assign("LBL_CHARSET", $app_strings['LBL_CHARSET']);
}
else
{
	$xtpl->assign("LBL_CHARSET", $default_charset);
}

$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$xtpl->assign("MODULE_NAME", $currentModule);
$xtpl->assign("DATE", getDisplayDate(date("Y-m-d H:i")));
if ($current_user->first_name != '') $xtpl->assign("CURRENT_USER", $current_user->first_name);
else $xtpl->assign("CURRENT_USER", $current_user->user_name);

$xtpl->assign("CURRENT_USER_ID", $current_user->id);

if (is_admin($current_user)) $xtpl->assign("ADMIN_LINK", "<a href='index.php?module=Settings&action=index'><img src='".$image_path."/settings_top.gif' hspace='3' align='absmiddle' border='0'>".$app_strings['LBL_SETTINGS']."</a>");

if (isset($_REQUEST['query_string'])) $xtpl->assign("SEARCH", $_REQUEST['query_string']);

if ($action == "EditView" || $action == "Login") $xtpl->assign("ONLOAD", 'onload="set_focus()"');

// Loop through the module list.
// For each tab that is off, parse a tab_off.
// For the current tab, parse a tab_on


//<<<<<<<<<<<<<<<< start of owner notify>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
$query = "select crmentity.setype,crmentity.crmid from crmentity inner join ownernotify on crmentity.crmid=ownernotify.crmid where 
ownernotify.smownerid=".$current_user->id;
$result = $adb->query($query);
$notify_values=array('Accounts'=>'Accounts:&nbsp&nbsp&nbsp;','Potentials'=>'Potentials:&nbsp&nbsp&nbsp;','Contacts'=>'Contacts:&nbsp&nbsp&nbsp;','Leads'=>'Leads:&nbsp&nbsp&nbsp;','SalesOrder'=>'SalesOrders:&nbsp&nbsp&nbsp;','PurchaseOrder'=>'PurchaseOrders:&nbsp&nbsp&nbsp;','Products'=>'Products:&nbsp&nbsp&nbsp;','Emails'=>'Emails:&nbsp&nbsp&nbsp;','HelpDesk'=>'HelpDesk:&nbsp&nbsp&nbsp;','Activities'=>'Activities:&nbsp&nbsp&nbsp;','Quotes'=>'Quotes:&nbsp&nbsp&nbsp;','Invoice'=>'Invoice:&nbsp&nbsp&nbsp;');

for($i=0;$i<$adb->num_rows($result);$i++)
{
	    $mod_notify[$i] = $adb->fetch_array($result);
		if($mod_notify[$i]['setype']=='Accounts')
		{
			$tempquery='select accountname from account where accountid='.$mod_notify[$i]['crmid'];
			$tempresult=$adb->query($tempquery);
			$account_name=$adb->fetch_array($tempresult);
			$notify_values['Accounts'].='<a href="index.php?module=Accounts&action=DetailView&record='.$mod_notify[$i]["crmid"].'">'.$account_name['accountname'].'</a>,&nbsp;';	
		}else if($mod_notify[$i]['setype']=='Potentials')
		{
			$tempquery='select potentialname from potential where potentialid='.$mod_notify[$i]['crmid'];
			$tempresult=$adb->query($tempquery);
			$potential_name=$adb->fetch_array($tempresult);
			$notify_values['Potentials'].='<a href="index.php?module=Potentials&action=DetailView&record='.$mod_notify[$i]["crmid"].'">'.$potential_name['potentialname'].'</a>,&nbsp;';
		}else if($mod_notify[$i]['setype']=='Contacts')
		{
			$tempquery='select lastname from contactdetails where contactid='.$mod_notify[$i]['crmid'];
			$tempresult=$adb->query($tempquery);
			$contact_name=$adb->fetch_array($tempresult);
			$notify_values['Contacts'].='<a href="index.php?module=Contacts&action=DetailView&record='.$mod_notify[$i]["crmid"].'">'.$contact_name['lastname'].'</a>,&nbsp;';

		}else if($mod_notify[$i]['setype']=='Leads')
		{
			$tempquery='select lastname from leaddetails where leadid='.$mod_notify[$i]['crmid'];
			$tempresult=$adb->query($tempquery);
			$lead_name=$adb->fetch_array($tempresult);
			$notify_values['Leads'].='<a href="index.php?module=Leads&action=DetailView&record='.$mod_notify[$i]["crmid"].'">'.$lead_name["lastname"].'</a>,';
		}else if($mod_notify[$i]['setype']=='SalesOrder')
		{
			$tempquery='select subject from salesorder where salesorderid='.$mod_notify[$i]['crmid'];
			$tempresult=$adb->query($tempquery);
			$sales_subject=$adb->fetch_array($tempresult);
			$notify_values['SalesOrder'].='<a href="index.php?module=SalesOrder&action=DetailView&record='.$mod_notify[$i]["crmid"].'">'.$sales_subject['subject'].'</a>,&nbsp;';

		}else if($mod_notify[$i]['setype']=='PurchaseOrder')
		{
			$tempquery='select subject from purchaseorder where purchaseorderid='.$mod_notify[$i]['crmid'];
			$tempresult=$adb->query($tempquery);
			$purchase_subject=$adb->fetch_array($tempresult);
			$notify_values['PurchaseOrder'].='<a href="index.php?module=PurchaseOrder&action=DetailView&record='.$mod_notify[$i]["crmid"].'">'.$purchase_subject['subject'].'</a>,&nbsp;';

		}else if($mod_notify[$i]['setype']=='Products')
		{
			$tempquery='select productname from products where productid='.$mod_notify[$i]['crmid'];
			$tempresult=$adb->query($tempquery);
			$product_name=$adb->fetch_array($tempresult);
			$notify_values['Products'].='<a href="index.php?module=Products&action=DetailView&record='.$mod_notify[$i]["crmid"].'">'.$product_name['productname'].'</a>,&nbsp;';
		}else if($mod_notify[$i]['setype']=='Emails')
		{
			$tempquery='select subject from activity where activityid='.$mod_notify[$i]['crmid'];
			$tempresult=$adb->query($tempquery);
			$email_subject=$adb->fetch_array($tempresult);
			$notify_values['Emails'].='<a href="index.php?module=Emails&action=DetailView&record='.$mod_notify[$i]["crmid"].'">'.$email_subject['subject'].'</a>,&nbsp;';

		}else if($mod_notify[$i]['setype']=='HelpDesk')
		{
			$tempquery='select title from troubletickets where ticketid='.$mod_notify[$i]['crmid'];
			$tempresult=$adb->query($tempquery);
			$HelpDesk_title=$adb->fetch_array($tempresult);
			$notify_values['HelpDesk'].='<a href="index.php?module=HelpDesk&action=DetailView&record='.$mod_notify[$i]["crmid"].'">'.$HelpDesk_title['title'].'</a>,&nbsp;';
		}else if($mod_notify[$i]['setype']=='Activities')
		{
			$tempquery='select subject from activity where activityid='.$mod_notify[$i]['crmid'];
			$tempresult=$adb->query($tempquery);
			$Activity_subject=$adb->fetch_array($tempresult);
			$notify_values['Activities'].=$Activity_subject['subject'].'</a>,&nbsp;';
		}else if($mod_notify[$i]['setype']=='Quotes')
		{
			$tempquery='select subject from quotes where quoteid='.$mod_notify[$i]['crmid'];
			$tempresult=$adb->query($tempquery);
			$quote_subject=$adb->fetch_array($tempresult);
			$notify_values['Quotes'].='<a href="index.php?module=Quotes&action=DetailView&record='.$mod_notify[$i]["crmid"].'">'.$quote_subject['subject'].'</a>,&nbsp;';
		}else if($mod_notify[$i]['setype']=='Invoice')
		{
			$tempquery='select subject from invoice where invoiceid='.$mod_notify[$i]['crmid'];
			$tempresult=$adb->query($tempquery);
			$invoice_subject=$adb->fetch_array($tempresult);
			$notify_values['Invoice'].='<a href="index.php?module=Invoice&action=DetailView&record='.$mod_notify[$i]["crmid"].'">'.$invoice_subject["subject"].'</a>,';
		}


}

if($notify_values['Accounts']!='Accounts:&nbsp&nbsp&nbsp;')
	$allnotification.=$notify_values['Accounts'];
if($notify_values['Potentials']!='Potentials:&nbsp&nbsp&nbsp;')
	$allnotification.=$notify_values['Potentials'];
if($notify_values['Contacts']!='Contacts:&nbsp&nbsp&nbsp;')
	$allnotification.=$notify_values['Contacts'];
if($notify_values['Leads']!='Leads:&nbsp&nbsp&nbsp;')
	$allnotification.=$notify_values['Leads'];
if($notify_values['SalesOrder']!='SalesOrders:&nbsp&nbsp&nbsp;')
	$allnotification.=$notify_values['SalesOrder'];
if($notify_values['PurchaseOrder']!='PurchaseOrders:&nbsp&nbsp&nbsp;')
	$allnotification.=$notify_values['PurchaseOrder'];
if($notify_values['Products']!='Products:&nbsp&nbsp&nbsp;')
	$allnotification.=$notify_values['Products'];
if($notify_values['Emails']!='Emails:&nbsp&nbsp&nbsp;')
	$allnotification.=$notify_values['Emails'];
if($notify_values['Activities']!='Activities:&nbsp&nbsp&nbsp;')
	$allnotification.=$notify_values['Activities'];
if($notify_values['Quotes']!='Quotes:&nbsp&nbsp&nbsp;')
	$allnotification.=$notify_values['Quotes'];
if($notify_values['Invoice']!='Invoice:&nbsp&nbsp&nbsp;')
	$allnotification.=$notify_values['Invoice'];

$allnotification='Notifications for '.$current_user->user_name.':'.$allnotification;
//echo $allnotification;
//die;
echo '<script language="JavaScript1.2">

//Specify the marquee\'s width (in pixels)
var marqueewidth="1024px"
//Specify the marquee\'s height
var marqueeheight="15px"
//Specify the marquee\'s marquee speed (larger is faster 1-10)
var marqueespeed=2
//configure background color:
var marqueebgcolor="#7DBEFF"
//Pause marquee onMousever (0=no. 1=yes)?
var pauseit=1

//Specify the marquee\'s content (don\'t delete <nobr> tag)
//Keep all content on ONE line, and backslash any single quotations (ie: that\'s great):

var marqueecontent=\'<nobr><font face="Arial">'.$allnotification.'</font></nobr>\'


////NO NEED TO EDIT BELOW THIS LINE////////////
marqueespeed=(document.all)? marqueespeed : Math.max(1, marqueespeed-1) //slow speed down by 1 for NS
var copyspeed=marqueespeed
var pausespeed=(pauseit==0)? copyspeed: 0
var iedom=document.all||document.getElementById
if (iedom)
document.write(\'<span id="temp" style="visibility:hidden;position:absolute;top:-100px;left:-9000px">\'+marqueecontent+\'</span>\')
var actualwidth=\'\'
var cross_marquee, ns_marquee

function populate(){
if (iedom){
cross_marquee=document.getElementById? document.getElementById("iemarquee") : document.all.iemarquee
cross_marquee.style.left=parseInt(marqueewidth)+8+"px"
cross_marquee.innerHTML=marqueecontent
actualwidth=document.all? temp.offsetWidth : document.getElementById("temp").offsetWidth
}
else if (document.layers){
ns_marquee=document.ns_marquee.document.ns_marquee2
ns_marquee.left=parseInt(marqueewidth)+8
ns_marquee.document.write(marqueecontent)
ns_marquee.document.close()
actualwidth=ns_marquee.document.width
}
lefttime=setInterval("scrollmarquee()",20)
}
window.onload=populate

function scrollmarquee(){
if (iedom){
if (parseInt(cross_marquee.style.left)>(actualwidth*(-1)+8))
cross_marquee.style.left=parseInt(cross_marquee.style.left)-copyspeed+"px"
else
cross_marquee.style.left=parseInt(marqueewidth)+8+"px"

}
else if (document.layers){
if (ns_marquee.left>(actualwidth*(-1)+8))
ns_marquee.left-=copyspeed
else
ns_marquee.left=parseInt(marqueewidth)+8
}
}

if (iedom||document.layers){
with (document){
document.write(\'<table border="0" cellspacing="0" cellpadding="0"><td>\')
if (iedom){
write(\'<div style="position:relative;width:\'+marqueewidth+\';height:\'+marqueeheight+\';overflow:hidden">\')
write(\'<div style="position:absolute;width:\'+marqueewidth+\';height:\'+marqueeheight+\';background-color:\'+marqueebgcolor+\'" onMouseover="copyspeed=pausespeed" onMouseout="copyspeed=marqueespeed">\')
write(\'<div id="iemarquee" style="position:absolute;left:0px;top:0px"></div>\')
write(\'</div></div>\')
}
else if (document.layers){
write(\'<ilayer width=\'+marqueewidth+\' height=\'+marqueeheight+\' name="ns_marquee" bgColor=\'+marqueebgcolor+\'>\')
write(\'<layer name="ns_marquee2" left=0 top=0 onMouseover="copyspeed=pausespeed" onMouseout="copyspeed=marqueespeed"></layer>\')
write(\'</ilayer>\')
}
document.write(\'</td></table>\')
}
}
</script>';

///end of code for notification


foreach($moduleList as $module_name)
{
	$xtpl->assign("MODULE_NAME", $app_list_strings['moduleList'][$module_name]);
	$xtpl->assign("MODULE_KEY", $module_name);
	if($module_name == $currentModule)
	{
		$xtpl->assign("TAB_CLASS", "currentTab");
		$xtpl->assign("CLASS_TABBORDER", "tabOnBorder");
		$xtpl->assign("IMAGE_TABTILE", "menu_on_tile.gif");
		$xtpl->assign("IMAGE_TABSTART", "menu_on_start.gif");
		$xtpl->assign("IMAGE_TABEND", "menu_on_end.gif");
	}
	else
	{
		$xtpl->assign("TAB_CLASS", "otherTab");
		$xtpl->assign("CLASS_TABBORDER", "tabOffBorder");
		$xtpl->assign("IMAGE_TABTILE", "menu_off_tile.gif");
		$xtpl->assign("IMAGE_TABSTART", "menu_off_start.gif");
		$xtpl->assign("IMAGE_TABEND", "menu_off_end.gif");
	}
	$xtpl->parse("main.tab");
}

// Assign the module name back to the current module.
$xtpl->assign("MODULE_NAME", $currentModule);

//Menu items to be displayed
$showmenu = 0;
foreach($module_menu as $menu_item)
{
	$after_this = current($module_menu);
	if($showmenu < 10)
	{

	if ($menu_item[1] != 'Deleted Items') {
		$xtpl->assign("URL", $menu_item[0]);
		$xtpl->assign("LABEL", $menu_item[1]);
		if (empty($after_this)) $xtpl->assign("SEPARATOR", "");
		else $xtpl->assign("SEPARATOR", "|");
	}
	else {
		$xtpl->assign("DELETED_ITEMS_URL", $menu_item[0]);
		$xtpl->assign("DELETED_ITEMS_LABEL", $menu_item[1]);
	}

	$xtpl->parse("main.sub_menu.sub_menu_item");
	}
        $showmenu++;
}
//Menu items to be displayed in drop down
$showmenu = 0;
$showmenu_drop = 10;
foreach($module_menu as $menu_item)
{
	if(($showmenu >= $showmenu_drop) && ($showmenu < count($module_menu)))
	{
		$after_this = current($module_menu);

		if ($menu_item[1] != 'Deleted Items') {
			$xtpl->assign("URL", $menu_item[0]);
			$xtpl->assign("LABEL", $menu_item[1]);
		}
		else {
			$xtpl->assign("DELETED_ITEMS_URL", $menu_item[0]);
			$xtpl->assign("DELETED_ITEMS_LABEL", $menu_item[1]);
		}

		$xtpl->parse("main.dropdown_sub_menu_item");
	}
	$showmenu++;
}

$xtpl->parse("main.sub_menu");

$xtpl->assign("TITLE", $app_strings['LBL_SEARCH']);
$xtpl->parse("main.left_form.left_form_search");
$xtpl->parse("main.left_form");
if($currentModule != "Rss")
{
$xtpl->assign("LASTVIEWED_TITLE", $app_strings['LBL_LAST_VIEWED']);

$tracker = new Tracker();
$history = $tracker->get_recently_viewed($current_user->id);

$current_row=1;

if (count($history) > 0) {
	foreach($history as $row)
	{
		$xtpl->assign("MODULE_NAME",$row['module_name']);
		$xtpl->assign("ROW_NUMBER",$current_row);
		$xtpl->assign("RECENT_LABEL",$row['item_summary']);

		if($row['module_name']=='Activities')
		{
			$sql = 'select activitytype from activity where activityid = '.$row['item_id'];
			$activitytype = $adb->query_result($adb->query($sql),0,'activitytype');
			if($activitytype == 'Task')
				$activity_mode = '&activity_mode=Task';
			elseif($activitytype == 'Call' || $activitytype == 'Meeting')
				$activity_mode = '&activity_mode=Events';
		}

		$url_module = $row['module_name'];
		$url_action = 'DetailView';

		$xtpl->assign("MODULE_IMAGE_NAME",$row['module_name']);

		$xtpl->assign("RECENT_URL","index.php?module=$url_module&action=$url_action&record=$row[item_id]$activity_mode");
		$activity_mode = '';	
		$xtpl->parse("main.left_form.left_form_recent_view.left_form_recent_view_row");
		$current_row++;
	}
}
else {
		$xtpl->parse("main.left_form.left_form_recent_view.left_form_recent_view_empty");
}
$xtpl->parse("main.left_form.left_form_recent_view");
$xtpl->parse("main.left_form");
}

//check for the access for Create/Edit and enable or disable 
//check for the presence of the currentModule and  also for EditView permission

$now_action =  $_REQUEST['action'];
$now_module = $_REQUEST['module'];

$tabid = getTabid($now_module);
$actionid = getActionid($now_action);

if($actionid == 3)
{
	$QuickCreateForm = getQuickCreate($tabid,$actionid); 	
}

if(isset($QuickCreateForm) && $QuickCreateForm == 'true')
{
	if($now_module == 'Faq')
                $currentModule = $now_module;

	require_once("modules/".$currentModule."/Forms.php");
	if (function_exists('get_new_record_form'))
	{
      		$xtpl->assign("NEW_RECORD", get_new_record_form());
	      	$xtpl->parse("main.left_form_new_record");
	}
}

if($currentModule == "Rss")
{
        require_once("modules/".$currentModule."/Forms.php");
        if (function_exists('get_rssfeeds_form'))
        {
                $xtpl->assign("RSSFEEDS_TITLE","<div style='float:left'>".$app_strings['LBL_RSS_FEEDS'].":</div><div style='float:right;'><a href='javascript:openPopUp(\"addRssFeedIns\",this,\"index.php?action=Popup&module=Rss\",\"addRssFeedWin\",350,150,\"menubar=no,toolbar=no,location=no,status=no,scrollbars=yes,resizable=yes\");' title='".$app_strings['LBL_ADD_RSS_FEEDS']."'>Add<img src='".$image_path."/addrss.gif' border=0 align=absmiddle></a>&nbsp;</div>");
		$xtpl->assign("RSSFEEDS", get_rssfeeds_form());
                $xtpl->parse("main.left_form_rss");
        }
}
             
$xtpl->assign("CLOCK_TITLE", $app_strings['LBL_WORLD_CLOCK']);
$xtpl->assign("WORLD_CLOCK", get_world_clock($image_path));
if($currentModule != "Rss" && $WORLD_CLOCK_DISPLAY == 'true')
{
	$xtpl->parse("main.left_form_clock");
}

$xtpl->assign("CALC_TITLE", $app_strings['LBL_CALCULATOR']);
$xtpl->assign("CALC", get_calc($image_path));
if($currentModule != "Rss" && $CALCULATOR_DISPLAY == 'true')
{
	$xtpl->parse("main.left_form_calculator");
}

$xtpl->parse("main");
$xtpl->out("main");
?>

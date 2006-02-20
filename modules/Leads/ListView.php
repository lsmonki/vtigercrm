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
 * Description:  TODO: To be written.
 ********************************************************************************/

require_once('Smarty_setup.php');
require_once("data/Tracker.php");
require_once('modules/Leads/Lead.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/ListView/ListView.php');
require_once('include/database/PearDatabase.php');
require_once('include/ComboUtil.php');
require_once('include/utils/utils.php');
require_once('modules/CustomView/CustomView.php');

global $app_strings;
global $list_max_entries_per_page;

$log = LoggerManager::getLogger('contact_list');

global $currentModule;
global $theme;

// Get _dom arrays from Database
$comboFieldNames = Array('leadsource'=>'leadsource_dom'
                      ,'leadstatus'=>'leadstatus_dom'
                      ,'rating'=>'rating_dom'
                      ,'industry'=>'industry_dom');
$comboFieldArray = getComboArray($comboFieldNames);

$category = getParentTab();

if (!isset($where)) $where = "";

$url_string = ''; // assigning http url string

$focus = new Lead();
$smarty = new vtigerCRM_Smarty;
$other_text=Array();
//<<<<<<<<<<<<<<<<<<< sorting - stored in session >>>>>>>>>>>>>>>>>>>>
if($_REQUEST['order_by'] != '')
	$order_by = $_REQUEST['order_by'];
else
	$order_by = (($_SESSION['LEADS_ORDER_BY'] != '')?($_SESSION['LEADS_ORDER_BY']):($focus->default_order_by));

if($_REQUEST['sorder'] != '')
	$sorder = $_REQUEST['sorder'];
else
	$sorder = (($_SESSION['LEADS_SORT_ORDER'] != '')?($_SESSION['LEADS_SORT_ORDER']):($focus->default_sort_order));

$_SESSION['LEADS_ORDER_BY'] = $order_by;
$_SESSION['LEADS_SORT_ORDER'] = $sorder;
//<<<<<<<<<<<<<<<<<<< sorting - stored in session >>>>>>>>>>>>>>>>>>>>



if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'true')
{
 	$where=Search($currentModule);

	// we have a query
	$url_string .="&query=true";

	$log->info("Here is the where clause for the list view: $where");

}

//<<<<cutomview>>>>>>>
$oCustomView = new CustomView("Leads");
$customviewcombo_html = $oCustomView->getCustomViewCombo();
$viewid = $oCustomView->getViewId($currentModule);
$viewnamedesc = $oCustomView->getCustomViewByCvid($viewid);
//<<<<<customview>>>>>

if($viewid != 0)
{
	$CActionDtls = $oCustomView->getCustomActionDetails($viewid);
}
// Buttons and View options
//Modified by Raju
//raju
if(isPermitted('Leads',2,'') == 'yes')
{
	$other_text['del'] =	$app_strings[LBL_MASS_DELETE];	

}
$other_text['s_mail'] = $app_strings[LBL_SEND_MAIL_BUTTON];

if(isPermitted('Leads',1,'') == 'yes')
{
	$other_text['c_owner'] = $app_strings[LBL_CHANGE_OWNER];
	$other_text['c_status'] = $app_strings[LBL_CHANGE_STATUS];
}
if(isset($CActionDtls))
{
	$other_text['s_cmail'] = $app_strings[LBL_SEND_CUSTOM_MAIL_BUTTON];
}

if($viewnamedesc['viewname'] == 'All')
{
$cvHTML = '<td><a href="index.php?module=Leads&action=CustomView">'.$app_strings['LNK_CV_CREATEVIEW'].'</a>
<span class="small">|</span>
<span disabled>'.$app_strings['LNK_CV_EDIT'].'</span>
<span class="small">|</span>
<span disabled>'.$app_strings['LNK_CV_DELETE'].'</span></td>';
}else
{
$cvHTML = '<td><a href="index.php?module=Leads&action=CustomView" class="small">'.$app_strings['LNK_CV_CREATEVIEW'].'</a>
<span class="small">|</span>
<a href="index.php?module=Leads&action=CustomView&record='.$viewid.'" class="small">'.$app_strings['LNK_CV_EDIT'].'</a>
<span class="small">|</span>
<a href="index.php?module=CustomView&action=Delete&dmodule=Leads&record='.$viewid.'" class="small">'.$app_strings['LNK_CV_DELETE'].'</a></td>';
}
	$customstrings = '<td>'.$app_strings[LBL_VIEW].'</td>
			<td style="padding-left:5px;padding-right:5px">
                        <SELECT NAME="viewname" class="small" onchange="showDefaultCustomView(this)">
				'.$customviewcombo_html.'
                        </SELECT></td>
			'.$cvHTML;


$custom= get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'],$other_text, false);
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("CUSTOMVIEW",$customstrings);
$smarty->assign("MODULE",$currentModule);
$smarty->assign("SINGLE_MOD",'Lead');
$smarty->assign("BUTTONS",$other_text);
$smarty->assign("CATEGORY",$category);


//Retreive the list from Database
//<<<<<<<<<customview>>>>>>>>>
if($viewid != "0")
{
	$listquery = getListQuery("Leads");
	$query = $oCustomView->getModifiedCvListQuery($viewid,$listquery,"Leads");
}else
{
	$query = getListQuery("Leads");
}
//<<<<<<<<customview>>>>>>>>>

if(isset($where) && $where != '')
{
        $query .= ' and '.$where;
}

$view_script = "<script language='javascript'>
	function set_selected()
	{
		len=document.massdelete.viewname.length;
		for(i=0;i<len;i++)
		{
			if(document.massdelete.viewname[i].value == '$viewid')
				document.massdelete.viewname[i].selected = true;
		}
	}
	set_selected();
	</script>";


if(isset($order_by) && $order_by != '')
{
	$tablename = getTableNameForField('Leads',$order_by);
	$tablename = (($tablename != '')?($tablename."."):'');

        $query .= ' ORDER BY '.$tablename.$order_by.' '.$sorder;
}

$list_result = $adb->query($query);

//Retreiving the no of rows
$noofrows = $adb->num_rows($list_result);

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
$navigation_array = getNavigationValues($start, $noofrows, $list_max_entries_per_page);


//mass merge for word templates -- *Raj*17/11
while($row = $adb->fetch_array($list_result))
{
	$ids[] = $row["crmid"];
}
if(isset($ids))
{
	echo "<input name='allids' type='hidden' value='".implode($ids,";")."'>";
}
if(isPermitted("Leads",8,'') == 'yes') 
{
	$smarty->assign("MERGEBUTTON","<td><input title=\"$app_strings[LBL_MERGE_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_MERGE_BUTTON_KEY]\" class=\"small\" onclick=\"return massMerge()\" type=\"submit\" name=\"Merge\" value=\" $app_strings[LBL_MERGE_BUTTON_LABEL]\"></td>");
	$wordTemplateResult = fetchWordTemplateList("Leads");
	$tempCount = $adb->num_rows($wordTemplateResult);
	$tempVal = $adb->fetch_array($wordTemplateResult);
	for($templateCount=0;$templateCount<$tempCount;$templateCount++)
	{
		$optionString .="<option value=\"".$tempVal["templateid"]."\">" .$tempVal["filename"] ."</option>";
		$tempVal = $adb->fetch_array($wordTemplateResult);
	}
	$smarty->assign("WORDTEMPLATEOPTIONS","<td>".$mod_strings['LBL_SELECT_TEMPLATE_TO_MAIL_MERGE']."</td><td style=\"padding-left:5px;padding-right:5px\"><select class=\"small\" name=\"mergefile\">".$optionString."</select></td>");
}
//mass merge for word templates

// Setting the record count string
//modified by rdhital
$start_rec = $navigation_array['start'];
$end_rec = $navigation_array['end_val']; 
//By Raju Ends

$record_string= $app_strings[LBL_SHOWING]." " .$start_rec." - ".$end_rec." " .$app_strings[LBL_LIST_OF] ." ".$noofrows;

//Retreive the List View Table Header
if($viewid !='')
$url_string .= "&viewname=".$viewid;

$listview_header = getListViewHeader($focus,"Leads",$url_string,$sorder,$order_by,"",$oCustomView);
$smarty->assign("LISTHEADER", $listview_header);

$listview_header_search=getSearchListHeaderValues($focus,"Leads",$url_string,$sorder,$order_by,"",$oCustomView);
$smarty->assign("SEARCHLISTHEADER", $listview_header_search);

$listview_entries = getListViewEntries($focus,"Leads",$list_result,$navigation_array,"","","EditView","Delete",$oCustomView);
$smarty->assign("LISTENTITY", $listview_entries);
$smarty->assign("SELECT_SCRIPT", $view_script);

$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"Leads","index",$viewid);
$alphabetical = AlphabeticalSearch($currentModule,'index','lastname','true','basic',"","","","",$viewid);
$smarty->assign("ALPHABETICAL", $alphabetical);
$smarty->assign("NAVIGATION", $navigationOutput);
$smarty->assign("RECORD_COUNTS", $record_string);
$smarty->display("ListView.tpl");

?>

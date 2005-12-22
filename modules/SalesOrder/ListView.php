<?
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/
require_once('include/database/PearDatabase.php');
require_once('Smarty_setup.php');
require_once('modules/SalesOrder/SalesOrder.php');
require_once('include/utils/utils.php');
require_once('include/utils/utils.php');
require_once('modules/CustomView/CustomView.php');


global $app_strings;
global $mod_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'SalesOrder');

global $list_max_entries_per_page;
global $urlPrefix;

global $currentModule;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');
echo "<br>";

$smarty = new vtigerCRM_Smarty;
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("MODULE",$currentModule);


$focus = new SalesOrder();

$url_string = ''; // assigning http url string

//<<<<<<<<<<<<<<<<<<< sorting - stored in session >>>>>>>>>>>>>>>>>>>>
if($_REQUEST['order_by'] != '')
	$order_by = $_REQUEST['order_by'];
else
	$order_by = (($_SESSION['SALESORDER_ORDER_BY'] != '')?($_SESSION['SALESORDER_ORDER_BY']):($focus->default_order_by));

if($_REQUEST['sorder'] != '')
	$sorder = $_REQUEST['sorder'];
else
	$sorder = (($_SESSION['SALESORDER_SORT_ORDER'] != '')?($_SESSION['SALESORDER_SORT_ORDER']):($focus->default_sort_order));

$_SESSION['SALESORDER_ORDER_BY'] = $order_by;
$_SESSION['SALESORDER_SORT_ORDER'] = $sorder;
//<<<<<<<<<<<<<<<<<<< sorting - stored in session >>>>>>>>>>>>>>>>>>>>


if(isset($_REQUEST['query']) && $_REQUEST['query'] != '' && $_REQUEST['query'] == 'true')
{
	$url_string .="&query=true";
	if (isset($_REQUEST['subject'])) $subject = $_REQUEST['subject'];
        if (isset($_REQUEST['accountname'])) $accountname = $_REQUEST['accountname'];
        if (isset($_REQUEST['quotename'])) $quotename = $_REQUEST['quotename'];

	$where_clauses = Array();

	//Added for Custom Field Search
	$sql="select * from field where tablename='salesordercf' order by fieldlabel";
	$result=$adb->query($sql);
	for($i=0;$i<$adb->num_rows($result);$i++)
	{
	        $column[$i]=$adb->query_result($result,$i,'columnname');
	        $fieldlabel[$i]=$adb->query_result($result,$i,'fieldlabel');
		$uitype[$i]=$adb->query_result($result,$i,'uitype');

	        if (isset($_REQUEST[$column[$i]])) $customfield[$i] = $_REQUEST[$column[$i]];
	
	        if(isset($customfield[$i]) && $customfield[$i] != '')
	        {
			if($uitype[$i] == 56)
                                $str=" salesordercf.".$column[$i]." = 1";
                        else
			        $str=" salesordercf.".$column[$i]." like '$customfield[$i]%'";
		        array_push($where_clauses, $str);
			$url_string .="&".$column[$i]."=".$customfield[$i];
	        }
	}
	//upto this added for Custom Field

	if (isset($subject) && $subject !='')
	{
		array_push($where_clauses, "salesorder.subject like ".PearDatabase::quote($subject.'%'));
		$url_string .= "&subject=".$subject;
	}
	
	if (isset($accountname) && $accountname !='')
	{
		array_push($where_clauses, "account.accountname like ".PearDatabase::quote($accountname.'%'));
		$url_string .= "&accountname=".$accountname;
	}

	if (isset($quotename) && $quotename !='')
	{
		array_push($where_clauses, "quotes.subject like ".PearDatabase::quote($quotename.'%'));
		 $url_string .= "&quotename=".$quotename;
	}
	
	$where = "";
	foreach($where_clauses as $clause)
	{
		if($where != "")
		$where .= " and ";
		$where .= $clause;
	}

	$log->info("Here is the where clause for the list view: $where");
 

}

//<<<<cutomview>>>>>>>
$oCustomView = new CustomView("SalesOrder");
$customviewcombo_html = $oCustomView->getCustomViewCombo();
if(isset($_REQUEST['viewname']) == false)
{
        if($oCustomView->setdefaultviewid != "")
        {
                $viewid = $oCustomView->setdefaultviewid;
        }else
        {
                $viewid = "0";
        }
}else
{
        $viewid =  $_REQUEST['viewname'];
		$oCustomView->setdefaultviewid = $viewid;
}
//<<<<<customview>>>>>

//Constructing the Search Form
/*
if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
        // Stick the form header out there.
	echo get_form_header($current_module_strings['LBL_SO_SEARCH_TITLE'],'', false);
  //      $search_form=new XTemplate ('modules/SalesOrder/SearchForm.html');
        $search_form->assign("MOD", $mod_strings);
        $search_form->assign("APP", $app_strings);
	$clearsearch = 'true';
	
	$search_form->assign("VIEWID",$viewid);

	$search_form->assign("JAVASCRIPT", get_clear_form_js());

	$search_form->assign("BASIC_LINK", "index.php?module=SalesOrder&action=index".$url_string."&viewname=".$viewid);
	$search_form->assign("ADVANCE_LINK", "index.php?module=SalesOrder&action=index&advanced=true".$url_string."&viewname=".$viewid);

	if ($subject !='') $search_form->assign("SUBJECT", $subject);
	if ($accountname !='') $search_form->assign("ACCOUNTNAME", $accountname);
	if ($quotename !='') $search_form->assign("QUOTENAME", $quotename);

//Combo Fields for Manufacturer and Category are moved from advanced to Basic Search
        if (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true') 
	{
		$url_string .="&advanced=true";
		$search_form->assign("ALPHABETICAL",AlphabeticalSearch('SalesOrder','index&smodule=SO','subject','true','advanced',"","","","",$viewid));

		$search_form->assign("SUPPORT_START_DATE",$_REQUEST['start_date']);
		$search_form->assign("SUPPORT_EXPIRY_DATE",$_REQUEST['expiry_date']);
		$search_form->assign("PURCHASE_DATE",$_REQUEST['purchase_date']);
		$search_form->assign("DATE_FORMAT", $current_user->date_format);

		//Added for Custom Field Search
		$sql="select * from field where tablename='salesordercf' order by fieldlabel";
		$result=$adb->query($sql);
		for($i=0;$i<$adb->num_rows($result);$i++)
		{
		        $column[$i]=$adb->query_result($result,$i,'columnname');
		        $fieldlabel[$i]=$adb->query_result($result,$i,'fieldlabel');
		        if (isset($_REQUEST[$column[$i]])) $customfield[$i] = $_REQUEST[$column[$i]];
		}
		require_once('include/CustomFieldUtil.php');
		$custfld = CustomFieldSearch($customfield, "salesordercf", "salesordercf", "salesorderid", $app_strings,$theme,$column,$fieldlabel);
		$search_form->assign("CUSTOMFIELD", $custfld);
		//upto this added for Custom Field

                $search_form->parse("advanced");
                $search_form->out("advanced");
	}
	else
	{        
		$search_form->assign("ALPHABETICAL",AlphabeticalSearch('SalesOrder','index&smodule=SO','subject','true','basic',"","","","",$viewid));
		$search_form->parse("main");
	        $search_form->out("main");
	}
echo get_form_footer();

}
*/

// Buttons and View options
$other_text = '<table width="100%" border="0" cellpadding="1" cellspacing="0">
	<form name="massdelete" method="POST">
	<tr>
	<input name="idlist" type="hidden">
	<input name="viewname" type="hidden" value="'.$viewid.'">
	<td>';
if(isPermitted('SalesOrder',2,'') == 'yes')
{
	$other_text .=	'<input class="button" type="submit" value="'.$app_strings[LBL_MASS_DELETE].'" onclick="return massDelete()"/>&nbsp;';
}

if($viewid == 0)
{
$cvHTML = '<span class="bodyText disabled">'.$app_strings['LNK_CV_EDIT'].'</span>
<span class="sep">|</span>
<span class="bodyText disabled">'.$app_strings['LNK_CV_DELETE'].'</span><span class="sep">|</span>
<a href="index.php?module=SalesOrder&action=CustomView&smodule=SO" class="link">'.$app_strings['LNK_CV_CREATEVIEW'].'</a>';
}else
{
$cvHTML = '<a href="index.php?module=SalesOrder&action=CustomView&record='.$viewid.'" class="link">'.$app_strings['LNK_CV_EDIT'].'</a>
<span class="sep">|</span>
<a href="index.php?module=CustomView&action=Delete&dmodule=SalesOrder&record='.$viewid.'" class="link">'.$app_strings['LNK_CV_DELETE'].'</a>
<span class="sep">|</span>
<a href="index.php?module=SalesOrder&action=CustomView" class="link">'.$app_strings['LNK_CV_CREATEVIEW'].'</a>';
}
	$other_text .='<td align="right">'.$app_strings[LBL_VIEW].'
                        <SELECT NAME="view" onchange="showDefaultCustomView(this)">
                                <OPTION VALUE="0">'.$mod_strings[LBL_ALL].'</option>
				'.$customviewcombo_html.'
                        </SELECT>
			'.$cvHTML.'
                </td>
        </tr>
        </table>';

//<<<<<<<<<customview>>>>>>>>>
if($viewid != "0")
{
	$listquery = getListQuery("SalesOrder");
	$list_query = $oCustomView->getModifiedCvListQuery($viewid,$listquery,"SalesOrder");
}else
{
	$list_query = getListQuery("SalesOrder");
}
//<<<<<<<<customview>>>>>>>>>

if(isset($where) && $where != '')
{
        $list_query .= ' and '.$where;
}

$smarty->assign("SOLISTHEADER", get_form_header($current_module_strings['LBL_LIST_SO_FORM_TITLE'], $other_text, false ));

if(isset($order_by) && $order_by != '')
{
	if($order_by == 'smownerid')
        {
                $list_query .= ' ORDER BY user_name '.$sorder;
        }
        else
        {
		$tablename = getTableNameForField('SalesOrder',$order_by);
		$tablename = (($tablename != '')?($tablename."."):'');

                $list_query .= ' ORDER BY '.$tablename.$order_by.' '.$sorder;
        }
}

$list_result = $adb->query($list_query);


//Retreiving the no of rows
$noofrows = $adb->num_rows($list_result);

$view_script = "<script language='javascript'>
	function set_selected()
	{
		len=document.massdelete.view.length;
		for(i=0;i<len;i++)
		{
			if(document.massdelete.view[i].value == '$viewid')
				document.massdelete.view[i].selected = true;
		}
	}
	set_selected();
	</script>";

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

// Setting the record count string
//modified by rdhital
$start_rec = $navigation_array['start'];
$end_rec = $navigation_array['end_val']; 
//By Raju Ends


$record_string= $app_strings[LBL_SHOWING]." " .$start_rec." - ".$end_rec." " .$app_strings[LBL_LIST_OF] ." ".$noofrows;

//Retreive the List View Table Header
if($viewid !='')
$url_string .="&viewname=".$viewid;

$listview_header = getListViewHeader($focus,"SalesOrder",$url_string,$sorder,$order_by,"",$oCustomView);
$smarty->assign("LISTHEADER", $listview_header);

$listview_entries = getListViewEntries($focus,"SalesOrder",$list_result,$navigation_array,'','&return_module=SalesOrder&return_action=index','EditView','Delete',$oCustomView);
$smarty->assign("LISTENTITY", $listview_entries);
$smarty->assign("SELECT_SCRIPT", $view_script);

$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"SalesOrder",'index',$viewid);
$smarty->assign("NAVIGATION", $navigationOutput);
$smarty->assign("RECORD_COUNTS", $record_string);

$smarty->display("ListView.tpl");

?>

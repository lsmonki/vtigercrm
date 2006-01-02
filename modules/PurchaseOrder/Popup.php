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
require_once('modules/PurchaseOrder/PurchaseOrder.php');
require_once('include/utils/utils.php');

global $app_strings;
global $mod_strings;
global $currentModule;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');


//to display the search Headings-start
echo get_module_title("PurchaseOrder", "PurchaseOrder" , true);
echo '<BR>';
echo get_form_header("Purchase Order Search", "", false);
//end

$smarty = new vtigerCRM_Smarty;
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("THEME_PATH",$theme_path);
$smarty->assign("MODULE",$currentModule);
$smarty->assign("SINGLE_MOD",'PurchaseOrder');

if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
        $smarty->assign("RETURN_MODULE",$_REQUEST['return_module']);


if (!isset($where)) $where = "";
$popuptype = '';
$popuptype = $_REQUEST["popuptype"];
$smarty->assign("POPUPTYPE",$popuptype);
$smarty->assign("RECORDID",$_REQUEST['recordid']);

if (isset($_REQUEST['order_by'])) $order_by = $_REQUEST['order_by'];

$url_string = '';
$sorder = 'ASC';
if(isset($_REQUEST['sorder']) && $_REQUEST['sorder'] != '')
$sorder = $_REQUEST['sorder'];

if($popuptype!='') $url_string .= "&popuptype=".$popuptype;

//for  Popup search
if(isset($_REQUEST['query']) && $_REQUEST['query'] != '' && $_REQUEST['query'] == 'true')
{
         $url_string .="&query=true";
         if (isset($_REQUEST['subject'])) $subject = $_REQUEST['subject'];
         if (isset($_REQUEST['vendorname'])) $vendorname = $_REQUEST['vendorname'];
         if (isset($_REQUEST['trackingno'])) $trackingno = $_REQUEST['trackingno'];

         if ($order_by !='') $smarty->assign("ORDER_BY", $order_by);
         if ($sorder !='') $smarty->assign("SORDER", $sorder);

         $where_clauses = Array();
         if(isset($subject) && $subject != '')
         {
               array_push($where_clauses, "purchaseorder.subject like ".PearDatabase::quote($subject."%"));
               $url_string .= "&subject=".$subject;

         }
       if(isset($vendorname) && $vendorname != "")
        {
                array_push($where_clauses, "vendor.vendorname like ".PearDatabase::quote("%".$vendorname."%"))
;
                $url_string .= "&vendorname=".$vendorname;
        }
        if(isset($trackingno) && $trackingno != "")
        {
                array_push($where_clauses, "purchaseorder.tracking_no like ".PearDatabase::quote("%".$trackingno."%"));
                $url_string .= "&trackingno=".$trackingno;
        }

        $where = "";
        foreach($where_clauses as $clause)
        {
                if($where != "")
                $where .= " and ";
                $where .= $clause;
        }
        if (!empty($assigned_user_id)) {
                if (!empty($where)) {
                        $where .= " AND ";
                }
                $where .= "crmentity.smownerid IN(";
                foreach ($assigned_user_id as $key => $val) {
                        $where .= PearDatabase::quote($val);
                        $where .= ($key == count($assigned_user_id) - 1) ? ")" : ", ";
                }
        }



        $log->info("Here is the where clause for the list view: $where");


}
//end


//Constructing the Search Form
/*if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
        // Stick the form header out there.
        $search_form=new XTemplate ('modules/PurchaseOrder/PopupSearchForm.html');
        $search_form->assign("MOD", $mod_strings);
        $search_form->assign("APP", $app_strings);
        $search_form->assign("POPUPTYPE",$popuptype);
        $clearsearch = 'true';

        if ($order_by !='') $search_form->assign("ORDER_BY", $order_by);
        if ($sorder !='') $search_form->assign("SORDER", $sorder);

        $search_form->assign("VIEWID",$viewid);

        $search_form->assign("JAVASCRIPT", get_clear_form_js());

        if($order_by != '') {
                $ordby = "&order_by=".$order_by;
        }
        else
        {
               $ordby ='';
        }


        $search_form->assign("BASIC_LINK", "index.php?module=PurchaseOrder".$ordby."&action=index".$url_string."&sorder=".$sorder);
        $search_form->assign("ADVANCE_LINK", "index.php?module=PurchaseOrder&action=index".$ordby."&advanced=true".$url_string."&sorder=".$sorder);


        if ($subject !='') $search_form->assign("SUBJECT", $subject);
        if ($vendorname !='') $search_form->assign("VENTORNAME", $vendorname);
        if ($trackingno !='') $search_form->assign("TRACKINGNO", $trackingno);


//Combo Fields for Manufacturer and Category are moved from advanced to Basic Search
        if (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true')
        {
                $url_string .="&advanced=true";
                $search_form->assign("ALPHABETICAL",AlphabeticalSearch('PurchaseOrder','Popup','subject','true','adva
nced',$popuptype,"","","",$viewid));

                $search_form->assign("SUPPORT_START_DATE",$_REQUEST['start_date']);
                $search_form->assign("SUPPORT_EXPIRY_DATE",$_REQUEST['expiry_date']);
                $search_form->assign("PURCHASE_DATE",$_REQUEST['purchase_date']);
                $search_form->assign("DATE_FORMAT", $current_user->date_format);

                //Added for Custom Field Search
                $sql="select * from field where tablename='purchaseordercf' order by fieldlabel";
                $result=$adb->query($sql);
                for($i=0;$i<$adb->num_rows($result);$i++)
                {
                        $column[$i]=$adb->query_result($result,$i,'columnname');
                        $fieldlabel[$i]=$adb->query_result($result,$i,'fieldlabel');
                        if (isset($_REQUEST[$column[$i]])) $customfield[$i] = $_REQUEST[$column[$i]];
                }
                require_once('include/CustomFieldUtil.php');
                $custfld = CustomFieldSearch($customfield, "purchaseordercf", "purchaseordercf", "purchaseorde
rid", $app_strings,$theme,$column,$fieldlabel);
                $search_form->assign("CUSTOMFIELD", $custfld);
                //upto this added for Custom Field


                $search_form->parse("advanced");
                $search_form->out("advanced");
        }
        else
        {
                $search_form->assign("ALPHABETICAL",AlphabeticalSearch('PurchaseOrder','Popup','subject','true','basic',$popuptype,"","","",$viewid));
                $search_form->parse("main");
                $search_form->out("main");
        }
echo get_form_footer();
//echo '<br><br>';
}*/




$smarty->assign("POLISTHEADER", get_form_header("Purchase Order List", "", false ));

$focus = new Order();

//Retreive the list from Database
$query = getListQuery("PurchaseOrder");

if(isset($where) && $where!='')
{
	$query .= $where;
}

if(isset($order_by) && $order_by != '')
{
        $query .= ' ORDER BY '.$order_by.' '.$sorder;
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

// Setting the record count string
if ($navigation_array['start'] == 1)
{
	if($noofrows != 0)
	$start_rec = $navigation_array['start'];
	else
	$start_rec = 0;
	if($noofrows > $list_max_entries_per_page)
	{
		$end_rec = $navigation_array['start'] + $list_max_entries_per_page - 1;
	}
	else
	{
		$end_rec = $noofrows;
	}
	
}
else
{
	if($navigation_array['next'] > $list_max_entries_per_page)
	{
		$start_rec = $navigation_array['next'] - $list_max_entries_per_page;
		$end_rec = $navigation_array['next'] - 1;
	}
	else
	{
		$start_rec = $navigation_array['prev'] + $list_max_entries_per_page;
		$end_rec = $noofrows;
	}
}
$record_string= $app_strings[LBL_SHOWING]." " .$start_rec." - ".$end_rec." " .$app_strings[LBL_LIST_OF] ." ".$noofrows;

//Retreive the List View Table Header

$focus->list_mode="search";
$focus->popup_type=$popuptype;

$listview_header = getSearchListViewHeader($focus,"PurchaseOrder",$url_string,$sorder,$order_by);
$smarty->assign("LISTHEADER", $listview_header);

$listview_entries = getSearchListViewEntries($focus,"PurchaseOrder",$list_result,$navigation_array);
$smarty->assign("LISTENTITY", $listview_entries);

if($order_by !='')
$url_string .="&order_by=".$order_by;
if($sorder !='')
$url_string .="&sorder=".$sorder;

$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"PurchaseOrder","Popup");
$smarty->assign("NAVIGATION", $navigationOutput);
$smarty->assign("RECORD_COUNTS", $record_string);

$smarty->display("Popup.tpl");



?>

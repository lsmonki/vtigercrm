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
require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');

global $mod_strings;
global $app_list_strings;
global $app_strings;
global $current_user;
$focus = 0;
global $theme;
global $vtlog;

//<<<<<>>>>>>
global $oCustomView;
//<<<<<>>>>>>

$error_msg = '';
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');
require_once('modules/CustomView/CustomView.php');

$submodule = array('VENDOR'=>'Vendor','PRICEBOOK'=>'PriceBook','PRODUCTS'=>'Products','PO'=>'Orders','SO'=>'SalesOrder');

if(isset($_REQUEST['smodule']) && $_REQUEST['smodule'] != '')
{
      $cv_module = $submodule[$_REQUEST['smodule']];
}
else
{
      $cv_module = $_REQUEST['module'];
}

//$cv_module = $_REQUEST['module'];
$recordid = $_REQUEST['record'];

$xtpl=new XTemplate ('modules/CustomView/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("MODULE",$cv_module);
$xtpl->assign("CVMODULE", $cv_module);
$xtpl->assign("CUSTOMVIEWID",$recordid);
$xtpl->assign("DATAFORMAT",$current_user->date_format);
if($recordid == "")
{
        $oCustomView = new CustomView();
        $modulecollist = $oCustomView->getModuleColumnsList($cv_module);
	$vtlog->logthis('CustomView :: Successfully got ColumnsList for the module'.$cv_module,'info');
	if(isset($modulecollist))
	{
        	$choosecolhtml = getByModule_ColumnsHTML($cv_module,$modulecollist);
	}
        //step2
        $stdfilterhtml = $oCustomView->getStdFilterCriteria();
	$vtlog->logthis('CustomView :: Successfully got StandardFilter for the module'.$cv_module,'info');
        $stdfiltercolhtml = getStdFilterHTML($cv_module);
        $stdfilterjs = $oCustomView->getCriteriaJS();

        //step4
        $advfilterhtml = getAdvCriteriaHTML();
	for($i=1;$i<11;$i++)
	{
		$xtpl->assign("CHOOSECOLUMN".$i,$choosecolhtml);
	}
	$vtlog->logthis('CustomView :: Successfully got AdvancedFilter for the module'.$cv_module,'info');
	for($i=1;$i<6;$i++)
	{
		$xtpl->assign("FOPTION".$i,$advfilterhtml);
        	$xtpl->assign("BLOCK".$i,$choosecolhtml);
	}

	$xtpl->assign("STDFILTERCOLUMNS",$stdfiltercolhtml);
	$xtpl->assign("STDFILTERCRITERIA",$stdfilterhtml);
	$xtpl->assign("STDFILTER_JAVASCRIPT",$stdfilterjs);

	$xtpl->assign("MANDATORYCHECK",implode(",",$oCustomView->mandatoryvalues));
	$xtpl->assign("SHOWVALUES",implode(",",$oCustomView->showvalues));
}
else
{
	$oCustomView = new CustomView();

	$customviewdtls = $oCustomView->getCustomViewByCvid($recordid);
	$vtlog->logthis('CustomView :: Successfully got ViewDetails for the Viewid'.$recordid,'info');

	$modulecollist = $oCustomView->getModuleColumnsList($cv_module);
	$selectedcolumnslist = $oCustomView->getColumnsListByCvid($recordid);
	$vtlog->logthis('CustomView :: Successfully got ColumnsList for the Viewid'.$recordid,'info');

	$xtpl->assign("VIEWNAME",$customviewdtls["viewname"]);

	if($customviewdtls["setdefault"] == 1)
	{
		$xtpl->assign("CHECKED","checked");
	}
	if($customviewdtls["setmetrics"] == 1)
        {
                $xtpl->assign("MCHECKED","checked");
        }
	for($i=1;$i<10;$i++)
        {
           $choosecolhtml = getByModule_ColumnsHTML($cv_module,$modulecollist,$selectedcolumnslist[$i-1]);
	   $xtpl->assign("CHOOSECOLUMN".$i,$choosecolhtml);
        }

	$stdfilterlist = $oCustomView->getStdFilterByCvid($recordid);
	$vtlog->logthis('CustomView :: Successfully got Standard Filter for the Viewid'.$recordid,'info');
	$stdfilterhtml = $oCustomView->getStdFilterCriteria($stdfilterlist["stdfilter"]);
        $stdfiltercolhtml = getStdFilterHTML($cv_module,$stdfilterlist["columnname"]);
        $stdfilterjs = $oCustomView->getCriteriaJS();

	if(isset($stdfilterlist["startdate"]) && isset($stdfilterlist["enddate"]))
	{
		$xtpl->assign("STARTDATE",$stdfilterlist["startdate"]);
		$xtpl->assign("ENDDATE",$stdfilterlist["enddate"]);
	}

	$advfilterlist = $oCustomView->getAdvFilterByCvid($recordid);
	$vtlog->logthis('CustomView :: Successfully got Advanced Filter for the Viewid'.$recordid,'info');
	for($i=1;$i<6;$i++)
        {
                $advfilterhtml = getAdvCriteriaHTML($advfilterlist[$i-1]["comparator"]);
		$advcolumnhtml = getByModule_ColumnsHTML($cv_module,$modulecollist,$advfilterlist[$i-1]["columnname"]);
		$xtpl->assign("FOPTION".$i,$advfilterhtml);
                $xtpl->assign("BLOCK".$i,$advcolumnhtml);
		$xtpl->assign("VALUE".$i,$advfilterlist[$i-1]["value"]);
        }

	$xtpl->assign("STDFILTERCOLUMNS",$stdfiltercolhtml);
        $xtpl->assign("STDFILTERCRITERIA",$stdfilterhtml);
        $xtpl->assign("STDFILTER_JAVASCRIPT",$stdfilterjs);

	$xtpl->assign("MANDATORYCHECK",implode(",",$oCustomView->mandatoryvalues));
	$xtpl->assign("SHOWVALUES",implode(",",$oCustomView->showvalues));
	
	$cactionhtml = "<input name='customaction' class='button' type='button' value='Create Custom Action' onclick=goto_CustomAction('".$cv_module."');>";

	if($cv_module == "Leads" || $cv_module == "Accounts" || $cv_module == "Contacts")
	{
		$xtpl->assign("CUSTOMACTIONBUTTON",$cactionhtml);
	}
}

$xtpl->assign("RETURN_MODULE", $cvmodule);
$xtpl->assign("RETURN_ACTION", "index");

$xtpl->parse("main");
$xtpl->out("main");

//step2
function getByModule_ColumnsHTML($module,$columnslist,$selected="")
{
	global $oCustomView;
	global $app_list_strings;
	
	$mod_strings = return_module_language($current_language,$module);

	foreach($oCustomView->module_list[$module] as $key=>$value)
        {
            $shtml .= "<optgroup label=\"".$app_list_strings['moduleList'][$module]." ".$key."\" class=\"select\" style=\"border:none\">";
	    if(isset($columnslist[$module][$key]))
	    {
            foreach($columnslist[$module][$key] as $field=>$fieldlabel)
            {
		    if(isset($mod_strings[$fieldlabel]))
		    {
			    if($selected == $field)
			    {
				    $shtml .= "<option selected value=\"".$field."\">".$mod_strings[$fieldlabel]."</option>";
			    }else
			    {
				    $shtml .= "<option value=\"".$field."\">".$mod_strings[$fieldlabel]."</option>";
			    }
		    }else
		    {
			    if($selected == $field)
			    {
				    $shtml .= "<option selected value=\"".$field."\">".$fieldlabel."</option>";
			    }else
			    {
				    $shtml .= "<option value=\"".$field."\">".$fieldlabel."</option>";
			    }
		    }
            }
	    }
        }
        return $shtml;
}
//step2

//step3
function getStdFilterHTML($module,$selected="")
{
        global $app_list_strings;
        global $oCustomView;
	//print_r($mod_strings);
        $result = $oCustomView->getStdCriteriaByModule($module);
	$mod_strings = return_module_language($current_language,$module);

        if(isset($result))
        {
                foreach($result as $key=>$value)
                {
                        if(isset($mod_strings[$value]))
			{
			if($key == $selected)
                        {
                        $shtml .= "<option selected value=\"".$key."\">".$app_list_strings['moduleList'][$module]." - ".$mod_strings[$value]."</option>";
                        }else
                        {
                        $shtml .= "<option value=\"".$key."\">".$app_list_strings['moduleList'][$module]." - ".$mod_strings[$value]."</option>";
                        }
			}else
			{
			if($key == $selected)
                        {
                   	$shtml .= "<option selected value=\"".$key."\">".$app_list_strings['moduleList'][$module]." - ".$value."</option>";
                        }else
                        {
                        $shtml .= "<option value=\"".$key."\">".$app_list_strings['moduleList'][$module]." - ".$value."</opt
ion>";
                        }
			}
                }
        }

        return $shtml;
}
//step3

//step4
function getAdvCriteriaHTML($selected="")
{
         global $adv_filter_options;
	 global $app_list_strings;

         foreach($adv_filter_options as $key=>$value)
         {
                if($selected == $key)
                {
                        $shtml .= "<option selected value=\"".$key."\">".$value."</option>";
                }else
                {
                        $shtml .= "<option value=\"".$key."\">".$value."</option>";
                }
         }

         return $shtml;
}
//step4

?>

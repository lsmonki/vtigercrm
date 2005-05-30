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
require_once("data/Tracker.php");
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/utils.php');
require_once('modules/Reports/Reports.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
$current_module_strings = return_module_language($current_language, 'Reports');
global $list_max_entries_per_page;
global $urlPrefix;
$log = LoggerManager::getLogger('report_list');
global $currentModule;
global $image_path;
global $theme;
global $ogReport;
// focus_list is the means of passing data to a ListView.
global $focus_list;

echo get_module_title($mod_strings['LBL_MODULE_NAME'], "Create Report", true);
echo "\n<BR>\n";

if(isset($_REQUEST["record"]))
{
	$reportid = $_REQUEST["record"];
	$oReport = new Reports($reportid);
	$primarymodule = $oReport->primodule;
	$secondarymodule = $oReport->secmodule;
	$reporttype = $oReport->reporttype;
	$reportname  = $oReport->reportname;
	$reportdescription  = $oReport->reportdescription;
	$folderid  = $oReport->folderid;	
	$ogReport = new Reports();
        $ogReport->getPriModuleColumnsList($oReport->primodule);
        $ogReport->getSecModuleColumnsList($oReport->secmodule);
}else
{
	$primarymodule=$_REQUEST["primarymodule"];
	$secondarymodule=$_REQUEST["secondarymodule"];
	$ogReport = new Reports();
	$ogReport->getPriModuleColumnsList($primarymodule);
	$ogReport->getSecModuleColumnsList($secondarymodule);
}
?>
<script language="JavaScript" type="text/JavaScript" src="include/general.js"></script>
<script language="JavaScript" type="text/JavaScript">    
        function switchToStep( num )
        {
            if( num == 1 )
            {
                document.StepOne.submit();
            }
        }
        
        function hideTabs( hide )
        {
            if( hide == true )
            {
                getObj( "tab3" ).style.display = "none";
//                getObj( "tab6" ).style.display = "none";
            }
            else
            {
                getObj( "tab3" ).style.display = "block";
//                getObj( "tab6" ).style.display = "block";
            }
        }
        
        function showSaveDialog()
        {    
            url = "index.php?module=Reports&action=SaveReport";
            window.open(url,"Save_Report","width=550,height=350,top=20,left=20;toolbar=no,status=no,menubar=no,directories=no,resizable=yes,scrollbar=no")
        }
    
        function saveAndRunReport()
        {
            formSelectColumnString();
            if( trim(getObj( 'record' ).value) == "" )
            {
                showSaveDialog();
		document.NewReport.action = "index.php?action=Save&module=Reports";
            }
            else
            {
                    document.NewReport.action = "index.php?action=Save&module=Reports";
		    document.NewReport.submit();
            }
        }       
        
        function saveAsNewAndRunReport()
        {
            formSelectColumnString();
            showSaveDialog();
        }       
        
        function cancelWizard()
        {
            document.NewReport.actionItem.value = "cancel";
            document.NewReport.submit();
        }       
	function trim(s) 
	{
		while (s.substring(0,1) == " ") {
			s = s.substring(1, s.length);
		}
		while (s.substring(s.length-1, s.length) == ' ') {
			s = s.substring(0,s.length-1);
		}
	
		return s;
	}
        function updateYAxisFields( colToTotalField, value, bool )
        {
        }
        
        function updateXAxisFields( value, label )
        {
        }
        
        function displayStep( num )
        {
            if( num == 3 || num == 6 )
            {
			    var typeObj = getObj( 'reportType' );
                if( typeObj[0].checked == true )
                {
                    return;
                }
            }
            
            for( i=1; i <= 5; i++ )
            {
                var stepId = "step" + i;
                var tabId = "tab" + i;
                if( i != num )
                {                    
                    //getObj( tabId ).className= "tabOff"
                    getObj( stepId ).style.display = "none";
                }
                else
                {
                    //getObj( tabId ).className = "tabOn";
                    getObj( stepId ).style.display = "block";
                }
            }            
        }
    </script>
<style>
        .step1, .step2 {
                padding: 5;
                font-family: Verdana, Arial, Helvetica, Sans-serif;
                font-size: 11px;
                font-weight: bold;
                color: #999;
                background-color: #E9E9E9;
        }
        .step2 {
                color: #000;
                background-color: #FC6;
        }
    </style>

<div style="margin:10 0 10 0">Provide the following report information</div>
<form method='post' name="NewReport" action='index.php'>
  <input type="hidden" name='modulesString' value=''/>
  <input type="hidden" name='primarymodule' value="<?php echo $primarymodule?>"/>
  <input type="hidden" name='secondarymodule' value="<?php echo $secondarymodule?>"/>
  <input type="hidden" name='record' value="<?php echo $reportid?>"/>
  <input type="hidden" name='module' value='Reports'/>
  <input type="hidden" name='reload' value='true'/>
  <input type="hidden" name='action' value='Save'/>
  <input type="hidden" name='reportName' value="<?php echo $reportname?>"/>
  <input type="hidden" name='reportDesc' value="<?php echo $reportdescription?>"/>
  <input type="hidden" name='folder' value="<?php echo $folderid?>"/>
  <table width="80%" border="0" cellspacing="0" cellpadding="2" class="formOuterBorder" id='tab1'>
    <tr>
      <td class="formSecHeader"><div style="float:left"><a href="javascript:displayStep(1)" class="tabLink">Report
        Type</a></div></td>
    </tr>
  </table>
  <table width="80%" border="0" cellspacing="0" cellpadding="2" class="formOuterBorder" id='step1' style="display:block">
    <tr>
      <td>
        <?php include("modules/Reports/ReportType.php");?>
      </td>
  </table>
  <table width="80%" border="0" cellspacing="0" cellpadding="2" class="formOuterBorder" id='tab2' style="margin-top:1px;">
    <tr>
      <td class="formSecHeader"><a href="javascript:displayStep(2)" class="tabLink">Select
        Columns</a></td>
    </tr>
  </table>
  <table width="80%" border="0" cellspacing="0" cellpadding="2" class="formOuterBorder" id='step2' style="display:none">
    <tr>
      <td>
        <?php include("modules/Reports/ReportColumns.php");?>
      </td>
  </table>
  <table width="80%" border="0" cellspacing="0" cellpadding="2" class="formOuterBorder" id='tab3' style="margin-top:1px;">
    <tr>
      <td class="formSecHeader"><a href="javascript:displayStep(3)" class="tabLink">Specify
        Grouping</a></td>
    </tr>
  </table>
  <table width="80%" border="0" cellspacing="0" cellpadding="2" class="formOuterBorder" id='step3' style="display:none">
    <tr>
      <td>
        <?php include("modules/Reports/ReportGrouping.php");?>
      </td>
  </table>
  <table width="80%" border="0" cellspacing="0" cellpadding="2" class="formOuterBorder" id='tab4' style="margin-top:1px;">
    <tr>
      <td class="formSecHeader"><a href="javascript:displayStep(4)" class="tabLink">Choose
        Columns to Total</a></td>
    </tr>
  </table>
  <table width="80%" border="0" cellspacing="0" cellpadding="2" class="formOuterBorder" id='step4' style="display:none">
    <tr>
      <td>
        <?php include("modules/Reports/ReportColumnsTotal.php");?>
      </td>
  </table>
  <table width="80%" border="0" cellspacing="0" cellpadding="2" class="formOuterBorder" id='tab5' style="margin-top:1px;">
    <tr>
      <td class="formSecHeader"><a href="javascript:displayStep(5)" class="tabLink">Specify
        Criteria</a></td>
    </tr>
  </table>
  <table width="80%" border="0" cellspacing="0" cellpadding="2" class="formOuterBorder" id='step5' style="display:none">
    <tr>
      <td>
        <?php include("modules/Reports/ReportFilters.php");?>
      </td>
  </table>
  <table width="80%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td> <div align="center"><br>
          <input name="btnSaveAndRun" class="button" type="button" value="Save and Run" onclick='saveAndRunReport()'/>
          <input name="cancel" class="button" type="button" value="Cancel" onClick="cancelWizard()">
        </div></td>
    </tr>
  </table>
</form>
<script>
    displayStep(1);
    setObjects();
    var obj = getObj( "reportType" );
    hideTabs( obj[0].checked );
</script>

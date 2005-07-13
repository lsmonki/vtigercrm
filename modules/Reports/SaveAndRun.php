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
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once ($theme_path."layout_utils.php");
require_once("config.php");
require_once('modules/Reports/Reports.php');
require_once('include/logging.php');
require_once('include/database/PearDatabase.php');
require_once("modules/Reports/ReportRun.php");
require_once('include/utils.php');
include('themes/'.$theme.'/header.php');

global $adb;
global $mod_strings;

$reportid = $_REQUEST["record"];

$stdDateFilterField = $_REQUEST["stdDateFilterField"];
$stdDateFilter = $_REQUEST["stdDateFilter"];
$startdate = $_REQUEST["startdate"];
$enddate = $_REQUEST["enddate"];

global $primarymodule;
global $secondarymodule;
global $orderbylistsql;
global $orderbylistcolumns;
global $ogReport;

$ogReport = new Reports($reportid);
$primarymodule = $ogReport->primodule;
$secondarymodule = $ogReport->secmodule;
$oReportRun = new ReportRun($reportid);
$sshtml = $oReportRun->GenerateReport("HTML");

?>
<html>
<head>
<script language="JavaScript" type="text/javascript" src="include/general.js"></script>
<script type="text/javascript" language="JavaScript">
    function openPage( url )
    {
        document.location.href = url;
    }

    function goToURL( url )
    {
        document.location.href = url;
    }
    
    function exportReport( fileType )
    {
        document.NewReport.fileType.value = fileType;
        document.NewReport.action = "/crm/ExportReport.do";
        document.NewReport.submit();
    }
    
    function showOrHideDetails( showOrHide )
    {
        document.NewReport.showDetails.value = showOrHide;
        document.NewReport.action = "/crm/CustomReport.do"
        document.NewReport.actionItem.value = "run";
        document.NewReport.submit();
    }
    
    function save( url, title, state )
    {    
        if( state == "UNSAVED" )
        {
            window.open(url,title,"width=950,height=650,top=20,left=20;toolbar=no,status=no,menubar=no,directories=no,resizable=yes,scrollbar=no")
        }
    }
    
    function saveAs( buttonIns, url, title )
    {   
        document.NewReport.action = "/crm/CustomReport.do";    
        openPopUp( "WinSaveAs", buttonIns, url, title, 300, 120, "toolbar=no,status=no,menubar=no,directories=no,resizable=yes,scrollbar=no" );
    }
    
    function showExportMenu()
    {
            getObj("dropDownMenu").style.display="block"
            getObj("dropDownMenu").style.left=findPosX(getObj("btnExport"))
            getObj("dropDownMenu").style.top=findPosY(getObj("btnExport"))+getObj("btnExport").offsetHeight
    }

    function hideExportMenu(ev)
    {
            if (browser_ie)
                    currElement=window.event.srcElement
            else if (browser_nn4 || browser_nn6)
                    currElement=ev.target

            if (currElement.id!="btnExport")
                    if (getObj("dropDownMenu").style.display=="block")
                            getObj("dropDownMenu").style.display="none"
    }
</script>
</head>
<body>
<?php
echo get_module_title($mod_strings['LBL_MODULE_NAME'], $ogReport->reportname, false);  
?>
<br>
<form name="NewReport" action="index.php" method="POST">
    <table width="90%" border="0" cellspacing="0" cellpadding="0" class="formOuterBorder">
        <tr>
          <td class="formSecHeader">Filter Options:</td>
        </tr>
        <tr>
          <td>    
		<?php include("modules/Reports/StandardFilter.php");?>
          </td>
        </tr>
    </table>
    <input type="hidden" name="booleanoperator" value="5"/>
    <input type="hidden" name="record" value="<?php echo $reportid?>"/>
    <input type="hidden" name="reload" value=""/>    
    <input type="hidden" name="module" value="Reports"/>
    <input type="hidden" name="action" value="SaveAndRun"/>
<br>
    <table align='center' border="0" cellspacing="2" cellpadding="2">
        <tr><td>
	    <input id="btnExport" name="btnExport" value="Export To PDF" class="button" type="button" onClick="goToURL( 'index.php?module=Reports&action=CreatePDF&record=<?php echo $reportid; ?>')" title="Export To PDF">

            <input value="Customize" class="button" type="button" onClick="goToURL( 'index.php?module=Reports&action=NewReport1&record=<?php echo $reportid; ?>' )" title="Customize">

	    <input value="Apply filter" class="button" type="submit" title="Apply filter"/>

        </td></tr>        
    </table>    
<br>   
<table> 
<tr>
    <td class='bodyText'>
        <?php echo $sshtml; ?>
    </td>
</tr>
</table>    
    <input type="hidden" name="dlgType" value="saveAs"/>
    <input type="hidden" name="reportName"/>
    <input type="hidden" name="reportDesc"/>
    <input type="hidden" name="folder"/>
</form>
</body>
</html>
<br>

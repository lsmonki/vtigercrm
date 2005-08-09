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

$filtercolumn = $_REQUEST["stdDateFilterField"];
$filter = $_REQUEST["stdDateFilter"];
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
$filterlist = $oReportRun->RunTimeFilter($filtercolumn,$filter,$startdate,$enddate);
//print_r($filterlist);
$sshtml = $oReportRun->GenerateReport("HTML",$filterlist);

?>
<html>
<head>
<script language="JavaScript" type="text/javascript" src="include/general.js"></script>
<script type="text/javascript" language="JavaScript">
    function goToURL( url )
    {
        document.location.href = url;
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
          <td class="formSecHeader"><?php echo $mod_strings['LBL_FILTER_OPTIONS'];?>:</td>
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
	    <input id="btnExport" name="btnExport" value="<?php echo $mod_strings['LBL_EXPORTPDF_BUTTON']?>" class="button" type="button" onClick="goToURL( 'index.php?module=Reports&action=CreatePDF&record=<?php echo $reportid; ?>')" title="<?php echo $mod_strings['LBL_EXPORTPDF_BUTTON']?>">

	    <input id="btnExport" name="btnExport" value="<?php echo $mod_strings['LBL_EXPORTXL_BUTTON']?>" class="button" type="button" onClick="goToURL( 'index.php?module=Reports&action=CreateXL&record=<?php echo $reportid; ?>')" title="<?php echo $mod_strings['LBL_EXPORTXL_BUTTON']?>">

            <input value="<?php echo $mod_strings['LBL_CUSTOMIZE_BUTTON'];?>" class="button" type="button" onClick="goToURL( 'index.php?module=Reports&action=NewReport1&record=<?php echo $reportid; ?>' )" title="<?php echo $mod_strings['LBL_CUSTOMIZE_BUTTON'];?>">

	    <input value="<?php echo $mod_strings['LBL_APPLYFILTER_BUTTON'];?>" class="button" type="submit" title="<?php echo $mod_strings['LBL_APPLYFILTER_BUTTON'];?>"/>

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

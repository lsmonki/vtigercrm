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

global $primarymodule;
global $secondarymodule;
global $orderbylistsql;
global $orderbylistcolumns;
global $ogReport;

$ogReport = new Reports($reportid);
$primarymodule = $ogReport->primodule;
$secondarymodule = $ogReport->secmodule;

$oReportRun = new ReportRun($reportid);

/*$columnlist = $oReportRun->getSelectColumnsList($reportid);
$groupslist = $oReportRun->getGroupingList($reportid);
if($oReporRun->reporttype == "summary")
{
	if(isset($oReportRun->groupbylist))
	{
		$newcolumnlist = array_diff($columnlist, $oReportRun->groupbylist);
		$selectlist = array_merge($oReportRun->groupbylist,$newcolumnlist);
	}else
	{
		$selectlist = $columnlist;
	}
}else
{
	$selectlist = $columnlist;
}


if(isset($selectlist))
{
	$selectedcolumns =  implode(", ",$selectlist);
}

if(isset($groupslist))
{
	$groupsquery = implode(", ",$groupslist);
}

$stdfilterlist = $oReportRun->getStdFilterList($reportid);
if(isset($stdfilterlist))
{
	$stdfiltersql = implode(", ",$stdfilterlist);
}

$columnstotal = $oReportRun->getColumnsTotal($reportid);
if(isset($columnstotal))
{
//	print_r($columnstotal);
}

$advfilterlist = $oReportRun->getAdvFilterList($reportid);
if(isset($advfilterlist))
{
	$advfiltersql = implode(" and ",$advfilterlist);
}

if($stdfiltersql != "")
{
	if($advfiltersql != "")
	{
		$wheresql = " and ".$stdfiltersql." and ".$advfiltersql;
	}else
	{
		$wheresql = " and ".$stdfiltersql;
	}
}else
{
	if($advfiltersql != "")
        {
                $wheresql = " and ".$advfiltersql;
	}
}

$modulequery = getListQuery($oReportRun->primarymodule);
//$modulequery = str_replace("crmentity.crmid","crmentity".$oReportRun->primarymodule.".crmid",$modulequery);

//$modulequery = str_replace("crmentity","crmentity crmentity".$oReportRun->primarymodule,$modulequery);
//echo $modulequery;
$reportquery = substr($modulequery, strpos($modulequery,'from'),strlen($modulequery));
//$modulequery = $oReportRun->getSQLforPrimaryModule($oReportRun->primarymodule);
$reportquery = "select ".$selectedcolumns." ".$reportquery." ".$wheresql;
//echo $reportquery;

/*function getModifiedCvListQuery($viewid,$listquery,$module)
{
	if($viewid != "" && $listquery != "")
	{
		$listviewquery = substr($listquery, strpos($listquery,'from'),strlen($listquery));
		$query = "select ".$this->getCvColumnListSQL($viewid)." ,crmentity.crmid ".$listviewquery;
		$stdfiltersql = $this->getCVStdFilterSQL($viewid);
		if(isset($stdfiltersql) && $stdfiltersql != '')
		{
			$query .= ' and '.$stdfiltersql;
		}

	}

	return $query;
}*/

//echo $reportquery;
//die("");*/
$sshtml = $oReportRun->GenerateReport("HTML");
//$sshtml = $sshtml."<br>".$oReportRun->GenerateReport("TOTALHTML");

//echo $sshtml;
//print_r($arr_val);
/*$filename = '/home/sumanraj/AdventNet/vtiger4.0/vtigerCRM4/apache/htdocs/vtigerCRM/modules/Reports/ReportHTML.htm';

if (!$handle = fopen($filename, "w")) {
         echo "Cannot open file ($filename)";
         exit;
    }

    // Write $somecontent to our opened file.
    if (fwrite($handle, $sshtml) === FALSE) {
        echo "Cannot write to file ($filename)";
        exit;
    }

    //echo "Success, wrote ($somecontent) to file ($filename)";

fclose($handle);
require_once("include/Report.php");
$report =& new Report($arr_val, ".","modules/Reports/ReportHTML.htm" );

 // Set column types
 //$types = array ( 'inserted' => 'date', 'value' => 'money');
 //$report->setVariableType($types);

 // Uncomment bellow to change the Currency simbol.

 // $report->setCurrencySymbol('$');

 // Change bellow if you dont want to use european style for dates

 //$report->europeanstyle = false;

 // Prepare the data

 $report->makeReport();

 // Show it

 $report->show();*/

/*require_once("include/pdfclassesandfonts/class.ezpdf.php");

 error_reporting(E_ALL);
$pdf = new Cezpdf('a3','portrait');
//$pdf = new Cezpdf();
$pdf -> ezSetMargins(10,10,10,10);
$pdf->selectFont('include/pdfclassesandfonts/fonts/Helvetica.afm');
 //$pdf->addText(30,400,30,"Hello World!!");
 //$data = array((array('num'=>1)));

 $pdf->ezTable($arr_val,' ','vtiger CRM Reports');
 //$pdf->stream();
if (isset($d) && $d){
  $pdfcode = $pdf->ezOutput(1);
  $pdfcode = str_replace("\n","\n<br>",htmlspecialchars($pdfcode));
  echo '<html><body>';
  echo trim($pdfcode);
  echo '</body></html>';
} else {
  $pdf->ezStream();
}*/
?>
<html>
<head>

<title></title>
<!--meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"-->

<link href="/crm/css/elegant.css" rel="stylesheet" type="text/css">
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
<body onclick="hideExportMenu(event)" onLoad="showDrillDownPtr()">
<table width='100%' cellspacing='0' cellpadding='0' border='0'>
    <tr><td class="title hline"><?php echo $oReport->reportname?></td></tr>
</table>
<br>

   
<form name="NewReport" action="/crm/CustomReport.do">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="secHead">Filter Options:</td>
        </tr>
        <tr>
          <td class="secContent">    
            <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
		<?php include("modules/Reports/StandardFilter.php");?>
          </td>
        </tr>
    </table>
    <input type="hidden" name="booleanoperator" value="or"/>
    <input type="hidden" name="reportId" value="30000000005463"/>
    <input type="hidden" name="reload" value="true"/>    
    <input type="hidden" name="fileType" />
    <input type="hidden" name="showDetails" value="true"/>

    <input type="hidden" name="actionItem" value="updateStdFilterAndRun"/>
<br>
    <table align='center' border="0" cellspacing="2" cellpadding="2">
        <tr><td>
            
	    <!--<input id="btnExport" name="btnExport" value="Export" class="dropDownButton" type="button" onClick="showExportMenu(event)" title="Export">-->
	    
	    <input id="btnExport" name="btnExport" value="Export To PDF" class="button" type="button" onClick="goToURL( 'index.php?module=Reports&action=CreatePDF&record=<?php echo $reportid; ?>')" title="Export To PDF">

            <input value="Save As" class="button" type="button" onClick="saveAs( this, '/crm/SaveReport.do?dlgType=saveAs', 'SaveReport' )" title="Save As">
            
            <input value="Customize" class="button" type="button" onClick="goToURL( 'index.php?module=Reports&action=NewReport1&record=<?php echo $reportid; ?>' )" title="Customize">

	    <input value="Reload" class="button" type="button" onClick="goToURL( 'index.php?module=Reports&action=SaveAndRun&record=<?php echo $reportid; ?>')" title="Reload">

	    <input value="Hide Details" class="button" type="button" onClick="showOrHideDetails( 'false' )" />

<!--             <input value="Create Chart" class="button" type="button" onClick="goToURL( '/crm/CustomReport.do?actionItem=addChart&reportId=30000000005463' )" />-->
            
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

<div id="dropDownMenu" class="dropDownMenu" style="display:none;">
<table border="0" cellspacing="0" cellpadding="4">
  <tr onClick="exportReport( 'xls' )" class="dropDownItem" onMouseOver="this.className='dropDownItemOver'" onMouseOut="this.className='dropDownItem'">
  	<td><img src="/crm/images/excel.gif" align="absmiddle" border="0"></td>
    <td>Export to Excel</td>
  </tr>
  <tr onClick="exportReport( 'csv' )" class="dropDownItem" onMouseOver="this.className='dropDownItemOver'" onMouseOut="this.className='dropDownItem'">
   	<td><img src="/crm/images/csv.gif" align="absmiddle" border="0"></td>
    <td>Export to CSV</td>

  </tr>
  <tr onClick="exportReport( 'pdf' )" class="dropDownItem" onMouseOver="this.className='dropDownItemOver'" onMouseOut="this.className='dropDownItem'">
  	<td><img src="/crm/images/pdf.gif" align="absmiddle" border="0"></td>
    <td>Export to PDF</td>
  </tr>
  
</table>
</div>
<div id="drillDownPtr" style="position:absolute;top:-1000px;left:-1000px;"><img src="/crm/images/arrow.gif"></div>
</body>
</html>
<script language="JavaScript" type="text/javascript">
function showDrillDownPtr() {
	var anchorFound;
	var url=document.location.href
	var objAnchor=document.getElementsByTagName("A")
	var target=unescape(url.substr(url.lastIndexOf("#")+1,url.length))
	
	for (i=0;i<objAnchor.length;i++) {
		if (objAnchor[i].name==target) {
			anchorFound=true
			anchorObj=objAnchor[i]
			anchorLeft=findPosX(objAnchor[i])
			anchorTop=findPosY(objAnchor[i])
			break;
		}
	}
	
	if (anchorFound==true) {
		getObj("drillDownPtr").style.top=anchorTop-1
		pos=(parseInt(anchorLeft)>50) ? anchorLeft-50 : pos=0
		intvl=setInterval("animatePtr()",15)
	}
}
var pos=0,anchorLeft=0,anchorTop=0,anchorObj;
function animatePtr() {
	if (pos<=anchorLeft-22) {
		getObj("drillDownPtr").style.left=pos
		pos++
	} else {
		clearInterval(intvl)
	}
}
</script>
<script language="javascript">
        addCalendar("Calendar01", "Start Date", "startdate", "NewReport");
        addCalendar("Calendar02", "End Date", "enddate", "NewReport");
</script>

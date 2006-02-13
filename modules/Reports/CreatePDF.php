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
require_once("modules/Reports/ReportRun.php");
require_once("modules/Reports/Reports.php");
require('include/fpdf/fpdf.php');

class PDF extends FPDF
{
//Load data
var $columnlength;

function getHeaderData($raw_data)
{
    if(isset($raw_data))
    {
	foreach($raw_data[0] as $key=>$value)
	{
		$headerdata[] = $key;
	}
    }

    return $headerdata;
}

//Simple table
function BasicTable($header,$data)
{
    //Header
    foreach($header as $col)
        $this->Cell(40,7,$col,1);
    $this->Ln();
    //Data
    foreach($data as $row)
    {
        foreach($row as $col)
            $this->Cell(40,6,$col,1);
        $this->Ln();
    }
}

//Better table
function ImprovedTable($header,$data)
{
    //Column widths
    $w=array(40,35,40,45);
    //Header
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C');
    $this->Ln();
    //Data
    foreach($data as $row)
    {
        $this->Cell($w[0],6,$row[0],'LR');
        $this->Cell($w[1],6,$row[1],'LR');
        $this->Cell($w[2],6,number_format($row[2]),'LR',0,'R');
        $this->Cell($w[3],6,number_format($row[3]),'LR',0,'R');
        $this->Ln();
    }
    //Closure line
    $this->Cell(array_sum($w),0,'','T');
}

//Colored table
function FancyTable($header,$data,$title)
{
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('','B',18);
    $this->Cell(($this->columnlength*50),10,$title,0,0,'C',0);
    $this->Ln();
    //Colors, line width and bold font
    $this->SetFont('Arial','',10);
    $this->SetFillColor(162,200,243);
    $this->SetTextColor(0);
    $this->SetDrawColor(61,121,206);
    $this->SetLineWidth(.3);
    $this->SetFont('Arial','B',10);
    //Header
    for($i=0;$i<count($header);$i++)
        $this->Cell(50,7,$header[$i],1,0,'C',1);
    $this->Ln();
    $this->SetFillColor(233,241,253);
    $this->SetTextColor(0);
    $this->SetFont('');
    //Data
    $fill=0;
    foreach($data as $key=>$array_value)
    {
    	foreach($array_value as $header=>$value)
	{
		$this->Cell(50,6,$value,'LR',0,'L',$fill);
	}
	$this->Ln();
       	$fill=!$fill;
    }
    //$this->Cell(array_sum($w),0,'','T');
}
}

$reportid = $_REQUEST["record"];
$oReport = new Reports($reportid);

$oReportRun = new ReportRun($reportid);
$arr_val = $oReportRun->GenerateReport("PDF",$filterlist);

if(isset($arr_val))
{
	$columnlength = count($arr_val[0]);
}
if($columnlength > 0 && $columnlength <= 4)
{
	$pdf = new PDF('P','mm','A4');
}elseif($columnlength >= 5 && $columnlength <= 8)
{
	$pdf = new PDF('P','mm','A3');
}elseif($columnlength >= 8 && $columnlength <= 12)
{
	$pdf = new PDF('L','mm','A3');
}elseif($columnlength > 12)
{
	$pdf = new PDF('L','mm','A3');
}

$pdf->columnlength = $columnlength;
$header=$pdf->getHeaderData($arr_val);
$pdf->SetFont('Arial','',10);
$pdf->AddPage();
$pdf->FancyTable($header,$arr_val,$oReport->reportname);
$pdf->Output();


/*$reportid = $_REQUEST["record"];
$oReport = new Reports($reportid);

$oReportRun = new ReportRun($reportid);
$arr_val = $oReportRun->GenerateReport("PDF");

if(isset($arr_val))
{
	$columnlength = count($arr_val[0]);
}
if($columnlength > 0 && $columnlength <= 4)
{
	$pdf = new Cezpdf('A4','portrait');
}elseif($columnlength >= 5 && $columnlength <= 8)
{
	$pdf = new Cezpdf('A3','portrait');
}elseif($columnlength >= 8 && $columnlength <= 12)
{
	$pdf = new Cezpdf('A2','portrait');
}elseif($columnlength > 12)
{
	$pdf = new Cezpdf('A1','portrait');
}
$pdf -> ezSetMargins(10,10,10,10);
$pdf->selectFont('include/pdfclassesandfonts/fonts/Helvetica.afm');
$pdf->ezTable($arr_val,' ',$oReport->reportname);

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

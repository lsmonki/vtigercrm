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
require('include/fpdf/fpdf.php');
require_once('modules/Invoice/Invoice.php');

$id = $_REQUEST['record'];
global $adb;
//retreiving the invoice info
$focus = new Invoice();
$focus->retrieve_entity_info($_REQUEST['record'],"Invoice");
$account_name = getAccountName($focus->column_fields[account_id]);
$iData[] = $account_name;
$iData[] = $id;
$iData[] = date('Y-m-d');
//newly added for Sales Order No.
if($focus->column_fields["salesorder_id"] != '')
{
	$so_name = getSoName($focus->column_fields["salesorder_id"]);
}
else
{
	$so_name = ' ';
}	

$iData[] = $so_name;

//setting the Customer Data
$iCustData[] = $account_name;

if($focus->column_fields["purchaseorder"] != '')
{
	$po_name = $focus->column_fields["purchaseorder"];
}
else
{
	$po_name = ' ';
}
$iCustData[] = $po_name;

if($focus->column_fields["duedate"] != '')
{
	$due_date = $focus->column_fields["duedate"];
}
else
{
	$due_date = ' ';
}
$iCustData[] = $due_date;

//setting the billing address
$bdata[] = $account_name;
if($focus->column_fields["bill_street"] != '')
{
        $bill_street = $focus->column_fields["bill_street"];
	$bdata[] = $bill_street;
	
}

if($focus->column_fields["bill_city"] != '')
{
        $bill_city = $focus->column_fields["bill_city"];
	$bdata[] = $bill_city;
}


if($focus->column_fields["bill_state"] != '')
{
        $bill_state = $focus->column_fields["bill_state"];
	$bdata[] = $bill_state;
}


if($focus->column_fields["bill_code"] != '')
{
        $bill_code = $focus->column_fields["bill_code"];
	$bdata[] = $bill_code;
}


if($focus->column_fields["bill_country"] != '')
{
        $bill_country = $focus->column_fields["bill_country"];
	$bdata[] = $bill_country;
}

for($i =0; $i <5; $i++)
{
	if(sizeof($bdata) < 6)
	{
		$bdata[] = ' '; 
	}
}

//setting the shipping address
$sdata[] = $account_name;
if($focus->column_fields["ship_street"] != '')
{
        $ship_street = $focus->column_fields["ship_street"];
	$sdata[] = $ship_street;
}

if($focus->column_fields["ship_city"] != '')
{
        $ship_city = $focus->column_fields["ship_city"];
	$sdata[] = $ship_city;
}


if($focus->column_fields["ship_state"] != '')
{
        $ship_state = $focus->column_fields["ship_state"];
	$sdata[] = $ship_state;
}


if($focus->column_fields["ship_code"] != '')
{
        $ship_code = $focus->column_fields["ship_code"];
	$sdata[] = $ship_code;
}


if($focus->column_fields["ship_country"] != '')
{
        $ship_country = $focus->column_fields["ship_country"];
	$sdata[] = $ship_country;
}

for($i =0; $i <5; $i++)
{
	if(sizeof($sdata) < 6)
	{
		$sdata[] = ' '; 
	}
}

//Getting the terms_conditions

if($focus->column_fields["terms_conditions"] != '')
{
        $conditions = $focus->column_fields["terms_conditions"];
}
else
{
        $conditions = ' ';
}

//Getting the Company Address
$add_query = "select * from organizationdetails";
$result = $adb->query($add_query);
$num_rows = $adb->num_rows($result);
$org_field_array = Array('organizationame','address','city','state','country','code','phone','fax','website');

$companyaddress = Array();
$logo_name = '';
	
if($num_rows == 1)
{
	$org_name = $adb->query_result($result,0,"organizationame");
	$org_address = $adb->query_result($result,0,"address");
	$org_city = $adb->query_result($result,0,"city");
	$org_state = $adb->query_result($result,0,"state");
	$org_country = $adb->query_result($result,0,"country");
	$org_code = $adb->query_result($result,0,"code");
	$org_phone = $adb->query_result($result,0,"phone");
	$org_fax = $adb->query_result($result,0,"fax");
	$org_website = $adb->query_result($result,0,"website");

	if($org_name != '')
	{
		$companyaddress[] =  $org_name;
	}
	if($org_address != '' || $org_city != '' || $org_state != '')
	{
		$companyaddress[] = $org_address.' '.$org_city.' '.$org_state;
	}
	if($org_country != '' || $org_code!= '')
	{
		$companyaddress[] = $org_country.' '.$org_code;
	}
	if($org_phone != '' || $org_fax != '')
	{
		$companyaddress[] = $org_phone.' '.$org_fax;
	}
	if($org_website != '')
	{
		$companyaddress[] = $org_website;
	}
	
	for($i =0; $i < 4; $i++)
	{
		if(sizeof($companyaddress) < 5)
		{
			$companyaddress[] = ' '; 
		}
	}	

	$logo_name = $adb->query_result($result,0,"logoname");
}
//Getting the logo


//getting the Product Data
$query="select products.productname,products.unit_price,invoiceproductrel.* from invoiceproductrel inner join products on products.productid=invoiceproductrel.productid where invoiceid=".$id;

$result = $adb->query($query);
$num_rows=$adb->num_rows($result);
for($i=1;$i<=$num_rows;$i++)
{
	$temp_data = Array();
        $productname=$adb->query_result($result,$i-1,'productname');
        $unitprice=$adb->query_result($result,$i-1,'unit_price');
        $productid=$adb->query_result($result,$i-1,'productid');
        $qty=$adb->query_result($result,$i-1,'quantity');
        $listprice=$adb->query_result($result,$i-1,'listprice');
        $total = $qty*$listprice;

	$temp_data['productname'] = $productname;
	$temp_data['qty'] = $qty;
	$temp_data['unitprice'] = $unitprice;
	$temp_data['listprice'] = $listprice;
	$temp_data['total'] = $total;
	$iDataDtls[] = $temp_data;

}
//getting the Total Array
$price_total[] = $focus->column_fields["hdnSubTotal"];
$price_total[] = $focus->column_fields["txtTax"];
$price_total[] = $focus->column_fields["txtAdjustment"];
$price_total[] = $focus->column_fields["hdnGrandTotal"];

class PDF extends FPDF
{

// Invoice Title
function setInvoiceTitle($title,$logo_name,$caddress)
{
	if($title != "")
	{
		if(isset($logo_name) && $logo_name != '')
		{
			$this->Image('test/logo/'.$logo_name,10,10,0,0);
		}
		else
		{
			//$this->Image('themes/Aqua/images/blank.jpg',10,10,0,0);
		}
		for($i=0;$i<count($caddress);$i++)
		{

			$this->Ln();
			$this->Cell(40);
			$this->SetFont('','',10);
			$this->Cell(0,5,$caddress[$i],0,0,'L',0);
		}
		$this->Ln();
		$this->SetFillColor(224,235,255);
    		$this->SetTextColor(0);
    		$this->SetFont('','B',18);
    		$this->Cell(0,10,$title,0,0,'C',0);

	}
}
//Invoice Address
function setAddress($billing="",$shipping="")
{
	
	$this->Ln();
	$this->SetFillColor(224,235,255);
 	$this->SetTextColor(0);
    	$this->SetFont('','B',10);
	$this->Cell(130,10,"Bill To:",0,0,'L',0);
 	$this->Cell(0,10,"Ship To:",0,0,'L',0);
	for($i=0;$i<count($billing);$i++)
	{
		$this->Cell(17);
		$this->SetFont('','',10);
		$this->Cell(130,5,$billing[$i],0,0,'L',0);
		$this->Cell(0,5,$shipping[$i],0,0,'L',0);
		$this->Ln();
	}

}
//Invoice from
function setInvoiceDetails($iHeader,$iData)
{
    $this->Ln();
    $this->SetFillColor(162,200,243);
    $this->SetTextColor(0);
    $this->SetDrawColor(61,121,206);
    //$this->SetLineWidth(.3);
    $this->SetFont('Arial','B',10);
    //Header
    $this->Cell(15);
    foreach($iHeader as $value)
    {
        $this->Cell(40,7,$value,1,0,'L',1);
    }
    $this->Ln();
    $this->SetFillColor(233,241,253);
    $this->SetTextColor(0);
    $this->SetFont('');
    //Data
    $this->Cell(15);
    $fill=0;
    foreach($iData as $value)
    {
		$this->Cell(40,6,$value,1,0,'L',0);
    }
    $this->Ln();
}

//customer Details
function setCustomerDetails($iCHeader,$iCData)
{
    $this->Ln();
    //$this->Cell(0);
    $this->SetFillColor(162,200,243);
    $this->SetTextColor(0);
    $this->SetDrawColor(61,121,206);
    //$this->SetLineWidth(.3);
    $this->SetFont('Arial','B',10);
    //Header
    //$this->Cell(15);
    foreach($iCHeader as $value)
    {
        $this->Cell(63,7,$value,1,0,'L',1);
    }
    $this->Ln();
    $this->SetFillColor(233,241,253);
    $this->SetTextColor(0);
    $this->SetFont('');
    //Data
    //$this->Cell(15);
    $fill=0;
    foreach($iCData as $value)
    {
		$this->Cell(63,6,$value,1,0,'L',0);
    }
    $this->Ln();
}

//Product Details
function setProductDetails($ivHeader,$ivData)
{
    $this->Ln();
    $this->Ln();
    $this->SetFillColor(162,200,243);
    $this->SetTextColor(0);
    $this->SetDrawColor(61,121,206);
    $this->SetLineWidth(.3);
    $this->SetFont('Arial','B',10);
    //Header
    foreach($ivHeader as $value)
    {
        $this->Cell(38,7,$value,0,0,'L',0);
    }
    $this->Ln();
    $this->SetDrawColor(0,0,0);
    $this->SetLineWidth(.5);
    $this->line(10,140,200,140);
    $this->SetFillColor(233,241,253);
    $this->SetTextColor(0);
    $this->SetFont('');
    //Data
    $fill=0;
    	foreach($ivData as $key=>$value)
	{
    		$this->Cell(38,6,$value['productname'],0,0,'L',0);
		$this->Cell(38,6,$value['qty'],0,0,'L',0);
		$this->Cell(38,6,$value['unitprice'],0,0,'L',0);
		$this->Cell(38,6,$value['listprice'],0,0,'L',0);
		$this->Cell(38,6,$value['total'],0,0,'R',0);
		$this->Ln();
	}
    $this->Ln();
}

function setTotal($price_total="",$conditions="")
{
	$this->Ln();
	$this->SetDrawColor(0,0,0);
	$this->SetLineWidth(.3);
//	$this->line(10,200,200,200);
	$this->SetFillColor(224,235,255);
 	$this->SetTextColor(0);
    	$this->SetFont('','B',10);
	$this->Cell(140,6,"Sub Total: ",0,0,'R',0);
 	$this->Cell(0,6,$price_total[0],1,0,'R',0);
    	$this->Ln(4);
	$this->Ln(4);
	$this->Cell(140,6,"Tax: ",0,0,'R',0);
 	$this->Cell(0,6,$price_total[1],1,0,'R',0);
	$this->Ln(4);
	$this->Ln(4);
	$this->Cell(140,6,"Adjustment: ",0,0,'R',0);
 	$this->Cell(0,6,$price_total[2],1,0,'R',0);
    	$this->Ln(4);
	$this->Ln(4);
	$this->Cell(140,6,"Grand total: ",0,0,'R',0);
 	$this->Cell(0,6,$price_total[3],1,0,'R',0);
	$this->Ln();
	$this->Ln();
	$this->Cell(0,8,"Terms & Conditions: ",0,0,'L',0);
	$this->Ln();
	$this->Cell(0,8,$conditions,0,0,'L',0);
}
}
$iHead = array("Company","Invoice No.","Date","Sales Order No.");
$iCustHeadDtls = array("Customer Name","Purchase Order","Due Date");
$iHeadDtls = array("Product Name","Quantity","List Price","Unit Price","Total");

$pdf = new PDF('P','mm','A4');
$pdf->SetFont('Arial','',10);
$pdf->AddPage();
$pdf->setInvoiceTitle("Invoice",$logo_name,$companyaddress);
$pdf->Ln();
$pdf->setInvoiceDetails($iHead,$iData);
$pdf->setAddress($bdata,$sdata);
$pdf->setCustomerDetails($iCustHeadDtls,$iCustData);
$pdf->setProductDetails($iHeadDtls,$iDataDtls);
$pdf->setTotal($price_total,$conditions);
$pdf->Output('Invoice.pdf','D');
exit;
?>

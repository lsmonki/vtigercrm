<?php
/*
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program; if not, write to the Free Software
 *   Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 *   (c) 2005 Matthew Brichacek <mmbrich@fosslabs.com>
 *
 *   Changes/Additions:
 *
 *   (c) 2005 OpenCRM
 *    - Improved currency support
 *    - Fixed IE download bug
 */

require_once('include/database/PearDatabase.php');

global $adb;
global $app_strings;
global $products_per_page;
global $max_desc_size;
global $max_prod_desc_size;

$id = $_REQUEST['record'];

$sql="select currency_symbol from currency_info";
$result = $adb->query($sql);
$currency_symbol = $adb->query_result($result,0,'currency_symbol');

/* ************* BEGIN USER CONFIG *************** */
$endpage="0";
$products_per_page="9";
$max_desc_size="75";
$max_prod_desc_size="175";
/* ************* END USER CONFIG ***************** */

require_once('include/VtigerPDF.php');
require_once('pdf_dataso.php');

$page_num='1';
$pdf = new PDF( 'P', 'mm', 'A4' );
$pdf->Open();

$num_pages=ceil(($num_products/$products_per_page));
$current_product=0;

for($l=0;$l<$num_pages;$l++)
{
	$line=array();
	if($num_pages == $page_num)
		$lastpage=1;
	
	while($current_product != $page_num*$products_per_page)
	{
		$line[]=$product_line[$current_product];
		$current_product++;
	}

	$pdf->AddPage();
	include("pdf_templates/so_header.php");
	include("pdf_templates/body.php");
	include("pdf_templates/so_footer.php");

	$page_num++;

	if (($endpage) && ($lastpage))
	{
		$pdf->AddPage();
		include("pdf_templates/so_header.php");
		include("pdf_templates/lastpage/body.php");
		include("pdf_templates/lastpage/so_footer.php");
	}
}


$pdf->Output('SalesOrder.pdf','D'); //added file name to make it work in IE, also forces the download giving the user the option to save
exit;
?>

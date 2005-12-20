<?php
require_once('include/fpdf/fpdf.php');
require_once('modules/Quotes/Quote.php');
require_once('include/database/PearDatabase.php');

//Curency Settings By OpenCRM
global $adb;
global $app_strings;

$sql="select currency_symbol from currency_info";
$result = $adb->query($sql);
$currency_symbol = $adb->query_result($result,0,'currency_symbol');

// would you like and end page?  1 for yes 0 for no
$endpage="1";
global $products_per_page;
$products_per_page="6";

// Xavier Nicolay 2004
// Version 1.01

class PDF extends FPDF
{
// private variables
var $columns;
var $format;
var $angle=0;

// private functions
function RoundedRect($x, $y, $w, $h, $r, $style = '')
{
	$k = $this->k;
	$hp = $this->h;
	if($style=='F')
		$op='f';
	elseif($style=='FD' or $style=='DF')
		$op='B';
	else
		$op='S';
	$MyArc = 4/3 * (sqrt(2) - 1);
	$this->_out(sprintf('%.2f %.2f m',($x+$r)*$k,($hp-$y)*$k ));
	$xc = $x+$w-$r ;
	$yc = $y+$r;
	$this->_out(sprintf('%.2f %.2f l', $xc*$k,($hp-$y)*$k ));

	$this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
	$xc = $x+$w-$r ;
	$yc = $y+$h-$r;
	$this->_out(sprintf('%.2f %.2f l',($x+$w)*$k,($hp-$yc)*$k));
	$this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
	$xc = $x+$r ;
	$yc = $y+$h-$r;
	$this->_out(sprintf('%.2f %.2f l',$xc*$k,($hp-($y+$h))*$k));
	$this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
	$xc = $x+$r ;
	$yc = $y+$r;
	$this->_out(sprintf('%.2f %.2f l',($x)*$k,($hp-$yc)*$k ));
	$this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
	$this->_out($op);
}

function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
{
	$h = $this->h;
	$this->_out(sprintf('%.2f %.2f %.2f %.2f %.2f %.2f c ', $x1*$this->k, ($h-$y1)*$this->k,
						$x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
}

function Rotate($angle,$x=-1,$y=-1)
{
	if($x==-1)
		$x=$this->x;
	if($y==-1)
		$y=$this->y;
	if($this->angle!=0)
		$this->_out('Q');
	$this->angle=$angle;
	if($angle!=0)
	{
		$angle*=M_PI/180;
		$c=cos($angle);
		$s=sin($angle);
		$cx=$x*$this->k;
		$cy=($this->h-$y)*$this->k;
		$this->_out(sprintf('q %.5f %.5f %.5f %.5f %.2f %.2f cm 1 0 0 1 %.2f %.2f cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
	}
}

function _endpage()
{
	if($this->angle!=0)
	{
		$this->angle=0;
		$this->_out('Q');
	}
	parent::_endpage();
}

// public functions
function sizeOfText( $text, $largeur )
{
	$index    = 0;
	$nb_lines = 0;
	$loop     = TRUE;
	while ( $loop )
	{
		$pos = strpos($text, "\n");
		if (!$pos)
		{
			$loop  = FALSE;
			$line = $text;
		}
		else
		{
			$line  = substr( $text, $index, $pos);
			$text = substr( $text, $pos+1 );
		}
		$length = floor( $this->GetStringWidth( $line ) );
		$res = 1 + floor( $length / $largeur) ;
		$nb_lines += $res;
	}
	return $nb_lines;
}

// addImage
// Default will place vtiger in the top left corner
function addImage( $logo_name, $location=array('10','10','0','0') ) {
	if($logo_name)//error checking just in case, by OpenCRM
	{
		$x1 = $location[0];
		$y1 = $location[1];
		$stretchx = $location[2];
		$stretchy = $location[3];
		$this->Image('test/logo/'.$logo_name,$x1,$y1,$stretchx,$stretchy);
	}
}

// Company
function addCompany( $nom, $address, $location='' )
{
	$x1 = $location[0];
	$y1 = $location[1];
	//Positionnement en bas
	$this->SetXY( $x1, $y1 );
	$this->SetFont('Arial','B',12);
	$length = $this->GetStringWidth( $nom );
	$this->Cell( $length, 2, $nom);
	$this->SetXY( $x1, $y1 + 4 );
	$this->SetFont('Arial','',10);
	$length = $this->GetStringWidth( $address );
	//Coordonnées de la société
	$lines = $this->sizeOfText( $address, $length) ;
	$this->MultiCell($length, 4, $address);
}

// bubble blocks
function title ($label, $total, $position)
{
	$r1  = $position[0];
	$r2  = $r1 + 19 + $position[2] ;
	$y1  = $position[1];
	$y2  = $y1;
	$mid = $y1 + ($y2 / 2);
	$width=10;
	$this->SetFillColor(192);
	$this->RoundedRect($r1-16, $y1-1, (strlen($label." ".$total)*8)+4, $y2+1, 4.5, 'DF');
	$this->SetXY( $r1 + 4, $y1+1 );
	$this->SetFont( "Helvetica", "B", 15);
	$this->Cell($width,5, $label." ".$total, 0, 0, "C");
}

// text block, non-wrapped
function addTextBlock( $title,$text,$positions )
{
	$r1  = $positions[0];
	$y1  = $positions[1];
	$this->SetXY( $r1, $y1);
	$this->SetFont( "Helvetica", "B", 10);
	$this->Cell( $positions[2], 4,$title);
	$this->SetXY( $r1, $y1+4);
	$this->SetFont( "Helvetica", "", 10);
	$this->MultiCell( $positions[2], 4, $text);
}

function tableWrapper($position)
{
	$r1  = $position[0];
	$r2  = $r1 + 19 + $position[2] ;
	$y1  = $position[1];
	if($position[3])
		$y2  = $position[3];
	else
		$y2  = 17;

	$mid = $y1 + (13 / 2);
	$width=10;
	$this->RoundedRect($r1, $y1, ($r2 - $r1), $y2, 4.5, 'D');
	$this->Line( $r1, $mid, $r2, $mid);
	$this->SetXY( $r1 + ($r2-$r1)/2 - 3, $y1+3 );
	$this->SetXY( $r1 + ($r2-$r1)/2 - 5, $y1 + 9 );
}

function addBubble($page,$title,$position)
{
	$r1  = $position[0];
	$r2  = $r1 + 19 + $position[2] ;
	$y1  = $position[1];
	if($position[3])
		$y2  = 17*$position[3];
	else
		$y2  = 17;

	$mid = $y1 + (19 / 2);
	$width=10;
	$this->RoundedRect($r1, $y1, ($r2 - $r1), $y2, 4.5, 'D');
	$this->Line( $r1, $mid, $r2, $mid);
	$this->SetXY( $r1 + ($r2-$r1)/2 - 3, $y1+3 );
	$this->SetFont( "Helvetica", "B", 10);
	$this->Cell($width,5, $title, 0, 0, "C");
	$this->SetXY( $r1 + ($r2-$r1)/2 - 5, $y1 + 9 );
	$this->SetFont( "Helvetica", "", 10);
	$this->MultiCell($width,5,$page, 0,0, "C");
}

// bubble blocks
function addBubbleBlock ($page, $title, $position)
{
	$r1  = $position[0];
	$r2  = $r1 + 19 + $position[2] ;
	$y1  = $position[1];
	$y2  = 17;

	$mid = $y1 + ($y2 / 2);
	$width=10;
	$this->RoundedRect($r1, $y1, ($r2 - $r1), $y2, 4.5, 'D');
	$this->Line( $r1, $mid, $r2, $mid);
	$this->SetXY( $r1 + ($r2-$r1)/2 - 5, $y1+3 );
	$this->SetFont( "Helvetica", "B", 10);
	$this->Cell($width,5, $title, 0, 0, "C");
	$this->SetXY( $r1 + ($r2-$r1)/2 - 5, $y1 + 9 );
	$this->SetFont( "Helvetica", "", 10);
	$this->Cell($width,5,$page, 0,0, "C");
}

// record blocks
function addRecBlock( $data, $title, $postion )
{
	$lengthtitle = strlen($title);
	$lengthdata = strlen($data);
	$length=$lengthtitle;
	$r1  = $postion[0];
	$r2  = $r1 + 40 + $length;
	$y1  = $postion[1];
	$y2  = $y1+10;
	$mid = $y1 + (($y2-$y1) / 2);

	$this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
	$this->Line( $r1, $mid, $r2, $mid);
	$this->SetXY( $r1 + ($r2-$r1)/2 -5 , $y1+1 );
	$this->SetFont( "Helvetica", "B", 10);
	$this->Cell(10,4, $title, 0, 0, "C");
	$this->SetXY( $r1 + ($r2-$r1)/2 -5 , $y1 + 5 );
	$this->SetFont( "Helvetica", "", 10);
	$this->Cell(10,4,$data, 0, 0, "C");
}

// description blocks
function addDescBlock( $data, $title, $position )
{
	$lengthtitle = strlen($title);
	$lengthdata= $position[3];

	$length=$position[2];
	$r1  = $position[0];
	$r2  = $r1 + 40 + $length;
	$y1  = $position[1];
	$y2  = $y1+10;
	$mid = $y1 + (($y2-$y1) / 2);

	$this->RoundedRect($r1,$y1, ($length + 40), ($lengthdata/140*30), 2.5, 'D');
	$this->Line( $r1, $mid, $r2, $mid);
	$this->SetXY( $position[0]+2 , $y1 + 1 );
	$this->SetFont( "Helvetica", "B", 10);
	$this->Cell(10,4, $title);
	$this->SetXY( $position[0]+2 , $y1 + 6 );
	$this->SetFont( "Helvetica", "", 10);
	$this->MultiCell(($length+36),4,$data);
}

function drawLine($positions)
{
	$x=$positions[0];
	$y=$positions[1];
	$width=$positions[2];
	$this->Line( $x, $y, $x+$width, $y);
}

// add columns to table
function addCols( $tab ,$positions ,$bottom)
{
	global $columns;

	$r1  = 10;
	$r2  = $this->w - ($r1 * 2) ;
	$y1  = 80;
	$x1  = $positions[1];
	//$y2  = $this->h - $x1 - $y1 - 17;
	$y2  = $bottom;
	$this->SetXY( $r1, $y1 );
	$this->SetFont( "Helvetica", "", 10);
	//$this->Rect( $r1, $y1, $r2, $y2, "D");

	$colX = $r1;
	$columns = $tab;
	while ( list( $lib, $pos ) = each ($tab) )
	{
		$this->SetXY( $colX, $y1+3 );
		$this->Cell( $pos, 1, $lib, 0, 0, "C");
		$colX += $pos;
	switch($lib) {
	  case 'Total':
	  break;
	  default:
			$this->Line( $colX, $y1, $colX, $y1+$y2);
	  break;
	}
	}
}

function addLineFormat( $tab )
{
	global $format, $columns;

	while ( list( $lib, $pos ) = each ($columns) )
	{
		if ( isset( $tab["$lib"] ) )
			$format[ $lib ] = $tab["$lib"];
	}
}

// add a line to the invoice/estimate
function addProductLine( $line, $tab )
{
	global $columns, $format;

	$ordonnee     = 10;
	$maxSize      = $line;

	reset( $columns );
	while ( list( $lib, $pos ) = each ($columns) )
	{
		$longCell  = $pos -2;
		$text    = $tab[ $lib ];
		$length    = $this->GetStringWidth( $text );
		$formText  = $format[ $lib ];
		$this->SetXY( $ordonnee, $line);
		$this->MultiCell( $longCell, 3 , $text, 3, $formText);
		if ( $maxSize < ($this->GetY()  ) )
			$maxSize = $this->GetY() ;
		$ordonnee += $pos;
	}
	return ( $maxSize - $line );
}

function addTotalsRec($names, $totals, $positions)
{
	$this->SetFont( "Arial", "B", 8);
	$r1  = $positions[0];
	$r2  = $r1 + 90;
	$y1  = $positions[1];
	$y2  = $y1+10;
	$this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
	$this->Line( $r1, $y1+4, $r2, $y1+4);
	$this->Line( $r1+27, $y1, $r1+27, $y2);  // avant Subtotal
	$this->Line( $r1+43, $y1, $r1+43, $y2);  // avant Tax
	$this->Line( $r1+66, $y1, $r1+66, $y2);  // avant Adjustment

	$this->SetXY( $r1+2, $y1);
	$this->Cell(10,4, $names[0]);
	$this->SetX( $r1+29,$y1 );
	$this->Cell(10,4, $names[1]);
	$this->SetX( $r1+45 );
	$this->Cell(10,4, $names[2]);
	$this->SetX( $r1+66 );
	$this->Cell(10,4, $names[3]);


	$this->SetXY( $r1+2, $y1+5 );
	$this->Cell( 10,4, $totals[0] );
	$this->SetXY( $r1+29, $y1+5 );
	$this->Cell( 10,4, $totals[1] );
	$this->SetXY( $r1+44, $y1+5 );
	$this->Cell( 10,4, $totals[2] );
	$this->SetXY( $r1+66, $y1+5 );
	$this->Cell( 10,4, $totals[3] );

	$this->SetFont( "Arial", "B", 6);
	$this->SetXY( $r1+90, $y2 - 8 );
	$this->SetFont( "Helvetica", "", 10);
}

// add a watermark (temporary estimate, DUPLICATA...)
// call this method first
function watermark( $text, $positions, $rotate = array('45','50','180') )
{
	$this->SetFont('Arial','B',50);
	$this->SetTextColor(230,230,230);
	$this->Rotate($rotate[0],$rotate[1],$rotate[2]);
	$this->Text($positions[0],$positions[1],$text);
	$this->Rotate(0);
	$this->SetTextColor(0,0,0);
}

}

function StripLastZero($string)
{
	$count=strlen($string);
	$ret=substr($string,0,($count-1));
	return $ret;
}


// **************** BEGIN POPULATE DATA ********************
$focus = new Quote();
$focus->retrieve_entity_info($_REQUEST['record'],"Quotes");
$account_name = getAccountName($focus->column_fields[account_id]);
$account_id = $focus->column_fields[account_id];
$quote_id=$_REQUEST['record'];

// Quote Information
$valid_till = $focus->column_fields["validtill"];
$valid_till = getDisplayDate($valid_till); 
$bill_street = $focus->column_fields["bill_street"];
$bill_city = $focus->column_fields["bill_city"];
$bill_state = $focus->column_fields["bill_state"];
$bill_code = $focus->column_fields["bill_code"];
$bill_country = $focus->column_fields["bill_country"];

$ship_street = $focus->column_fields["ship_street"];
$ship_city = $focus->column_fields["ship_city"];
$ship_state = $focus->column_fields["ship_state"];
$ship_code = $focus->column_fields["ship_code"];
$ship_country = $focus->column_fields["ship_country"];

$conditions = $focus->column_fields["terms_conditions"];
$description = $focus->column_fields["description"];
$quote_status = $focus->column_fields["quotestage"];

// Company information
$add_query = "select * from organizationdetails";
$result = $adb->query($add_query);
$num_rows = $adb->num_rows($result);

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

		$logo_name = $adb->query_result($result,0,"logoname");
}

//getting the Total Array
$price_subtotal = $currency_symbol.number_format(StripLastZero($focus->column_fields["hdnSubTotal"]),2,'.',',');
$price_tax = $currency_symbol.number_format(StripLastZero($focus->column_fields["txtTax"]),2,'.',',');
$price_adjustment = $currency_symbol.number_format(StripLastZero($focus->column_fields["txtAdjustment"]),2,'.',',');
$price_total = $currency_symbol.number_format(StripLastZero($focus->column_fields["hdnGrandTotal"]),2,'.',',');

//getting the Product Data
$query="select products.productname,products.unit_price,products.product_description,quotesproductrel.* from quotesproductrel inner join products on products.productid=quotesproductrel.productid where quoteid=".$quote_id;

global $result;
$result = $adb->query($query);
$num_products=$adb->num_rows($result);
for($i=0;$i<$num_products;$i++) {
		$product_name[$i]=$adb->query_result($result,$i,'productname');
		$prod_description[$i]=$adb->query_result($result,$i,'product_description');
		$product_id[$i]=$adb->query_result($result,$i,'productid');
		$qty[$i]=$adb->query_result($result,$i,'quantity');

		$unit_price[$i]= $currency_symbol.number_format($adb->query_result($result,$i,'unit_price'),2,'.',',');
		$list_price[$i]= $currency_symbol.number_format(StripLastZero($adb->query_result($result,$i,'listprice')),2,'.',',');
		$list_pricet[$i]= $adb->query_result($result,$i,'listprice');
		$prod_total[$i]= $qty[$i]*$list_pricet[$i];


		$product_line[] = array( "Product Name"    => $product_name[$i],
				"Description"  => $prod_description[$i],
				"Qty"     => $qty[$i],
				"List Price"      => $list_price[$i],
				"Unit Price" => $unit_price[$i],
				"Total" => $currency_symbol.number_format($prod_total[$i],2,'.',','));
}

	$total[]=array("Unit Price" => $app_strings['LBL_SUB_TOTAL'],
		"Total" => $price_subtotal);

	$total[]=array("Unit Price" => $app_strings['LBL_ADJUSTMENT'],
		"Total" => $price_adjustment);

	$total[]=array("Unit Price" => $app_strings['LBL_TAX'],
		"Total" => $price_tax);

	$total[]=array("Unit Price" => $app_strings['LBL_GRAND_TOTAL'],
		"Total" => $price_total);


// ************************ END POPULATE DATA ***************************8

$page_num='1';
$pdf = new PDF( 'P', 'mm', 'A4' );
$pdf->Open();
//$pdf->AddPage();

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
	include("pdf_templates/header.php");
	include("pdf_templates/body.php");
	include("pdf_templates/footer.php");

	$page_num++;

	if (($endpage) && ($lastpage))
	{
		$pdf->AddPage();
		include("pdf_templates/header.php");
		include("pdf_templates/lastpage/body.php");
		include("pdf_templates/lastpage/footer.php");
	}
}


$pdf->Output('Quotes.pdf','D'); //added file name to make it work in IE, also forces the download giving the user the option to save

?>

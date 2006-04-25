<?php
/*
 * This class is based off of files that were obtained from the FPDF
 * website (http://fpdf.org).  Each file was released as FREEWARE
 * from the following authors:
 *
 * (c) 2004 Xavier Nicolay (original invoice pdf)
 * (c) <unknown> Shailesh Humbad (POSTNET functions)
 *
 * Additions/Changes:
 *
 * (c) 2005 Matthew Brichacek <mmbrich@fosslabs.com> (adapted for VTiger CRM)
 * - Changed most functions to generic layout type of functions
 * - Added POSTNet functions from fpdf examples
 * - Added extra shapes
 * - Ability to change curve
 * - Begin documenting functions
 *
 * (c) 2005 OpenCRM
 * - fixed failure if no logo is specified
 *
 */

require_once('include/fpdf/fpdf.php');

class PDF extends FPDF
{
// private variables
var $columns;
var $format;
var $angle=0;


/* ********************* BEGIN SHAPE FUNCTIONS *************** */
function RoundedBottom($x,$y,$w,$h,$r,$style = '')
{
    $k = $this->k;
    $hp = $this->h;
    if($style=='F')
        $op='f';
    elseif($style=='FD' or $style=='DF')
        $op='B';
    else
        $op='S';
    $this->_out(sprintf('%.2f %.2f m',($x+$r)*$k,($hp-$y)*$k ));
    $MyArc = 3 * (sqrt(2) - 1);;
    $xc = $x+$w-$r ;
    $yc = $y+$r;
    $this->_out(sprintf('%.2f %.2f l', $xc*$k,($hp-$y)*$k ));

    $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
    $xc = $x+$w-$r ;
    $yc = $y+$h-$r;
    $MyArc = 3/2 * (sqrt(2) - 1);
    $this->_out(sprintf('%.2f %.2f l',($x+$w)*$k,($hp-$yc)*$k));
    $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
    $xc = $x+$r ;
    $yc = $y+$h-$r;
    $MyArc = 3/2 * (sqrt(2) - 1);
    $this->_out(sprintf('%.2f %.2f l',$xc*$k,($hp-($y+$h))*$k));
    $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
    $xc = $x+$r ;
    $yc = $y+$r;
    $MyArc = 3 * (sqrt(2) - 1);;
    $this->_out(sprintf('%.2f %.2f l',($x)*$k,($hp-$yc)*$k ));
    $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
    $this->_out($op);
}

function RoundedTop($x,$y,$w,$h,$r,$style = '')
{
    $k = $this->k;
    $hp = $this->h;
    if($style=='F')
        $op='f';
    elseif($style=='FD' or $style=='DF')
        $op='B';
    else
        $op='S';
    $this->_out(sprintf('%.2f %.2f m',($x+$r)*$k,($hp-$y)*$k ));
    $MyArc = 3/2 * (sqrt(2) - 1);
    $xc = $x+$w-$r ;
    $yc = $y+$r;
    $this->_out(sprintf('%.2f %.2f l', $xc*$k,($hp-$y)*$k ));

    $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
    $xc = $x+$w-$r ;
    $yc = $y+$h-$r;
    $MyArc = 3 * (sqrt(2) - 1);;
    $this->_out(sprintf('%.2f %.2f l',($x+$w)*$k,($hp-$yc)*$k));
    $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
    $xc = $x+$r ;
    $yc = $y+$h-$r;
    $MyArc = 3 * (sqrt(2) - 1);;
    $this->_out(sprintf('%.2f %.2f l',$xc*$k,($hp-($y+$h))*$k));
    $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
    $xc = $x+$r ;
    $yc = $y+$r;
    $MyArc = 3/2 * (sqrt(2) - 1);
    $this->_out(sprintf('%.2f %.2f l',($x)*$k,($hp-$yc)*$k ));
    $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
    $this->_out($op);
}

function RoundedRight($x,$y,$w,$h,$r,$style = '')
{
    $k = $this->k;
    $hp = $this->h;
    if($style=='F')
        $op='f';
    elseif($style=='FD' or $style=='DF')
        $op='B';
    else
        $op='S';
    $MyArc = 3/2 * (sqrt(2) - 1);
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
    $MyArc = 3 * (sqrt(2) - 1);;
    $this->_out(sprintf('%.2f %.2f l',$xc*$k,($hp-($y+$h))*$k));
    $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
    $xc = $x+$r ;
    $yc = $y+$r;
    $this->_out(sprintf('%.2f %.2f l',($x)*$k,($hp-$yc)*$k ));
    $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
    $this->_out($op);
}

function RoundedLeft($x,$y,$w,$h,$r,$style = '')
{
    $k = $this->k;
    $hp = $this->h;
    if($style=='F')
        $op='f';
    elseif($style=='FD' or $style=='DF')
        $op='B';
    else
        $op='S';
    $MyArc = 3 * (sqrt(2) - 1);;
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
    $MyArc = 3/2 * (sqrt(2) - 1);
    $this->_out(sprintf('%.2f %.2f l',$xc*$k,($hp-($y+$h))*$k));
    $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
    $xc = $x+$r ;
    $yc = $y+$r;
    $this->_out(sprintf('%.2f %.2f l',($x)*$k,($hp-$yc)*$k ));
    $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
    $this->_out($op);
}

// private functions
function RoundedRect($x, $y, $w, $h, $r, $style = '',$curve='')
{
    $k = $this->k;
    $hp = $this->h;
    if($style=='F')
        $op='f';
    elseif($style=='FD' or $style=='DF')
        $op='B';
    else
        $op='S';
    if($curve=='')
    	$MyArc = 3/2 * (sqrt(2) - 1);
    else 
    	$MyArc = $curve * (sqrt(2) - 1);
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
/* ********************* END SHAPE FUNCTIONS *************** */

/* ********************* BEGIN POSTNET FUNCTIONS *************** */
/* POSTNETBarCode - creates a US postal service barcode out of a ZIP Code
 * $x = x position in PDF 
 * $y = y position in PDF 
 * $zipcode = a valid US zipcode, may contain a dash
 */
function POSTNETBarCode($x, $y, $zipcode)
{
        // Save nominal bar dimensions in user units
        // Full Bar Nominal Height = 0.125"
        $FullBarHeight = 9 / $this->k;
        // Half Bar Nominal Height = 0.050"
        $HalfBarHeight = 3.6 / $this->k;
        // Full and Half Bar Nominal Width = 0.020"
        $BarWidth = 1.44 / $this->k;
        // Bar Spacing = 0.050"
        $BarSpacing = 3.6 / $this->k;

        $FiveBarSpacing = $BarSpacing * 5;

        // 1 represents full-height bars and 0 represents half-height bars
        $BarDefinitionsArray = Array(
            1 => Array(0,0,0,1,1),
            2 => Array(0,0,1,0,1),
            3 => Array(0,0,1,1,0),
            4 => Array(0,1,0,0,1),
            5 => Array(0,1,0,1,0),
            6 => Array(0,1,1,0,0),
            7 => Array(1,0,0,0,1),
            8 => Array(1,0,0,1,0),
            9 => Array(1,0,1,0,0),
            0 => Array(1,1,0,0,0));

        // validate the zip code
        $this->_ValidateZipCode($zipcode);

        // set the line width
        $this->SetLineWidth($BarWidth);

        // draw start frame bar
        $this->Line($x, $y, $x, $y - $FullBarHeight);
        $x += $BarSpacing;

        // draw digit bars
for($i = 0; $i < 5; $i++)
        {
            $this->_DrawDigitBars($x, $y, $BarSpacing, $HalfBarHeight,
                $FullBarHeight, $BarDefinitionsArray, $zipcode{$i});
            $x += $FiveBarSpacing;
        }
        // draw more digit bars if 10 digit zip code
        if(strlen($zipcode) == 10)
        {
            for($i = 6; $i < 10; $i++)
            {
                $this->_DrawDigitBars($x, $y, $BarSpacing, $HalfBarHeight,
                    $FullBarHeight, $BarDefinitionsArray, $zipcode{$i});
                $x += $FiveBarSpacing;
            }
        }

        // draw check sum digit
        $this->_DrawDigitBars($x, $y, $BarSpacing, $HalfBarHeight,
            $FullBarHeight, $BarDefinitionsArray,
            $this->_CalculateCheckSumDigit($zipcode));
        $x += $FiveBarSpacing;

        // draw end frame bar
        $this->Line($x, $y, $x, $y - $FullBarHeight);
}

// Validates a zipcode for POSTNET
function _ValidateZipCode($zipcode)
{
        $functionname = "ValidateZipCode Error: ";

        // check if zipcode is an array or object
        if(is_array($zipcode) || is_object($zipcode))
        {
            trigger_error($functionname.
                "Zip code may not be an array or object.", E_USER_ERROR);
        }

        // convert zip code to a string
        $zipcode = strval($zipcode);

        // check if length is 5
        if ( strlen($zipcode) != 5 && strlen($zipcode) != 10 ) {
            trigger_error($functionname.
                "Zip code must be 5 digits or 10 digits including hyphen. len:".                strlen($zipcode)." zipcode: ".$zipcode, E_USER_ERROR);
        }

        if ( strlen($zipcode) == 5 ) {
            // check that all characters are numeric
            for ( $i = 0; $i < 5; $i++ ) {
                if ( is_numeric( $zipcode{$i} ) == false ) {
                    trigger_error($functionname.
                        "5 digit zip code contains non-numeric character.",
                        E_USER_ERROR);
                }
            }
        } else {
            // check for hyphen
            if ( $zipcode{5} != "-" ) {
                trigger_error($functionname.
                    "10 digit zip code does not contain hyphen in right place.",                    E_USER_ERROR);
            }
            // check that all characters are numeric
            for ( $i = 0; $i < 10; $i++ ) {
                if ( is_numeric($zipcode{$i}) == false && $i != 5 ) {
                    trigger_error($functionname.
                        "10 digit zip code contains non-numeric character.",
                        E_USER_ERROR);
                }
            }
        }

        // return the string
        return $zipcode;
}

// takes a validated zip code and
// calculates the checksum for POSTNET
function _CalculateCheckSumDigit($zipcode)
{
        // calculate sum of digits
        if( strlen($zipcode) == 10 ) {
            $sumOfDigits = $zipcode{0} + $zipcode{1} +
                $zipcode{2} + $zipcode{3} + $zipcode{4} +
                $zipcode{6} + $zipcode{7} + $zipcode{8} +
                $zipcode{9};
        } else {
            $sumOfDigits = $zipcode{0} + $zipcode{1} +
                $zipcode{2} + $zipcode{3} + $zipcode{4};
        }

        // return checksum digit
        if( ($sumOfDigits % 10) == 0 )
            return 0;
        else
            return 10 - ($sumOfDigits % 10);
}

// Takes a digit and draws the corresponding POSTNET bars.
function _DrawDigitBars($x, $y, $BarSpacing, $HalfBarHeight, $FullBarHeight,        $BarDefinitionsArray, $digit)
{
        // check for invalid digit
        if($digit < 0 && $digit > 9)
            trigger_error("DrawDigitBars: invalid digit.", E_USER_ERROR);

        // draw the five bars representing a digit
        for($i = 0; $i < 5; $i++)
        {
            if($BarDefinitionsArray[$digit][$i] == 1)
                $this->Line($x, $y, $x, $y - $FullBarHeight);
            else
                $this->Line($x, $y, $x, $y - $HalfBarHeight);
            $x += $BarSpacing;
        }
}

/* Text rotation for watermark
 * $angle = angle to rotate
 * $x = x position in PDF
 * $y = y postiion in PDF
 */
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
/* ********************* END POSTNET FUNCTIONS *************** */

/* ********************* BEGIN LAYOUT FUNCTIONS *************** */
/* addImage 
 * $logo_name = name of logo, no path needed.
 * $location = array ('x','y','width','height')
 * Default will place vtiger in the top left corner
 */
function addImage( $logo_name, $location=array('10','10','0','0') ) {
    $x1 = $location[0];
    $y1 = $location[1];
    $stretchx = $location[2];
    $stretchy = $location[3];
    $this->Image('test/logo/'.$logo_name,$x1,$y1,$stretchx,$stretchy);
}

/* Title bubble - shaded bubble for PDF title
 * $label = title label
 * $ext = text to append to title
 * $position = array(x,y,width)
 *
 */
function title ($label, $ext, $position)
{
    $r1  = $position[0];
    $r2  = $r1 + 19 + $position[2] ;
    $y1  = $position[1];
    $y2  = $y1;
    $mid = $y1 + ($y2 / 2);
    $width=10;
    $this->SetFillColor(192);
    //$this->RoundedRect($r1-16, $y1-1, (strlen($label." ".$ext)*8)+4, $y2+1, 2.5, 'DF');
    $this->RoundedRect($r1-16, $y1-1, 52, $y2+1, 2.5, 'DF');
    $this->SetXY( $r1 + 4, $y1+1 );
    $this->SetFont( "Helvetica", "B", 15);
    $this->Cell($width,5, $label." ".$ext, 0, 0, "C");
}

/* Text Block - a non-wrapped text block.
 * line return characters are not ignored (ie: \n)
 * $title = title of block 
 * $text = text of block 
 * $positions = array(x,y,width)
 *
 */
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

/* Table Wrapper - generic bubble - will be depricated
 * in future releases by addBubble() 
 * $position = array(x,y,width,height)
 * $type = D,L,R,B,T  - type of shape
 * $curve = help calculate curve (only for type == D)
 */
function tableWrapper($position,$type='D',$curve='')
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
    switch($type) {
	case 'T':
    		$this->RoundedTop($r1, $y1, ($r2 - $r1), $y2, 4.5, 'D');
	break;
	case 'B':
    		$this->RoundedBottom($r1, $y1, ($r2 - $r1), $y2, 4.5, 'D');
	break;
	case 'L':
    		$this->RoundedLeft($r1, $y1, ($r2 - $r1), $y2, 4.5, 'D');
	break;
	case 'R':
    		$this->RoundedRight($r1, $y1, ($r2 - $r1), $y2, 4.5, 'D');
	break;
	case 'D':
    		$this->RoundedRect($r1, $y1, ($r2 - $r1), $y2, 4.5, 'D',$curve);
	break;
    }
    $this->Line( $r1, $mid, $r2, $mid);
    $this->SetXY( $r1 + ($r2-$r1)/2 - 3, $y1+3 );
    $this->SetXY( $r1 + ($r2-$r1)/2 - 5, $y1 + 9 );
}

/* Bubble - generic bubble - can be used to wrap almost anything
 * $text =  any text you would like in the bubble
 * $title = bolded title
 * $position = array(x,y,width,height)
 * $type = D,L,R,B,T  - type of shape
 * $curve = help calculate curve (only for type == D)
 */
function addBubble($text,$title,$position,$type='D',$curve='') 
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

    switch($type) {
	case 'T':
    		$this->RoundedTop($r1, $y1, ($r2 - $r1), $y2, 4.5, 'D');
	break;
	case 'B':
    		$this->RoundedBottom($r1, $y1, ($r2 - $r1), $y2, 4.5, 'D');
	break;
	case 'L':
    		$this->RoundedLeft($r1, $y1, ($r2 - $r1), $y2, 4.5, 'D');
	break;
	case 'R':
    		$this->RoundedRight($r1, $y1, ($r2 - $r1), $y2, 4.5, 'D');
	break;
	case 'D':
    		$this->RoundedRect($r1, $y1, ($r2 - $r1), $y2, 4.5, 'D',$curve);
	break;
    }
    $this->Line( $r1, $mid, $r2, $mid);
    $this->SetXY( $r1 + ($r2-$r1)/2 - 3, $y1+3 );
    $this->SetFont( "Helvetica", "B", 10);
    $this->Cell($width,5, $title, 0, 0, "C");
    $this->SetXY( $r1 + ($r2-$r1)/2 - 5, $y1 + 9 );
    $this->SetFont( "Helvetica", "", 10);
    $this->MultiCell($width,5,$text, 0,0, "C");
}

/* Bubble Block - small information bubble
 * $text =  any text you would like in the bubble
 * $title = bolded title
 * $position = array(x,y,width)
 * $type = D,L,R,B,T  - type of shape
 * $curve = help calculate curve (only for type == D)
 */
// bubble blocks
function addBubbleBlock ($text, $title, $position,$type='D',$curve='')
{
    $r1  = $position[0];
    $r2  = $r1 + 19 + $position[2] ;
    $y1  = $position[1];
    $y2  = 17;

    $mid = $y1 + ($y2 / 2);
    $width=10;
    switch($type) {
	case 'T':
    		$this->RoundedTop($r1, $y1, ($r2 - $r1), $y2, 4.5, 'D');
	break;
	case 'B':
    		$this->RoundedBottom($r1, $y1, ($r2 - $r1), $y2, 4.5, 'D');
	break;
	case 'L':
    		$this->RoundedLeft($r1, $y1, ($r2 - $r1), $y2, 4.5, 'D');
	break;
	case 'R':
    		$this->RoundedRight($r1, $y1, ($r2 - $r1), $y2, 4.5, 'D');
	break;
	case 'D':
    		$this->RoundedRect($r1, $y1, ($r2 - $r1), $y2, 4.5, 'D',$curve);
	break;
    }
    $this->Line( $r1, $mid, $r2, $mid);
    $this->SetXY( $r1 + ($r2-$r1)/2 - 5, $y1+3 );
    $this->SetFont( "Helvetica", "B", 10);
    $this->Cell($width,5, $title, 0, 0, "C");
    $this->SetXY( $r1 + ($r2-$r1)/2 - 5, $y1 + 9 );
    $this->SetFont( "Helvetica", "", 10);
    $this->Cell($width,5,$text, 0,0, "C");
}

/* Rec Block - small block for record info, less "bubble"
 * than a bubble block
 * $text =  any text you would like in the bubble
 * $title = bolded title
 * $position = array(x,y,width)
 * $type = D,L,R,B,T  - type of shape
 * $curve = help calculate curve (only for type == D)
 */
function addRecBlock( $text, $title, $positions, $type='D', $curve='' )
{
    $lengthtitle = strlen($title);
    $lengthdata = strlen($text);
    $length=$lengthtitle;
    $r1  = $positions[0];
    $r2  = $r1 + 40 + $positions[2];
    $y1  = $positions[1];
    $y2  = $y1+10;
    $mid = $y1 + (($y2-$y1) / 2);

    switch($type) {
	case 'T':
    		$this->RoundedTop($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
	break;
	case 'B':
    		$this->RoundedBottom($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
	break;
	case 'L':
    		$this->RoundedLeft($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
	break;
	case 'R':
    		$this->RoundedRight($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
	break;
	case 'D':
    		$this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D',$curve);
	break;
    }
    $this->Line( $r1, $mid, $r2, $mid);
    $this->SetXY( $r1 + ($r2-$r1)/2 -5 , $y1+1 );
    $this->SetFont( "Helvetica", "B", 10);
    $this->Cell(10,4, $title, 0, 0, "C");
    $this->SetXY( $r1 + ($r2-$r1)/2 -5 , $y1 + 5 );
    $this->SetFont( "Helvetica", "", 10);
    $this->Cell(10,4,$text, 0, 0, "C");
}

/* Desc Block - block mean to for large data types, less "bubble"
 * than a bubble block
 * $text =  any text you would like in the bubble
 * $title = bolded title
 * $position = array(x,y,width,height)
 * $type = D,L,R,B,T  - type of shape
 * $curve = help calculate curve (only for type == D)
 */
function addDescBlock( $text, $title, $position, $type='D',$curve='' )
{
    global $max_desc_size;
    $lengthtitle = strlen($title);
    $lengthdata= $position[3];

    $length=$position[2];
    $r1  = $position[0];
    $r2  = $r1 + 40 + $length;
    $y1  = $position[1];
    $y2  = $y1+10;
    $mid = $y1 + (($y2-$y1) / 2)+1;

    if ((strlen($text) > $max_desc_size) && ($max_desc_size != 0))
    {
	$text = substr($text,0,$max_desc_size);
    }

    switch($type) {
	case 'T':
    		$this->RoundedTop($r1,$y1, ($length + 40), ($lengthdata/140*30), 2.5, 'D');
	break;
	case 'B':
    		$this->RoundedBottom($r1,$y1, ($length + 40), ($lengthdata/140*30), 2.5, 'D');
	break;
	case 'L':
    		$this->RoundedLeft($r1,$y1, ($length + 40), ($lengthdata/140*30), 2.5, 'D');
	break;
	case 'R':
    		$this->RoundedRight($r1,$y1, ($length + 40), ($lengthdata/140*30), 2.5, 'D');
	break;
	case 'D':
    		$this->RoundedRect($r1,$y1, ($length + 40), ($lengthdata/140*30), 2.5, 'D',$curve);
	break;
    }
    $this->Line( $r1, $mid, $r2, $mid);
    $this->SetXY( $position[0]+2 , $y1 + 1 );
    $this->SetFont( "Helvetica", "B", 10);
    $this->Cell(10,4, $title);
    $this->SetXY( $position[0]+2 , $y1 + 6 );
    $this->SetFont( "Helvetica", "", 10);
    $this->MultiCell(($length+36),4,$text);
}

/* Draw Line - draw a verticle line anwhere on the PDF
 * $positions = array(x,y,with)
 *
 */
function drawLine($positions)
{
    $x=$positions[0];
    $y=$positions[1];
    $width=$positions[2];
    $this->Line( $x, $y, $x+$width, $y);
}

/* Add Cols - add product columns to the table
 *    $tab = array( 	"Product Name" 	=> size,
 *                     	"Description" 	=> size,
 *                     	"Qty" 		=> size,
 *                     	"List Price" 	=> size,
 *                     	"Unit Price" 	=> size,
 *		 	"total" 	=> size);
 * $postitions = array(x,y)
 * $bottom = bottom of the table in the PDF
 */
function addCols( $tab ,$positions ,$bottom)
{
    global $columns;

    $r1  = 10;
    $r2  = $this->w - ($r1 * 2) ;
    $y1  = 85; // adjust this value for starting point of columns
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
          case 'Product Name':
          case 'Description':
          case 'Qty':
		// Use this for a short line in the products table
                //$this->Line( $colX, $y1, $colX, $y1+$y2-25);
                $this->Line( $colX, $y1, $colX, $y1+$y2);
          break;
          case 'Total':
          break;
          default:
                $this->Line( $colX, $y1, $colX, $y1+$y2);
          break;
	}
    }
}

/* Line Format - setup column alignment in the table
/*    $tab = array( 	"Product Name" 	=> alignment,
 *                     	"Description" 	=> alignment,
 *                     	"Qty" 		=> alignment,
 *                     	"List Price" 	=> alignment,
 *                     	"Unit Price" 	=> alignment,
 *		 	"total" 	=> alignment);
 *
 */
function addLineFormat( $tab )
{
    global $format, $columns;
    
    while ( list( $lib, $pos ) = each ($columns) )
    {
        if ( isset( $tab["$lib"] ) )
            $format[ $lib ] = $tab["$lib"];
    }
}

/* Product Line - adds a product line to the table
 *    $tab = array( 	"Product Name" 	=> prodname,
 *                     	"Description" 	=> descr,
 *                     	"Qty" 		=> qty,
 *                     	"List Price" 	=> listprice,
 *                     	"Unit Price" 	=> unitprice,
 *		 	"total" 	=> total);
 *    $line = where to place this line in the table
 *    you will need to calculate this with your padding
 *    values in body.php
 */
function addProductLine( $line, $tab )
{
    global $columns, $format, $max_prod_desc_size;

    $ordonnee     = 10;
    $maxSize      = $line;

    reset( $columns );
    while ( list( $lib, $pos ) = each ($columns) )
    {
        $longCell  = $pos -2;
        $text    = $tab[ $lib ];
	if ((strlen($text) > $max_prod_desc_size) && 
	    ($lib == "Description") && 
	    ($max_prod_desc_size != 0))
		$text=substr($text,0,$max_prod_desc_size)."...";

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

/* Totals Rec - experimental totals bubble
 * $names = array(Subtotal, Adjustment, ...)
 * $totals = array(subtotalnumber,adjustmentnumber, ...)
 * $position = array(x,y)
 * $type = D,L,R,B,T  - type of shape
 * $curve = help calculate curve (only for type == D)
 */
function addTotalsRec($names, $totals, $positions,$type='D',$curve='')
{
    $this->SetFont( "Arial", "B", 8);
    $r1  = $positions[0];
    $r2  = $r1 + 90;
    $y1  = $positions[1];
    $y2  = $y1+10;
    switch($type) {
	case 'T':
    		$this->RoundedTop($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
	break;
	case 'B':
    		$this->RoundedBottom($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
	break;
	case 'L':
    		$this->RoundedLeft($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
	break;
	case 'R':
    		$this->RoundedRight($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
	break;
	case 'D':
    		$this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D',$curve);
	break;
    }
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

/* watermark - add a watermark or gray text to PDF
 * $text = text to watermark
 * $positions = array(x,y)
 * $rotate = array(x,y,% of rotation) -- still needs work
 */
function watermark( $text, $positions, $rotate = array('45','50','180') )
{
    $this->SetFont('Arial','B',50);
    $this->SetTextColor(230,230,230);
    $this->Rotate($rotate[0],$rotate[1],$rotate[2]);
    $this->Text($positions[0],$positions[1],$text);
    $this->Rotate(0);
    $this->SetTextColor(0,0,0);
}
/* ********************* END LAYOUT FUNCTIONS *************** */

}
?>

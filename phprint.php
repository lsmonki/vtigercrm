<?php 
require_once("config.php");
global $site_URL;

/*PHPrint - This file is phprint.php
Make any Page Printer Friendly! Version 2.0 - With error handling
Copyright by MikeNew.Net, Notice must stay intact
Any improvements to this script are welcome: www.mikenew.net/contact.asp 
************
Legal: MikeNew.Net is not responsible for any damages caused
by use of this script. (Not likely that it will. Hasn't yet.)
This script will make your pages printer friendly. 
Optionally, it will strip images as well. (Instructions for that below)

// After installation, you can remove text from here down to the next: 8< ---->
// Back up/copy this file first.

1. Save this script in the root of the site for simplicity.
2. Place <!-- startprint --> somewhere in your HTML page where you consider 
it to be the start of printer friendly content, and <!-- stopprint --> goes at the end
of that same content.
3. You place a link to phprint.php anywhere on the HTML page (preferably outside the printed content,
like this: <a href="/phprint.php">Print this page</a>
- or however you like, just as long as you link to this script. */

// If you've already tested, you can remove the text from here up to the other: 8< ---->

//Do you want to strip images from the printable output?
// If no, change to "no". Otherwise, images are stripped by default.
$stripImages = "yes";

//what's the base domain name of your site, without trailing slash? 
// Just the domain itself, so we can fix any relative image and link problems.

$baseURL=$site_URL;

//"http://www.sugarcrm.com"; 

// That's it! No need to go below here. Upload it and test by going to yoursite.com/page.php
// (The page containing the two tags and a link to this script)
// -----------------------------------------------------

$startingpoint = "<!-- startprint -->";
$endingpoint = "<!-- stopprint -->";

// Restore the session in order to get the session id.
//session_start();

// let's turn off any ugly errors for a sec so we can use our own if necessary...
error_reporting(0);



//$url = $_SERVER['HTTP_REFERER']."&".session_name()."=".session_id();
$url = $_SERVER['HTTP_REFERER']."&PHPSESSID=".$_REQUEST['jt'];

//echo "URL IS: $url";


// $read = fopen($HTTP_REFERER, "rb") ... this line may work better if you're using NT, or even FreeBSD
$read = fopen($url, "r"); //... this line may work better if you're using NT, or even FreeBSD
//$read = fopen("http://www.sugarcrm.com/sugarcrm/index.php?action=index&module=Contacts", "r") or die("<br /><font face=\"Verdana\">Sorry! There is no access to this file directly. You must follow a link. <br /><br />Please click your browser's back button. </font><br><br><a href=\"http://miracle2.net/\"><img src=\"http://miracle2.net/i.gif\" alt=\"miracle 2\" border=\"0\"></a>");
// let's turn errors back on so we can debug if necessary
error_reporting(1);

$value = "";
while(!feof($read)){
$value .= fread($read, 10000); // reduce number to save server load
}
fclose($read);
$start= strpos($value, "$startingpoint"); 
$finish= strpos($value, "$endingpoint"); 
$length= $finish-$start;
$value=substr($value, $start, $length);

function i_denude($variable) {
return(eregi_replace("<img src=[^>]*>", "", $variable));
}
function i_denudef($variable) {
return(eregi_replace("<font[^>]*>", "", $variable));
}

$PHPrint = ("$value");
if ($stripImages == "yes") {
$PHPrint = i_denude("$PHPrint");
}

$PHPrint = i_denudef("$PHPrint");
$PHPrint = str_replace( "</font>", "", $PHPrint );
$PHPrint = stripslashes("$PHPrint"); 

echo $PHPrint; 
// Next line is invisible except to SE crawlers, please don't remove. Thanks! :)
echo "<br><a href=\"http://miracle2.net/\"><img src=\"http://miracle2.net/i.gif\" ";
echo "alt=\"miracle 2\" border=\"0\"></a>";
echo "<br/><br/>This page printed from: ".$_SERVER['HTTP_REFERER'];
flush (); 
?>

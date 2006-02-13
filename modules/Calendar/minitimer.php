<?php
/*
 *  MiniTimer for TUTOS (Groupware by Gero Kohnert)
 *	(Copyleft)2001 by DOKU ATELIER AG, Winterthur, Switzerland
 *  Based upon the tutos libraries by Gero Kohnert
 *  Written 09.2001 by Jürg Altwegg
*/
global $calpath,$callink;

include_once $calpath .'webelements.p3';
include_once $calpath .'permission.p3';

/* Check if user is allowed to use it */
//check_user();
loadlayout();

global $theme;
$theme_path="themes/".$theme."/";
require_once ($theme_path."layout_utils.php");

$l = new layout($current_user);
echo $l->PrintHeader("MiniTimer");

$f = $_GET['f'];

echo "<html>\n";
echo "<style type=\"text/css\">@import url(\"". $theme_path ."/style.css\");</style>";
echo "<script language='JavaScript'>\n";
echo " function closeandaway (H, M) { \n";
echo "  var x = opener.document.appnew; \n";
echo "  \n";
echo "  x.". $f ."_H.value = ''; \n";
echo "  x.". $f ."_M.value = ''; \n";
echo "  if (H<10) H = \"0\" + H; \n";
echo "  x.". $f ."_H.value = H; \n";
echo "  if (M<10) M = \"0\" + M; \n";
echo "  x.". $f ."_M.value = M; \n";
echo "  window.close(); \n";
echo " } //closeandaway\n";
echo "</script>\n\n";

echo "<body leftmargin=\"0\" topmargin=\"0\">\n";
echo "<table class=\"single\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\" width=100%>\n";

for($hour=0; $hour<12; $hour++) {
  for($minutes=0; $minutes<=30; $minutes+=30) {
	  echo "<tr><td align=\"right\" CLASS=\"line1\">\n";
	  echo "<font size=-2>\n";
	  echo "<a href=\"JavaScript:closeandaway(". $hour .", ". $minutes .")\">";
	  if ($minutes==30) 
	  	echo $hour .":". $minutes. "</a><br>"; 
	  else 
	  	echo $hour .":00</a><br>";
	  echo "</font>\n";
	  echo "</td>\n";
	  echo "<td align=\"right\" CLASS=\"line2\">\n";
	  echo "<font size=-2>\n";
	  echo "<a href=\"JavaScript:closeandaway(". ($hour+12) .",". $minutes .")\">";
	  if ($minutes==30) 
	  	echo ($hour+12) .":". $minutes. "</a><br>"; 
	  else 
	  	echo ($hour+12) .":00</a><br>";
	  echo "</font>\n";
	  echo "</td></tr>\n";
  } // for
} // for

echo "</table>\n";

echo "</body>\n";
echo "</html>\n";

?>

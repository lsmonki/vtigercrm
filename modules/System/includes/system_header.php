<?php 

// phpSysInfo - A PHP System Information Script
// http://phpsysinfo.sourceforge.net/

// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

// $Id: system_header.php,v 1.1 2005/03/14 07:43:20 shankarr Exp $
header("Cache-Control: no-cache, must-revalidate");
if (!isset($charset)) {
  $charset = 'iso-8859-1';
} 

setlocale (LC_TIME, $text['locale']);

header('Content-Type: text/html; charset=' . $charset);
// our text direction (for hebrew)
if (!$text_dir) {
  $text_dir = 'ltr';
} 

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<?php
echo created_by();

?>

<head>
    <title>
<?php
global $XPath;
echo $text['title'], " -- ", $XPath->getData('/phpsysinfo/Vitals/Hostname'), " --\n";

?>
    </title>
<?php
if (isset($charset) && $charset == 'euc-jp') {
    echo "    <meta http-equiv=\"content-type\" content=\"text/html;charset=$charset\">\n";
}
if (isset($refresh) && ($refresh = intval($refresh))) {
  echo "    <meta http-equiv=\"Refresh\" content=\"$refresh\">\n";
}
if (file_exists(APP_ROOT . "/templates/$template/$template.css")) {
  echo '    <link rel="STYLESHEET" type="text/css" href="modules/System/templates/';
  echo $template . '/' . $template;
  echo ".css\">";
}

?>

</head>

<?php
if (file_exists(APP_ROOT . "/templates/$template/images/$template" . "_background.gif")) {
  echo '<body background="modules/System/templates/' . $template . '/images/' . $template . '_background.gif" dir="' . $text_dir . '">';
} else {
  echo "<body dir=$text_dir>";
}

?>

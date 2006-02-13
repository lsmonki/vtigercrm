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
// $Id: systemconfig.php,v 1.1 2005/03/14 07:40:50 shankarr Exp $
// phpsysinfo release version number
$VERSION = "2.3";
define('APP_ROOT', dirname(__FILE__));
set_magic_quotes_runtime(0);
if (!file_exists(APP_ROOT . '/config.php')) {
  echo '<center><b>Error: config.php does not exist.</b></center>';
  exit;
}
require(APP_ROOT .'/config.php'); // get the config file

if (!extension_loaded('xml')) {
  echo '<center><b>Error: phpsysinfo requires xml module.</b></center>';
  exit;

}

// reassign HTTP variables (incase register_globals is off)
if (!empty($HTTP_GET_VARS)) while (list($name, $value) = each($HTTP_GET_VARS)) $$name = $value;
if (!empty($HTTP_POST_VARS)) while (list($name, $value) = each($HTTP_POST_VARS)) $$name = $value;
// Check to see if where running inside of phpGroupWare
if (isset($sessionid) && $sessionid && $kp3 && $domain) {
  define('PHPGROUPWARE', 1);
  $phpgw_info['flags'] = array('currentapp' => 'phpsysinfo-dev'
    );
  include(APP_ROOT .'../header.inc.php');
} else {
  define('PHPGROUPWARE', 0);
}

if (!isset($template)) {
  $template = $_COOKIE['template'];
}

if (!isset($template)) {
  $template = $default_template;
}
//echo 'template is '.$template;
// check to see if we have a random template first
if ($template == 'random') {
  $dir = opendir(APP_ROOT .'/templates/');
  while (($file = readdir($dir)) != false) {
    if ($file != 'CVS' && $file != '.' && $file != '..') {
      $buf[] = $file;
    }
  }
  $template = $buf[array_rand($buf, 1)];
  $random = true;
}
if ($template != 'xml') {
  $template = basename(APP_ROOT .'/templates/' . $template); 
  // figure out if we got a template passed in the url
  if (!file_exists(APP_ROOT ."/templates/$template")) {
    // default template we should use if we don't get a argument.
    $template = $default_template;
  }
}
define('TEMPLATE_SET', $template);
// get our current language
// default to english, but this is negotiable.

if (!isset($lng)) {
  $lng = $_COOKIE['lng'];
}

if (!isset($lng)) {
  $lng = $default_lng;
}

$lng = basename(APP_ROOT .'/includes/lang/' . $lng . '.php', '.php');

if (!file_exists(APP_ROOT .'/includes/lang/' . $lng . '.php')) {
  // see if the browser knows the right languange.
  if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    $plng = split(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
    if (count($plng) > 0) {
      while (list($k, $v) = each($plng)) {
        $k = split(';', $v, 1);
        $k = split('-', $k[0]);
        if (file_exists(APP_ROOT .'/includes/lang/' . $k[0] . '.php')) {
          $lng = $k[0];
          break;
        }
      }
    }
  }
}

require(APP_ROOT .'/includes/lang/' . $lng . '.php'); // get our language include

// Figure out which OS where running on, and detect support
if (file_exists(APP_ROOT . '/includes/os/class.' . PHP_OS . '.inc.php')) {
  require(APP_ROOT .'/includes/os/class.' . PHP_OS . '.inc.php');
  $sysinfo = new sysinfo;
} else {
  echo '<center><b>Error: ' . PHP_OS . ' is not currently supported</b></center>';
  exit;
}

if (!empty($sensor_program)) {
  if (file_exists(APP_ROOT . '/includes/mb/class.' . $sensor_program . '.inc.php')) {
    require(APP_ROOT .'/includes/mb/class.' . $sensor_program . '.inc.php');
    $mbinfo = new mbinfo;
  } else {
    echo '<center><b>Error: ' . $sensor_program . ' is not currently supported</b></center>';
    exit;
  }
}

require(APP_ROOT .'/includes/common_functions.php'); // Set of common functions used through out the app
require(APP_ROOT .'/includes/xml/vitals.php');
require(APP_ROOT .'/includes/xml/network.php');
require(APP_ROOT .'/includes/xml/hardware.php');
require(APP_ROOT .'/includes/xml/memory.php');
require(APP_ROOT .'/includes/xml/filesystems.php');
require(APP_ROOT .'/includes/xml/mbinfo.php');

$xml = "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
$xml .= "<!DOCTYPE phpsysinfo SYSTEM \"phpsysinfo.dtd\">\n\n";
$xml .= created_by();
$xml .= "<phpsysinfo>\n";
$xml .= "  <Generation version=\"$VERSION\" timestamp=\"" . time() . "\"/>\n";
$xml .= xml_vitals();
$xml .= xml_network();
$xml .= xml_hardware();
$xml .= xml_memory();
$xml .= xml_filesystems();
if (!empty($sensor_program)) {
  $xml .= xml_mbtemp();
  $xml .= xml_mbfans();
  $xml .= xml_mbvoltage();
} ;
$xml .= "</phpsysinfo>";

if ($template == 'xml') {
  // just printout the XML and exit
  Header("Content-Type: text/xml\n\n");
  print $xml;
} else {

  // If they have GD complied into PHP, find out the height of the image to make this cleaner
  if (function_exists('getimagesize') && $template != 'xml') {
    $image_prop = getimagesize(APP_ROOT . '/templates/' . TEMPLATE_SET . '/images/bar_middle.gif');
    define('BAR_HEIGHT', $image_prop[1]);
    unset($image_prop);
  } else {
    // Until they complie GD into PHP, this could look ugly
    define('BAR_HEIGHT', 16);
  } 
  // Store the current template name in a cookie, set expire date to one month later
  // Store 'random' if we want a random template
  if ($random) {
    setcookie("template", 'random', (time() + 60 * 60 * 24 * 30));
  } else {
    setcookie("template", $template, (time() + 60 * 60 * 24 * 30));
  } 
  // Store the current language selection in a cookie
  setcookie("lng", $lng, (time() + 60 * 60 * 24 * 30));

  if (PHPGROUPWARE != 1) {
    require(APP_ROOT .'/includes/class.Template.inc.php'); // template library
  } 
  // fire up the template engine
  $tpl = new Template(APP_ROOT . '/templates/' . TEMPLATE_SET);
  $tpl->set_file(array('form' => 'form.tpl'
      )); 
  // print out a box of information
  function makebox ($title, $content, $percent) {
    $t = new Template(APP_ROOT . '/templates/' . TEMPLATE_SET);

    $t->set_file(array('box' => 'box.tpl'
        ));

    $t->set_var('title', $title);
    $t->set_var('content', $content);
    if (empty($content)) {
      return '';
    } else {
      return $t->parse('out', 'box');
    } 
  } 
  // Fire off the XPath class
  require(APP_ROOT .'/includes/XPath.class.php');
  $XPath = new XPath();
  $XPath->importFromString($xml); 
  // let the page begin.
  require(APP_ROOT .'/includes/system_header.php');

  $tpl->set_var('title', $text['title'] . ': ' . $XPath->getData('/phpsysinfo/Vitals/Hostname') . ' (' . $XPath->getData('/phpsysinfo/Vitals/IPAddr') . ')');

  $tpl->set_var('vitals', makebox($text['vitals'], html_vitals(), '100%'));
  $tpl->set_var('network', makebox($text['netusage'], html_network(), '100%'));
  $tpl->set_var('hardware', makebox($text['hardware'], html_hardware(), '100%'));
  $tpl->set_var('memory', makebox($text['memusage'], html_memory(), '100%'));
  $tpl->set_var('filesystems', makebox($text['fs'], html_filesystems(), '100%'));
  if (!empty($sensor_program)) {
    $tpl->set_var('mbtemp', makebox($text['temperature'], html_mbtemp(), '100%'));
    $tpl->set_var('mbfans', makebox($text['fans'], html_mbfans(), '100%'));
    $tpl->set_var('mbvoltage', makebox($text['voltage'], html_mbvoltage(), '100%'));
  } else {
    $tpl->set_var('mbtemp', '');
    $tpl->set_var('mbfans', '');
    $tpl->set_var('mbvoltage', '');
  } ; 
  // parse our the template
  $tpl->pparse('out', 'form'); 
  // finally our print our footer
  if (PHPGROUPWARE == 1) {
    $phpgw->common->phpgw_footer();
  } else {
    require(APP_ROOT .'/includes/system_footer.php');
  }
}

?>

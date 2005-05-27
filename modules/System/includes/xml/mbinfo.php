<?php
//
// phpSysInfo - A PHP System Information Script
// http://phpsysinfo.sourceforge.net/
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.  
//   
// $Id: mbinfo.php,v 1.5 2004/08/24 22:58:31 webbie Exp $

function xml_mbtemp() {
    global $text;
    global $mbinfo;

    $data = $mbinfo->temperature();

    $_text = "  <MBinfo>\n";
    if (sizeof($data) > 0) {
    $_text .= "    <Temperature>\n";
    for ($i=0, $max = sizeof($data); $i < $max; $i++) {
        $_text .= "       <Item>\n";
        $_text .= "      <Label>" . $data[$i]['label'] . "</Label>\n";
        $_text .= "      <Value>" . $data[$i]['value'] . "</Value>\n";
        $_text .= "      <Limit>" . $data[$i]['limit'] . "</Limit>\n";
        $_text .= "       </Item>\n";
    }
    $_text .= "    </Temperature>\n";
    }

    return $_text;  
};

function xml_mbfans() {
    global $text;
    global $mbinfo;

    $data = $mbinfo->fans();
    if (sizeof($data) > 0) {
        $_text = "    <Fans>\n";
        for ($i=0, $max = sizeof($data); $i < $max; $i++) {
            $_text .= "       <Item>\n";
            $_text .= "      <Label>" . $data[$i]['label'] . "</Label>\n";
            $_text .= "      <Value>" . $data[$i]['value'] . "</Value>\n";
            $_text .= "      <Min>" . $data[$i]['min'] . "</Min>\n";
            $_text .= "      <Div>" . $data[$i]['div'] . "</Div>\n";
            $_text .= "       </Item>\n";
        }
        $_text .= "    </Fans>\n";
    }

    return $_text;  
};

function xml_mbvoltage() {
    global $text;
    global $mbinfo;

    $data = $mbinfo->voltage();
    if (sizeof($data) > 0) {
        $_text = "    <Voltage>\n";
        for ($i=0, $max = sizeof($data); $i < $max; $i++) {
            $_text .= "       <Item>\n";
            $_text .= "      <Label>" . $data[$i]['label'] . "</Label>\n";
            $_text .= "      <Value>" . $data[$i]['value'] . "</Value>\n";
            $_text .= "      <Min>" . $data[$i]['min'] . "</Min>\n";
            $_text .= "      <Max>" . $data[$i]['max'] . "</Max>\n";
            $_text .= "       </Item>\n";
        }
        $_text .= "    </Voltage>\n";
    }
    $_text .= "  </MBinfo>\n";

    return $_text;  
};


function html_mbtemp() {
  global $text;
  global $mbinfo;

  $data=array();

  $scale_factor = 4;

  $_text = "\n<table width=\"100%\">\n";
  $_text .= '<tr><td><font size="-1"><b>'. $text['s_label'] . '</b></font></td><td><font size="-1"><b>' . $text['s_value'] . '</b></font></td><td align="right" valign="top"><font size="-1"><b>' . $text['s_limit'] . '</b></font></td></tr>';

  $data = $mbinfo->temperature();
  for ($i=0, $max = sizeof($data); $i < $max; $i++) {
     $_text .= "\t<tr>\n";
     $_text .= "\t\t<td align=\"left\" valign=\"top\"><font size=\"-1\">". $data[$i]['label'] . "</font></td>\n";
     $_text .= "\t\t<td align=\"left\" valign=\"top\"><font size=\"-1\">";
     $_text .= create_bargraph($data[$i]['value'], $data[$i]['value'], $scale_factor);
     $_text .= "&nbsp;" . round($data[$i]['value']) . $text['degree_mark'] . "</font></td>\n";
     $_text .= "\t\t<td align=\"right\" valign=\"top\"><font size=\"-1\">". $data[$i]['limit'] . " " . $text['degree_mark'] . "</font></td>\n"; 
  };
  $_text .= "\n</table>\n";

  return $_text;  
};


function html_mbfans() {
  global $text;
  global $mbinfo;

  $_text ="\n<table width=\"100%\">\n";

  $_text .= '<tr><td><font size="-1"><b>' . $text['s_label'] . '</b></font></td><td align="right"><font size="-1"><b>' . $text['s_value'] . '</b></font></td><td align="right"><font size="-1"><b>' . $text['s_min'] . '</b></font></td><td align="right"><font size="-1"><b>' . $text['s_div'] . '</b></font></td></tr>';

  $data = $mbinfo->fans();
  $show_fans = FALSE;

  for ($i=0, $max = sizeof($data); $i < $max; $i++) {
      $_text .= "\t<tr>\n";
      $_text .= "\t\t<td align=\"left\" valign=\"top\"><font size=\"-1\">". $data[$i]['label'] . "</font></td>\n";
      $_text .= "\t\t<td align=\"right\" valign=\"top\"><font size=\"-1\">". round($data[$i]['value']) . " " . $text['rpm_mark'] . "</font></td>\n"; 
      $_text .= "\t\t<td align=\"right\" valign=\"top\"><font size=\"-1\">". $data[$i]['min'] . " " . $text['rpm_mark'] . "</font></td>\n"; 
      $_text .= "\t\t<td align=\"right\" valign=\"top\"><font size=\"-1\">
" . $data[$i]['div'] . "</font></td>\n";
      if (round($data[$i]['value']) > 0) { 
          $show_fans = TRUE;
      }
  };
  $_text .= "\n</table>\n";

  if (!$show_fans) {
      $_text = '';
  }

  return $_text;  
};


function html_mbvoltage() {
  global $text;
  global $mbinfo;

  $_text = "\n<table width=\"100%\">\n";

  $_text .= '<tr><td><font size="-1"><b>' . $text['s_label'] . '</b></font></td><td align="right"><font size="-1"><b>' . $text['s_value'] . '</b></font></td><td align="right"><font size="-1"><b>' . $text['s_min'] . '</b></font></td><td align="right"><font size="-1"><b>' . $text['s_max'] . '</b></font></td></tr>';

    $data = $mbinfo->voltage();
    for ($i=0, $max = sizeof($data); $i < $max; $i++) {
            $_text .= "\t<tr>\n";
            $_text .= "\t\t<td align=\"left\" valign=\"top\"><font size=\"-1\">". $data[$i]['label'] . "</font></td>\n";
            $_text .= "\t\t<td align=\"right\" valign=\"top\"><font size=\"-1\">". $data[$i]['value'] . " " . $text['voltage_mark'] . "</font></td>\n"; 
            $_text .= "\t\t<td align=\"right\" valign=\"top\"><font size=\"-1\">". $data[$i]['min'] . " " . $text['voltage_mark'] . "</font></td>\n"; 
            $_text .= "\t\t<td align=\"right\" valign=\"top\"><font size=\"-1\">" . $data[$i]['max'] . " " . $text['voltage_mark'] . "</font></td>\n";
    };

  $_text .= "\n</table>\n";

  return $_text;  
};
?>

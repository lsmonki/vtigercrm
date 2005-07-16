<?php

/**
 * options_highlight.php
 *
 * Copyright (c) 1999-2005 The SquirrelMail Project Team
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Displays message highlighting options
 *
 * @version $Id$
 * @package squirrelmail
 */

/**
 * Path for SquirrelMail required files.
 * @ignore
 */

echo get_module_title("Emails", $mod_strings['LBL_MODULE_TITLE'], true); 
$submenu = array('LBL_EMAILS_TITLE'=>'index.php?module=Emails&action=ListView.php','LBL_WEBMAILS_TITLE'=>'index.php?module=squirrelmail-1.4.4&action=redirect');
$sec_arr = array('index.php?module=Emails&action=ListView.php'=>'Emails','index.php?module=squirrelmail-1.4.4&action=redirect'=>'Emails'); 
echo '<br>';
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
   <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
     <td class="tabStart">&nbsp;&nbsp;</td>
<?
	if(isset($_REQUEST['smodule']) && $_REQUEST['smodule'] != '')
	{
		$classname = "tabOff";
	}
	else
	{
		$classname = "tabOn";
	}
	$listView = "ListView.php";
	foreach($submenu as $label=>$filename)
	{
		$cur_mod = $sec_arr[$filename];
		$cur_tabid = getTabid($cur_mod);

		if($tab_per_Data[$cur_tabid] == 0)
		{

			list($lbl,$sname,$title)=split("_",$label);
			if(stristr($label,"EMAILS"))
			{

				echo '<td class="tabOn" nowrap><a href="index.php?module=Emails&action=ListView&smodule='.$_REQUEST['smodule'].'" class="tabLink">'.$mod_strings[$label].'</a></td>';

				$listView = $filename;
				$classname = "tabOff";
			}
			elseif(stristr($label,$_REQUEST['smodule']))
			{
				echo '<td class="tabOn" nowrap><a href="index.php?module=squirrelmail-1.4.4&action=redirect&smodule='.$_REQUEST['smodule'].'" class="tabLink">'.$mod_strings[$label].'</a></td>';	
				$listView = $filename;
				$classname = "tabOff";
			}
			else
			{
				echo '<td class="'.$classname.'" nowrap><a href="index.php?module=squirrelmail-1.4.4&action=redirect&smodule='.$sname.'" class="tabLink">'.$mod_strings[$label].'</a></td>';	
			}
			$classname = "tabOff";
		}

	}
?>
     <td width="100%" class="tabEnd">&nbsp;</td>
   </tr>
 </table></td>
 </tr>
 </table>
 <br>
<?
//define('SM_PATH','../');
define('SM_PATH','modules/squirrelmail-1.4.4/');
/* SquirrelMail required files. */
require_once(SM_PATH . 'include/validate.php');
require_once(SM_PATH . 'functions/display_messages.php');
require_once(SM_PATH . 'functions/imap.php');
require_once(SM_PATH . 'functions/plugin.php');
require_once(SM_PATH . 'functions/strings.php');
require_once(SM_PATH . 'functions/html.php');
require_once(SM_PATH . 'functions/forms.php');

/* get globals */
sqGetGlobalVar('theaction', $theaction);
sqGetGlobalVar('theid', $theid);
sqGetGlobalVar('identname', $identname);
sqGetGlobalVar('newcolor_choose', $newcolor_choose);
sqGetGlobalVar('newcolor_input', $newcolor_input);
sqGetGlobalVar('color_type', $color_type);
sqGetGlobalVar('match_type', $match_type);
sqGetGlobalVar('value', $value);

//Richie
/* end of get globals */
 
function oh_opt( $val, $sel, $tit ) {
	global $mod_strings;

	echo 'function oh_opt invoked';
    echo "<option value=\"$val\"";
    if ( $sel )
        echo ' selected="selected"';
    echo  ">$tit</option>\n";
}
//Richie
if (!isset($theaction)) {
    $theaction = '';
}
if (! isset($message_highlight_list)) {
	echo ' array not set so creating a new array <br>';
    $message_highlight_list = array();
}
if (isset($theid) && ($theaction == 'delete') ||
                     ($theaction == 'up')     ||
                     ($theaction == 'down')) {
    $new_rules = array();
    switch($theaction) {
        case('delete'):
            foreach($message_highlight_list as $rid => $rule)
	    {
                 if($rid != $theid)
		 {
                    $new_rules[] = $rule;
                 }
		    
            }
            break;
        case('down'):
            $theid++;
//	    echo 'down called';
        case('up'):
          foreach($message_highlight_list as $rid => $rule) {
            //        echo '<br> up selected , printing theid ....... ';
                if($rid == $theid) {
                  $temp_rule         = $new_rules[$rid-1];
                  $new_rules[$rid-1] = $rule;
                  $new_rules[$rid]   = $temp_rule;
                } else {
                  $new_rules[$rid]   = $rule;
                  //    echo '>>>>>>>>>>>> '.$new_rules[$rid];
                }
            }
            break;
        default:
            $new_rules = $message_highlight_list;
            break;
    }
    $message_highlight_list = $new_rules;
    //print_r($message_highlight_list);
    setPref($data_dir, $username, 'hililist', serialize($message_highlight_list));
    //header('Location:  index.php?module=squirrelmail-1.4.4&action=options_highlight');
    //exit;
} else if ($theaction == 'save') {
	//Richie
    if ($color_type == 1) $newcolor = $newcolor_choose;
    elseif ($color_type == 2) $newcolor = $newcolor_input;
    else $newcolor = $color_type;

    $newcolor = str_replace('#', '', $newcolor);
    $newcolor = str_replace('"', '', $newcolor);
    $newcolor = str_replace('\'', '', $newcolor);
    $value = str_replace(',', ' ', $value);
    //invoked only during Edit
    if(isset($theid))
    {
      $message_highlight_list[$theid] = array( 'name' => $identname, 'color' => $newcolor, 'value' => $value, 'match_type' => $match_type );
    } else {
      $message_highlight_list[] =   array( 'name' => $identname, 'color' => $newcolor, 'value' => $value, 'match_type' => $match_type );
    }
    setPref($data_dir, $username, 'hililist', serialize($message_highlight_list));
}
//displayPageHeader($color, 'None');
echo
html_tag( 'table', "\n" .
    html_tag( 'tr', "\n" .
        html_tag( 'td', '<center><b>' . _($mod_strings['LBL_OPTIONS']) . ' - ' . _($mod_strings['LBL_MESSAGE_HIGHLIGHTING']) . '</b></center>', 'center')
   ),
   'center', $color[12], 'width="95%" border="0" cellpadding="1" cellspacing="0"' ) . "<br />\n" .
html_tag( 'table', '', '', '', 'width="100%" border="0" cellpadding="1" cellspacing="0"' ) . 
    html_tag( 'tr' ) . "\n" .
        html_tag( 'td', '', 'center' );

echo '<center>[<a href="index.php?module=squirrelmail-1.4.4&action=options_highlight&theaction=add">' . _($mod_strings['LNK_NEW']) . '</a>]'.
        ' - [<a href="index.php?module=squirrelmail-1.4.4&action=options">'._($mod_strings['LNK_DONE']).'</a>]</center><br />'."\n";
	//Problem area is here as the array is not getting properly updated

$mhl_count = count($message_highlight_list);
if ($mhl_count > 0) {
    echo html_tag( 'table', '', 'center', '', 'width="80%" border="0" cellpadding="3" cellspacing="0"' ) . "\n";
    for ($i=0; $i < $mhl_count; $i++) {
        $match_type = '';
        switch ($message_highlight_list[$i]['match_type'] ) {
            case 'from' :
                $match_type = _("From");
            break;
            case 'to' :
                $match_type = _("To");
            break;
            case 'cc' :
                $match_type = _("Cc");
            break;
            case 'to_cc' :
                $match_type = _("To or Cc");
            break;
            case 'subject' :
                $match_type = _("subject");
            break;
        }

        $links = '<small>[<a href="index.php?module=squirrelmail-1.4.4&action=options_highlight&theaction=edit&amp;theid=' . $i . '">' .
                 _($mod_strings['LBL_EDIT']) .
                 '</a>]&nbsp;[<a href="index.php?module=squirrelmail-1.4.4&action=options_highlight&theaction=delete&amp;theid='.  $i . '">' .
                 _($mod_strings['LBL_DELETE']);
        if($i > 0) {
            $links .= '</a>]&nbsp;[<a href="index.php?module=squirrelmail-1.4.4&action=options_highlight&theaction=up&amp;theid='.  $i . '">' .  _($mod_strings['LBL_UP']);
        }
        if($i+1 < $mhl_count) {
            $links .= '</a>]&nbsp;[<a href="index.php?module=squirrelmail-1.4.4&action=options_highlight&theaction=down&amp;theid='.  $i . '">' .  _($mod_strings['LBL_DOWN']);
        }
        $links .= '</a>]</small>';

        echo html_tag( 'tr',
                    html_tag( 'td',
                        $links,
                    'left', $color[4], 'width="20%" nowrap' ) .
                    html_tag( 'td',
                        htmlspecialchars($message_highlight_list[$i]['name']) ,
                    'left' ) .
                    html_tag( 'td',
                        $match_type . ' = ' .
                        htmlspecialchars($message_highlight_list[$i]['value']) ,
                    'left' ) ,
                '', $message_highlight_list[$i]['color'] ) . "\n";
    }
    echo "</table>\n".
        "<br />\n";
} else {
    echo '<center>' . _($mod_strings['LBL_NO_HIGHLIGHTING_IS_DEFINED']) . "</center><br />\n".
        "<br />\n";
}
if ($theaction == 'edit' || $theaction == 'add') {

    $color_list[0] = '4444aa';
    $color_list[1] = '44aa44';
    $color_list[2] = 'aaaa44';
    $color_list[3] = '44aaaa';
    $color_list[4] = 'aa44aa';
    $color_list[5] = 'aaaaff';
    $color_list[6] = 'aaffaa';
    $color_list[7] = 'ffffaa';
    $color_list[8] = 'aaffff';
    $color_list[9] = 'ffaaff';
    $color_list[10] = 'aaaaaa';
    $color_list[11] = 'bfbfbf';
    $color_list[12] = 'dfdfdf';
    $color_list[13] = 'ffffff';

    # helpful color chart from http://www.visibone.com/colorlab/big.html
    $new_color_list["0,0"] = 'cccccc';
    $new_color_list["0,1"] = '999999';
    $new_color_list["0,2"] = '666666';
    $new_color_list["0,3"] = '333333';
    $new_color_list["0,4"] = '000000';

    # red
    $new_color_list["1,0"] = 'ff0000';
    $new_color_list["1,1"] = 'cc0000';
    $new_color_list["1,2"] = '990000';
    $new_color_list["1,3"] = '660000';
    $new_color_list["1,4"] = '330000';

    $new_color_list["2,0"] = 'ffcccc';
    $new_color_list["2,1"] = 'cc9999';
    $new_color_list["2,2"] = '996666';
    $new_color_list["2,3"] = '663333';
    $new_color_list["2,4"] = '330000';

    $new_color_list["3,0"] = 'ffcccc';
    $new_color_list["3,1"] = 'ff9999';
    $new_color_list["3,2"] = 'ff6666';
    $new_color_list["3,3"] = 'ff3333';
    $new_color_list["3,4"] = 'ff0000';

    # green
    $new_color_list["4,0"] = '00ff00';
    $new_color_list["4,1"] = '00cc00';
    $new_color_list["4,2"] = '009900';
    $new_color_list["4,3"] = '006600';
    $new_color_list["4,4"] = '003300';

    $new_color_list["5,0"] = 'ccffcc';
    $new_color_list["5,1"] = '99cc99';
    $new_color_list["5,2"] = '669966';
    $new_color_list["5,3"] = '336633';
    $new_color_list["5,4"] = '003300';

    $new_color_list["6,0"] = 'ccffcc';
    $new_color_list["6,1"] = '99ff99';
    $new_color_list["6,2"] = '66ff66';
    $new_color_list["6,3"] = '33ff33';
    $new_color_list["6,4"] = '00ff00';

    # blue
    $new_color_list["7,0"] = '0000ff';
    $new_color_list["7,1"] = '0000cc';
    $new_color_list["7,2"] = '000099';
    $new_color_list["7,3"] = '000066';
    $new_color_list["7,4"] = '000033';

    $new_color_list["8,0"] = 'ccccff';
    $new_color_list["8,1"] = '9999cc';
    $new_color_list["8,2"] = '666699';
    $new_color_list["8,3"] = '333366';
    $new_color_list["8,4"] = '000033';

    $new_color_list["9,0"] = 'ccccff';
    $new_color_list["9,1"] = '9999ff';
    $new_color_list["9,2"] = '6666ff';
    $new_color_list["9,3"] = '3333ff';
    $new_color_list["9,4"] = '0000ff';

    # yellow
    $new_color_list["10,0"] = 'ffff00';
    $new_color_list["10,1"] = 'cccc00';
    $new_color_list["10,2"] = '999900';
    $new_color_list["10,3"] = '666600';
    $new_color_list["10,4"] = '333300';

    $new_color_list["11,0"] = 'ffffcc';
    $new_color_list["11,1"] = 'cccc99';
    $new_color_list["11,2"] = '999966';
    $new_color_list["11,3"] = '666633';
    $new_color_list["11,4"] = '333300';

    $new_color_list["12,0"] = 'ffffcc';
    $new_color_list["12,1"] = 'ffff99';
    $new_color_list["12,2"] = 'ffff66';
    $new_color_list["12,3"] = 'ffff33';
    $new_color_list["12,4"] = 'ffff00';

    # cyan
    $new_color_list["13,0"] = '00ffff';
    $new_color_list["13,1"] = '00cccc';
    $new_color_list["13,2"] = '009999';
    $new_color_list["13,3"] = '006666';
    $new_color_list["13,4"] = '003333';

    $new_color_list["14,0"] = 'ccffff';
    $new_color_list["14,1"] = '99cccc';
    $new_color_list["14,2"] = '669999';
    $new_color_list["14,3"] = '336666';
    $new_color_list["14,4"] = '003333';

    $new_color_list["15,0"] = 'ccffff';
    $new_color_list["15,1"] = '99ffff';
    $new_color_list["15,2"] = '66ffff';
    $new_color_list["15,3"] = '33ffff';
    $new_color_list["15,4"] = '00ffff';

    # magenta
    $new_color_list["16,0"] = 'ff00ff';
    $new_color_list["16,1"] = 'cc00cc';
    $new_color_list["16,2"] = '990099';
    $new_color_list["16,3"] = '660066';
    $new_color_list["16,4"] = '330033';

    $new_color_list["17,0"] = 'ffccff';
    $new_color_list["17,1"] = 'cc99cc';
    $new_color_list["17,2"] = '996699';
    $new_color_list["17,3"] = '663366';
    $new_color_list["17,4"] = '330033';

    $new_color_list["18,0"] = 'ffccff';
    $new_color_list["18,1"] = 'ff99ff';
    $new_color_list["18,2"] = 'ff66ff';
    $new_color_list["18,3"] = 'ff33ff';
    $new_color_list["18,4"] = 'ff00ff';

    $selected_input = FALSE;
    $selected_i = null;
    $selected_choose = FALSE;
    $selected_predefined = FALSE;

    for ($i=0; $i < 14; $i++) {
        ${"selected".$i} = '';
    }
    if ($theaction == 'edit' && isset($theid) && isset($message_highlight_list[$theid]['color'])) {
echo 'theaction is '.$theaction .'  theid is '.$theid .'  msghighlightlist is  ' .$message_highlight_list[$theid]['color'];
        for ($i=0; $i < 14; $i++) {
            if ($color_list[$i] == $message_highlight_list[$theid]['color']) {
                $selected_choose = TRUE;
                $selected_i = $color_list[$i];
                continue;
            }
        }
    }

    if ($theaction == 'edit' && isset($theid) && isset($message_highlight_list[$theid]['color'])) {
        $current_color = $message_highlight_list[$theid]['color'];
    }
    else {
        $current_color = '63aa7f';
    }

    $pre_defined_color = 0;
    for($x = 0; $x < 5; $x++) {
        for($y = 0; $y < 19; $y++) {
            $gridindex = "$y,$x";
            $gridcolor = $new_color_list[$gridindex];
            if ($gridcolor == $current_color) {
                $pre_defined_color = 1;
                break;
            }
        }
    }

    if (isset($theid) && !isset($message_highlight_list[$theid]['color']))
        $selected_choose = TRUE;
    else if ($pre_defined_color)
        $selected_predefined = TRUE;
    else if ($selected_choose == '')
        $selected_input = TRUE;

    echo addForm('index.php?module=squirrelmail-1.4.4&action=options_highlight', 'POST', 'f').
         addHidden('theaction', 'save');
    if($theaction == 'edit') {
        echo addHidden('theid', (isset($theid)?$theid:''));
    }
    echo html_tag( 'table', '', 'center', '', 'width="80%" cellpadding="3" cellspacing="0" border="0"' ) . "\n";
    echo html_tag( 'tr', '', '', $color[0] ) . "\n";
    echo html_tag( 'td', '', 'right', '', 'nowrap' ) . "<b>\n";
    echo _($mod_strings['LBL_IDENTIFYING_NAME']) . ":";
    echo '      </b></td>' . "\n";
    echo html_tag( 'td', '', 'left' ) . "\n";
    if ($theaction == 'edit' && isset($theid) && isset($message_highlight_list[$theid]['name']))
        $disp = $message_highlight_list[$theid]['name'];
    else
        $disp = '';
    echo "         ".addInput('identname', $disp);
    echo "      </td>\n";
    echo "   </tr>\n";
    echo html_tag( 'tr', html_tag( 'td', '<small><small>&nbsp;</small></small>', 'left' ) ) ."\n";
    echo html_tag( 'tr', '', '', $color[0] ) . "\n";
    echo html_tag( 'td', '<b>'. _($mod_strings['LBL_COLOR']) . ':</b>', 'right' );
    echo html_tag( 'td', '', 'left' );
    echo '         '.addRadioBox('color_type', $selected_choose, '1');

    $selops = array (
        $color_list[0] => _("Dark Blue"),
        $color_list[1] => _("Dark Green"),
        $color_list[2] => _("Dark Yellow"),
        $color_list[3] => _("Dark Cyan"),
        $color_list[4] => _("Dark Magenta"),
        $color_list[5] => _("Light Blue"),
        $color_list[6] => _("Light Green"),
        $color_list[7] => _("Light Yellow"),
        $color_list[8] => _("Light Cyan"),
        $color_list[9] => _("Light Magenta"),
        $color_list[10] => _("Dark Gray"),
        $color_list[11] => _("Medium Gray"),
        $color_list[12] => _("Light Gray"),
        $color_list[13] => _("White") );

    echo addSelect('newcolor_choose', $selops, $selected_i, TRUE);
    echo "<br />\n";

    echo '         '.addRadioBox('color_type', $selected_input, 2).
        ' &nbsp;'. _($mod_strings['LBL_OTHER'].":") .
        addInput('newcolor_input',
            (($selected_input && isset($theid)) ? $message_highlight_list[$theid]['color'] : ''),
            '7');
    echo _("Ex: 63aa7f")."<br />\n";
    echo "      </td>\n";
    echo "   </tr>\n";

    # Show grid of color choices
    echo html_tag( 'tr', '', '', $color[0] ) . "\n";
    echo html_tag( 'td', '', 'left', '', 'colspan="2"' );
    echo html_tag( 'table', '', 'center', '', 'border="0" cellpadding="2" cellspacing="1"' ) . "\n";

    for($x = 0; $x < 5; $x++) {
        echo html_tag( 'tr' ) . "\n";
        for($y = 0; $y < 19; $y++) {
        $gridindex = "$y,$x";
        $gridcolor = $new_color_list[$gridindex];
        echo html_tag( 'td', addRadioBox('color_type', ($gridcolor == $current_color), '#'.$gridcolor),
            'left', $gridcolor, 'colspan="2"' );
        }
        echo "</tr>\n";
    }
    echo "</table>\n";
    echo "</td></tr>\n";

    echo html_tag( 'tr', html_tag( 'td', '<small><small>&nbsp;</small></small>', 'left' ) ) . "\n";
    echo html_tag( 'tr', '', '', $color[0] ) . "\n";
    echo html_tag( 'td', '', 'center', '', 'colspan="2"' ) . "\n";
    echo "         <select name=\"match_type\">\n";
    oh_opt( 'from',
            (isset($theid)?$message_highlight_list[$theid]['match_type'] == 'from':1),
            _($mod_strings['LBL_FROM']) );
    oh_opt( 'to',
            (isset($theid)?$message_highlight_list[$theid]['match_type'] == 'to':0),
            _($mod_strings['LBL_TO']) );
    oh_opt( 'cc',
            (isset($theid)?$message_highlight_list[$theid]['match_type'] == 'cc':0),
            _($mod_strings['LBL_CC']) );
    oh_opt( 'to_cc',
            (isset($theid)?$message_highlight_list[$theid]['match_type'] == 'to_cc':0),
            _($mod_strings['LBL_TO_OR_CC']) );
    oh_opt( 'subject',
            (isset($theid)?$message_highlight_list[$theid]['match_type'] == 'subject':0),
            _($mod_strings['LBL_SUBJECT']) );
    echo "         </select>\n";
    echo '<b>' . _($mod_strings['LBL_MATCHES']) . ':</b> ';
    if ($theaction == 'edit' && isset($theid) && isset($message_highlight_list[$theid]['value']))
        $disp = $message_highlight_list[$theid]['value'];
    else
        $disp = '';
    echo '         '.addInput('value', $disp, 40);
    echo "        </td>\n";
    echo "   </tr>\n";
    echo "</table>\n";
    echo '<center><input type="submit" value="' . _($mod_strings['LBL_SUBMIT']) . "\" /></center>\n";
    echo "</form>\n";
}
do_hook('options_highlight_bottom');
?>
</table></body></html>

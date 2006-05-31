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

include("language/en_us.lang.php");
global $mod_strings;

function GetForgotPasswordUI($mod_strings,$mail_send_message='')
{
	$list .= '<br><br>';
	$list .= '<link rel="stylesheet" type="text/css" href="css/style.css">';
        $list .= '<form name="forgot_password" action="index.php" method="post">';
        $list .= '<input type="hidden" name="email_id">';
        $list .= '<input type="hidden" name="param" value="forgot_password">';
        $list .= '<table width="50%" border="0" cellspacing="2" cellpadding="2" align="center">';
	$list .= '<tr><td class="detailedViewHeader" nowrap colspan=2 ><b>'.$mod_strings['LBL_FORGOT_LOGIN'].'</b></td></tr>';
	$list .= '<tr><td colspan=2 class="dvtCellInfo">&nbsp;</td></tr>';
	if($mail_send_message != '')
	{
		$list .= '<tr><td nowrap colspan=2 class="dvtCellInfo">'.$mail_send_message.'</td></tr>';
	}
        $list .= '<tr><td nowrap class="dvtCellLabel" align="right">'.$mod_strings['LBL_YOUR_EMAIL'].'</td>';
        $list .= '<td class="dvtCellInfo"><input class="detailedViewTextBox" type="text" name="email_id" STYLE="width:185px;" MAXLENGTH="80" VALUE=""/></td>';
	$list .= '<tr><td>&nbsp;</td><td><input type="submit" value="'.$mod_strings['LBL_SEND_PASSWORD'].'"></td></tr>';
        $list .= '</table></form>';

	return $list;
}
if($_REQUEST['mail_send_message'] != '')
{
	$mail_send_message = explode("@@@",$_REQUEST['mail_send_message']);

	if($mail_send_message[0] == 'true')
	{
		$list = '<link rel="stylesheet" type="text/css" href="css/style.css">';
		$list .= '<table width="100%" border="0" cellspacing="2" cellpadding="2" align="center">';
		$list .= '<tr><td class="detailedViewHeader" nowrap colspan=2 align="center"><b>';
		$list .= $mod_strings['LBL_FORGOT_LOGIN'].'</b></td></tr>';
		$list .= '<br><tr><td>&nbsp;</td></tr>';
		$list .= '<tr><td class="dvtCellInfo">'.$mail_send_message[1].'</td></tr>';
		$list .= '<br><tr><td>&nbsp;</td></tr>';
		$list .= '<tr><td align="right"><a href="login.php?close_window=true"> '.$mod_strings['LBL_CLOSE'].'</a>';
		$list .= '</td></tr></table>';

		echo $list;
	}
	elseif($mail_send_message[0] == 'false')
	{
		$list = GetForgotPasswordUI($mod_strings,$mail_send_message[1]);
		echo $list;
	}
}
elseif($_REQUEST['param'] == 'forgot_password')
{
	$list = GetForgotPasswordUI($mod_strings);
        echo $list;
}
elseif($_REQUEST['param'] == 'sign_up')
{
	echo 'Sign Up..........';
}




?>

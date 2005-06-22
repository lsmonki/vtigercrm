<?php
include("language/en_us.lang.php");
global $mod_strings;

function GetUI($mod_strings,$mail_send_message='')
{
	$list .= '<br><br>';
	$list .= '<link rel="stylesheet" type="text/css" href="customerportal.css">';
        $list .= '<form name="forgot_password" action="general.php" method="post">';
        $list .= '<input type="hidden" name="email_id">';
        $list .= '<input type="hidden" name="param" value="forgot_password">';
        $list .= '<table width="50%" border="0" cellspacing="2" cellpadding="2" align="center">';
	$list .= '<tr><td class="pageTitle uline" nowrap colspan=2 align="left">'.$mod_strings['LBL_FORGOT_LOGIN'].'</td></tr>';
	$list .= '<tr><td>&nbsp;</td></tr>';
	if($mail_send_message != '')
	{
		$list .= '<tr><td nowrap colspan=2>'.$mail_send_message.'</td></tr>';
	}
        $list .= '<tr><td nowrap><div align="right">'.$mod_strings['LBL_YOUR_EMAIL'].'</strong></div></td>';
        $list .= '<td><input type="text" name="email_id" STYLE="width:185px;" MAXLENGTH="80" VALUE=""/></td>';
	$list .= '<tr><td>&nbsp;</td><td><input class="button" type="submit" value="'.$mod_strings['LBL_SEND_PASSWORD'].'"></td></tr>';
        $list .= '</table></form>';

	return $list;
}
if($_REQUEST['mail_send_message'] != '')
{
	$mail_send_message = explode("@@@",$_REQUEST['mail_send_message']);

	if($mail_send_message[0] == 'true')
	{
		$list = '<link rel="stylesheet" type="text/css" href="customerportal.css">';
		$list .= '<table width="100%" border="0" cellspacing="2" cellpadding="2" align="center">';
		$list .= '<tr><td class="pageTitle uline" nowrap colspan=2 align="center">';
		$list .= $mod_strings['LBL_FORGOT_LOGIN'].'</td></tr>';
		$list .= '<br><tr><td>&nbsp;</td></tr>';
		$list .= '<tr><td>'.$mail_send_message[1].'</td></tr>';
		$list .= '<br><tr><td>&nbsp;</td></tr>';
		$list .= '<tr><td align="right"><a href="cp_index.php?close_window=true"> '.$mod_strings['LBL_CLOSE'].'</a>';
		$list .= '</td></tr></table>';

		echo $list;
	}
	elseif($mail_send_message[0] == 'false')
	{
		$list = GetUI($mod_strings,$mail_send_message[1]);
		echo $list;
	}
}
elseif($_REQUEST['param'] == 'forgot_password')
{
	$list = GetUI($mod_strings);
        echo $list;
}
elseif($_REQUEST['param'] == 'sign_up')
{
	echo 'Sign Up..........';
}




?>

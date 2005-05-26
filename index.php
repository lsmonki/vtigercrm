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


include("PortalConfig.php");
include("language/en_us.lang.php");
?>


<HTML>
<HEAD>
<title></title>
<link rel="stylesheet" type="text/css" href="customerportal.css">
</head>
<body>
<br>
<br>
<br>
<br>
<table width="50%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr> 
    <td width="5" height="5"><img src="images/cp_top_start.gif" width="5" height="5"></td>
    <td class="topBand" width="100%" height="5"><img src="images/spacer.gif"></td>
    <td width="5" height="5"><div align="right"><img src="images/cp_top_end.gif" width="5" height="5"></td>
  </tr>
  <tr> 
    <td colspan="3" height="35" class="topBand">&nbsp;&nbsp;<img src="images/cp_logo.gif"></td>
  </tr>
  <tr> 
    <td colspan="3" height="10" class="tabBg"><img src="images/spacer.gif"></td>
  </tr>
</table>
<table width="50%" border="0" cellspacing="0" cellpadding="0" align="center" class="uline">
  <tr> 
    <td class="pageTitle uline"><?php  echo $mod_strings['LBL_LOGIN']?></td>
  </tr>
  <tr>

  <tr><td>	<?php if($_REQUEST['error_msg'] != '') echo $_REQUEST['error_msg']; ?>    </td></tr>

    <td style="padding:10px"><FORM NAME="login" ACTION="<?php echo $Authenticate_Path ?>/CustomerAuthenticate.php" METHOD="POST" onSubmit="handleLogin();">
        <INPUT TYPE="hidden" NAME="un" VALUE="">
        <INPUT TYPE="hidden" NAME="width" VALUE="">
        <INPUT TYPE="hidden" NAME="height" VALUE="">
        <INPUT TYPE="hidden" NAME="orgId" VALUE="00D300000000MDF">
        <INPUT TYPE="hidden" NAME="startURL" VALUE="">
        <INPUT TYPE="hidden" NAME="jse" ID="jse" VALUE="0">
        <table width="100%" border="0" cellspacing="2" cellpadding="2">
          <tr> 
            <td><div align="right"><strong><?php  echo $mod_strings['LBL_USER_NAME']?></strong></div></td>
            <td><INPUT TYPE="text" NAME="username" STYLE="width:185px;" MAXLENGTH="80" VALUE=""></td>
          </tr>
          <tr> 
            <td><div align="right"><strong><?php  echo $mod_strings['LBL_PASSWORD']?></strong></div></td>
            <td><INPUT TYPE="password" NAME="pw" STYLE="width:185px;" MAXLENGTH="80"></td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td><input class="button" type="submit" value="<?php  echo $mod_strings['LBL_LOGIN']?>"></td>
          </tr>
        </table>
      </form></td>
  </tr>
</table>
</body>
</html>

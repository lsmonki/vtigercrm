<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/ColorPicker2.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/slider.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/prototype_fade.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/effectspack.js"></script>
<style type="text/css">@import url(themes/blue/style.css);</style>


<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
        {include file='SettingsMenu.tpl'}
<td width="75%" valign="top">

<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">

<tr>
        <td valign="top"><img src="{$IMAGE_PATH}showPanelTopLeft.gif"></td>
        <td class="showPanelBg" valign="top" width="100%">
                <div class="small" style="padding: 10px;">
                        <span class="lvtHeaderText">My Preferences </span> <br>
                        <hr noshade="noshade" size="1"><br>

  		<form action="index.php" method="post" name="EditView" id="form">
			<input type="hidden" name="module" value="Settings">
		  	<input type="hidden" name="action">
  			<input type="hidden" name="server_type" value="email">
			<input type="hidden" name="record" value="{$RECORD_ID}">
		        <input type="hidden" name="edit" value="{$EDIT}">
			<input type="hidden" name="return_module" value="Settings">
			<input type="hidden" name="return_action" value="index">
	</tr>	
		
		<table align="center" border="0" cellpadding="0" cellspacing="0" width="95%">
                  <tr>

                        <td>
                            <table class="small" border="0" cellpadding="3" cellspacing="0" width="100%">
                                <tr>
                                    <td class="dvtTabCache" style="width: 10px;" nowrap="nowrap">&nbsp;</td>
                                    <td width="75" align="center" nowrap="nowrap" class="dvtUnSelectedCell"><a href="index.php?module=Users&action=DetailView&record=1"><b>My Details</a></b></td>
                                    <td class="dvtSelectedCell" style="width: 100px;" align="center" nowrap="nowrap"><b>My Mail Server Details </b></td>
		                    <td class="dvtTabCache" nowrap="nowrap">&nbsp;</td>
                                </tr>

                            </table>
                        </td>
                </tr>
                <tr>
                        <td align="left" valign="top">

<!-- General Contents for Mail Server Starts Here -->

<table class="dvtContentSpace" border="0" cellpadding="3" cellspacing="0" width="100%">
<tr>
   <td align="left">
     <table border="0" cellpadding="0" cellspacing="0" width="100%">
       <tr>
          <td style="padding: 10px;"><table width="100%"  border="0" cellspacing="0" cellpadding="5">
       <tr>
           <td colspan="3" class="detailedViewHeader"><b>Email ID</b></td>
       </tr>
       <tr>
          <td class="dvtCellLabel" align="right" width="33%">{$MOD.LBL_DISPLAY_NAME}</td>
          <td class="dvtCellInfo" width="33%"><input type="text" name="displayname" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="{$DISPLAYNAME}"/></td>
          <td class="dvtCellInfo" width="34%">(example : John Fenner) </td>
       </tr>
       <tr>
          <td class="dvtCellLabel" align="right"><FONT class="required" color="red">{$APP.LBL_REQUIRED_SYMBOL}</FONT> {$MOD.LBL_EMAIL_ADDRESS} </td>
          <td class="dvtCellInfo"><input type="text" name="email" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" value="{$EMAIL}"/></td>
          <td class="dvtCellInfo">( example : johnfenner@mailserver.com )</td>
       </tr>
       <tr><td colspan="3" >&nbsp;</td></tr>
       <tr>
          <td colspan="3"  class="detailedViewHeader"><b>Mail Server Settings</b></td>
       </tr>
       <tr>
          <td class="dvtCellLabel" align="right"><FONT class="required" color="red">{$APP.LBL_REQUIRED_SYMBOL}</FONT>Mail Server Name or IP </td>
          <td class="dvtCellInfo"><input type="text" name="mail_servername" value="{$SERVERNAME}"  class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'"/></td>
          <td class="dvtCellInfo">&nbsp;</td>
       </tr>
       <tr>
           <td class="dvtCellLabel" align="right"><FONT class="required" color="red">{$APP.LBL_REQUIRED_SYMBOL}</FONT>User Name</td>
           <td class="dvtCellInfo"><input type="text" name="server_username" value="{$SERVERUSERNAME}"  class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'"/></td>
           <td class="dvtCellInfo">&nbsp;</td>
       </tr>
       <tr>
           <td class="dvtCellLabel" align="right"><FONT class="required" color="red">{$APP.LBL_REQUIRED_SYMBOL}</FONT>Password</td>
           <td class="dvtCellInfo"><input type="password" name="server_password" value="{$SERVER_PASSWORD}"  class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'"/></td>
           <td class="dvtCellInfo">&nbsp;</td>
       </tr>
       <tr>
           <td colspan="3" class="dvtCellInfo">&nbsp;</td>
       </tr>
       <tr>
           <td class="dvtCellLabel" align="right">Mail Protocol</td>
           <td class="dvtCellInfo">
		<input type="radio" name="mailprotocol" value="imap" {$IMAP}/>&nbsp;{$MOD.LBL_IMAP} 
		<input type="radio" name="mailprotocol" value="pop3" {$POP3}/>&nbsp;{$MOD.LBL_POP} 
		<input type="radio" name="mailprotocol" value="imap2" {$IMAP2}/>&nbsp;IMAP2
		<input type="radio" name="mailprotocol" value="IMAP4" {$IMAP4}/>&nbsp;IMAP4
	   </td>	
           <td class="dvtCellInfo">&nbsp;</td>
        </tr>
        <tr>
           <td class="dvtCellLabel" align="right">SSL Options </td>
           <td class="dvtCellInfo">
		<input type="radio" name="ssltype" value="notls" {$NOTLS} />&nbsp;No TLS
		<input type="radio" name="ssltype" value="tls" {$TLS} />&nbsp; TLS </td>
           <td class="dvtCellInfo">&nbsp;</td>
       </tr>
       <tr>
           <td class="dvtCellLabel" align="right">Certificate Validations </td>
           <td class="dvtCellInfo">
		<input type="radio" name="sslmeth" value="validate-cert" {$VALIDATECERT} />&nbsp;Validate SSL Cert
		<input type="radio" name="sslmeth" value="novalidate-cert" {$NOVALIDATECERT} />&nbsp;Don't Validate SSL Cert
	   </td>	
           <td class="dvtCellInfo">&nbsp;</td>
       </tr>
       <tr>
           <td class="dvtCellLabel" align="right">Refresh Timeout </td>
           <td class="dvtCellInfo">
		<select name="box_refresh">
			<option value="60000">1 minute
			<option value="120000">2 minutes
			<option value="240000">3 minutes
			<option value="360000">4 minutes
			<option value="480000">5 minutes
		</select>
	   </td>
           <td class="dvtCellInfo">&nbsp;</td>
       </tr>
       <tr>
           <td class="dvtCellLabel" align="right"><FONT class="required" color="red">{$APP.LBL_REQUIRED_SYMBOL}</FONT>Email's per Page </td>
           <td class="dvtCellInfo"><input type="text" name="mails_per_page" value="{$MAILS_PER_PAGE}" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'"/></td>
           <td class="dvtCellInfo">&nbsp;</td>
       </tr>
       <tr><td colspan="3" style="border-bottom:1px dashed #CCCCCC;">&nbsp;</td></tr>
       <tr>
           <td colspan="3" align="center">
		<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="classBtn" onclick="this.form.action.value='SaveMailAccount'; return verify_data(EditView)" type="submit" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " >
			&nbsp;&nbsp;
	        <input title="{$APP.LBL_CANCEL_BUTTON_LABEL}>" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="classBtn" onclick="this.form.action.value='ListMailAccount'; this.form.module.value='Settings'; this.form.return_action.value='index'; this.form.return_module.value='Settings';" type="submit" name="button" value="{$APP.LBL_CANCEL_BUTTON_LABEL}"></td>
           </td>
       </tr>
       <tr><td colspan="3" style="border-top:1px dashed #CCCCCC;">&nbsp;</td></tr>
       </table>
	   </td>
            </tr>

     </table></td>
     </tr>
</table>
</td></tr>
</table>
</form>
</td></tr>
</table>
</td></tr>
</table>

{$JAVASCRIPT}
	{include file="SettingsSubMenu.tpl"}					

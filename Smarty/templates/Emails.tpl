{*<!--
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is:  vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
  *
 ********************************************************************************/
-->*}
<!--  USER  SETTINGS PAGE STARTS HERE -->
<script language="javascript">
function ajaxSaveResponse(response)
{ldelim}
	hide("status");
	document.getElementById("email_con").innerHTML=response.responseText;
{rdelim}
function ajaxgetResponse(response)
{ldelim}
	hide("status");
	document.getElementById("EmailDetails").innerHTML=response.responseText;
{rdelim}
function setSubject(subject)
{ldelim}
document.getElementById("subjectsetter").innerHTML=subject
{rdelim}
function getEmailContents(id)
{ldelim}
	show("status");
	var ajaxObj = new Ajax(ajaxgetResponse);
	var urlstring ="module=Emails&action=EmailsAjax&file=DetailView&mode=ajax&record="+id;
	ajaxObj.process("index.php?",urlstring);
{rdelim}
{literal}

function ajaxDelResponse(response)
{
	hide("status");
	document.getElementById('EmailDetails').innerHTML = '<table valign="top" border="0" cellpadding="0" cellspacing="0" width="100%"><tbody><tr><td class="forwardBg"><table border="0" cellpadding="0" cellspacing="0" width="100%"><tbody><tr><td colspan="2">&nbsp;</td></tr></tbody></table></td></tr><tr><td style="padding-top: 10px;" bgcolor="#ffffff" height="300" valign="top"></td></tr></tbody></table>';
	document.getElementById("subjectsetter").innerHTML='';
	document.getElementById("email_con").innerHTML=response.responseText;
}

{/literal}
function ShowFolders(folderid)
{ldelim}
    var ajaxObj = new Ajax(ajaxDelResponse);
	gFolderid = folderid;
	getObj('search_text').value = '';
	switch(folderid)
	{ldelim}
		case 1:
			getObj('mail_fldrname').innerHTML = '{$MOD.LBL_ALLMAILS}';
			break;
		case 2:
			getObj('mail_fldrname').innerHTML = '{$MOD.LBL_TO_CONTACTS}';
			break;
		case 3:
			getObj('mail_fldrname').innerHTML = '{$MOD.LBL_TO_ACCOUNTS}';
			break;
		case 4:
			getObj('mail_fldrname').innerHTML = '{$MOD.LBL_TO_LEADS}';
			break;
		case 5:
			getObj('mail_fldrname').innerHTML = '{$MOD.LBL_TO_USERS}';
			break;
		case 6:
			getObj('mail_fldrname').innerHTML = '{$MOD.LBL_QUAL_CONTACT}';
	{rdelim}
    var urlstring ="module=Emails&ajax=true&action=EmailsAjax&file=ListView&folderid="+folderid;
   	ajaxObj.process("index.php?",urlstring);
{rdelim}
</script>
<script language="JavaScript" type="text/javascript" src="modules/Emails/Email.js"></script>
<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
	<tr>
		<td class="showPanelBg" valign="top" width="95%"  style="padding-left:20px; "><br />
        	        <span class="lvtHeaderText">{$CATEGORY} &gt; {$MODULE} </span>
            <hr noshade="noshade" size="1" />
		</td>
		<td width="5%" class="showPanelBg">&nbsp;</td>
	</tr>
	<tr>
		<td width="95%" style="padding-left:20px;" valign="top">
			<!-- module Select Table -->
			<table width="100%"  border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="7" height="6" style="font-size:1px;font-family:Arial, Helvetica, sans-serif;"><img src="{$IMAGE_PATH}top_left.jpg" align="top"  /></td>
					<td bgcolor="#EBEBEB" style="font-size:1px;font-family:Arial, Helvetica, sans-serif;height:6px;"></td>
					<td width="8" height="6" style="font-size:1px;font-family:Arial, Helvetica, sans-serif;"><img src="{$IMAGE_PATH}top_right.jpg" width="8" height="6" align="top" /></td>
				</tr>
				<tr>
					<td bgcolor="#EBEBEB" width="7"></td>
					<td bgcolor="#ECECEC" style="padding-left:10px;height:20px;vertical-align:middle;">
					<form name="massdelete" method="POST">
						<table width="100%"  border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td colspan="3" style="vertical-align:middle;">
									<table width="100%" cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td width="10%" >
											<img src="{$IMAGE_PATH}check_mail.gif" align="absmiddle" />
						&nbsp;<a href="#" class="webMnu" >{$MOD.LBL_CHK_MAIL}</a>
											</td>
											<td width="10%">
											<img src="{$IMAGE_PATH}compose.gif" align="absmiddle" />
						&nbsp;<a href="javascript:;" onClick="OpenCompose('','create');" class="webMnu">{$MOD.LBL_COMPOSE}</a>
											</td>
											<td width="10%">
											<img src="{$IMAGE_PATH}webmail_settings.gif" align="absmiddle" />
						&nbsp;<a href="index.php?module=Settings&action=AddMailAccount&record={$USERID}" class="webMnu">{$MOD.LBL_SETTINGS}</a>
											</td>
											<td><img src="{$IMAGE_PATH}webmail_header.gif" align="right"/></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
                    <td width="28%" bgcolor="#949494"><span class="subHdr"><b>{$MOD.LBL_EMAIL_FOLDERS}</b></span> </td>
								<td width="2%">&nbsp;</td>
					 <td width="60%" class="subHdr"><span id="mail_fldrname"><strong>{$MOD.LBL_ALLMAILS}</strong></span></td>
							</tr>
							<tr>
								<td rowspan="6" valign="top" bgcolor="#FFFFFF" style="padding:10px; ">
							<img src="{$IMAGE_PATH}webmail_root.gif" align="absmiddle" />&nbsp;<b class="txtGreen">{$MOD.LBL_INBOX}</b>
								<ul style="list-style-type:none;">
									<li><img src="{$IMAGE_PATH}webmail_downarrow.gif" align="absmiddle" />&nbsp;&nbsp;
										<a href="javascript:;" onClick="ShowFolders(6)" class="webMnu">{$MOD.LBL_QUAL_CONTACT}</a>&nbsp;<b></b>
									</li>
									<li><img src="{$IMAGE_PATH}webmail_downarrow.gif" align="absmiddle" />&nbsp;&nbsp;
									<a href="index.php?module=Webmails&action=index" class="webMnu">{$MOD.LBL_MY_MAILS}</a>&nbsp;<b></b>
									</li>
								</ul><br />
							<img src="{$IMAGE_PATH}webmail_root.gif" align="absmiddle" />&nbsp;<b class="txtGreen">{$MOD.LBL_SENT_MAILS}</b>
								<ul style="list-style-type:none;">
									<li><img src="{$IMAGE_PATH}webmail_uparrow.gif" align="absmiddle" />&nbsp;&nbsp;
									<a href="javascript:;" onClick="ShowFolders(1)" class="webMnu">{$MOD.LBL_ALLMAILS}</a>&nbsp;<b></b>
									<li><img src="{$IMAGE_PATH}webmail_uparrow.gif" align="absmiddle" />&nbsp;&nbsp;
									<a href="javascript:;" onClick="ShowFolders(2)" class="webMnu">{$MOD.LBL_TO_CONTACTS}</a>&nbsp;<b></b>
									</li>
									<li><img src="{$IMAGE_PATH}webmail_uparrow.gif" align="absmiddle" />&nbsp;&nbsp;
									<a href="javascript:;" onClick="ShowFolders(3)" class="webMnu">{$MOD.LBL_TO_ACCOUNTS}</a>&nbsp;<b></b>
									</li>
									<li><img src="{$IMAGE_PATH}webmail_uparrow.gif" align="absmiddle" />&nbsp;&nbsp;
									<a href="javascript:;" onClick="ShowFolders(4)" class="webMnu">{$MOD.LBL_TO_LEADS}</a>&nbsp;
									</li>
									<li><img src="{$IMAGE_PATH}webmail_uparrow.gif" align="absmiddle" />&nbsp;&nbsp;
									<a href="javascript:;" onClick="ShowFolders(5)" class="webMnu">{$MOD.LBL_TO_USERS}</a>&nbsp;
									</li>
								</ul><br />
								</td>
								<td>&nbsp;</td>
								
								<td class="delBg">
									<table width="100%"  border="0" cellspacing="0" cellpadding="0">
									<input name="idlist" type="hidden">
										<tr>
											<td width="25%"><input type="button" name="Button2" value=" {$APP.LBL_DELETE_BUTTON}"  class="classWebBtn" onClick="return massDelete();"/> &nbsp;
											</td>
											<td width="75%" align="right">
							<font color="#000000">{$APP.LBL_SEARCH}</font>&nbsp;<input type="text" name="search_text" id="search_text" class="importBox" onkeyUp="Searchfn();">&nbsp;
											<select name="search_field" id="search_field" onChange="Searchfn();" class="importBox">
											<option value='subject'>{$MOD.LBL_IN_SUBJECT}</option>
											<option value='user_name'>{$MOD.LBL_IN_SENDER}</option>
											<option value='join'>{$MOD.LBL_IN_SUBJECT_OR_SENDER}</option>
											</select>&nbsp;
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td style="padding:1px;" align="left">
									<div id="email_con">
									{include file="EmailContents.tpl"}
									</div>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td height="5"></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td class="mailHdr" id="subjectsetter">&nbsp;</td>
							</tr>
							<tr>
								<td>&nbsp;</td>	
								<td valign="top">
									<div id="EmailDetails">
									{include file="EmailDetails.tpl"}
									</div>
								</td>
							</tr>
						</table>
						</form>
					</td>
					<td bgcolor="#EBEBEB" width="8"></td>
				</tr>
				<tr>
					<td width="7" height="8" style="font-size:1px;font-family:Arial, Helvetica, sans-serif;"><img src="{$IMAGE_PATH}bottom_left.jpg" align="bottom"  /></td>
					<td bgcolor="#ECECEC" height="8" style="font-size:1px;" ></td>
					<td width="8" height="8" style="font-size:1px;font-family:Arial, Helvetica, sans-serif;"><img src="{$IMAGE_PATH}bottom_right.jpg" align="bottom" /></td>
				</tr>
			</table><br />
		</td>
		<td>&nbsp;</td>
	</tr>
</table>
<!-- END -->
<script>
function OpenCompose(id,mode) 
{ldelim}
	switch(mode)
		{ldelim}
		case 'edit':
			url = 'index.php?module=Emails&action=EmailsAjax&file=EditView&record='+id;
			break;
		case 'create':
			url = 'index.php?module=Emails&action=EmailsAjax&file=EditView';
			break;
		case 'forward':
			url = 'index.php?module=Emails&action=EmailsAjax&file=EditView&record='+id+'&forward=true';
			break;
		{rdelim}
	openPopUp('xComposeEmail',this,url,'createemailWin',820,652,'menubar=no,toolbar=no,location=no,status=no,resizable=no');
{rdelim}
</script>

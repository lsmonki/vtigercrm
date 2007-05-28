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
                {include file='Buttons_List1.tpl'} 
<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
	<tr>
		 <td class="showPanelBg" valign="top" width="95%"  style="padding-left:20px; ">&nbsp;
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
				<td bgcolor="#ECECEC" style="padding-left:10px;height:20px;vertical-align:middle;"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td colspan="3" style="padding:10px;vertical-align:middle;">
							<table width="100%" cellpadding="0" cellspacing="0" border="0">
									<tr>
											<td width="10%">
												<img src="{$IMAGE_PATH}check_mail.gif" align="absmiddle" />
												&nbsp;<a href="javascript:;" class="webMnu" onclick="check_for_new_mail('{$MAILBOX}');" >{$MOD.LBL_CHK_MAIL}</a>
											</td>
											<td width="10%">
												<img src="{$IMAGE_PATH}compose.gif" align="absmiddle" />
												&nbsp;<a href="javascript:;" onclick="OpenCompose('','create');" class="webMnu">{$MOD.LBL_COMPOSE}</a>
											</td>
											<td width="10%">
												<img src="{$IMAGE_PATH}webmail_settings.gif" align="absmiddle" />
												&nbsp;<a href="index.php?module=Settings&action=AddMailAccount&record={$USERID}" class="webMnu">{$MOD.LBL_SETTINGS}</a>
											</td>
											<td width="12%">
												<img src="{$IMAGE_PATH}webmail_settings.gif" align="absmiddle" />
												&nbsp;<a href="javascript:;"  onclick="show_hidden();" class="webMnu">{$MOD.LBL_SHOW_HIDDEN}</a>
											</td>
											<td width="18%">
												<img src="{$IMAGE_PATH}webmail_settings.gif" align="absmiddle" />
												&nbsp;<a href="javascript:;" onclick="runEmailCommand('expunge','0');" class="webMnu">{$MOD.LBL_EXPUNGE_MAILBOX}</a>
											</td>
					<td><img src="{$IMAGE_PATH}webmail_header.gif" align="right"/></td>
									</tr>
							</table>
					</td>
                  </tr>
                  <tr>
                    <td width="22%" bgcolor="#949494" style="overflow:auto"><span class="subHdr"><b>{$MOD.LBL_EMAIL_FOLDERS}</b></span> </td>
                    <td width="2%">&nbsp;</td>
					 <td width="60%" class="subHdr"><span style="float:left"><strong>{$ACCOUNT} &gt; {$MAILBOX} </strong></span> <span style="float:right">{$NAVIGATION}</span></td>
                  </tr>
                  <tr>
                    <td rowspan="6" valign="top" bgcolor="#FFFFFF" style="padding:10px; ">
							<img src="{$IMAGE_PATH}webmail_root.gif" align="absmiddle" />&nbsp;<span onmouseover="show_addfolder();" onmouseout="show_addfolder();" style="cursor:pointer;"><b class="txtGreen">{$MOD.LBL_MY_MAILS}</b>&nbsp;&nbsp;<span id="folderOpts" style="position:absolute;display:none">Add Folder[X]</span></span>

							<ul style="list-style-type:none;">

                                                  {foreach item=row from=$BOXLIST}
                                                        {foreach item=row_values from=$row}
                                				{$row_values}
                                                        {/foreach}
                                                  {/foreach}
							</ul><br />

							<img src="{$IMAGE_PATH}webmail_root.gif" align="absmiddle" />&nbsp;<b class="txtGreen">{$MOD.LBL_SENT_MAILS}</b>
							<ul style="list-style-type:none;">
								<li><img src="{$IMAGE_PATH}webmail_uparrow.gif" align="absmiddle" />&nbsp;&nbsp;
										<a href="index.php?module=Emails&action=ListView" class="webMnu">{$MOD.LBL_TO_LEADS}</a>&nbsp;<b></b>
								</li>
								<li><img src="{$IMAGE_PATH}webmail_uparrow.gif" align="absmiddle" />&nbsp;&nbsp;
										<a href="index.php?module=Emails&action=ListView" class="webMnu">{$MOD.LBL_TO_ACCOUNTS}</a>&nbsp;<b></b>
								</li>
								<li><img src="{$IMAGE_PATH}webmail_uparrow.gif" align="absmiddle" />&nbsp;&nbsp;
										<a href="index.php?module=Emails&action=ListView" class="webMnu">{$MOD.LBL_TO_CONTACTS}</a>&nbsp;
								</li>
							</ul><br />
							<img src="{$IMAGE_PATH}webmail_root.gif" align="absmiddle" />&nbsp;<b class="txtGreen">{$MOD.LBL_TRASH}</b>
							<ul style="list-style-type:none;">
								<li><img src="{$IMAGE_PATH}webmail_trash.gif" align="absmiddle" />&nbsp;&nbsp;
										<a href="#" class="webMnu">{$MOD.LBL_JUNK_MAILS}</a>&nbsp;<b></b>
								</li>
							</ul>

					</td>
                    <td>&nbsp;</td>
                    <td class="delBg"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="45%">
			<input type="button" name="mass_del" value=" {$MOD.LBL_DELETE} "  class="crmbutton small delete" onclick="mass_delete();"/>
			<input type="button" name="Button2" value=" {$MOD.LBL_MOVE_TO} "  class="crmbutton small edit" onclick="move_messages();"/> {$FOLDER_SELECT}
                        </td>
			{if $DEGRADED_SERVICE eq 'false'}
                        <td width="75%" align="right">
							<font color="#000000">{$APP.LBL_SEARCH}</font>&nbsp;<input type="text" name="srch" class="importBox" id="search_input"/>&nbsp;
							<select name="optionSel" class="importBox" id="search_type"><option selected value="SUBJECT">in Subject</option><option value="BODY">in Body</option><option value="TO">in To:</option><option value="CC">in CC:</option><option value="BCC">in BCC:</option><option value="FROM">in From:</option></select>&nbsp;
							<input type="button" name="find" value=" {$APP.LBL_FIND_BUTTON} " class="crmbutton small create" onclick="search_emails();" />
			</td>
			{/if}
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td style="padding:1px;" align="left">
						<div id="rssScroll">
						<table class="rssTable" cellspacing="0" cellpadding="0" border="0" width="100%" id="message_table">
	                      <tr>
    	                    <th><input type="checkbox" name="checkbox" value="checkbox"  onclick="select_all();"/></th>
							{foreach item=element from=$LISTHEADER}
								{$element}
							{/foreach}
                    	  </tr>
						  {foreach item=row from=$LISTENTITY}
						  	{foreach item=row_values from=$row}
                	        				{$row_values}
							{/foreach}
						  {/foreach}
                    </table>
					</div>
				</td>
             </tr>
			 <tr>
			 	<td></td>
				<td height="5"></td>
			 </tr>
			 <tr>
			   <td>&nbsp;</td>
			   </tr>
			 <tr style="visibility:hidden" class="previewWindow">
			   <td>&nbsp;</td>
			   <td class="forwardBg">
			   		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td width="75%">
						  <span id="qualify_button"><input type="button" name="Qualify2" value=" {$MOD.LBL_QUALIFY_BUTTON} " class="crmbutton small create" /></span>&nbsp;
						  <span id="reply_button"><input type="button" name="reply" value=" {$MOD.LBL_REPLY_TO_SENDER} " class="crmbutton small edit" /></span>&nbsp;
						  <span id="reply_button_all"><input type="button" name="reply" value=" {$MOD.LBL_REPLY_ALL} " class="crmbutton small edit" /></span>&nbsp;
						  <span id="forward_button"><input type="button" name="forward" value=" {$MOD.LBL_FORWARD_BUTTON} " class="crmbutton small edit" /></span>&nbsp;
						  <span id="download_attach_button"><input type="button" name="download" value=" {$MOD.LBL_DOWNLOAD_ATTCH_BUTTON} " class="crmbutton small save" /></span>
						</td>
						<td width="25%" align="right"><span id="delete_button"><input type="button" name="Button" value=" {$APP.LBL_DELETE_BUTTON} "  class="crmbutton small delete" /></span></td>
					  </tr>
					</table>
				</td>
			   </tr>
			 <tr style="visibility:hidden" class="previewWindow">
			   <td>&nbsp;</td>
			   <td height="300" bgcolor="#FFFFFF" valign="top" style="padding-top:10px;">
			   		<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr><td width="20%" align="right"><b>{$MOD.LBL_FROM}</b></td><td id="from_addy">&nbsp;</td></tr>
						<tr><td width="20%" align="right"><b>{$MOD.LBL_TO}</b></td><td id="to_addy">&nbsp;</td></tr>
						<tr><td align="right"><b>{$MOD.LBL_SUBJECT}</b></td><td id="webmail_subject"></td></tr>
						<tr><td align="right"><b>{$MOD.LBL_DATE}</b></td><td id="webmail_date"></td>
							<td id="full_view"><a href="javascript:;">Full Window View</a></td></tr>
						<tr><td align="right" style="border-bottom:1px solid #666666;" colspan="3">&nbsp;</td></tr>
					</table>
			   <span id="body_area" style="width:95%">&nbsp;</span></td>
			   </tr>
                </table>
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
<script>
function OpenCompose(id,mode)
{ldelim}
        switch(mode)
                {ldelim}
                case 'edit':
                        url = 'index.php?module=Webmails&action=EditView&record='+id;
                        break;
                case 'create':
			url = 'index.php?module=Emails&action=EmailsAjax&file=EditView';
                        break;
                case 'forward':
                        url = 'index.php?module=Emails&action=EmailsAjax&mailid='+id+'&forward=true&webmail=true&file=EditView&mailbox={$MAILBOX}';
                        break;
                case 'reply':
                        url = 'index.php?module=Emails&action=EmailsAjax&mailid='+id+'&reply=single&webmail=true&file=EditView&mailbox={$MAILBOX}';
                        break;
                case 'replyall':
                        url = 'index.php?module=Emails&action=EmailsAjax&mailid='+id+'&reply=all&webmail=true&file=EditView&mailbox={$MAILBOX}';
                        break;
                case 'attachments':
                        url = 'index.php?module=Webmails&action=dlAttachments&mailid='+id+'&mailbox={$MAILBOX}';
                        break;
                case 'full_view':
                        url = 'index.php?module=Webmails&action=DetailView&record='+id+'&mailid='+id+'&mailbox={$MAILBOX}';
                        break;
                {rdelim}
        openPopUp('xComposeEmail',this,url,'createemailWin',830,662,'menubar=no,toolbar=no,location=no,status=no,resizable=yes,scrollbars=yes');
{rdelim}
</script>
<!-- END -->

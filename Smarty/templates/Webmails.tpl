<!--  USER  SETTINGS PAGE STARTS HERE -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
	<tr>
		 <td class="showPanelBg" valign="top" width="95%"  style="padding-left:20px; ">
        	        <span class="lvtHeaderText">{$CATEGORY} -&gt; {$MODULE} </span>
        	        <span id="status" style="display:none"><img src="{$IMAGE_PATH}busy.gif"> &nbsp; Executing Command...</span><hr noshade="noshade" size="1" /><br />
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
							<table width="55%" cellpadding="0" cellspacing="0" border="0">
									<tr>
											<td>
												<img src="{$IMAGE_PATH}check_mail.gif" align="absmiddle" />
												&nbsp;<a href="javascript:window.location = window.location;" class="webMnu" >Check Mail</a>
											</td>
											<td>
												<img src="{$IMAGE_PATH}compose.gif" align="absmiddle" />
												&nbsp;<a href="index.php?module=Emails&action=EditView&return_action=DetailView&parenttab=My Home Page" class="webMnu">Compose</a>
											</td>
											<td>
												<img src="{$IMAGE_PATH}webmail_settings.gif" align="absmiddle" />
												&nbsp;<a href="#" class="webMnu">Settings</a>
											</td>
											<td>
												<img src="{$IMAGE_PATH}webmail_settings.gif" align="absmiddle" />
												&nbsp;<a href="javascript:;"  onclick="window.location = window.location+'&show_hidden=true';" class="webMnu">Show Hidden</a>
											</td>
											<td>
												<img src="{$IMAGE_PATH}webmail_settings.gif" align="absmiddle" />
												&nbsp;<a href="javascript:;" onclick="runEmailCommand('expunge','0');" class="webMnu">Expunge Mailbox</a>
											</td>
									</tr>
							</table>
					</td>
                  </tr>
                  <tr>
                    <td width="28%" bgcolor="#949494"><span class="subHdr"><b>Email Folders</b></span> </td>
                    <td width="2%">&nbsp;</td>
					 <td width="60%" class="subHdr"><strong>Mails in {$ACCOUNT} &gt; {$MAILBOX} </strong></td>
                  </tr>
                  <tr>
                    <td rowspan="6" valign="top" bgcolor="#FFFFFF" style="padding:10px; ">
							<img src="{$IMAGE_PATH}webmail_root.gif" align="absmiddle" />&nbsp;<b>{$ACCOUNT}</b>
							<ul style="list-style-type:none;">


                                                  {foreach item=row from=$BOXLIST}
                                                        {foreach item=row_values from=$row}
                                {$row_values}
                                                        {/foreach}
                                                  {/foreach}



							</ul><br />
					</td>
                    <td>&nbsp;</td>
                    <td class="delBg"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="45%"><input type="button" name="Button2" value=" Move To "  class="classWebBtn"/> {$FOLDER_SELECT}&nbsp;
                        </td>
                        <td width="75%" align="right">
							<font color="#000000">Search:</font>&nbsp;<input type="text" name="srch" class="importBox" />&nbsp;
							<select name="optionSel" class="importBox"><option selected>in Subject</option></select>&nbsp;
							<input type="button" name="find" value=" Find " class="classWebBtn" />
						</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                    <td style="padding:1px;" align="left">
						<div id="rssScroll">
						<table class="rssTable" cellspacing="0" cellpadding="0" border="0">
	                      <tr>
    	                    <th width="5%"><input type="checkbox" name="checkbox" value="checkbox"  /></th>
							{foreach item=element from=$LISTHEADER}
                	        <th>{$element}</th>
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
						  <span id="qualify_button"><input type="button" name="Qualify2" value=" Qualify " class="classWebBtn" /></span>&nbsp;
						  <span id="reply_button"><input type="button" name="reply" value=" Reply to Sender " class="classWebBtn" /></span>&nbsp;
						  <span id="reply_button_all"><input type="button" name="reply" value=" Reply to All " class="classWebBtn" /></span>&nbsp;
						  <input type="button" name="forward" value=" Forward " class="classWebBtn" />&nbsp;
						  <input type="button" name="download" value=" Download Attachments " class="classWebBtn" onclick="fnvshobj(this,'reportLay');"  onmouseout="fninvsh('reportLay')" />
						</td>
						<td width="25%" align="right"><span id="delete_button"><input type="button" name="Button" value=" Delete "  class="classWebBtn" /></span></td>
					  </tr>
					</table>
				</td>
			   </tr>
			 <tr style="visibility:hidden" class="previewWindow">
			   <td>&nbsp;</td>
			   <td height="300" bgcolor="#FFFFFF" valign="top" style="padding-top:10px;">
			   		<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr><td width="20%" align="right"><b>From :</b></td><td id="from_addy">&nbsp;</td></tr>
						<tr><td width="20%" align="right"><b>To :</b></td><td id="to_addy">&nbsp;</td></tr>
						<tr><td align="right"><b>Subject</b></td><td id="webmail_subject"></td></tr>
						<tr><td align="right"><b>Date</b></td><td id="webmail_date"></td></tr>
						<tr><td align="right" style="border-bottom:1px solid #666666;" colspan="2">&nbsp;</td></tr>
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
<!-- END -->


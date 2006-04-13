<!--  USER  SETTINGS PAGE STARTS HERE -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
	<tr>
		 <td class="showPanelBg" valign="top" width="95%"  style="padding-left:20px; "><br />
        	        <span class="lvtHeaderText">Home&gt; Web Mail </span>
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
				<td bgcolor="#ECECEC" style="padding-left:10px;height:20px;vertical-align:middle;"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td colspan="3" style="padding:10px;vertical-align:middle;">
							<table width="35%" cellpadding="0" cellspacing="0" border="0">
									<tr>
											<td>
												<img src="{$IMAGE_PATH}check_mail.gif" align="absmiddle" />
												&nbsp;<a href="#" class="webMnu" >Check Mail</a>
											</td>
											<td>
												<img src="{$IMAGE_PATH}compose.gif" align="absmiddle" />
												&nbsp;<a href="index.php?module=Emails&action=EditView&return_action=DetailView&parenttab=My Home Page" class="webMnu">Compose</a>
											</td>
											<td>
												<img src="{$IMAGE_PATH}webmail_settings.gif" align="absmiddle" />
												&nbsp;<a href="#" class="webMnu">Settings</a>
											</td>
									</tr>
							</table>
					</td>
                  </tr>
                  <tr>
                    <td width="28%" bgcolor="#949494"><span class="subHdr"><b>Email Folders</b></span> </td>
                    <td width="2%">&nbsp;</td>
					 <td width="60%" class="subHdr"><strong>Mails in All mails &gt; Inpox </strong></td>
                  </tr>
                  <tr>
                    <td rowspan="6" valign="top" bgcolor="#FFFFFF" style="padding:10px; ">
							<img src="{$IMAGE_PATH}webmail_root.gif" align="absmiddle" />&nbsp;<b>All Mails</b>
							<ul style="list-style-type:none;">
								<li><img src="{$IMAGE_PATH}webmail_downarrow.gif" align="absmiddle" />&nbsp;&nbsp;
										<a href="#" class="webMnu">Inbox</a>&nbsp;<b>(121)</b>
								</li>
								<li><img src="{$IMAGE_PATH}webmail_uparrow.gif" align="absmiddle" />&nbsp;&nbsp;
										<a href="#" class="webMnu">Sent</a>&nbsp;<b>(21)</b>
								</li>
								<li><img src="{$IMAGE_PATH}webmail_trash.gif" align="absmiddle" />&nbsp;&nbsp;
										<a href="#" class="webMnu">Trash</a>&nbsp;
								</li>
							</ul><br />
							<img src="{$IMAGE_PATH}webmail_root.gif" align="absmiddle" />&nbsp;<b class="txtGreen">Qualified Mails</b>
							<ul style="list-style-type:none;">
								<li><img src="{$IMAGE_PATH}webmail_downarrow.gif" align="absmiddle" />&nbsp;&nbsp;
										<a href="#" class="webMnu">Inbox</a>&nbsp;<b>(221)</b>
								</li>
								<li><img src="{$IMAGE_PATH}webmail_uparrow.gif" align="absmiddle" />&nbsp;&nbsp;
										<a href="#" class="webMnu">Sent</a>&nbsp;<b>(521)</b>
								</li>
								<li><img src="{$IMAGE_PATH}webmail_trash.gif" align="absmiddle" />&nbsp;&nbsp;
										<a href="#" class="webMnu">Trash</a>&nbsp;
								</li>
							</ul>
					</td>
                    <td>&nbsp;</td>
                    <td class="delBg"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="25%"><input type="button" name="Button2" value=" Delete "  class="classWebBtn"/> &nbsp;
                          <input type="button" name="Qualify" value=" Qualify " class="classWebBtn" />
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
						<table class="rssTable" cellspacing="0" cellpadding="0">
	                      <tr>
    	                    <th width="5%"><input type="checkbox" name="checkbox" value="checkbox"  /></th>
							{foreach item=element from=$LISTHEADER}
                	        <th>{$element}</th>
							{/foreach}
                    	  </tr>
						  {foreach item=row from=$LISTENTITY}
	                      <tr onmouseover="this.className='prvPrfHoverOn'" onmouseout="this.className='prvPrfHoverOff'">
    	                    <td><input type="checkbox" name="checkbox2" value="checkbox" /></td>
						  	{foreach item=row_values from=$row}
                	        <td><b>{$row_values}</b></td>
							{/foreach}
                    	  </tr>
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
			   <td class="mailHdr">Network world : WiFiTech Introduces new gen WiFi Router </td>
			   </tr>
			 <tr>
			   <td>&nbsp;</td>
			   <td class="forwardBg">
			   		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
					  <tr>
						<td width="75%">
						  <input type="button" name="Qualify2" value=" Qualify " class="classWebBtn" />&nbsp;
						  <input type="button" name="reply" value=" Reply " class="classWebBtn" />&nbsp;
						  <input type="button" name="forward" value=" Forward " class="classWebBtn" />&nbsp;
						  <input type="button" name="download" value=" Download Attachments " class="classWebBtn" onclick="fnvshobj(this,'reportLay');"  onmouseout="fninvsh('reportLay')" />
						</td>
						<td width="25%" align="right"><input type="button" name="Button" value=" Delete "  class="classWebBtn"/></td>
					  </tr>
					</table>
				</td>
			   </tr>
			 <tr>
			   <td>&nbsp;</td>
			   <td height="300" bgcolor="#FFFFFF" valign="top" style="padding-top:10px;">
			   		<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr><td width="20%" align="right"><b>From :</b></td><td>&nbsp;</td></tr>
						<tr><td align="right">CC :</td><td>&nbsp;</td></tr>
						<tr><td align="right">BCC : </td><td>&nbsp;</td></tr>
						<tr><td align="right"><b>Subject</b></td><td>&nbsp;</td></tr>
						<tr><td align="right" style="border-bottom:1px solid #666666;" colspan="2">&nbsp;</td></tr>
					</table>
			   </td>
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


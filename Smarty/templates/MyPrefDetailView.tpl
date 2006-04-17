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
                <form name="DetailView" method="POST" action="index.php" ENCTYPE="multipart/form-data" id="form">
                        <input type="hidden" name="module" value="Users">
                        <input type="hidden" name="record" value="{$ID}">
                        <input type="hidden" name="isDuplicate" value=false>
                        <input type="hidden" name="action">
                        <input type="hidden" name="category" value="{$CATEGORY}">
                        <input type="hidden" name="mode1" value={$MODE1}>
                        <input type="hidden" name="user_name" value="{$USER_NAME}">
                        <input type="hidden" name="old_password">
                        <input type="hidden" name="new_password">
                        <input type="hidden" name="return_module">
                        <input type="hidden" name="return_action">
                        <input type="hidden" name="return_id">
                       <input type="hidden" name="forumDisplay">
			
			</tr>
		 <table align="center" border="0" cellpadding="0" cellspacing="0" width="95%">
		  <tr>

			<td>
			    <table class="small" border="0" cellpadding="3" cellspacing="0" width="100%">
				<tr>
				    <td class="dvtTabCache" style="width: 10px;" nowrap="nowrap">&nbsp;</td>
			            <td width="75" align="center" nowrap="nowrap" class="dvtSelectedCell" id="pi" onclick="fnLoadValues('pi','mi','mnuTab','mnuTab2')"><b>My Details</b></td>
                    		    <td class="dvtUnSelectedCell" style="width: 100px;" align="center" nowrap="nowrap"><a href="index.php?action=MailAccountDetailView&module=Settings&record={$RECORD_ID}"> <b>My Mail Server Details </a></b></td>
                   	<td class="dvtTabCache" nowrap="nowrap">&nbsp;</td>
                   		</tr>
	
		            </table>
			</td>
		</tr>
		<tr>
			<td align="left" valign="top">
		
			<div id="mnuTab">
			  <table class="dvtContentSpace" border="0" cellpadding="3" cellspacing="0" width="100%">
                	      <tr>

                        	<td align="left">
                          	<table border="0" cellpadding="0" cellspacing="0" width="100%">
                              	<tr>
                                	<td style="padding: 10px;" width="75%">
                                  <!-- General details -->
                                  <table width="100%"  border="0" cellspacing="0" cellpadding="5">
                                    <tr>
                                      <td colspan="4" align="left">
						{$EDIT_BUTTON}
					</td>
                                    </tr>
           			    <tr>
                                    	  <td colspan="4" class="detailedViewHeader"><b>My Details </b> </td>
                                    </tr>
                                    <tr>
                                      	<td class="dvtCellLabel" align="right" width="25%">User Name</td>
                                        <td width="30%"  class="dvtCellInfo">{$USER_NAME}</td>
				        <td width="25%" class="dvtCellLabel" align="right">Admin</td>
				        <td width="25%" class="dvtCellInfo">
						<input type="checkbox" name="is_admin" DISABLED {$IS_ADMIN}>
					</td>
                                    </tr>
                                    <tr>
                                        <td class="dvtCellLabel" align="right">First Name</td>
                                        <td width="30%" class="dvtCellInfo" nowrap>{$FIRST_NAME}</td>
                                        <td width="25%" align="right" class="dvtCellLabel">My Group Name </td>
                                        <td width="25%" class="dvtCellInfo">{$GROUPASSIGNED}</td>
                                    </tr>
                                    <tr>
                                        <td class="dvtCellLabel" align="right">Last Name </td>
                                        <td class="dvtCellInfo">{$LAST_NAME}</td>
                                        <td class="dvtCellLabel" align="right">My Role </td>
                                        <td class="dvtCellInfo">{$ROLEASSIGNED}</td>
                                    </tr>

                                    <tr>
                                        <td class="dvtCellLabel" align="right">Password</td>
                                        <td width="30%" align=left class="dvtCellInfo"><input name="pass" type="button" class="classBtn" id="pass" value=" Change Now... "  onclick="fnvshobj(this,'roleLay');"/></td>
                                        <td class="dvtCellLabel" align="right">My Status </td>
                                        <td class="dvtCellInfo">{$STATUS}</td>
                                    </tr>
                                    <tr><td colspan="4">&nbsp;</td></tr>
				    <tr>
	                                <td colspan="4" class="detailedViewHeader"><b>My Defaults</b> </td>
                                    </tr>
                                    <tr>
                                        <td class="dvtCellLabel" align="right" width="25%">Default Activity View </td>
                                        <td width="30%"  class="dvtCellInfo">{$ACTIVITY_VIEW}</td>
                                        <td width="25%" class="dvtCellLabel" align="right">Default Calendar View </td>
					<td width="25%" class="dvtCellInfo">{$COLORASSIGNED}</td>
                                    </tr>
                                    <tr>
                                        <td class="dvtCellLabel" align="right">Default Lead View </td>
                                        <td width="30%" class="dvtCellInfo" nowrap>{$LEAD_VIEW}</td>
                                        <td width="25%" align="right" class="dvtCellLabel">Default Currency </td>
                                        <td width="25%" class="dvtCellInfo">{$CURRENCY_NAME}</td>
                                    </tr>
               			    <tr><td colspan="4">&nbsp;</td></tr>
   				    <tr>
                                        <td colspan="4" class="detailedViewHeader"><b>My Designation &amp; Contact Details </b> </td>
                                    </tr>
                                    <tr>
                                        <td class="dvtCellLabel" align="right" width="25%">Title</td>
                                        <td width="30%"  class="dvtCellInfo">{$TITLE}</td>
				        <td width="25%" class="dvtCellLabel" align="right">Office Phone </td>
					<td width="25%" class="dvtCellInfo">{$PHONE_WORK}</td>
									 
                                    </tr>
                                    <tr>
                                        <td class="dvtCellLabel" align="right">Department</td>
                                        <td width="30%" class="dvtCellInfo" nowrap>{$DEPARTMENT}</td>
                                        <td width="25%" align="right" class="dvtCellLabel">Mobile</td>
                                        <td width="25%" class="dvtCellInfo">{$PHONE_MOBILE}</td>
                                    </tr>
                                    <tr>
                                        <td class="dvtCellLabel" align="right">Reports To </td>
                                        <td class="dvtCellInfo">{$REPORTS_TO_NAME}{$REPORTS_TO_ID}</td>
                                        <td class="dvtCellLabel" align="right">Other</td>
                                        <td class="dvtCellInfo">{$PHONE_OTHER}</td>
                                    </tr>

                                    <tr>
                                        <td class="dvtCellLabel" align="right">Other Email </td>
                                        <td width="30%" align=left class="dvtCellInfo">{$EMAIL2}</td>
                                        <td class="dvtCellLabel" align="right">Fax</td>
                                        <td class="dvtCellInfo">{$PHONE_FAX}</td>
                                    </tr>
				    <tr>
                                        <td class="dvtCellLabel" align="right">Chat IDs </td>
                                        <td width="30%" align=left class="dvtCellInfo">{$YAHOO_ID}</td>
                                        <td class="dvtCellLabel" align="right">Home Phone </td>
                                        <td class="dvtCellInfo">{$PHONE_HOME}</td>
                                    </tr>
				    <tr>
                                        <td class="dvtCellLabel" align="right">Date Format </td>
                                        <td width="30%" align=left class="dvtCellInfo">{$DATE_FORMAT}</td>
                                        <td class="dvtCellLabel" align="right">Notes</td>
                                        <td class="dvtCellInfo">{$DESCRIPTION}</td>
                                    </tr>
				    <tr>
                                        <td class="dvtCellLabel" align="right">Signature</td>
                                        <td width="30%" align=left class="dvtCellInfo">{$SIGNATURE}</td>
                                        <td class="dvtCellLabel" align="right">&nbsp;</td>
                                        <td class="dvtCellInfo">&nbsp;</td>
                                    </tr>

                                    <tr><td colspan="4">&nbsp;</td></tr>
				    <tr>
                                        <td colspan="4" class="detailedViewHeader"><b>My Postal Address </b> </td>
                                    </tr>
                                    <tr>
                                        <td class="dvtCellLabel" align="right" width="25%">Street Address </td>
                                        <td width="30%" colspan="3"  class="dvtCellInfo">{$ADDRESS_STREET}</td>
				    </tr>
                                    <tr>
                                        <td class="dvtCellLabel" align="right">City</td>
                                        <td width="30%" colspan="3" nowrap class="dvtCellInfo">{$ADDRESS_CITY}</td>
                                    </tr>
                                    <tr>
                                        <td class="dvtCellLabel" align="right">State</td>
                                        <td colspan="3" class="dvtCellInfo">{$ADDRESS_STATE}</td>
                                    </tr>
                                    <tr>
                                        <td class="dvtCellLabel" align="right">Postal Code </td>
                                        <td width="30%" colspan="3" align=left class="dvtCellInfo">{$ADDRESS_POSTALCODE}</td>
                                    </tr>
				    <tr>
                                        <td class="dvtCellLabel" align="right">Country</td>
                                        <td width="30%" colspan="3" align=left class="dvtCellInfo">{$ADDRESS_COUNTRY}</td>
                                    </tr>
				   <tr>
					<td colspan="4">
						{$EDIT_BUTTON}
					</td>
				   </tr>
				   <tr><td colspan="4">&nbsp;</td></tr>
                    </table></td>
				  <td width="25%" valign="top" style="padding:10px; ">
	  		<table width="100%" align="center" cellpadding="0" cellspacing="0" border="0">
				<tr><td height="35">&nbsp;</td></tr>
				<tr><td class="detailedViewHeader"><b>My Photo</b></td></tr>
				<tr><td align="center"><img src="images/myPreferences.gif" /></td>
				</tr>
				<tr><td align="center"><input type="button" value=" Change Photo... " class="classBtn"  onclick="fnvshNrm('chPhoto')"/></td></tr>
				<tr>
					<td align="center">
	
				<div id="chPhoto"> 
					 <table width="100%" border="0" cellpadding="5" cellspacing="0">
		 		<tr>
					<td width="50%" align="left" style="border-bottom:1px dotted #666666;">
						<b>Change Photo</b></td>
																				<td width="50%" align="right" style="border-bottom:1px dotted #666666;">
																					<a href="javascript:fninvsh('chPhoto');"><img src="../themes/blue/images/close.gif" border="0"  align="absmiddle" /></a></td>
																		</tr>
																		<tr>
																			<td align="center" colspan="2"><input type="file" name="newPhoto" size="15" /></td>
																		</tr>
																		<tr>

																			<td align="center" colspan="2">
																					<input type="button" name="cSave" value=" &nbsp;OK&nbsp; "  class="classBtn" onclick="fninvsh('chPhoto');" />
																					&nbsp;<input type="button" name="cCancel" value=" Cancel "  class="classBtn" onclick="fninvsh('chPhoto');" />
																			</td>
																		</tr>
																 </table>
															</div>
													</td>
												</tr>

										</table> 
								  </td>
                              </tr>
                        </table></td>
                      </tr>
				    </table>
					
				</div>

				
				<div id="mnuTab2">
					
				</div>
			</td>
		</tr>
	</table>

	</form>
</div>
	  </td>
		</tr>
  </table>

</td></tr>
</table>

{$JAVASCRIPT}
	{include file = 'SettingsSubMenu.tpl'}


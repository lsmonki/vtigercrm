<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/ColorPicker2.js"></script>

<script language="JavaScript" type="text/javascript">

        var cp2 = new ColorPicker('window');

function pickColor(color)
{ldelim}
        ColorPicker_targetInput.value = color;
        ColorPicker_targetInput.style.backgroundColor = color;
{rdelim}
</script>
<style type="text/css">@import url(themes/blue/style.css);</style>


<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">

<tr>
	<td valign="top"><img src="{$IMAGE_PATH}showPanelTopLeft.gif"></td>
	<td class="showPanelBg" valign="top" width="100%">
		<div class="small" style="padding: 10px;">
			<span class="lvtHeaderText">My Preferences </span> <br>
		 	<hr noshade="noshade" size="1"><br> 
		<form name="EditView" method="POST" action="index.php" ENCTYPE="multipart/form-data">
			<input type="hidden" name="module" value="Users">
			<input type="hidden" name="record" value="{$ID}">
			<input type="hidden" name="mode" value="register">
			<input type='hidden' name='parenttab' value='Settings'>
			<input type="hidden" name="activity_mode" value="{$ACTIVITYMODE}">
			<input type="hidden" name="action">
			<input type="hidden" name="return_module" value="{$RETURN_MODULE}">
			<input type="hidden" name="return_id" value="{$RETURN_ID}">
			<input type="hidden" name="return_action" value="{$RETURN_ACTION}">			
			<input type="hidden" name="tz" value="Europe/Berlin">			
			<input type="hidden" name="holidays" value="de,en_uk,fr,it,us,">			
			<input type="hidden" name="workdays" value="0,1,2,3,4,5,6,">			
			<input type="hidden" name="namedays" value="">			
			<input type="hidden" name="weekstart" value="1">
			
</tr>
		 <table align="center" border="0" cellpadding="0" cellspacing="0" width="95%">
		  <tr>

			<td>
			    <table class="small" border="0" cellpadding="3" cellspacing="0" width="100%">
				<tr>
				    <td class="dvtTabCache" style="width: 10px;" nowrap="nowrap">&nbsp;</td>
			            <td width="75" align="center" nowrap="nowrap" class="dvtSelectedCell" id="pi" onclick="fnLoadValues('pi','mi','mnuTab','mnuTab2')"><b>My Details</b></td>
                    		    <td class="dvtUnSelectedCell" style="width: 100px;" align="center" nowrap="nowrap" ><a href="index.php?action=AddMailAccount&module=Settings"><b>My Mail Server Details </a></b></td>
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
                                    	  <td colspan="4" class="detailedViewHeader"><b>My Details </b> </td>
                                    </tr>
                                    <tr>
                                      	<td class="dvtCellLabel" align="right" width="25%">
						<span class="style1"><font color='red'>*</font></span>{$UMOD.LBL_USER_NAME} </td>
                                        <td width="30%"  class="dvtCellInfo">
						<input type="text" name="user_name" value='{$USER_NAME}' class="detailedViewTextBox"  onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"{$USERNAME_READONLY} /></td>
				        <td width="25%" class="dvtCellLabel" align="right">Admin</td>
				        <td width="25%" class="dvtCellInfo">
						<input type="checkbox" name="is_admin" DISABLED {$IS_ADMIN}>
					</td>
                                    </tr>
                                    <tr>
                                        <td class="dvtCellLabel" align="right">First Name</td>
                                        <td width="30%" class="dvtCellInfo" nowrap>
						<input type="text" name="first_name" value='{$FIRST_NAME}' class="detailedViewTextBox"  onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" /></td>
                                        <td width="25%" align="right" class="dvtCellLabel">My Group Name </td>
                                        <td width="25%" class="dvtCellInfo">{$GROUPASSIGNED}</td>
                                    </tr>
                                    <tr>
                                        <td class="dvtCellLabel" align="right">
						<span class="style1"><font color='red'>*</font></span>{$UMOD.LBL_LAST_NAME}</td>
                                        <td class="dvtCellInfo">
						<input type="text" name="last_name" value='{$LAST_NAME}' class="detailedViewTextBox"  onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" /></td>
                                        <td class="dvtCellLabel" align="right">
						<span class="style1"><font color='red'>*</font></span>My Role</td>
                                        <td class="dvtCellInfo">{$USER_ROLE}</td>
                                    </tr>

                                    <tr>
                                        <td class="dvtCellLabel" align="right">Password</td>
                                        <td width="30%" align=left class="dvtCellInfo"><input name="pass" type="button" class="classBtn" id="pass" value=" Change Now... "  onclick="fnvshobj(this,'roleLay');"/></td>
                                        <td class="dvtCellLabel" align="right">
						<span class="style1"><font color='red'>*</font></span>My Status</td>
				 		{$USER_STATUS_OPTIONS}
                                    </tr>
                                    <tr><td colspan="4">&nbsp;</td></tr>
				    <tr>
	                                <td colspan="4" class="detailedViewHeader"><b>My Defaults</b> </td>
                                    </tr>
                                    <tr>
                                        <td class="dvtCellLabel" align="right" width="25%">Default Activity View </td>
                                        <td width="30%"  class="dvtCellInfo">{$ACTIVITY_VIEW}</td>
                                        <td width="25%" class="dvtCellLabel" align="right">Default Calendar View </td>
					      <td width="25%">&nbsp;&nbsp;{$CAL_COLOR}</td>
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
                                        <td width="30%"  class="dvtCellInfo">
							<input type="text" name="title" value='{$TITLE}' class="detailedViewTextBox"  onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" /></td>
				        <td width="25%" class="dvtCellLabel" align="right">Office Phone </td>
					<td width="25%" class="dvtCellInfo">
							<input type="text" name="phone_work" value='{$PHONE_WORK}' class="detailedViewTextBox"  onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" /></td>
									 
                                    </tr>
                                    <tr>
                                        <td class="dvtCellLabel" align="right">Department</td>
                                        <td width="30%" class="dvtCellInfo" nowrap>
							<input type="text" name="department" value='{$DEPARTMENT}' class="detailedViewTextBox"  onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" /></td>
                                        <td width="25%" align="right" class="dvtCellLabel">Mobile</td>
                                        <td width="25%" class="dvtCellInfo">
							<input type="text" value='{$PHONE_MOBILE}' name="phone_mobile" class="detailedViewTextBox"  onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" /></td>
                                    </tr>
                                    <tr>
                                        <td class="dvtCellLabel" align="right">Reports To </td>
                                        <td class="dvtCellInfo">
							<input readonly name='reports_to_name' class="small" type="text" value='{$REPORTS_TO_NAME}'><input name='reports_to_id' type="hidden" value='{$REPORTS_TO_ID}'>&nbsp;<input title="Change [Alt+C]" accessKey="C" type="button" class="small" value='Change' name=btn1 LANGUAGE=javascript onclick='return window.open("index.php?module=Users&action=Popup&form=UsersEditView&form_submit=false","test","width=600,height=400,resizable=1,scrollbars=1");'></td>
                                        <td class="dvtCellLabel" align="right">Other</td>
                                        <td class="dvtCellInfo">
							<input type="text" name="phone_other" value='{$PHONE_OTHER}' class="detailedViewTextBox"  onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" /></td>
                                    </tr>

                                    <tr>
                                        <td class="dvtCellLabel" align="right">Other Email </td>
                                        <td width="30%" align=left class="dvtCellInfo">
							<input type="text" name="email2" value='{$EMAIL2}' class="detailedViewTextBox"  onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" /></td>
                                        <td class="dvtCellLabel" align="right">Fax</td>
                                        <td class="dvtCellInfo">
							<input type="text" name="phone_fax" value='{$PHONE_FAX}' class="detailedViewTextBox"  onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" /></td>
                                    </tr>
				    <tr>
                                        <td class="dvtCellLabel" align="right">Chat IDs </td>
                                        <td width="30%" align=left class="dvtCellInfo">
							<input type="text" name="yahoo_id" value='{$YAHOO_ID}' class="detailedViewTextBox"  onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" /></td>
                                        <td class="dvtCellLabel" align="right">Home Phone </td>
                                        <td class="dvtCellInfo">
							<input type="text" name="phone_home" value='{$PHONE_HOME}' class="detailedViewTextBox"  onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" /></td>
                                    </tr>
				    <tr>
                                        <td class="dvtCellLabel" align="right">Date Format </td>
                                        <td width="30%" align=left class="dvtCellInfo">{$DATE_FORMAT}</td>
                                             <td class="dvtCellLabel" align="right">&nbsp;</td>
                                             <td class="dvtCellInfo">&nbsp;</td>
                                    </tr>
				    <tr>
                                        <td class="dvtCellLabel" align="right">Signature</td>
                                        <td width="30%" align=left class="dvtCellInfo">
							<textarea name="signature" rows="3" class="detailedViewTextBox"  onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" >{$SIGNATURE}</textarea></td>
                                        <td class="dvtCellLabel" align="right">Notes</td>
                                        <td class="dvtCellInfo">
							<textarea name="description" rows="3" class="detailedViewTextBox"  onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" >{$DESCRIPTION}</textarea></td>
                                    </tr>

                                    <tr><td colspan="4">&nbsp;</td></tr>
				    <tr>
                                        <td colspan="4" class="detailedViewHeader"><b>My Postal Address </b> </td>
                                    </tr>
                                    <tr>
                                        <td class="dvtCellLabel" align="right" width="25%">Street Address </td>
                                        <td width="30%" colspan="3"  class="dvtCellInfo">
							<textarea name="address_street" rows="3" class="detailedViewTextBox"  onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" >{$ADDRESS_STREET}</textarea></td>
				    </tr>
                                    <tr>
                                        <td class="dvtCellLabel" align="right">City</td>
                                        <td width="30%" colspan="3" nowrap class="dvtCellInfo">
								<input type="text" name="address_city" value='{$ADDRESS_CITY}' class="detailedViewTextBox"  onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" /></td>
                                    </tr>
                                    <tr>
                                        <td class="dvtCellLabel" align="right">State</td>
                                        <td colspan="3" class="dvtCellInfo">
								<input type="text" value='{$ADDRESS_STATE}' name="address_state" class="detailedViewTextBox"  onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" /></td>
                                    </tr>
                                    <tr>
                                        <td class="dvtCellLabel" align="right">Postal Code </td>
                                        <td width="30%" colspan="3" align=left class="dvtCellInfo">
								<input type="text" value='{$ADDRESS_POSTALCODE}' name="address_postalcode" class="detailedViewTextBox"  onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" /></td>
                                    </tr>
				    <tr>
                                        <td class="dvtCellLabel" align="right">Country</td>
                                        <td width="30%" colspan="3" align=left class="dvtCellInfo">
								<input type="text" value='{$ADDRESS_COUNTRY}' name="address_country" class="detailedViewTextBox"  onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" /></td>
                                    </tr>
				   <tr>
					<td colspan=2>&nbsp;</td></tr>
				   </tr>	
				            <td colspan=3 align="center">
						    <input title="Save [Alt+S]" accesskey="S" class="classBtn"  name="button" value="  Save  "  onclick="this.form.action.value='Save'; return verify_data(EditView)" style="width: 70px;" type="submit" />&nbsp;&nbsp; 
						    <input title="Cancel [Alt+X]" accesskey="X" class="classBtn" name="button" value="  Cancel  " onclick="window.history.back()" style="width: 70px;" type="button" />
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
                                    <td class="dvtCellLabel" align="right" width="33%">Display Name </td>
                                    <td class="dvtCellInfo" width="33%"><input type="text" name="textfield22" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'"/></td>
                                    <td class="dvtCellInfo" width="34%">(example : John Fenner) </td>

                                  </tr>
                                  <tr>
                                    <td class="dvtCellLabel" align="right">Email ID </td>
                                    <td class="dvtCellInfo"><input type="text" name="textfield222" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'"/></td>
                                    <td class="dvtCellInfo">( example : johnfenner@mailserver.com )</td>
                                  </tr>
                                  <tr><td colspan="3" >&nbsp;</td></tr>
                                  <tr>

                                    <td colspan="3"  class="detailedViewHeader"><b>Mail Server Settings</b></td>
                                    </tr>
                                  <tr>
                                    <td class="dvtCellLabel" align="right">Server Name or IP </td>
                                    <td class="dvtCellInfo"><input type="text" name="textfield2222" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'"/></td>
                                    <td class="dvtCellInfo">&nbsp;</td>
                                  </tr>
                                  <tr>

                                    <td class="dvtCellLabel" align="right">User Name</td>
                                    <td class="dvtCellInfo"><input type="text" name="textfield2223" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'"/></td>
                                    <td class="dvtCellInfo">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td class="dvtCellLabel" align="right">Password</td>
                                    <td class="dvtCellInfo"><input type="text" name="textfield2224" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'"/></td>
                                    <td class="dvtCellInfo">&nbsp;</td>

                                  </tr>
                                  <tr>
                                    <td colspan="3" class="dvtCellInfo">&nbsp;</td>
                                    </tr>
                                  <tr>
                                    <td class="dvtCellLabel" align="right">Protocol</td>
                                    <td class="dvtCellInfo"><input type="text" name="textfield2225" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'"/></td>
                                    <td class="dvtCellInfo">&nbsp;</td>

                                  </tr>
                                  <tr>
                                    <td class="dvtCellLabel" align="right">SSL Options </td>
                                    <td class="dvtCellInfo"><input type="text" name="textfield2226" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'"/></td>
                                    <td class="dvtCellInfo">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td class="dvtCellLabel" align="right">Certificate Validations </td>

                                    <td class="dvtCellInfo"><input type="text" name="textfield2227" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'"/></td>
                                    <td class="dvtCellInfo">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td class="dvtCellLabel" align="right">Show Body in Quick View </td>
                                    <td class="dvtCellInfo"><input type="text" name="textfield2228" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'"/></td>
                                    <td class="dvtCellInfo">&nbsp;</td>
                                  </tr>

                                  <tr>
                                    <td class="dvtCellLabel" align="right">Email's per Page </td>
                                    <td class="dvtCellInfo"><input type="text" name="textfield2229" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'"/></td>
                                    <td class="dvtCellInfo">&nbsp;</td>
                                  </tr>
                                  <tr><td colspan="3" style="border-bottom:1px dashed #CCCCCC;">&nbsp;</td></tr>
                                  <tr>
								  		<td colspan="3" align="center">

												<input type="button" name="save" value=" &nbsp;Save&nbsp; " class="classBtn" />
												&nbsp;&nbsp;
												<input type="button" name="cancel" value=" Cancel " class="classBtn" />
										</td>
								</tr>
								<tr><td colspan="3" style="border-top:1px dashed #CCCCCC;">&nbsp;</td></tr>
                               </table>
							   </td>
                              </tr>

                        </table></td>
                      </tr>
				    </table>
					
				</div>
			</td>
		</tr>
	</table>

	</form>
</div>
	  </td>
		</tr>
  </table>

{$JAVASCRIPT}

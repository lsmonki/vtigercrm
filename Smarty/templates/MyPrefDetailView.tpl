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
<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/general.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/ColorPicker2.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/slider.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/prototype_fade.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/effectspack.js"></script>
<style type="text/css">@import url(themes/blue/style.css);</style>


<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">

<tr>
	<td valign="top"><img src="{$IMAGE_PATH}showPanelTopLeft.gif"></td>
	<td class="showPanelBg" valign="top" width="100%">
		<div class="small" style="padding: 10px;">
			<span class="lvtHeaderText">{$APP.LBL_MY_PREFERENCES}</span> <br>
		 	<hr noshade="noshade" size="1"><br> 
                <form name="DetailView" method="POST" action="index.php" ENCTYPE="multipart/form-data" id="form">
                        <input type="hidden" name="module" value="Users">
                        <input type="hidden" name="record" value="{$ID}">
                        <input type="hidden" name="isDuplicate" value=false>
                        <input type="hidden" name="action">
                        <input type="hidden" name="parenttab" value="{$PARENTTAB}">
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
			            <td width="75" align="center" nowrap="nowrap" class="dvtSelectedCell" id="pi" onclick="fnLoadValues('pi','mi','mnuTab','mnuTab2')"><b>{$MOD.LBL_MY_DETAILS}</b></td>
                    		    <td class="dvtUnSelectedCell" style="width: 100px;" align="center" nowrap="nowrap"><a href="index.php?action=AddMailAccount&module=Settings&record={$ID}"> <b>{$MOD.LBL_MY_MAIL_SERVER_DET}</a></b></td>
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
						&nbsp;
						{$CHANGE_PW_BUTTON}
					</td>
                                    </tr>
           			    <tr>
                                    	  <td colspan="4" class="detailedViewHeader"><b>{$MOD.LBL_MY_DETAILS}</b> </td>
                                    </tr>
                                    <tr>
                                      	<td class="dvtCellLabel" align="right" width="25%"><span class="style1"><font color='red'>{$APP.LBL_REQUIRED_SYMBOL}</font></span>{$UMOD.LBL_USER_NAME}</td>
                                        <td width="30%"  class="dvtCellInfo">{$USER_NAME}</td>
				        <td width="25%" class="dvtCellLabel" align="right">{$UMOD.LBL_ADMIN}</td>
				        <td width="25%" class="dvtCellInfo">
						<input type="checkbox" name="is_admin" DISABLED {$IS_ADMIN}>
					</td>
                                    </tr>
                                    <tr>
                                        <td class="dvtCellLabel" align="right">{$UMOD.LBL_FIRST_NAME}</td>
                                        <td width="30%" class="dvtCellInfo" nowrap>{$FIRST_NAME}</td>
                                        <td width="25%" align="right" class="dvtCellLabel">{$UMOD.LBL_MY} {$UMOD.LBL_GROUP_NAME} </td>
                                        <td width="25%" class="dvtCellInfo">{$GROUPASSIGNED}</td>
                                    </tr>
                                    <tr>
                                        <td class="dvtCellLabel" align="right"><span class="style1"><font color='red'>{$APP.LBL_REQUIRED_SYMBOL}</font></span>{$UMOD.LBL_LAST_NAME}</td>
                                        <td class="dvtCellInfo">{$LAST_NAME}</td>
                                        <td class="dvtCellLabel" align="right"><span class="style1"><font color='red'>{$APP.LBL_REQUIRED_SYMBOL}</font></span>{$UMOD.LBL_MY} {$UMOD.LBL_USER_ROLE}</td>
                                        <td class="dvtCellInfo">{$ROLEASSIGNED}</td>
                                    </tr>

                                    <tr>
                                        <td class="dvtCellLabel" align="right"><span class="style1"><font color='red'>{$APP.LBL_REQUIRED_SYMBOL}</font></span>{$UMOD.LBL_EMAIL}</td>
					<td class="dvtCellInfo"><a href="mailto:{$EMAIL1}" target="_blank">{$EMAIL1}</a></td>
                                {*        <td width="30%" align=left class="dvtCellInfo"><input name="pass" type="button" class="classBtn" id="pass" value=" Change Now... "  onclick="fnvshobj(this,'roleLay');"/></td>*}
                                        <td class="dvtCellLabel" align="right"><span class="style1"><font color='red'>{$APP.LBL_REQUIRED_SYMBOL}</font></span>{$UMOD.LBL_MY} {$UMOD.LBL_STATUS}</td>
                                        <td class="dvtCellInfo">{$STATUS}</td>
                                    </tr>
                                    <tr><td colspan="4">&nbsp;</td></tr>
				    <tr>
	                                <td colspan="4" class="detailedViewHeader"><b>{$UMOD.LBL_MY_DEFAULTS}</b> </td>
                                    </tr>
                                    <tr>
                                        <td class="dvtCellLabel" align="right" width="25%">{$UMOD.LBL_ACTIVITY_VIEW}</td>
                                        <td width="30%"  class="dvtCellInfo">{$ACTIVITY_VIEW}</td>
                                        <td width="25%" class="dvtCellLabel" align="right">{$UMOD.LBL_COLOR}</td>
					<td width="25%" class="dvtCellInfo">{$COLORASSIGNED}</td>
                                    </tr>
                                    <tr>
                                        <td class="dvtCellLabel" align="right">{$UMOD.LBL_LEAD_VIEW}</td>
                                        <td width="30%" class="dvtCellInfo" nowrap>{$LEAD_VIEW}</td>
                                        <td width="25%" align="right" class="dvtCellLabel">{$UMOD.LBL_CURRENCY_NAME}</td>
                                        <td width="25%" class="dvtCellInfo">{$CURRENCY_NAME}</td>
                                    </tr>
               			    <tr><td colspan="4">&nbsp;</td></tr>
   				    <tr>
                                        <td colspan="4" class="detailedViewHeader"><b>{$UMOD.LBL_MY_DESG}</b> </td>
                                    </tr>
                                    <tr>
                                        <td class="dvtCellLabel" align="right" width="25%">{$UMOD.LBL_TITLE}</td>
                                        <td width="30%"  class="dvtCellInfo">{$TITLE}</td>
				        <td width="25%" class="dvtCellLabel" align="right">{$UMOD.LBL_OFFICE_PHONE}</td>
					<td width="25%" class="dvtCellInfo">{$PHONE_WORK}</td>
									 
                                    </tr>
                                    <tr>
                                        <td class="dvtCellLabel" align="right">{$UMOD.LBL_DEPARTMENT}</td>
                                        <td width="30%" class="dvtCellInfo" nowrap>{$DEPARTMENT}</td>
                                        <td width="25%" align="right" class="dvtCellLabel">{$UMOD.LBL_MOBILE_PHONE}</td>
                                        <td width="25%" class="dvtCellInfo">{$PHONE_MOBILE}</td>
                                    </tr>
                                    <tr>
                                        <td class="dvtCellLabel" align="right">{$UMOD.LBL_REPORTS_TO}</td>
                                        <td class="dvtCellInfo">{$REPORTS_TO_NAME}{$REPORTS_TO_ID}</td>
                                        <td class="dvtCellLabel" align="right">{$UMOD.LBL_OTHER_PHONE}</td>
                                        <td class="dvtCellInfo">{$PHONE_OTHER}</td>
                                    </tr>

                                    <tr>
                                        <td class="dvtCellLabel" align="right">{$UMOD.LBL_OTHER_EMAIL}</td>
                                        <td width="30%" align=left class="dvtCellInfo">{$EMAIL2}</td>
                                        <td class="dvtCellLabel" align="right">{$UMOD.LBL_FAX}</td>
                                        <td class="dvtCellInfo">{$PHONE_FAX}</td>
                                    </tr>
				    <tr>
                                        <td class="dvtCellLabel" align="right">Chat IDs </td>
                                        <td width="30%" align=left class="dvtCellInfo">{$YAHOO_ID}</td>
                                        <td class="dvtCellLabel" align="right">{$UMOD.LBL_HOME_PHONE}</td>
                                        <td class="dvtCellInfo">{$PHONE_HOME}</td>
                                    </tr>
				    <tr>
                                        <td class="dvtCellLabel" align="right">{$UMOD.LBL_DATE_FORMAT}</td>
                                        <td width="30%" align=left class="dvtCellInfo">{$DATE_FORMAT}</td>
                                        <td class="dvtCellLabel" align="right">{$UMOD.LBL_NOTES}</td>
                                        <td class="dvtCellInfo">{$DESCRIPTION}</td>
                                    </tr>
				    <tr>
                                        <td class="dvtCellLabel" align="right">{$UMOD.LBL_SIGNATURE}</td>
                                        <td width="30%" align=left class="dvtCellInfo">{$SIGNATURE}</td>
                                        <td class="dvtCellLabel" align="right">&nbsp;</td>
                                        <td class="dvtCellInfo">&nbsp;</td>
                                    </tr>

                                    <tr><td colspan="4">&nbsp;</td></tr>
				    <tr>
                                        <td colspan="4" class="detailedViewHeader"><b>{$UMOD.LBL_MY_ADDR} </b> </td>
                                    </tr>
                                    <tr>
                                        <td class="dvtCellLabel" align="right" width="25%">{$UMOD.LBL_ADDRESS}</td>
                                        <td width="30%" colspan="3"  class="dvtCellInfo">{$ADDRESS_STREET}</td>
				    </tr>
                                    <tr>
                                        <td class="dvtCellLabel" align="right">{$UMOD.LBL_CITY}</td>
                                        <td width="30%" colspan="3" nowrap class="dvtCellInfo">{$ADDRESS_CITY}</td>
                                    </tr>
                                    <tr>
                                        <td class="dvtCellLabel" align="right">{$UMOD.LBL_STATE}</td>
                                        <td colspan="3" class="dvtCellInfo">{$ADDRESS_STATE}</td>
                                    </tr>
                                    <tr>
                                        <td class="dvtCellLabel" align="right">{$UMOD.LBL_POSTAL_CODE}</td>
                                        <td width="30%" colspan="3" align=left class="dvtCellInfo">{$ADDRESS_POSTALCODE}</td>
                                    </tr>
				    <tr>
                                        <td class="dvtCellLabel" align="right">{$UMOD.LBL_COUNTRY}</td>
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
				<tr><td class="detailedViewHeader"><b>{$UMOD.LBL_MY_PHOTO}</b></td></tr>
				<tr><td align="center"><img src="images/myPreferences.gif" /></td>
				</tr>
				<tr><td align="center"><input type="button" value=" {$UMOD.LBL_CHANGE_PHOTO} " class="classBtn"  onclick="fnvshNrm('chPhoto')"/></td></tr>
				<tr>
					<td align="center">
	
				<div id="chPhoto"> 
					 <table width="100%" border="0" cellpadding="5" cellspacing="0">
		 		<tr>
					<td width="50%" align="left" style="border-bottom:1px dotted #666666;">
						<b>{$UMOD.LBL_CHANGE_PHOTO}</b></td>
																				<td width="50%" align="right" style="border-bottom:1px dotted #666666;">
																					<a href="javascript:fninvsh('chPhoto');"><img src="{$IMAGE_PATH}close.gif" border="0"  align="absmiddle" /></a></td>
																		</tr>
																		<tr>
																			<td align="center" colspan="2"><input type="file" name="newPhoto" size="15" /></td>
																		</tr>
																		<tr>

																			<td align="center" colspan="2">
																					<input type="button" name="cSave" value=" &nbsp;OK&nbsp; "  class="classBtn" onclick="fninvsh('chPhoto');" />
																					&nbsp;<input type="button" name="cCancel" value=" {$APP.LBL_CANCEL_BUTTON_LABEL} "  class="classBtn" onclick="fninvsh('chPhoto');" />
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


{$JAVASCRIPT}
{*
<div id="roleLay">
	<table width="100%" border="0" cellpadding="5" cellspacing="0">
	<tr>
		<td width="50%" align="left" class="genHeaderSmall">Change Password </td>
		<td width="50%" align="right"><a href="javascript:fninvsh('roleLay');"><img src="themes/blue/images/close.gif" border="0"  align="absmiddle" /></a></td>
	</tr>
	<tr><td colspan="2"><hr /></td></tr>
	<tr>
		<td align="right"><b>Enter Current Password :</b></td>
		<td align="left"><input type="password" id="currentPass" class="importBox" /></td>
	</tr>
	<tr>
		<td align="right"><b>Enter New Password :</b></td>
		<td align="left"><input type="password" id="newPass" name="newPass" class="importBox" /></td>
	</tr>
	<tr>
		<td align="right"><b>Confirm New Password :</b></td>
		<td align="left"><input type="password" id="confirmPass"  name="confirmPass" class="importBox" /></td>
	</tr>
	<tr>
		<td style="border-bottom:1px dashed #CCCCCC;" colspan="2">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2" align="center">
		<input type="button" name="save" value=" &nbsp;Save&nbsp; " class="classBtn" onclick="checkPassword({$ID})" />&nbsp;&nbsp;
		<input type="button" name="cancel" value=" Cancel " class="classBtn" onclick="fninvsh('roleLay');" />
		</td>
	</tr>
	<tr><td colspan="2" style="border-top:1px dashed #CCCCCC;">&nbsp;</td></tr>
	</table>
</div>
<script>
{literal}
function checkPassword(record)
{
	var old_pwd = getObj("currentPass").value;
	alert(old_pwd);
	var new_pwd = getObj("newPass").value;
	alert(new_pwd);
	var conf_pwd = getObj("confirmPass").value;
	alert(conf_pwd);
	if(new_pwd != conf_pwd)
	{
		alert("Password Mismatch")
	}
	else
		alert("Password Matches");
}
{/literal}

</script>*}

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
<script language="JavaScript" type="text/javascript" src="include/js/ColorPicker2.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/slider.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/prototype_fade.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/effectspack.js"></script>
<script language="javascript" type="text/javascript" src="include/js/general.js"></script>
<style type="text/css">@import url(themes/blue/style.css);</style>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
{if $CATEGORY eq 'Settings'}
	{include file='SettingsMenu.tpl'}
{/if}
<td width="75%" valign="top">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr><td class="padTab" align="left">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<form name="DetailView" method="POST" action="index.php" ENCTYPE="multipart/form-data" id="form">
			<input type="hidden" name="module" value="Users">
			<input type="hidden" name="record" value="{$ID}">
			<input type="hidden" name="isDuplicate" value=false>
			<input type="hidden" name="action">

{if $CATEGORY eq 'Settings'}
			<input type="hidden" name="modechk" value="prefview">
{/if}
			<input type="hidden" name="user_name" value="{$USER_NAME}">
			<input type="hidden" name="old_password">
			<input type="hidden" name="new_password">
			<input type="hidden" name="return_module">
			<input type="hidden" name="return_action">
			<input type="hidden" name="return_id">
			<input type="hidden" name="forumDisplay">
{if $CATEGORY eq 'Settings'}
			<input type="hidden" name="parenttab" value="{$PARENTTAB}">
{/if}	

			<tr>
				<td style="border-bottom:1px dashed #CCCCCC;">
					<table width="100%" cellpadding="5" cellspacing="0" border="0">
					<tr>
						<td colspan="2" style="padding:5px;">
						{if $CATEGORY eq 'Settings'}
							<span class="lvtHeaderText">
							<b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a> > {$MOD.LBL_USER_MANAGEMENT} > {$MOD.LBL_USERS}</b></span>
						{else}
							<span class="lvtHeaderText">	
							<b>{$APP.LBL_MY_PREFERENCES}</b>
							</span>
						{/if}
											
				<hr noshade="noshade" size="1" />
											</td>
									  </tr>
									<tr>
											<td width="5%"><img src="{$IMAGE_PATH}user.gif" align="absmiddle"></td>
											<td width="95%"><span class="genHeaderGrayBig">{$USER_NAME}</span><br>
												<b class="small">{$UMOD.LBL_DETAIL_VIEW} {$FIRST_NAME} {$LAST_NAME}</b>
											</td>
									</tr>
							</table>
					</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr valign="center">
			        <td class="small" align="center">
					{$EDIT_BUTTON}
				{$CHANGE_PW_BUTTON}
				{$LOGIN_HISTORY_BUTTON}
			{if $CATEGORY eq 'Settings'}
				{$DUPLICATE_BUTTON}
			{/if}	
				{$CHANGE_HOMEPAGE_BUTTON}	
				{$LISTROLES_BUTTON}
	</td></tr>

		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2">
		<table align="center" border="0" cellpadding="0" cellspacing="0" width="99%">
		  
		  <tr>
			<td>
			  <table class="small" border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr >
			     <td class="dvtTabCache" width="10" nowrap="nowrap">&nbsp;</td>
			        <td id="prof" width="25%" align="center" nowrap="nowrap" class="dvtSelectedCell" onClick="fnVis('prof')"><b>{$UMOD.LBL_USER_LOGIN_ROLE}</b></td>
				    <td id="more" width="25%" align="center" nowrap="nowrap" class="dvtUnSelectedCell" onClick="fnVis('more')"><b>{$UMOD.LBL_USER_MORE_INFN}</b></td>
				    <td id="addr" width="25%" align="center" nowrap="nowrap" class="dvtUnSelectedCell" onClick="fnVis('addr')"><b>{$UMOD.LBL_USER_ADDR_INFN}</b></td>
				    <td class="dvtTabCache" nowrap="nowrap" width="10">&nbsp;</td>
			  </tr>
			  </table>
			</td>
		  </tr>
		  <tr>
		  	<td align="left" valign="top">
			<div id="mnuTab">
				<table class="dvtContentSpace" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr><td height="35">&nbsp;</td></tr>
				<tr><td align="left">
				<table width="99%"  border="0" cellspacing="0" cellpadding="5" align="center" class="small">
  		    	<tr>
				<td colspan="4" class="detailedViewHeader"><b>{$UMOD.LBL_USER_INFORMATION}</b></td>
				</tr>  
				<tr>
				<td class="dvtCellLabel" align="right"><span class="style1"><font color='red'>{$APP.LBL_REQUIRED_SYMBOL}</font></span>{$UMOD.LBL_USER_NAME} </td>
				<td class="dvtCellInfo">{$USER_NAME}&nbsp;</td>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_ADMIN} </td>
        	    <td class="dvtCellInfo"><input type="checkbox" name="is_admin" {$IS_ADMIN}/></td>
				</tr>
				{if $MODE eq 'edit'}
				<tr>
				<td class="dvtCellLabel" align="right" width="20%"><span class="style1"><font color='red'>{$APP.LBL_REQUIRED_SYMBOL}</font></span>{$UMOD.LBL_PASSWORD} </td>
			    <td class="dvtCellInfo" width="20%"><input  name="new_password" type="password" class="detailedViewTextBox"  onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" /></td>
			    <td class="dvtCellLabel" width="20%" align="right"><span class="style1"><font color='red'>{$APP.LBL_REQUIRED_SYMBOL}</font></span>{$UMOD.LBL_CONFIRM_PASSWORD} </td>
			    <td class="dvtCellInfo" width="20%"><input name="confirm_new_password" type="password" class="detailedViewTextBox"  onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" /></td>
			    </tr>
				{/if}	
			    <tr>
			    <td class="dvtCellLabel" align="right">{$UMOD.LBL_FIRST_NAME} </td>
			    <td class="dvtCellInfo">{$FIRST_NAME}&nbsp;</td>
			    <td class="dvtCellLabel" align="right"><span class="style1"><font color='red'>{$APP.LBL_REQUIRED_SYMBOL}</font></span>{$UMOD.LBL_LAST_NAME}</td>
			    <td class="dvtCellInfo">{$LAST_NAME}&nbsp;</td>
			 	</tr>
				<tr>
				<td class="dvtCellLabel" align="right"><span class="style1"><font color='red'>{$APP.LBL_REQUIRED_SYMBOL}</font></span>{$UMOD.LBL_USER_ROLE}</td>
				<td class="dvtCellInfo">{$ROLEASSIGNED}&nbsp;</td>							      <td class="dvtCellLabel" align="right">{$UMOD.LBL_GROUP_NAME}</td>
				<td class="dvtCellInfo">{$GROUPASSIGNED}&nbsp;</td>
			    </tr>
				<tr>
				<td class="dvtCellLabel" align="right"><span class="style1"><font color='red'>{$APP.LBL_REQUIRED_SYMBOL}</font></span>{$UMOD.LBL_EMAIL}</td>
				<td class="dvtCellInfo"><a href="mailto:{$EMAIL1}" target="_blank">{$EMAIL1}</a>&nbsp;</td>
				<td class="dvtCellLabel" align="right"><span class="style1"><font color='red'>{$APP.LBL_REQUIRED_SYMBOL}</font></span>{$UMOD.LBL_STATUS}</td>

				<td class="dvtCellInfo">{$STATUS}&nbsp;</td>
				</tr>
				<tr><td colspan="4" class="dvtCellInfo" height="30">&nbsp;</td></tr>
				<tr>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_ACTIVITY_VIEW}</td>
				<td class="dvtCellInfo">{$ACTIVITY_VIEW}&nbsp;</td>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_LEAD_VIEW}</td>
				<td span class="dvtCellInfo">{$LEAD_VIEW}&nbsp;</td>
				</tr>
				<tr>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_CURRENCY_NAME}</td>
		                <td span class="dvtCellInfo">{$CURRENCY_NAME}&nbsp;</td>
				<td class="dvtCellLabel" align="right"></td>
				<td class="dvtCellInfo">&nbsp;</td>
				</tr>
				<tr><td colspan="4" height="30">&nbsp;</td></tr>
				</table>
				</td></tr>
				</table>
			</div>
			<div id="mnuTab1" >
			  	<table class="dvtContentSpace" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr><td height="35">&nbsp;</td></tr>
				<tr><td align="left">
				<table width="99%"  border="0" cellspacing="0" cellpadding="5" align="center" class="small">
				<tr>
				<td colspan="4" class="detailedViewHeader"><b>{$UMOD.LBL_USER_MORE_INFN}</b></td>
				</tr>  
				<tr>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_TITLE}</td>
				<td class="dvtCellInfo">{$TITLE}&nbsp;</td>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_OFFICE_PHONE}</td>
         		<td class="dvtCellInfo">{$PHONE_WORK}&nbsp;</td>
				</tr>
				<tr>
				<td class="dvtCellLabel" align="right" width="20%">{$UMOD.LBL_DEPARTMENT}</td>
				<td class="dvtCellInfo" width="20%">{$DEPARTMENT}&nbsp;</td>
				<td class="dvtCellLabel" width="20%" align="right">{$UMOD.LBL_MOBILE_PHONE}</td>
				<td class="dvtCellInfo" width="20%">{$PHONE_MOBILE}&nbsp;</td>
			    </tr>
			    <tr>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_REPORTS_TO}</td>
				<td class="dvtCellInfo" width="20%">{$REPORTS_TO_NAME}{$REPORTS_TO_ID}&nbsp;</td>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_OTHER_PHONE}</td>
				<td class="dvtCellInfo">{$PHONE_OTHER}&nbsp;</td>
				</tr>
				<tr>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_OTHER_EMAIL}</td>
				<td class="dvtCellInfo">{$EMAIL2}&nbsp;</td>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_FAX}</td>
				<td class="dvtCellInfo">{$PHONE_FAX}&nbsp;</td>
				</tr>
				<tr>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_YAHOO_ID}</td>
				<td class="dvtCellInfo">{$YAHOO_ID}&nbsp;</td>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_HOME_PHONE}</td>
				<td class="dvtCellInfo">{$PHONE_HOME}&nbsp;</td>
				</tr>
				<tr>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_DATE_FORMAT}</td>
				<td class="dvtCellInfo" width="30%">{$DATE_FORMAT}&nbsp;</td>
				<td class="dvtCellLabel" align="right">&nbsp;</td>
				<td class="dvtCellInfo">&nbsp;</td>
				</tr>
				<tr>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_SIGNATURE}</td>
				<td class="dvtCellInfo">{$SIGNATURE}&nbsp;</td>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_NOTES}</td>
				<td span class="dvtCellInfo">{$DESCRIPTION}&nbsp;</td>
				</tr>
				<tr><td colspan="4" height="30">&nbsp;</td></tr>
				</table>
				</td></tr>
				</table>
			</div>
		  	<div id="mnuTab2" >
 			    <table class="dvtContentSpace" border="0" cellpadding="0" cellspacing="0" width="100%">
			    <tr><td height="35">&nbsp;</td></tr>
			    <tr><td align="left">
				<table width="99%"  border="0" cellspacing="0" cellpadding="5" align="center" class="small">
				<tr>
				<td colspan="4" class="detailedViewHeader"><b>{$UMOD.LBL_USER_ADDR_INFN}</b></td>
				</tr>  
				<tr>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_ADDRESS}</td>
				<td class="dvtCellInfo">{$ADDRESS_STREET}&nbsp;</td>
				<td class="dvtCellInfo" >&nbsp;</td>
				<td class="dvtCellInfo">&nbsp;</td>
				</tr>
				<tr>
				<td class="dvtCellLabel" align="right" width="20%">{$UMOD.LBL_CITY}</td>
				<td class="dvtCellInfo" width="20%">{$ADDRESS_CITY}&nbsp;</td>
				<td class="dvtCellInfo" width="20%">&nbsp;</td>
				<td class="dvtCellInfo" width="20%">&nbsp;</td>
				</tr>
				<tr>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_STATE}</td>
				<td class="dvtCellInfo">{$ADDRESS_STATE}&nbsp;</td>
				<td class="dvtCellInfo">&nbsp;</td>
				<td class="dvtCellInfo">&nbsp;</td>
				</tr>
				<tr>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_POSTAL_CODE}</td>
				<td class="dvtCellInfo">{$ADDRESS_POSTALCODE}&nbsp;</td>
			    <td class="dvtCellInfo">&nbsp;</td>
			    <td class="dvtCellInfo">&nbsp;</td>
				</tr>
				<tr>
			    <td class="dvtCellLabel" align="right">{$UMOD.LBL_COUNTRY}</td>
			    <td class="dvtCellInfo">{$ADDRESS_COUNTRY}&nbsp;</td>
			    <td class="dvtCellInfo" >&nbsp;</td>
			    <td class="dvtCellInfo">&nbsp;</td>
			    </tr>
			    <tr><td colspan="4" height="30">&nbsp;</td></tr>
				</table>
				</td></tr>
				</table>
			</div>
			</td>
			</tr>
			
	        <tr><td>&nbsp;</td></tr>
			
	  </table>



</td></tr>
</table>
</form>
</td></tr>
</table>
</td></tr>
</table>
</td>
</tr>
</table>

{$JAVASCRIPT}
{if $CATEGORY eq 'Settings'}
	{include file='SettingsSubMenu.tpl'}
{/if}

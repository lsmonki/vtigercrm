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
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr><td class="detailedViewHeader" align="left"><b>{$MOD.LBL_USER_MANAGEMENT}</b></td></tr>
	
	<tr><td class="padTab" align="left">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<form name="DetailView" method="POST" action="index.php" ENCTYPE="multipart/form-data" id="form">
			<input type="hidden" name="module" value="Users">
			<input type="hidden" name="record" value="{$ID}">
			<input type="hidden" name="isDuplicate" value=false>
			<input type="hidden" name="action">
			<input type="hidden" name="user_name" value="{$USER_NAME}">
			<input type="hidden" name="old_password">
			<input type="hidden" name="new_password">
			<input type="hidden" name="return_module">
			<input type="hidden" name="return_action">
			<input type="hidden" name="return_id">
			<input type="hidden" name="forumDisplay">

			<tr valign="center">
		            <td class="small" valign="center">
				{$USER_IMAGE}
				{$EDIT_BUTTON}
				{$CHANGE_PW_BUTTON}
				{$LOGIN_HISTORY_BUTTON}
				{$LIST_MAILSERVER_BUTTON}
				{$DUPLICATE_BUTTON}
				{$TABCUSTOMIZE_BUTTON}
				{$DELETE_BUTTON}
				{$LISTROLES_BUTTON}
				{$CHANGE_HOMEPAGE_BUTTON}
	</td></tr>

		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2">
	
		<div id="contents">
		<div id="fadedtabs">
		<table align="center" border="0" cellpadding="0" cellspacing="0" width="99%">
		  
		  <tr>
			<td>
			  <table class="small" border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr id="tabs">
			     <td class="dvtTabCache" width="10" nowrap="nowrap">&nbsp;</td>
			        <td id="prof" width="25%" align="center" nowrap="nowrap" class="dvtSelectedCell"><a onclick="new EffectPack.TabToggle(this);" href="#tab1" id="current"><b>{$UMOD.LBL_USER_LOGIN_ROLE}</b></a></td>
				    <td id="more" width="25%" align="center" nowrap="nowrap" class="dvtUnSelectedCell"><a onclick="new EffectPack.TabToggle(this);" href="#tab2"><b>{$UMOD.LBL_USER_MORE_INFN}</b></a></td>
				    <td id="addr" width="25%" align="center" nowrap="nowrap" class="dvtUnSelectedCell"><a onclick="new EffectPack.TabToggle(this);" href="#tab3"><b>{$UMOD.LBL_USER_ADDR_INFN}</b></a></td>
				    <td class="dvtTabCache" nowrap="nowrap" width="10">&nbsp;</td>
			  </tr>
			  </table>
			</td>
		  </tr>
		  <tr>
		  	<td align="left" valign="top">
			<div id="tab1" class="tabset_content">
				<table class="dvtContentSpace" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr><td height="35">&nbsp;</td></tr>
				<tr><td align="left">
				<table width="99%"  border="0" cellspacing="0" cellpadding="5" align="center">
  		    	<tr>
				<td colspan="4" class="detailedViewHeader"><b>{$UMOD.LBL_USER_INFORMATION}</b></td>
				</tr>  
				<tr>
				<td class="dvtCellLabel" align="right"><span class="style1"><font color='red'>*</font></span>{$UMOD.LBL_USER_NAME} </td>
				<td class="dvtCellInfo">{$USER_NAME}</td>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_ADMIN} </td>
        	    <td class="dvtCellInfo"><input type="checkbox" name="is_admin" DISABLED {$IS_ADMIN}/></td>
				</tr>
				{if $MODE eq 'edit'}
				<tr>
				<td class="dvtCellLabel" align="right" width="20%"><span class="style1"><font color='red'>*</font></span>{$UMOD.LBL_PASSWORD} </td>
			    <td class="dvtCellInfo" width="20%"><input type="text" name="new_password" type="password" class="detailedViewTextBox"  onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" /></td>
			    <td class="dvtCellLabel" width="20%" align="right"><span class="style1"><font color='red'>*</font></span>{$UMOD.LBL_CONFIRM_PASSWORD} </td>
			    <td class="dvtCellInfo" width="20%"><input type="text" name="confirm_new_password" type="password" class="detailedViewTextBox"  onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" /></td>
			    </tr>
				{/if}	
			    <tr>
			    <td class="dvtCellLabel" align="right">{$UMOD.LBL_FIRST_NAME} </td>
			    <td class="dvtCellInfo">{$FIRST_NAME}</td>
			    <td class="dvtCellLabel" align="right"><span class="style1"><font color='red'>*</font></span>{$UMOD.LBL_LAST_NAME}</td>
			    <td class="dvtCellInfo">{$LAST_NAME}</td>
			 	</tr>
				<tr>
				<td class="dvtCellLabel" align="right"><span class="style1"><font color='red'>*</font></span>{$UMOD.LBL_USER_ROLE}</td>
				<td class="dvtCellInfo">{$ROLEASSIGNED}</td>							      <td class="dvtCellLabel" align="right">{$UMOD.LBL_GROUP_NAME}</td>
				<td class="dvtCellInfo">{$GROUPASSIGNED}</td>
			    </tr>
				<tr>
				<td class="dvtCellLabel" align="right"><span class="style1"><font color='red'>*</font></span>{$UMOD.LBL_EMAIL}</td>
				<td class="dvtCellInfo">{$EMAIL1}</td>
				<td class="dvtCellLabel" align="right"><span class="style1"><font color='red'>*</font></span>{$UMOD.LBL_STATUS}</td>

				<td class="dvtCellInfo">{$STATUS}</td>
				</tr>
				<tr><td colspan="4" class="dvtCellInfo" height="30">&nbsp;</td></tr>
				<tr>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_ACTIVITY_VIEW}</td>
				<td class="dvtCellInfo">{$ACTIVITY_VIEW}</td>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_LEAD_VIEW}</td>
				<td span class="dvtCellInfo">{$LEAD_VIEW}</td>
				</tr>
				<tr>
				<td class="dvtCellLabel" align="right"><span class="style1"><font color='red'>*</font></span>{$UMOD.LBL_COLOR}</td>
				<td class="dvtCellInfo">{$COLORASSIGNED}</td>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_CURRENCY_NAME}</td>
                <td span class="dvtCellInfo">{$CURRENCY_NAME}</td>
				</tr>
				<tr><td colspan="4" height="30">&nbsp;</td></tr>
				</table>
				</td></tr>
				</table>
			</div>
			<div id="tab2" class="tabset_content">
			  	<table class="dvtContentSpace" border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr><td height="35">&nbsp;</td></tr>
				<tr><td align="left">
				<table width="99%"  border="0" cellspacing="0" cellpadding="5" align="center">
				<tr>
				<td colspan="4" class="detailedViewHeader"><b>{$UMOD.LBL_USER_MORE_INFN}</b></td>
				</tr>  
				<tr>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_TITLE}</td>
				<td class="dvtCellInfo">{$TITLE}</td>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_OFFICE_PHONE}</td>
         		<td class="dvtCellInfo">{$PHONE_WORK}</td>
				</tr>
				<tr>
				<td class="dvtCellLabel" align="right" width="20%">{$UMOD.LBL_DEPARTMENT}</td>
				<td class="dvtCellInfo" width="20%">{$DEPARTMENT}</td>
				<td class="dvtCellLabel" width="20%" align="right">{$UMOD.LBL_MOBILE_PHONE}</td>
				<td class="dvtCellInfo" width="20%">{$PHONE_MOBILE}</td>
			    </tr>
			    <tr>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_REPORTS_TO}</td>
				<td class="dvtCellInfo" width="20%">{$REPORTS_TO_NAME}{$REPORTS_TO_ID}&nbsp;</td>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_OTHER_PHONE}</td>
				<td class="dvtCellInfo">{$PHONE_OTHER}</td>
				</tr>
				<tr>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_OTHER_EMAIL}</td>
				<td class="dvtCellInfo">{$EMAIL2}</td>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_FAX}</td>
				<td class="dvtCellInfo">{$PHONE_FAX}</td>
				</tr>
				<tr>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_YAHOO_ID}</td>
				<td class="dvtCellInfo">{$YAHOO_ID}</td>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_HOME_PHONE}</td>
				<td class="dvtCellInfo">{$PHONE_HOME}</td>
				</tr>
				<tr>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_DATE_FORMAT}</td>
				<td class="dvtCellInfo" width="30%">{$DATE_FORMAT}</td>
				<td class="dvtCellLabel" align="right">&nbsp;</td>
				<td class="dvtCellInfo">&nbsp;</td>
				</tr>
				<tr>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_SIGNATURE}</td>
				<td class="dvtCellInfo">{$SIGNATURE}</td>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_NOTES}</td>
				<td span class="dvtCellInfo">{$DESCRIPTION}
				</td>
				</tr>
				<tr><td colspan="4" height="30">&nbsp;</td></tr>
				</table>
				</td></tr>
				</table>
			</div>
		  	<div id="tab3" class="tabset_content">
 			    <table class="dvtContentSpace" border="0" cellpadding="0" cellspacing="0" width="100%">
			    <tr><td height="35">&nbsp;</td></tr>
			    <tr><td align="left">
				<table width="99%"  border="0" cellspacing="0" cellpadding="5" align="center">
				<tr>
				<td colspan="4" class="detailedViewHeader"><b>{$UMOD.LBL_USER_ADDR_INFN}</b></td>
				</tr>  
				<tr>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_ADDRESS}</td>
				<td class="dvtCellInfo">{$ADDRESS_STREET}</td>
				<td class="dvtCellInfo" >&nbsp;</td>
				<td class="dvtCellInfo">&nbsp;</td>
				</tr>
				<tr>
				<td class="dvtCellLabel" align="right" width="20%">{$UMOD.LBL_CITY}</td>
				<td class="dvtCellInfo" width="20%">{$ADDRESS_CITY}</td>
				<td class="dvtCellInfo" width="20%">&nbsp;</td>
				<td class="dvtCellInfo" width="20%">&nbsp;</td>
				</tr>
				<tr>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_STATE}</td>
				<td class="dvtCellInfo">{$ADDRESS_STATE}</td>
				<td class="dvtCellInfo">&nbsp;</td>
				<td class="dvtCellInfo">&nbsp;</td>
				</tr>
				<tr>
				<td class="dvtCellLabel" align="right">{$UMOD.LBL_POSTAL_CODE}</td>
				<td class="dvtCellInfo">{$ADDRESS_POSTALCODE}</td>
			    <td class="dvtCellInfo">&nbsp;</td>
			    <td class="dvtCellInfo">&nbsp;</td>
				</tr>
				<tr>
			    <td class="dvtCellLabel" align="right">{$UMOD.LBL_COUNTRY}</td>
			    <td class="dvtCellInfo">{$ADDRESS_COUNTRY}</td>
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
</div>
<script type="text/javascript">
contents = document.getElementsByClassName('tabset_content');
for(var i = 1; i < contents.length; i++)
{ldelim}
	contents[i].style.display = 'none';
{rdelim}
//to set the slider to the default size
document.getElementById('handle1').style.left='45px';
</script>
</div>
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
	{include file='SettingsSubMenu.tpl'}

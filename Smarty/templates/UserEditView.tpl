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
<style type="text/css">@import url(themes/blue/style.css);</style>

<script language="JavaScript" type="text/javascript">

 	var cp2 = new ColorPicker('window');
	
function pickColor(color)
{ldelim}
	ColorPicker_targetInput.value = color;
        ColorPicker_targetInput.style.backgroundColor = color;
{rdelim}	

function openPopup(){ldelim}
		window.open("index.php?module=Users&action=UsersAjax&file=RolePopup&parenttab=Settings","roles_popup_window","height=425,width=640,toolbar=no,menubar=no,dependent=yes,resizable =no");
	{rdelim}	
</script>	

<script language="javascript">
function check_duplicate()
{ldelim}
	var user_name = window.document.EditView.user_name.value;
	new Ajax.Request(
                'index.php',
                {ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
                        method: 'post',
                        postBody: 'module=Users&action=UsersAjax&file=Save&ajax=true&dup_check=true&userName='+user_name,
                        onComplete: function(response) {ldelim}
				if(response.responseText == 'SUCCESS')
			                document.EditView.submit();
       				else
			                alert(response.responseText);
                        {rdelim}
                {rdelim}
        );

{rdelim}

</script>



<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
{if $PARENTTAB neq ''}	
	{include file='SettingsMenu.tpl'}
{/if}
<td width="75%" valign="top">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr><td align="left">
			<table width="100%" cellpadding="5" cellspacing="0" border="0">
			<tr>
			<td colspan="2" style="padding:5px;">
				<span class="lvtHeaderText">
					{if $PARENTTAB neq ''}	
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
										{if $MODE eq 'edit'}
												<td width="95%"><span class="genHeaderGrayBig">{$USER_NAME}</span><br>
												<b class="small">{$UMOD.LBL_EDIT_VIEW} {$FIRST_NAME} {$LAST_NAME}</b>
											</td>
										{else}
												<td width="95%"><span class="genHeaderGrayBig">{$UMOD.LBL_NEW_USER_BUTTON_LABEL}</span><br>
												<b class="small">{$UMOD.LBL_CREATE_NEW_USER}</b>
											</td>
										{/if}
											
									</tr>
							</table>
	</td></tr>
	
	<tr><td class="padTab" align="left">
		<form name="EditView" method="POST" action="index.php" ENCTYPE="multipart/form-data">
			<input type="hidden" name="module" value="Users">
			<input type="hidden" name="record" value="{$ID}">
			<input type="hidden" name="mode" value="{$MODE}">
			<input type='hidden' name='parenttab' value='{$PARENTTAB}'>
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
		<table width="100%" border="0" cellpadding="0" cellspacing="0">

		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2">
		<table align="center" border="0" cellpadding="0" cellspacing="0" width="99%">
		  <tr>
			<td>
			  <table class="small" border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr>
			     <td id="prof" width="25%" align="center" nowrap="nowrap" class="dvtSelectedCell" onclick="fnVis('prof')" ><b>{$UMOD.LBL_USER_LOGIN_ROLE}</b></td>
			     <td class="dvtTabCache" width="10" nowrap="nowrap">&nbsp;</td>
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

									   {foreach key=header item=data from=$BLOCKS}
					     <tr>
											<td colspan=4 class="detailedViewHeader">
											<b>{$header}</b>
						</td>
					     </tr>
										<!-- Handle the ui types display -->
										{include file="DisplayFields.tpl"}

									   {/foreach}


							        <tr>
							            <td colspan=4><div align="center">
								    <input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accesskey="{$APP.LBL_SAVE_BUTTON_KEY}" class="small"  name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  "  onclick="this.form.action.value='Save'; return verify_data(EditView)" style="width: 70px;" type="button" />
								    <input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accesskey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="small" name="button" value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  " onclick="window.history.back()" style="width: 70px;" type="button" />
								        </div></td>
								</tr>
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
{if $PARENTTAB neq ''}
	{include file='SettingsSubMenu.tpl'}
{/if}

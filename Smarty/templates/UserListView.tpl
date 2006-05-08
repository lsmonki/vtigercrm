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
<style type="text/css">@import url(themes/blue/style.css);</style>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	{include file='SettingsMenu.tpl'}
<td width="75%" valign="top" class="showPanelBg">
		<form action="index.php" method="post" name="EditView" id="form">
		<input type='hidden' name='module' value='Users'>
<input type='hidden' name='action' value='EditView'>
<input type='hidden' name='return_action' value='ListView'>
<input type='hidden' name='return_module' value='Users'>
<input type='hidden' name='parenttab' value='Settings'>
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="small">
			<tr>
					<td colspan="2" style="padding:5px;">
							<span class="lvtHeaderText">
							<b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a>
									 > {$MOD.LBL_USER_MANAGEMENT} > Users</b></span>
									<hr noshade="noshade" size="1" />
					</td>
			  </tr>
			  <tr>
					<td style="padding:5px;border-right:1px dashed #CCCCCC;"> 
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td colspan="2" align="left"><img src="{$IMAGE_PATH}user.gif" align="absmiddle">&nbsp;<span class="genHeaderGray">{$MOD.LBL_USER_MANAGEMENT}</span></td>
									<td align="right" style="padding:5px; ">
											<input title='New User [Alt+N]' accessyKey='N' class='classBtn' type='submit' name='button' value='New User' >
								 	 </td>
								</tr>
								<tr><td colspan="3" style="border-bottom:1px dashed #CCCCCC;">&nbsp;</td></tr>
								<tr>
									<td align="left" style="padding:5px;">{$RECORD_COUNTS}</td>
									<td>&nbsp;</td>
									{$NAVIGATION}
								</tr>
							</table>
					</td>
					<td width="25%" rowspan="2" class="padTab" align="center">
								<div id="chPhoto" style="display:block;width:80%;">
								<table width="100%"   cellspacing="0" cellpadding="5" class="small">
								<tr><td align="left" colspan="2" style="border-bottom:1px dotted #CCCCCC;">
											<b>{$CMOD.LBL_STATISTICS}</b></td></tr>
								<tr><td align="right"><b>{$CMOD.LBL_TOTAL}</b></td>
								<td  align="left">{$USER_COUNT.user} {$CMOD.LBL_USERS}</td>	
								</tr>	
								<tr><td  align="right"><b>{$CMOD.LBL_ADMIN} :	</b></td>
								<td  align="left">{$USER_COUNT.admin} {$CMOD.LBL_USERS}</td>	
								</tr>	
								<tr><td  align="right"><b>{$CMOD.LBL_OTHERS}</b></td>
								<td  align="left">{$USER_COUNT.nonadmin} {$CMOD.LBL_USERS}</td>	
								</tr>	
							</table>
					    </div></td>
			</tr>
			<tr>
					<td width="75%" style="border-right:1px dashed #CCCCCC;padding:5px;">
							<table width="100%" border="0" cellpadding="5" cellspacing="1" class="small" style="background-color: rgb(204, 204, 204);">
                          <tbody>
                          	<tr>
							{foreach item=header from=$LIST_HEADER}
                              <td class="lvtCol">{$header}</td>
							{/foreach}	
                            </tr>
							{section name=entries loop=$LIST_ENTRIES}
                            <tr  class="lvtColData" onmouseover="this.className='lvtColDataHover'" onmouseout="this.className='lvtColData'" bgcolor="white">
                             {foreach item=listvalues from=$LIST_ENTRIES[entries]}
							  <td >{$listvalues}</td>
                              {/foreach}
							 </tr>
							{/section}	
                          </tbody>
                        </table>
					</td>
			</tr>
		</table>
	</form>
</td>
</tr>
</table>

</td>
</tr>
</table>
<div id="tempdiv" style="display:block;position:absolute;left:350px;top:200px;"></div>
<div id="status" style="display:none;position:absolute;background-color:#bbbbbb;vertical-align:center;left:887px;top:0px;height:17px;">Processing Request...</div>
<script>
function ajaxSaveResponse(response)
{ldelim}
	hide("status");
	document.getElementById("tempdiv").innerHTML=response.responseText;
{rdelim}

function DeleteProfile(userid)
{ldelim}
	show("status");
	var ajaxObj = new Ajax(ajaxSaveResponse);
	var urlstring = "module=Users&action=UsersAjax&file=UserDeleteStep1&record="+userid;
	ajaxObj.process("index.php?",urlstring);
{rdelim}
</script>

	{include file='SettingsSubMenu.tpl'}


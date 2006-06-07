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
									 > {$MOD.LBL_USER_MANAGEMENT} > {$MOD.LBL_USERS}</b></span>
									<hr noshade="noshade" size="1" />
					</td>
			  </tr>
			  <tr>
					<td style="padding:5px;"> 
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td colspan="2" align="left"><img src="{$IMAGE_PATH}user.gif" align="absmiddle">&nbsp;<span class="genHeaderGray">{$MOD.LBL_USER_MANAGEMENT}</span></td>
									<td align="right" style="padding:5px; ">
											<input title="{$CMOD.LBL_NEW_USER_BUTTON_TITLE}" accessyKey="{$CMOD.LBL_NEW_USER_BUTTON_KEY}" class='classBtn' type='submit' name='button' value="{$CMOD.LBL_NEW_USER_BUTTON_LABEL}" >
								 	 </td>
								</tr>
								<tr><td colspan="3" style="border-bottom:1px dashed #CCCCCC;">&nbsp;</td></tr>
							</table>
					</td>
					<td>&nbsp;</td>
			</tr>
			<tr><td colspan=2>
					<div id="ListViewContents">
						{include file="UserListViewContents.tpl"}
					</div>
					
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
{literal}
<script>
function getListViewEntries_js(module,url)
{
	$("status").style.display="inline";
        new Ajax.Request(
		'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: 'module=Users&action=UsersAjax&file=ListView&ajax=true&'+url,
                        onComplete: function(response) {
                                $("status").style.display="none";
				$("ListViewContents").innerHTML= response.responseText;
                        }
                }
        );
}

function deleteUser(userid)
{
	$("status").style.display="inline";
        new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: 'action=UsersAjax&file=UserDeleteStep1&return_action=ListView&return_module=Users&module=Users&parenttab=Settings&record='+userid,
                        onComplete: function(response) {
                                $("status").style.display="none";
				$("tempdiv").innerHTML= response.responseText;
                        }
                }
        );
}

function transferUser(del_userid)
{
	$("status").style.display="inline";
	$("DeleteLay").style.display="none";
	var trans_userid=$('transfer_user_id').options[$('transfer_user_id').options.selectedIndex].value;
        new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: 'module=Users&action=UsersAjax&file=DeleteUser&ajax=true&delete_user_id='+del_userid+'&transfer_user_id='+trans_userid,
                        onComplete: function(response) {
                                $("status").style.display="none";
                                $("ListViewContents").innerHTML= response.responseText;
                        }
                }
        );

}
</script>
{/literal}

	{include file='SettingsSubMenu.tpl'}


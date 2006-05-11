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
<td width="75%" valign="top">
	<form action="index.php" method="post" name="new" id="form">
<input type="hidden" name="module" value="Users">
<input type="hidden" name="action" value="createnewgroup">
<input type="hidden" name="groupId" value="{$GROUPID}">
<input type="hidden" name="mode" value="edit">
<input type="hidden" name="parenttab" value="Settings">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
    <tr>
      <td class="showPanelBg" valign="top" width="95%"  style="padding-left:20px; "><br />
          <span class="lvtHeaderText"><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a> > {$MOD.LBL_USER_MANAGEMENT} > {$CMOD.LBL_GROUP_MEMBERS_LIST}</b> </span>
          <hr noshade="noshade" size="1" />
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td width="95%" style="padding-left:20px;" valign="top"><table width="95%" cellpadding="5" cellspacing="0" class="leadTable" align="center">
          <tr>
            <td style="padding:5px;border-bottom:2px dotted #CCCCCC;" width="5%" ><img src="{$IMAGE_PATH}groups.gif" width="48" height="48" align="absmiddle" /> </td>
            <td style="padding:5px;border-bottom:2px dotted #AAAAAA;"><span class="genHeaderGrayBig">{$GROUPINFO.0.groupname} {$APP.LBL_GROUP}</span><br />
                <span class="big">{$CMOD.LBL_DETAIL_VIEW} {$GROUPINFO.0.groupname} {$APP.LBL_GROUP}</span> </td>
          </tr>
          <tr>
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" ><table width="100%" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                  <td colspan="4" class="detailedViewHeader"><b>{$CMOD.LBL_GROUP_DETAILS}</b></td>
                </tr>
                <tr>
                  <td class="dvtCellLabel" width="5%">&nbsp;</td>
				  <td class="dvtCellLabel" align="right" width="25%"><b>{$CMOD.LBL_GROUP_NAME} {$CMOD.LBL_COLON}</b></td>
                  <td class="dvtCellInfo" align="left" width="25%">{$GROUPINFO.0.groupname}</td>
                  <td class="dvtCellInfo" width="45%">&nbsp;</td>
                </tr>
                <tr>
                  <td class="dvtCellLabel" width="5%">&nbsp;</td>
				  <td class="dvtCellLabel" align="right"><b>{$CMOD.LBL_DESCRIPTION} {$CMOD.LBL_COLON}</b></td>
                  <td colspan="2" align="left" class="dvtCellInfo">{$GROUPINFO.0.description}</td>
                </tr>
                <tr>
                  <td colspan="4"  class="dvtCellInfo">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="4"  class="detailedViewHeader"><b>{$CMOD.LBL_MEMBER_LIST}</b></td>
                </tr>
                <tr>
                  	{foreach key=type item=details from=$GROUPINFO.1} 
							{if $details.0 neq ''}		
                      	  	<tr>
								{if $type == "User"}
								<td class="dvtCellLabel" valign="top" align="left" width="5%">
										<img src="{$IMAGE_PATH}user_icon.gif" align="absmiddle">
								</td>
								{/if}
								{if $type == "Role" }
								<td class="dvtCellLabel" valign="top" align="left" width="5%">
										<img src="{$IMAGE_PATH}roles_icon.gif" align="absmiddle">
								</td>
								{/if}
								{if $type == "Role and Subordinates" }
								<td class="dvtCellLabel" valign="top" align="left" width="5%">
										<img src="{$IMAGE_PATH}roles_icon.gif" align="absmiddle">
								</td>
								{/if}
								{if $type == "Group" }
								<td class="dvtCellLabel" valign="top" align="left" width="5%">
										<img src="{$IMAGE_PATH}groups_icon.gif" align="absmiddle">
								</td>
								{/if}
								<td class="dvtCellLabel" valign="top" align="right" width="15%"><b>{$type} {$CMOD.LBL_COLON} </b></td>
                          		<td class="dvtCellInfo" width="45%" colspan="2">
									{foreach item=element from=$details}
											<a href="index.php?module=Users&action={$element.memberaction}&{$element.actionparameter}={$element.memberid}">{$element.membername}</a><br />
									{/foreach}
                              </td>
                        </tr>
						{/if}
						{/foreach}	
                 <tr>
                  <td colspan="4"  class="dvtCellInfo" align="center">
				  	<!-- <input title="Back" accessKey="C" class="classBtn" onclick="window.history.back();" type="button" name="New" value=" <  Back " > &nbsp; -->
					 <input value="   {$APP.LBL_EDIT_BUTTON_LABEL}   " title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="classBtn" type="submit" name="Edit" >
                    &nbsp;<input value=" {$APP.LBL_DELETE_BUTTON_LABEL} " title="{$APP.LBL_DELETE_BUTTON_TITLE}" accessKey="{$APP.LBL_DELETE_BUTTON_KEY}" class="classBtn" type="button" name="Delete" onClick="deletegroup('{$GROUPID}','{$GROUP_NAME}')";>	
				</td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td colspan="2">&nbsp;</td>
          </tr>
      </table></td>
    </tr>
  </table></td>
</tr>
</table></from>
</td>
</tr>
</table>
<script>
function deletegroup(id,groupname)
{ldelim}
		if(confirm("Are you sure you want to delete the group "+groupname+" ?"))
			document.location.href="index.php?module=Users&action=DeleteGroup&groupId="+id;	
		else
			return false;
{rdelim}
</script>
	{include file='SettingsSubMenu.tpl'}


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

<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
<tr>
<td class="showPanelBg" valign="top" width="100%" colspan="3" style="padding-left:20px; "><br />
<span class="lvtHeaderText"><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a> > {$MOD.LBL_USER_MANAGEMENT} > {$MOD.LBL_DEFAULT_ORGANIZATION_FIELDS}</b></span>
<hr noshade="noshade" size="1" />
</td>
</tr>
<tr>
<td width="75%" style="padding-left:20px;" valign="top">
	
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<form action="index.php" method="post" name="new" id="form">
		<input type="hidden" name="module" value="Users">
		<input type="hidden" name="parenttab" value="Settings">
		<input type="hidden" name="fld_module" id="fld_module">
		{if $MODE neq 'view'}
		<input type="hidden" name="action" value="UpdateDefaultFieldLevelAccess">
		{else}
		<input type="hidden" name="action" value="EditDefOrgFieldLevelAccess">
		{/if}	
		<tbody><tr>
		<td style="font-size: 1px; font-family: Arial,Helvetica,sans-serif;" height="6" width="7"><img src="{$IMAGE_PATH}top_left.jpg" align="top"></td>
		<td style="font-size: 1px; font-family: Arial,Helvetica,sans-serif; height: 6px;" bgcolor="#ebebeb"></td>
		<td style="font-size: 1px; font-family: Arial,Helvetica,sans-serif;" height="6" width="8"><img src="{$IMAGE_PATH}top_right.jpg" align="top" height="6" width="8"></td>
	 	</tr>
		<tr>
		<td bgcolor="#ebebeb" width="7"></td>
		<td bgcolor="#ebebeb">	
			<table border="0" cellpadding="3" cellspacing="0" width="100%">
			<tbody><tr>
			<td class="genHeaderSmall" height="25" valign="middle">{$CMOD.LBL_GLOBAL_FIELDS_MANAGER}</td>
			<td align="right">&nbsp;</td>
			</tr>
			<tr><td colspan="2"></td></tr>
		    <tr>
			<td colspan="2" nowrap="nowrap">
				
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody><tr bgcolor="#ffffff">
				<td style="padding: 10px;" align="left">
				<b>{$CMOD.LBL_SELECT_SCREEN}</b>
				
				<select name="selectmodule" style="width: 200px; font-size: 10px;" onChange="changemodules(this)">
				{foreach item=module from=$FIELD_INFO}
				{if $module == $DEF_MODULE}
					<option selected value='{$module}'>{$APP.$module}</option>
				{else}		
					<option value='{$module}' >{$APP.$module}</option>
				{/if}
				{/foreach}
				</select>
				
				</td>
				<td style="padding-right: 10px;" align="right">&nbsp;
				{if $MODE neq 'edit'}
				<input title="Edit" accessKey="E" class="classBtn" type="submit" name="Edit" value="{$APP.LBL_EDIT_BUTTON}"></td>
				{else}
				<input title="save" accessKey="S" class="classBtn" type="submit" name="Save" value="{$APP.LBL_SAVE_LABEL}"></td>
				{/if}
			    </tr>
				<tr><td colspan="2" height="7"></td></tr>
				<tr bgcolor="#ffffff">
				<td style="padding: 10px;" colspan="2">
					
					{foreach key=module item=info from=$FIELD_LISTS}
					{if $module == $DEF_MODULE}
					<div id="{$module}_fields" style="display:block">	
					{else}
					<div id="{$module}_fields" style="display:none">	
					{/if}	
					<table class="small" border="0" cellpadding="5" cellspacing="0" width="100%">
	                <tbody><tr><td colspan="4" style="border-bottom: 1px dashed rgb(204, 204, 204);">
					<b>{$CMOD.LBL_FIELDS_AVLBL} {$APP.$module} </b><br>
	  				 {$CMOD.LBL_FIELDS_SELECT_DESELECT}
				    </td></tr>
					<tr><td colspan="4">&nbsp;</td></tr>
					
					{foreach item=elements from=$info}
					<tr>
					
						{foreach item=elementinfo from=$elements}
        	            <td width="5%">{$elementinfo.1}</td>
						<td width="45%">{$elementinfo.0}</td>
				   		{/foreach}
						
            	    </tr>
					{/foreach}

					<tr><td colspan="4">&nbsp;</td></tr>
                	</tbody></table>
					</div>
					{/foreach}
				
				</td>
 				</tr>
				<tr><td colspan="2" style="border-top: 1px dashed rgb(204, 204, 204);padding:10px;" bgcolor="white" align="center">
				<input name="Cancel" value=" {$APP.LBL_CANCEL_BUTTON_LABEL} " class="classBtn" type="button" onClick="window.history.back();">
				</td></tr>
				</tbody>
				</table>

			</td>
		  	</tr>
			</tbody></form></table>
		 <!-- End of Module Display -->
    </td>
	<td bgcolor="#ebebeb" width="8"></td>
    </tr>
 	<tr>
	<td style="font-size: 1px; font-family: Arial,Helvetica,sans-serif;" height="8" width="7"><img src="{$IMAGE_PATH}bottom_left.jpg" align="bottom"></td>
	<td style="font-size: 1px;" bgcolor="#ebebeb" height="8"></td>
	<td style="font-size: 1px; font-family: Arial,Helvetica,sans-serif;" height="8" width="8"><img src="{$IMAGE_PATH}bottom_right.jpg" align="bottom"></td>
	</tr>
	</tbody></table>
		  
	
</td>
<td width="1%" style="border-right:1px dotted #CCCCCC;">&nbsp;</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
	{include file='SettingsSubMenu.tpl'}
<script>
var def_field='{$DEF_MODULE}_fields';
{literal}
function changemodules(selectmodule)
{
	hide(def_field);
	module=selectmodule.options[selectmodule.options.selectedIndex].value;
	document.getElementById('fld_module').value = module; 
	def_field = module+"_fields";
	show(def_field);
}
</script>
{/literal}


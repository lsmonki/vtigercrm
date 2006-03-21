<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
<style type="text/css">@import url(themes/blue/style.css);</style>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	{include file='SettingsMenu.tpl'}
<td width="75%" valign="top">

<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
<tr>
<td class="showPanelBg" valign="top" width="100%" colspan="3" style="padding-left:20px; "><br />
<span class="lvtHeaderText"><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a> > {$MOD.LBL_USER_MANAGEMENT} > {$MOD.LBL_PROFILES}</b></span>
<hr noshade="noshade" size="1" />
</td>
</tr>
<tr>
<td width="75%" style="padding-left:20px;" valign="top">
	
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<form action="index.php" method="post" name="new" id="form">
		<input type="hidden" name="module" value="Users">
		<input type="hidden" name="parenttab" value="Settings">
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
			<td class="genHeaderSmall" height="25" valign="middle">Global Fields Manager</td>
			<td align="right">&nbsp;</td>
			</tr>
			<tr><td colspan="2"></td></tr>
		    <tr>
			<td colspan="2" nowrap="nowrap">
				
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tbody><tr bgcolor="#ffffff">
				<td style="padding: 10px;" align="left">
				<b>Select the Screen / Module :</b>
				
				<select name="selectmodule" style="width: 200px; font-size: 10px;" onChange="changemodules(this)">
				{foreach item=module from=$FIELD_INFO}
				<option>{$module}</option>
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
					{if $module == 'Leads'}
					<div id="{$module}_fields" style="display:block">	
					{else}
					<div id="{$module}_fields" style="display:none">	
					{/if}	
					<table class="small" border="0" cellpadding="5" cellspacing="0" width="100%">
	                <tbody><tr><td colspan="4" style="border-bottom: 1px dashed rgb(204, 204, 204);">
					<b>Fields Available in {$module} </b><br>
	  				Select or De-Select fields to be shown
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
				<input name="Cancel" value=" Cancel " class="classBtn" type="button" onClick="window.history.back();">
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
{literal}
<script>
var def_field='Leads_fields';
function changemodules(selectmodule)
{
	hide(def_field);
	module=selectmodule.options[selectmodule.options.selectedIndex].value;
	def_field = module+"_fields";
	show(def_field);
}
</script>
{/literal}


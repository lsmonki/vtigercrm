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
<span class="lvtHeaderText"><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a> > {$MOD.LBL_USER_MANAGEMENT} > {$MOD.LBL_PROFILES}</b></span>
<hr noshade="noshade" size="1" />
</td>
</tr>
<tr>
<td width="75%" style="padding-left:20px;" valign="top">
	
	<table width="75%" border="0" cellpadding="5" cellspacing="0" align="center">
	<tr>
															<td width="5%" style="border-bottom:1px dashed #CCCCCC;">
																	<img src="{$IMAGE_PATH}profile.gif" align="absmiddle">
															</td>
															<td style="border-bottom:1px dashed #CCCCCC;"> 
																	<span class="genHeaderGrayBig">{$CMOD.LBL_PROFILE_DETAIL_VIEW}</span><br>
																	<span	class="genHeaderSmall">{$PROFILE_NAME}</span>
															</td>
													</tr>
	<tr>
	<td colspan="2"><table border="0" cellspacing="0" cellpadding="0" width="100%">
	<tr class="small">
	<td><img src="{$IMAGE_PATH}prvPrfTopLeft.gif" /></td>
	<td class="prvPrfTopBg" width="100%" ></td>

	<td><img src="{$IMAGE_PATH}prvPrfTopRight.gif" /></td>
	</tr>
	</table>
	<form action="index.php" method="post" name="new" id="form">
	<input type="hidden" name="module" value="Users">		
	<input type="hidden" name="action" value="profilePrivileges">		
	<input type="hidden" name="parenttab" value="Settings">
	<input type="hidden" name="return_action" value="profilePrivileges">		
	<input type="hidden" name="mode" value="edit">	
	<input type="hidden" name="profileid" value="{$PROFILEID}">	
	
	<table border="0" cellspacing="0" cellpadding=	"0" width="100%" class="prvPrfOutline">
	<tr>
	<td>
	<!-- tabs -->
	<table border="0" cellspacing="0" cellpadding="5" width="100%" class="small">
	<tr>
	<td width="20%" id="prvPrfTab1" class="prvPrfUnSelectedTab" align="center" style="height:31px;" onClick="toggleshowhide('global_privileges','prvPrfTab1');">{$CMOD.LBL_GLOBAL_PRIVILEGES}</td>
	<td width="20%" id="prvPrfTab2" class="prvPrfUnSelectedTab" align="center" onClick="toggleshowhide('tab_privileges','prvPrfTab2');">{$CMOD.LBL_TAB_PRIVILEGES}</td>
	<td width="20%" id="prvPrfTab3" class="prvPrfUnSelectedTab" align="center" onClick="toggleshowhide('standard_privileges','prvPrfTab3');">{$CMOD.LBL_STANDARD_PRIVILEGES}</td>
	<td width="20%" id="prvPrfTab4" class="prvPrfUnSelectedTab" align="center" onClick="toggleshowhide('field_privileges','prvPrfTab4');">{$CMOD.LBL_FIELD_PRIVILEGES}</td>
	<td width="20%" id="prvPrfTab5" class="prvPrfUnSelectedTab" align="center" onClick="toggleshowhide('utility_privileges','prvPrfTab5');">{$CMOD.LBL_UTILITIES}</td>
	</tr>
	</table>
		<div id="global_privileges" style="display:none;">
		<!-- Headers -->
		<table border="0" cellspacing="0" cellpadding="5" width="100%" class="prvPrfBgImgGlobal">
		<tr>
		<td>
		<table border="0" cellspacing="0" cellpadding="5" width="100%" class="small">
		<tr>
		<td><!-- Module name heading -->
		<table border="0" cellspacing="0" cellpadding="2" class="small">
		<tr>
		<td valign="top"><img src="{$IMAGE_PATH}prvPrfHdrArrow.gif"/> </td>
	
		<td class="prvPrfBigText"><b> {$CMOD.LBL_GLOBAL_PRIVILEGES} {$CMOD.LBL_FOR} "{$PROFILE_NAME}"</b> <br />
		<font class="small">{$CMOD.LBL_GLOBAL_MESG_OPTION} </font> </td>
		<td class="small" style="padding-left:10px" align="right"></td>
		</tr>
		</table></td>
		<td align="right" valign="bottom">&nbsp; </td>

		</tr>
		</table>
		<!-- privilege lists -->
		<table border="0" cellspacing="0" cellpadding="0" width="100%" >
		<tr>
		<td align="center" style="height:10px"><img src="{$IMAGE_PATH}prvPrfLine.gif" style="width:100%;height:1px" /></td>
		</tr>
		</table>
		<table border="0" cellspacing="0" cellpadding="10" width="100%">
		<tr>
		<td >
		<table border="0" cellspacing="0" cellpadding="5" width="90%" class="small" align="center">
		<tr>
		<td class="prvPrfTexture" style="width:20px">&nbsp;</td>
		<td width="97%" valign="top">
		<table border="0" cellspacing="0" cellpadding="2" width="100%" class="small">
		<tr id="gva">
		<td valign="top">{$GLOBAL_PRIV.0}</td>
		<td ><b>{$CMOD.LBL_VIEW_ALL}</b> </td>
		</tr>
		<tr >
		<td valign="top"></td>
		<td width="100%" >{$CMOD.LBL_ALLOW} "{$PROFILE_NAME}" {$CMOD.LBL_MESG_VIEW}</td>
		</tr>
		<tr>
		<td>&nbsp;</td>
		</tr>
		<tr>
		<td valign="top">{$GLOBAL_PRIV.1}</td>
		<td ><b>{$CMOD.LBL_EDIT_ALL}</b> </td>
		</tr>
		<tr>
		<td valign="top"></td>
		<td > {$CMOD.LBL_ALLOW} "{$PROFILE_NAME}" {$CMOD.LBL_MESG_EDIT}</td>
		</tr>
		</table>
		</td>
		</tr>	
		</table></td>
		</tr>
		</table></td>
		</tr>
		</table>
		</div>
		
		<div id="standard_privileges" style="display:none;">
		<table border=0 cellspacing=0 cellpadding=5 width=100% >
		<tr>
		<td>
		<table border=0 cellspacing=0 cellpadding=5 width=100% class=small>
		<tr>
		<td>
		<!-- Module name heading -->
		<table border=0 cellspacing=0 cellpadding=2 class=small>
		<tr>
		<td valign=top >
		<img src="{$IMAGE_PATH}prvPrfHdrArrow.gif">
		</td>
		<td class="prvPrfBigText">
		<b> {$CMOD.LBL_STANDARD_PRIVILEGES} {$CMOD.LBL_FOR} "{$PROFILE_NAME}"</b> <br>
		<font class=small>{$CMOD.LBL_STD_MESG_OPTION} </font> 
		</td>
		</tr>
		</table>
		</td>
		<td align=right valign=bottom>&nbsp;
		
		</td>
		</tr>
		</table>
		<!-- privilege lists -->
		<table border=0 cellspacing=0 cellpadding=0 width=100% >
		<tr>
		<td align=center style="height:10px"><img src="{$IMAGE_PATH}prvPrfLine.gif" style="width:100%;height:1px"></td>
		</tr>
		</table>
		<table border=0 cellspacing=0 cellpadding=10 width=100%>
		<tr>
		<td >
		<table border=0 cellspacing=0 cellpadding=5 width=90% class=small align=center >
		<tr>
		<td class="prvPrfTexture" style="width:20px">&nbsp;</td>
		<td width=97% valign=top onMouseOver="this.className='prvPrfHoverOn'" onMouseOut="this.className='prvPrfHoverOff'" >
		<table border=0 cellspacing=0 cellpadding=5 width=100% class=small>
		
		<tr>
		<td><b>{$CMOD.LBL_ENTITY}</b></td>
		<td><b>{$CMOD.LBL_CREATE_EDIT}</b></td>
		<td><b>{$CMOD.LBL_DELETE}</b></td>
		<td><b>{$CMOD.LBL_VIEW}</b></td>
		</tr>
		
		{foreach item=value from=$STANDARD_PRIV}
		<tr>
		<td>{$APP[$value.0]}</td>
		<td>{$value.1}</td>
		<td>{$value.2}</td>
		<td>{$value.3}</td>
		</tr>
		{/foreach}
		
		</table>
		</td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		</div>
		
		<div id="tab_privileges" style="display:none;">
		<table border=0 cellspacing=0 cellpadding=5 width=100% >
		<tr>
		<td>
		<table border=0 cellspacing=0 cellpadding=5 width=100% class=small>
		<tr>
		<td>
		<!-- Module name heading -->
		<table border=0 cellspacing=0 cellpadding=2 class=small>
		<tr>
		<td valign=top >
		<img src="{$IMAGE_PATH}prvPrfHdrArrow.gif">
		</td>
		<td class="prvPrfBigText">
		<b> {$CMOD.LBL_TAB_PRIVILEGES} {$CMOD.LBL_FOR} "{$PROFILE_NAME}"</b> <br>
		<font class=small>{$CMOD.LBL_TAB_MESG_OPTION} </font> 
		</td>
		</tr>
		</table>
		</td>
		<td align=right valign=bottom>&nbsp;
		
		</td>
		</tr>
		</table>
		<!-- privilege lists -->
		<table border=0 cellspacing=0 cellpadding=0 width=100% >
		<tr>
		<td align=center style="height:10px"><img src="{$IMAGE_PATH}prvPrfLine.gif" style="width:100%;height:1px"></td>
		</tr>
		</table>
		<table border=0 cellspacing=0 cellpadding=10 width=100%>
		<tr>
		<td >
		<!-- Home tab -->
		<table border=0 cellspacing=0 cellpadding=5 width=90% class=small align=center >
		<tr>
		<td class="prvPrfTexture" style="width:20px">&nbsp;</td>
		<td width=97% valign=top onMouseOver="this.className='prvPrfHoverOn'" onMouseOut="this.className='prvPrfHoverOff'" >
		<table border=0 cellspacing=0 cellpadding=5 width=100% class=small>
		

		{foreach item=value from=$TAB_PRIV}
		<tr>
		{foreach item=element from=$value}
		<td width=35%>{$APP[$element.0]}</td>
		<td width=15%>{$element.1}</td>
		{/foreach}
		</tr>
		{/foreach}
	
		</table>
		</td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		</td>
		</tr>

		</table>
		</div>
		
		<div id="utility_privileges" style="display:none;">
		<table border=0 cellspacing=0 cellpadding=5 width=100% >
		<tr>
		<td>
		<table border=0 cellspacing=0 cellpadding=5 width=100% class=small>
		<tr>
		<td>
		<!-- Module name heading -->
		<table border=0 cellspacing=0 cellpadding=2 class=small>
		<tr>
		<td valign=top >
		<img src="{$IMAGE_PATH}prvPrfHdrArrow.gif">
		</td>
		<td class="prvPrfBigText">
		<b> {$CMOD.LBL_UTILITY_PRIVILEGES} {$CMOD.LBL_FOR} "{$PROFILE_NAME}"</b> <br>
		<font class=small>{$CMOD.LBL_UTILITY_MESG_OPTION}  </font> 
		</td>
		</tr>
		</table>
		</td>
		<td align=right valign=bottom>&nbsp;
		
		</td>
		</tr>
		</table>
		<!-- privilege lists -->
		<table border=0 cellspacing=0 cellpadding=0 width=100% >
		<tr>
		<td align=center style="height:10px"><img src="{$IMAGE_PATH}prvPrfLine.gif" style="width:100%;height:1px"></td>
		</tr>
		</table>
		<table border=0 cellspacing=0 cellpadding=10 width=100%>
		<tr>
		<td >
		<!-- Home tab -->
		<table border="0" cellspacing="0" cellpadding="5" width="90%" class="small" align="center" >
		
		{foreach key=module item=value from=$UTILITIES_PRIV}
		<tr>
		<td colspan="4" style="border-bottom:1px solid #efefef"><b>{$module}</b></td>
		</tr>
		<tr>
		<td class="prvPrfTexture" style="width:20px">&nbsp;</td>
		<td width="97%" valign="top" onmouseover="this.className='prvPrfHoverOn'" onmouseout="this.className='prvPrfHoverOff'" >
		<table border="0" cellspacing="0" cellpadding="5" width="100%" class="small">
		{foreach item=element from=$value}
		<tr>
		<td width=25%>{$APP[$element.0.0]}</td>
		<td width=25%>{$element.0.1}</td>
		<td width=25%>{$APP[$element.1.0]}</td>
		<td width=25%>{$element.1.1}</td>
		{/foreach}
		</tr>
		</table>
		</td>
		</tr>
		
		{/foreach}
		</table>
		</td>
		</tr>
		</table>
		</td>
		</tr>

		</table>

		</div>
		
		
		<div id="field_privileges" style="display:none;">
		<table border="0" cellspacing="0" cellpadding="5" width="100%" >
		<tr>
		<td><table border="0" cellspacing="0" cellpadding="5" width="100%" class="small">
		<tr>
		<td><!-- Module name heading -->
		<table border="0" cellspacing="0" cellpadding="2" class="small">
		<tr>
		<td valign="top" ><img src="{$IMAGE_PATH}prvPrfHdrArrow.gif" /> </td>

		<td class="prvPrfBigText"><b> {$CMOD.LBL_FIELD_PRIVILEGES} {$CMOD.LBL_FOR} "{$PROFILE_NAME}"</b> <br />
		<font class="small">{$CMOD.LBL_FIELD_MESG_OPTION} </font> </td>
		</tr>
		</table></td>
		<td align="right" valign="bottom">
		</td>
		</tr>
		</table>
		<!-- privilege lists -->
		<table border="0" cellspacing="0" cellpadding="0" width="100%" >
		<tr>
		<td align="center" style="height:10px"><img src="{$IMAGE_PATH}prvPrfLine.gif" style="width:100%;height:1px" /></td>
		</tr>

		</table>
		<table border="0" cellspacing="0" cellpadding="10" width="100%">
		<tr>
		<td >
		<table border="0" cellspacing="0" cellpadding="5" width="100%" class="small">
		<tr>
		<td>
		<select id="module_list" name="module_list" onchange="showmoduleperm(this)">
		{foreach key=module item=label from=$PRI_FIELD_LIST}
			<option value="{$label}">{$APP.$label}</option>
		{/foreach}
		</td>
		<td width=50%>&nbsp;</td>
		</tr>
		<tr>
		<td colspan=2>
	
		{foreach key=module item=value from=$FIELD_PRIVILEGES}
		<div id="field_{$module}" style="display:none;">
		<table border="0" cellspacing="0" cellpadding="5" width="100%" class="small">
		{foreach item=row_value from=$value}
		<tr>
		{foreach item=element from=$row_value}
		<td>{$element.0}</td>
		<td>{$element.1}</td>
		{/foreach}
		</tr>
		{/foreach}
		</table>
		</div>
		{/foreach}
		
		</td>
		</tr>
		</table></td>
		</tr>
		</table></td>
		</tr>
		</table>
		</div>
		
		</td>
			
		</tr>
		</table>
	<table border="0" cellspacing="0" cellpadding="0" width="100%" class="small">
	<tr>
	<td><img src="{$IMAGE_PATH}prvPrfBottomLeft.gif" /></td>
	<td class="prvPrfBottomBg" width="100%"></td>
	<td><img src="{$IMAGE_PATH}prvPrfBottomRight.gif" /></td>
	</tr>
	</table></td>

	</tr>
	</table></td>
	</tr>	
	<tr><td colspan="2" style="border-bottom:1px dashed #CCCCCC;">&nbsp;</td></tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
	<td colspan="2" align="center">
	<input type="hidden" id="selected_tab" name="selected_tab" value="{$SELECTED_TAB}">		
	<input type="hidden" id="selected_module" name="selected_module" value="{$SELECTED_MODULE}">		
	<input type="submit" value=" {$APP.LBL_EDIT_BUTTON_LABEL} " onClick="page_submit(this);" name="edit" class="classBtn"/>&nbsp;&nbsp;
	<input type="button" value=" {$APP.LBL_CANCEL_BUTTON_LABEL} " name="Cancel" onClick="window.history.back();" class="classBtn"/>

	</td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr><td colspan="2" style="border-top:1px solid #CCCCCC;">&nbsp;</td></tr>
	</table>
	
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

<script language="javascript" type="text/javascript">
var Selected_div= '{$SELECTED_TAB}';
divarray = new Array('global_privileges','tab_privileges','standard_privileges','field_privileges','utility_privileges'); 	
tabarray = new Array('prvPrfTab1','prvPrfTab2','prvPrfTab3','prvPrfTab4','prvPrfTab5'); 	
var defaultmodule = '{$SELECTED_MODULE}';
function set_default()
{ldelim}
	show (Selected_div);
	show (defaultmodule);
	for(i = 0; i < divarray.length ;i++)
	{ldelim}
		if(divarray[i] == Selected_div)
			break;	
	{rdelim}
	document.getElementById(tabarray[i]).className="prvPrfSelectedTab";
	module_combo = document.getElementById('module_list');
	module_combo_selected = defaultmodule.replace(/field_/gi,'')
	for(i = 0; i < module_combo.length;i++)
	{ldelim}
		if(module_combo.options[i].value == module_combo_selected)
		{ldelim}	
			module_combo.options[i].selected = true;	
			break;	
		{rdelim}	
	{rdelim}
{rdelim}
set_default();
function toggleshowhide(currentselecteddiv,currentselectedtab)
{ldelim}
	for(i = 0; i < divarray.length ;i++)
	{ldelim}
		if(Selected_div == divarray[i])
			break;	
	{rdelim}	
	hide (Selected_div);
	document.getElementById(tabarray[i]).className="prvPrfUnSelectedTab";
	show (currentselecteddiv);
	document.getElementById(currentselectedtab).className="prvPrfSelectedTab";
	Selected_div = currentselecteddiv;
	document.getElementById('selected_tab').value =	Selected_div; 	
{rdelim}	
function showmoduleperm(selectmodule_view)
{ldelim}
	hide(defaultmodule);
	defaultmodule='field_'+selectmodule_view.options[selectmodule_view.options.selectedIndex].value;
	show(defaultmodule);
	document.getElementById('selected_module').value = defaultmodule;	
{rdelim}	
	
</script>

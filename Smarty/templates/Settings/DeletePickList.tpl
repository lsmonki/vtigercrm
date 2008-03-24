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

{if $MODE eq 'delete'}
	<div style="position:relative;display: block;" id="orgLay" class="layerPopup">
	<table border="0" cellpadding="5" cellspacing="0" class="layerHeadingULine">
		<tr>
			<td class="layerPopupHeading" align="left" width="40%" nowrap>{$APP.DELETE_PICKLIST_VALUES} - {$FIELDLABEL}</td>
			<td align="right" width="60%"><img src="{$IMAGE_PATH}close.gif" align="middle" border="0" onclick="Myhide('deletediv');"></td>
		</tr>
	</table>
	
			<table border=0 cellspacing=0 cellpadding=5 width=100%>
			<tr><td valign=top align=left width=200px;>
			<select id="availPickList" multiple="multiple" wrap size="20" name="availList" style="width:200px;border:1px solid #666666;font-family:Arial, Helvetica, sans-serif;font-size:11px;">
				{foreach item=pick_val from=$PICKVAL}
					<option value="{$pick_val}">{$pick_val}</option>
				{/foreach}
			</select>
			</td>
			<td valign=top align=left>
				<!--img src="{$IMAGE_PATH}movecol_del.gif" onmouseover="this.src='{$IMAGE_PATH}movecol_del_over.gif'" onmouseout="this.src='{$IMAGE_PATH}movecol_del.gif'" onclick="" onmousedown="this.src='{$IMAGE_PATH}movecol_del_down.gif'" align="absmiddle" border="0" -->
				<input type="button" value="{$APP.LBL_APPLY_LABEL}" name="del" class="crmButton small edit" onclick="delPickList(this,'{$MODULE}',{$NONEDIT_FLAG});">
				<input type="button" value="{$APP.LBL_CANCEL_BUTTON_LABEL}" name="cancel" class="crmButton small cancel" onclick="Myhide('deletediv');">
			</td>
			
			</tr>
			
			{if is_array($NONEDITPICKLIST)}
			<tr><td colspan=3>
				<table border=0 cellspacing=0 cellpadding=0 width=100%>
				<tr><td><b><u>{$MOD.LBL_NON_EDITABLE_PICKLIST_ENTRIES} :</u></b></td></tr>
			{foreach item=nonedit from=$NONEDITPICKLIST}
				<tr><td>
					<b>{$nonedit}</b>
				</td></tr>
				
			{/foreach}
				</table>
			</td></tr>	
			{/if}
			</table>
		


</div>
{else}
	<div id="ssignedPick" class="layerPopup">
		{$OUTPUT}
	</div>		
{/if}

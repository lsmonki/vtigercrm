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
<!-- Customized Reports Table Starts Here  -->
	<form>
	<input id="folder_ids" name="folderId" type="hidden" value='{$FOLDE_IDS}'>
	{foreach item=reportfolder from=$REPT_CUSFLDR}
	<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">
		<tr>
		<td class="dvtCellLabel" style="padding:5px;" width="5%" align="right">
		<td class="dvtCellLabel" style="padding:5px;" width="75%" align="right">
		<input type="button" name="Edit" value=" Rename " class="classBtn" onClick="EditFolder('{$reportfolder.id}','{$reportfolder.name}','{$reportfolder.description}'),fnvshobj(this,'orgLay');">
		<td class="dvtCellLabel" style="padding:5px;" align="right">
		<input type="button" name="delete" value=" Delete Folder " class="classBtn" onClick="DeleteFolder('{$reportfolder.id}');">
		</td>
		</tr>
		<tr>
		<td class="reportHdr"><img src="{$IMAGE_PATH}Reports_arrow.gif" width="20" height="20" align="absmiddle" /></td><td class="reportHdr" align="left" colspan="2" id='folder{$reportfolder.id}'>{$reportfolder.name}</td>
		</tr>
		<tr>
		<td colspan="3">
		<table style="background-color: rgb(204, 204, 204);" class="small" border="0" cellpadding="5" cellspacing="1" width="100%">
			<tbody>
			<tr>
			<td class="lvtCol" width="5%"><input type="checkbox" name="selectall" onclick='toggleSelect(this.checked,"selected_id{$reportfolder.id}")' value="checkbox" /></td>
			<td class="lvtCol" width="35%">{$MOD.LBL_REPORT_NAME}</td>
			<td class="lvtCol" width="50%">{$MOD.LBL_DESCRIPTION}</td>
			<td class="lvtCol" width="10%">{$MOD.LBL_TOOLS}</td>
			</tr>
			{foreach name=reportdtls item=reportdetails from=$reportfolder.details}
			<tr class="lvtColData" onmouseover="this.className='lvtColDataHover'" onmouseout="this.className='lvtColData'" bgcolor="white">
			<td><input name="selected_id{$reportfolder.id}" value="{$reportdetails.reportid}" onclick='toggleSelectAll(this.name,"selectall")' type="checkbox"></td>
			<td><a href="index.php?module=Reports&action=SaveAndRun&record={$reportdetails.reportid}&folderid={$reportfolder.id}">{$reportdetails.reportname}</a></td>
			<td>{$reportdetails.description}</td>
			<td align="center" nowrap>
			{if $reportdetails.customizable eq '1'}
			<a href="javascript:;" onClick="editReport('{$reportdetails.reportid}');"><img src="{$IMAGE_PATH}editfield.gif" align="absmiddle" title="Customize..." border="0"></a>
			{/if}
			{if $reportdetails.state neq 'SAVED'}
			&nbsp;| &nbsp;<a href="javascript:;" onClick="DeleteReport('{$reportdetails.reportid}');"><img src="{$IMAGE_PATH}delete.gif" align="absmiddle" title="Delete..." border="0"></a>
			{/if}
			</td>
			</tr>
			{/foreach}
			</tbody>
		</table>
		</td>
		</tr>
	</table>
	<br />
	{foreachelse}
	<div align="center"	style="position:relative;width:50%;height:30px;border:1px dashed #CCCCCC;background-color:#FFFFCC;padding:10px;">
	<a href="javascript:;" onclick="fnvshobj(this,'orgLay');">{$MOD.LBL_CLICK_HERE}</a>&nbsp;{$MOD.LBL_TO_ADD_NEW_GROUP}
	</div>
	{/foreach}
	</form>
	<!-- Customized Reports Table Ends Here  -->

<div style="display: none; left: 193px; top: 106px;" id="folderLay" onmouseout="fninvsh('folderLay')" onmouseover="fnvshNrm('folderLay')">
<table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="100%">
	<tbody><tr><td style="border-bottom: 1px solid rgb(204, 204, 204); padding: 5px;"><b>{$MOD.LBL_CREATE_NEW} :</b></td></tr>
	<tr>
	<td>
	{foreach item=folder from=$REPT_FOLDERS}
	<a href="javascript:;" onClick="MoveReport('{$folder.id}','{$folder.name}');" class="reportMnu">- {$folder.name}</a>
	{/foreach}
	</td>
	</tr>
	<tr><td style="padding: 5px;">&nbsp;</td></tr>
	</tbody>
</table>
</div>

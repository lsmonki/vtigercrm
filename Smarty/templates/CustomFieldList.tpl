{*
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/ *}
<script language="javascript">
function ajaxSaveResponse(response)
{ldelim}
        document.getElementById("cfList").innerHTML=response.responseText;
{rdelim}
function getCustomFieldList(customField)
{ldelim}
	var ajaxObj = new Ajax(ajaxSaveResponse);
	var modulename = customField.options[customField.options.selectedIndex].value;
	var urlstring ="module=Settings&action=CustomFieldList&fld_module="+modulename+"&parenttab=Settings&ajax=true";
	ajaxObj.process("index.php?",urlstring);
{rdelim}
</script>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
        {include file='SettingsMenu.tpl'}
<td width="75%" valign="top">
<table width="99%" border="0" cellpadding="0" cellspacing="0" align="center">
	<tr>
		<td class="showPanelBg" valign="top" style="padding-left:20px; "><br />
	       	        <span class="lvtHeaderText">{$MOD.LBL_SETTINGS} &gt; {$MOD.LBL_STUDIO} &gt; {$MOD.LBL_CUSTOM_FIELD_SETTINGS} </span>
        	        <hr noshade="noshade" size="1" />
		</td>
	</tr>
	
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td>
			<table width="95%" cellpadding="5" cellspacing="0" class="leadTable" align="center">
				<tr>
					<td style="padding:5px;border-bottom:2px dotted #CCCCCC;" width="5%" >
						<img src="images/picklistEditor.gif" align="left" />
					</td>
					<td style="padding:5px;border-bottom:2px dotted #AAAAAA;">
						<span class="genHeaderGrayBig">Custom Filed Settings</span>
						<br />
						<span class="big">Feature Explanation......</span>
					</td>
				</tr>
				<tr><td colspan="2">&nbsp;</td></tr>
				<tr>
					<td align="right"><img src="images/one.gif" /></td>
					<td><b class="lvtHeaderText">Select Module</b></td>
				</tr>
				<tr>
					<form name="selectModule">
					<td>&nbsp;</td>
					<td>
						Select the CRM module to show CustomFields :
						<select name="pick_module" class="importBox" onChange="getCustomFieldList(this)">
        	                                	{foreach key=sel_value item=value from=$MODULES}
                	                                	<option value="{$sel_value}">{$value}</option>
							{/foreach}
						</select>
					</td>
					</form>
				</tr>
				<tr><td colspan="2">&nbsp;</td></tr>
				<tr>
					<td align="right"><img src="images/two.gif" width="29" height="31" /></td>
					<td>
						<b class="lvtHeaderText">Custom Fields in Leads</b>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<form action="index.php" method="post" name="new" id="form">
						<input type="hidden" name="fld_module" value="{$MODULE}">
						<input type="hidden" name="module" value="Settings">
						<input type="hidden" name="parenttab" value="Settings">
						<input type="hidden" name="mode">
						<input type="hidden" name="action" value="CreateCustomField">
						<table width="95%" border="0" cellpadding="5" cellspacing="0">
							<tr><td align="right"><input type="button" value=" New Custom Field " onclick="fnvshNrm('orgLay')" class="classBtn"/></td></tr>
						</table>
						</form>
						<div id="cfList">
						<table style="background-color: rgb(204, 204, 204);" class="small" border="0" cellpadding="5" cellspacing="1" width="95%">
						<tbody>
							<tr>
								<td class="lvtCol" width="5%">#</td>
							        <td class="lvtCol" width="35%">Field Lable </td>
							        <td class="lvtCol" width="50%">Field Type </td>
								<td class="lvtCol" width="10%">Tools</td>
							</tr>
							{foreach item=entries key=id from=$CFENTRIES}
							<tr class="lvtColData" onmouseover="this.className='lvtColDataHover'" onmouseout="this.className='lvtColData'" bgcolor="white">
								{foreach item=value from=$entries}
									<td nowrap>{$value}</td>
								{/foreach}
							</tr>
							{/foreach}
						</tbody>
						</table><br />
						{if $MODULE eq 'Leads'}
						<table width="35%" style="border:1px dashed #CCCCCC;background-color:#FFFFEC;" cellpadding="5" cellspacing="0">
							<tr>
								<td style="padding:5px;" width="5%" >
								<img src="themes/blue/images/mapping.gif" align="absmiddle" /> </td>
								<td><span  class="genHeaderSmall">Filed Mapping</span><br />
								Field Mapping allows you to ....
								</td>
							</tr>
							<tr><td colspan="2" align="right"><input type="button" value=" Edit Field Mapping " class="classBtn" /></td></tr>
						</table> 
						{/if}
						</div>
					</td>
				</tr>
				<tr><td colspan="2">&nbsp;</td></tr>
			</table>
		</td>
	</tr>
</table>
<div id="orgLay" style="top:175px;left:275px; ">
	<table width="100%" border="0" cellpadding="5" cellspacing="0">
		<tr>
			<td width="40%" align="left" class="genHeaderSmall">Add Field </td>
			<td width="60%" align="right"><a href="javascript:fninvsh('orgLay');"><img src="{$IMAGE_PATH}close.gif" border="0"  align="absmiddle" /></a></td>
		</tr>
		<tr><td colspan="2"><hr /></td></tr>
		<tr>

			<td width="30%">
				<iframe name="fieldLayer" src="index.php?module=Settings&amp;action=fieldtypes" height="170" scrolling="yes" width="150"></iframe>
			</td>
			<td width="70%" align="left" valign="top">
				<table border="0" cellpadding="5" cellspacing="0" width="100%">
        				<tr> 
						<td class="dataLabel" nowrap="nowrap" width="30%" align="right"><b>Label : </b></td>
						<td width="70%" align="left"><input name="fldLabel" value=""  type="text" class="txtBox"></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr><td style="border-bottom:1px dashed #CCCCCC;" colspan="2">&nbsp;</td></tr>
		<tr>
			<td colspan="2" align="center">
				<input type="button" name="save" value=" &nbsp;Save&nbsp; " class="classBtn" />&nbsp;&nbsp;
				<input type="button" name="cancel" value=" Cancel " class="classBtn" onclick="fninvsh('orgLay');" />
			</td>

		</tr>
		<tr><td colspan="2" style="border-top:1px dashed #CCCCCC;">&nbsp;</td></tr>
	</table>
</div>
</td>
</tr>
</table>
        {include file='SettingsSubMenu.tpl'}

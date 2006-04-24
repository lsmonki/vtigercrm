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
<script language="JavaScript" type="text/javascript" src="include/js/customview.js"></script>
<script language="javascript">
function ajaxSaveResponse(response)
{ldelim}
        document.getElementById("cfList").innerHTML=response.responseText;
        //document.getElementById("test").value=response.responseText;
{rdelim}
function getCustomFieldList(customField)
{ldelim}
	var ajaxObj = new Ajax(ajaxSaveResponse);
	var modulename = customField.options[customField.options.selectedIndex].value;
	var urlstring ="module=Settings&action=SettingsAjax&file=CustomFieldList&fld_module="+modulename+"&parenttab=Settings&ajax=true";
	ajaxObj.process("index.php?",urlstring);
{rdelim}

function deleteCustomField(id, fld_module, colName, uitype)
{ldelim}
        if(confirm("Are you sure?"))
        {ldelim}
                document.form.action="index.php?module=Settings&action=DeleteCustomField&fld_module="+fld_module+"&fld_id="+id+"&colName="+colName+"&uitype="+uitype
                document.form.submit()
        {rdelim}
{rdelim}

function ajaxCFSaveResponse(response)
{ldelim}
        document.getElementById("createcf").innerHTML=response.responseText;
{rdelim}
function getCreateCustomFieldForm(customField,id,tabid,ui)
{ldelim}
        var ajaxObj = new Ajax(ajaxCFSaveResponse);
        var modulename = customField;
        var urlstring ="module=Settings&action=SettingsAjax&file=CreateCustomField&fld_module="+modulename+"&parenttab=Settings&ajax=true&fieldid="+id+"&tabid="+tabid+"&uitype="+ui;
        ajaxObj.process("index.php?",urlstring);
{rdelim}

</script>
<div id="createcf" style="display:block;position:absolute;top:175px;left:275px;"></div>
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
						<img src="{$IMAGE_PATH}mapping.gif" align="left" />
					</td>
					<td style="padding:5px;border-bottom:2px dotted #AAAAAA;">
						<span class="genHeaderGrayBig">Custom Field Settings</span>
						<br />
						<span class="big">Create and Manage user defined fields......</span>
						{*<textarea rows=20 cols=70 id="test"></textarea>*}
					</td>
				</tr>
				<tr><td colspan="2">&nbsp;</td></tr>
				<tr>
					<td align="right"><img src="{$IMAGE_PATH}one.gif" /></td>
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
				<tr><td colspan ="2">
				
						<div id="cfList">
				{include file="CustomFieldEntries.tpl"}					
						</div>

				</td></tr>
				<tr><td colspan="2">&nbsp;</td></tr>
			</table>
		</td>
	</tr>
</table>
</td>
</tr>
</table>
        {include file='SettingsSubMenu.tpl'}

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
{literal}

function getCustomFieldList(customField)
{
	var modulename = customField.options[customField.options.selectedIndex].value;
	new Ajax.Request(
		'index.php',
		{queue: {position: 'end', scope: 'command'},
			method: 'post',
			postBody: 'module=Settings&action=SettingsAjax&file=CustomFieldList&fld_module='+modulename+'&parenttab=Settings&ajax=true',
			onComplete: function(response) {
				$("cfList").innerHTML=response.responseText;
			}
		}
	);	
}

function deleteCustomField(id, fld_module, colName, uitype)
{
        if(confirm("Are you sure?"))
        {
                document.form.action="index.php?module=Settings&action=DeleteCustomField&fld_module="+fld_module+"&fld_id="+id+"&colName="+colName+"&uitype="+uitype
                document.form.submit()
        }
}

function getCreateCustomFieldForm(customField,id,tabid,ui)
{
        var modulename = customField;
	new Ajax.Request(
		'index.php',
		{queue: {position: 'end', scope: 'command'},
			method: 'post',
			postBody: 'module=Settings&action=SettingsAjax&file=CreateCustomField&fld_module='+customField+'&parenttab=Settings&ajax=true&fieldid='+id+'&tabid='+tabid+'&uitype='+ui,
			onComplete: function(response) {
				$("createcf").innerHTML=response.responseText;
			}
		}
	);

}

function CustomFieldMapping()
{
        document.form.action="index.php?module=Settings&action=LeadCustomFieldMapping";
        document.form.submit();
}
{/literal}
</script>
<div id="createcf" style="display:block;position:absolute;top:175px;left:275px;"></div>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
        {include file='SettingsMenu.tpl'}
<td width="75%" valign="top">
<b><font color=red>{$DUPLICATE_ERROR} </font></b>
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
						<span class="genHeaderGrayBig">{$MOD.LBL_CUSTOM_FIELD_SETTINGS}</span>
						<br />
						<span class="big">{$MOD.LBL_CREATE_AND_MANAGE_USER_DEFINED_FIELDS}</span>
					</td>
				</tr>
				<tr><td colspan="2">&nbsp;</td></tr>
				{include file="CustomFieldCombo.tpl"}
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

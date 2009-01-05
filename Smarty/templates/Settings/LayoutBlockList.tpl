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
<script language="JavaScript">
{literal}
function check(){
	var m=document.addtodb.blocklabel.value;
	var n=m.replace(/^[\s]+/,'').replace(/[\s]+$/,'').replace(/[\s]{2,}/,' ');
	if(n=="")
	{
		alert("Block name can not be blank");
		return false;
	}else
	{
		document.addtodb.submit();
	}
	return true;
}
{/literal}</script>
<script language="javascript">
//var test="";
//eval("if(test==\"\")alert(\"here1\");");

function getCustomFieldList(customField)
{ldelim}
	var modulename = customField.options[customField.options.selectedIndex].value;
	$('module_info').innerHTML = '{$MOD.LBL_CUSTOM_FILED_IN} "'+modulename+'" {$APP.LBL_MODULE}';
	new Ajax.Request(
		'index.php',
		{ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
			method: 'post',
			postBody: 'module=Settings&action=SettingsAjax&file=LayoutBlockList&fld_module='+modulename+'&parenttab=Settings&ajax=true',
			onComplete: function(response) {ldelim}
				$("cfList").innerHTML=response.responseText;
			{rdelim}
		{rdelim}
	);	
{rdelim}

<!-- tanmoy on 6/09/2007--->
function changeFieldorder(customField,what_to_do,fieldid,blockid)
{ldelim}
//alert('what_to_do'+what_to_do);
//alert('fieldid'+fieldid);
//alert('blockid'+blockid);
var sel = document.getElementById("pick_module");
sel.options[sel.selectedIndex].value;
	var modulename = sel.options[sel.selectedIndex].value;
	$('module_info').innerHTML = '{$MOD.LBL_CUSTOM_FILED_IN} "'+modulename+'" {$APP.LBL_MODULE}';
	new Ajax.Request(
		'index.php',
		{ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
			method: 'post',
			postBody: 'module=Settings&action=SettingsAjax&file=LayoutBlockList&fld_module='+modulename+'&parenttab=Settings&what_to_do='+what_to_do+'&fieldid='+fieldid+'&blockid='+blockid+'&ajax=true',
			//postBody: 'module=Settings&action=SettingsAjax&file=LayoutBlockList&fld_module='+modulename+'&parenttab=Settings&ajax=true&fieldid='+fieldid,
			onComplete: function(response) {ldelim}
			$("cfList").innerHTML=response.responseText;
			//alert('The field moved Successfully');
			location.href='index.php?module=Settings&action=LayoutBlockList&parenttab=Settings&fld_module='+modulename;
			{rdelim}
		{rdelim}
		
	);	
{rdelim}

function changeShowstatus(customBlock,tabid,blockid,what_to_do)	
{ldelim}
//var display = document.getElementById("display_status");
//var what_to_do = display.options[display.selectedIndex].value;

//alert('what_to_do'+what_to_do);
//alert('fieldid'+fieldid);
//alert('blockid'+blockid);
var sel = document.getElementById("pick_module");
sel.options[sel.selectedIndex].value;
	var modulename = sel.options[sel.selectedIndex].value;
	$('module_info').innerHTML = '{$MOD.LBL_CUSTOM_FILED_IN} "'+modulename+'" {$APP.LBL_MODULE}';
	new Ajax.Request(
		'index.php',
		{ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
			method: 'post',
			postBody: 'module=Settings&action=SettingsAjax&file=LayoutBlockList&fld_module='+modulename+'&parenttab=Settings&what_to_do='+what_to_do+'&tabid='+tabid+'&blockid='+blockid+'&ajax=true',
			onComplete: function(response) {ldelim}
			$("cfList").innerHTML=response.responseText;
			//alert('The Block moved Successfully');
			location.href='index.php?module=Settings&action=LayoutBlockList&parenttab=Settings&fld_module='+modulename;
			{rdelim}
		{rdelim}
		
	);	

	
//alert("mname="+modulename);
{rdelim}




function changeBlockorder(customBlock,what_to_do,tabid,blockid)	
{ldelim}
//alert('what_to_do'+what_to_do);
//alert('fieldid'+fieldid);
//alert('blockid'+blockid);
var sel = document.getElementById("pick_module");
sel.options[sel.selectedIndex].value;
	var modulename = sel.options[sel.selectedIndex].value;
	$('module_info').innerHTML = '{$MOD.LBL_CUSTOM_FILED_IN} "'+modulename+'" {$APP.LBL_MODULE}';
	new Ajax.Request(
		'index.php',
		{ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
			method: 'post',
			postBody: 'module=Settings&action=SettingsAjax&file=LayoutBlockList&fld_module='+modulename+'&parenttab=Settings&what_to_do='+what_to_do+'&tabid='+tabid+'&blockid='+blockid+'&ajax=true',
			onComplete: function(response) {ldelim}
			$("cfList").innerHTML=response.responseText;
			//alert('The Block moved Successfully');
			location.href='index.php?module=Settings&action=LayoutBlockList&parenttab=Settings&fld_module='+modulename;
			{rdelim}
		{rdelim}
		
	);	

	
//alert("mname="+modulename);
{rdelim}

<!-- end of tanmoy on 6/09/2007-->


{literal}
/*function deleteCustomField(id, fld_module, colName, uitype)
{
        if(confirm("Are you sure?"))
        {
                document.form.action="index.php?module=Settings&action=DeleteCustomField&fld_module="+fld_module+"&fld_id="+id+"&colName="+colName+"&uitype="+uitype+"&from=layouteditor";
                document.form.submit()
        }
}*/

function deleteCustomBlock(fld_module,blockid,tabid,blockname,no)
{
	if(confirm("Are you sure?"))
	{
		var modulename = fld_module;
		if(no>0)
		{  
			new Ajax.Request(
				'index.php',
				{queue: {position: 'end', scope: 'command'},
					method: 'post',
					postBody: 'module=Settings&action=SettingsAjax&file=MoveAllField&fld_module='+fld_module+'&parenttab=Settings&ajax=true&blockid='+blockid+'&tabid='+tabid+'&blockname='+blockname,
					onComplete: function(response) {
					$("createcf").innerHTML=response.responseText;
					gselected_fieldtype = '';
					}
				}
			);
		}else
		{
			location.href='index.php?module=Settings&action=MoveBlockFieldToDB&parenttab=Settings&fld_module='+modulename+'&deleteblockid='+blockid;
		}
	}
}

function getCreateCustomFieldForm(customField,blockid,tabid,ui,blockname,fieldselect,mode)
{
		var modulename = customField;
		new Ajax.Request(
			'index.php',
			{queue: {position: 'end', scope: 'command'},
			method: 'post',
			postBody: 'module=Settings&action=SettingsAjax&file=AddBlockField&fld_module='+customField+'&parenttab=Settings&ajax=true&blockid='+blockid+'&tabid='+tabid+'&uitype='+ui+'&blockname='+blockname+'&fieldselect='+fieldselect+'&mode='+mode,
			onComplete: function(response) {
				$("createcf").innerHTML=response.responseText;
				gselected_fieldtype = '';
			}
		}
	);

}


function getCreateCustomBlockForm(customField,blockid,tabid,blocklabel,mode)
{
	//alert('here1');
	var modulename = customField;
	new Ajax.Request(
		'index.php',
		{queue: {position: 'end', scope: 'command'},
			method: 'post',
			postBody: 'module=Settings&action=SettingsAjax&file=AddBlock&fld_module='+customField+'&parenttab=Settings&ajax=true&blockid='+blockid+'&tabid='+tabid+'&blocklabel='+blocklabel+'&mode='+mode+'&blockselect='+blockid,
			onComplete: function(response) {
		//alert(response.responseText);
				$("createcf").innerHTML=response.responseText;
				gselected_fieldtype = '';
			}
		}
	);


}


function makeFieldSelected(oField,fieldid)
{
	if(gselected_fieldtype != '')
	{
		$(gselected_fieldtype).className = 'customMnu';
	}
	oField.className = 'customMnuSelected';	
	gselected_fieldtype = oField.id;	
	selFieldType(fieldid)
	document.getElementById('selectedfieldtype').value = fieldid;
}
function CustomFieldMapping()
{
        document.form.action="index.php?module=Settings&action=LeadCustomFieldMapping";
        document.form.submit();
}

function field_validate()
{
	var fld_label = document.getElementById('fldLabel').value;
	if(trim(fld_label)!=''){	
		return true;
	}
	else
	{
		alert('Label cannot be Emtpy');
		return false;
	}
}

var gselected_fieldtype = '';
{/literal}
</script>
<div id="createcf" style="display:block;position:absolute;width:500px;"></div>
<br>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
<tbody><tr>
        <td valign="top"><img src="themes/images/showPanelTopLeft.gif"></td>
        <td class="showPanelBg" style="padding: 10px;" valign="top" width="100%">
        <br>

	<div align=center>
			{include file='SetMenu.tpl'}
			<!-- DISPLAY -->
			{if $MODE neq 'edit'}
			<b><font color=red>{$DUPLICATE_ERROR} </font></b>
			{/if}
			
				<table class="settingsSelUITopLine" border="0" cellpadding="5" cellspacing="0" width="100%">
				<tbody><tr>
					<td rowspan="2" valign="top" width="50"><img src="themes/images/orgshar.gif" alt="Users" title="Users" border="0" height="48" width="48"></td>
					<td class="heading2" valign="bottom"><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS}</a> &gt; {$MOD.LBL_LAYOUT_EDITOR}</b></td>
				</tr>

				<tr>
					<td class="small" valign="top">{$MOD.LBL_LAYOUT_EDITOR_DESCRIPTION}</td>
				</tr>
				</tbody></table>
				
				<br>
				<table border="0" cellpadding="10" cellspacing="0" width="100%">
				<tbody><tr>
				<td>

				<table class="tableHeading" border="0" cellpadding="5" cellspacing="0" width="100%">
				<tbody><tr>
					{assign var="MODULELBL" value=$MODULE}
					{if $APP.$MODULE}
						{assign var="MODULELBL" value=$APP.$MODULE}
					{/if}
					<td class="big" nowrap><strong><span id="module_info">{$MOD.LBL_CUSTOM_FILED_IN} "{$MODULELBL}" {$APP.LBL_MODULE}</span></strong> </td>
					<td class="small" align="right">
					{$MOD.LBL_SELECT_CF_TEXT}
		                	<select name="pick_module" class="importBox" onChange="getCustomFieldList(this)" id='pick_module'>
                		        {foreach key=sel_value item=value from=$MODULES}
		                        {if $MODULE eq $sel_value}
                	                       	{assign var = "selected_val" value="selected"}
		                        {else}
                        	                {assign var = "selected_val" value=""}
                                	{/if}	                                
									{assign var="modulelabel" value=$value}
									{if $APP.$value}
										{assign var="modulelabel" value=$APP.$value}
									{/if}
	                                <option value="{$sel_value}" {$selected_val}>{$modulelabel}</option>
        		                {/foreach}
			                </select>
					</td>
					</tr>
				</tbody>
				</table>
				<div id="cfList">
                                {include file="Settings/LayoutBlockEntries.tpl"}
                </div>	
			<table border="0" cellpadding="5" cellspacing="0" width="100%">
			<tr>

		  	<td class="small" align="right" nowrap="nowrap"><a href="#top">{$MOD.LBL_SCROLL}</a></td>
			</tr>
			</table>
			</td>
			</tr>
			</table>
		<!-- End of Display -->
		
		</td>
        </tr>
        </table>
        </td>
        </tr>
        </table>
        </div>

        </td>
        <td valign="top"><img src="themes/images/showPanelTopRight.gif"></td>
        </tr>
</tbody>
</table>
<br>

<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/ajax.js"></script>
<script language="javascript">
function ajaxmoduleresponse(response)
{ldelim}
	hide("status");
	document.getElementById("module_list_owner").innerHTML=response.responseText;
{rdelim}
</script>
<style type="text/css">@import url(themes/blue/style.css);</style>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	{include file='SettingsMenu.tpl'}
<td width="75%" valign="top">

<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
<td class="showPanelBg" valign="top" width="100%" colspan="3" style="padding-left:20px; "><br/>
<span class="lvtHeaderText"><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a> > {$MOD.LBL_USER_MANAGEMENT} > {$MOD.LBL_ASSIGN_MODULE_OWNERS}</b></span>
<hr noshade="noshade" size="1" />
</td>
</tr>
<tr>
<td width="75%" style="padding-left:20px;" valign="top">
	
	<div id="module_list_owner">	
	{include file='Settings/ModuleOwnersContents.tpl'}
	</div>
	
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
	<div id="status" style="display:none;position:absolute;background-color:#bbbbbb;left:887px;top:0px;height:17px;white-space:nowrap;"">Processing Request...</div>
{literal}
<script>
function assignmodulefn(mode)
{
	show('status');
	var ajaxObj = new Ajax(ajaxmoduleresponse);
	var urlstring ='';
	for(i = 0;i < document.support_owners.elements.length;i++)
	{
		if(document.support_owners.elements[i].name != 'button')
		urlstring +='&'+document.support_owners.elements[i].name+'='+document.support_owners.elements[i].value;
	}
	
	urlstring +='&list_module_mode='+mode+'&file_mode=ajax';
	ajaxObj.process("index.php?",urlstring);
}
</script>
{/literal}

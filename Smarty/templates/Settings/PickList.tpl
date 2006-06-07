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
<script src="include/scriptaculous/prototype.js" type="text/javascript"></script>
<script src="include/scriptaculous/scriptaculous.js" type="text/javascript"></script>
<script language="JavaScript" type="text/javascript" src="include/js/ajax.js"></script>
<script>
{literal}
function splitvalues() {
	var picklistobj=getobj("listarea")
	var picklistcontent=picklistobj.value
	var picklistary=new array()
	var i=0;
	
	//splitting up of values
	if (picklistcontent.indexof("\n")!=-1) {
		while(picklistcontent.indexof("\n")!=-1) {
			if (picklistcontent.replace(/^\s+/g, '').replace(/\s+$/g, '').length>0) {
				picklistary[i]=picklistcontent.substr(0,picklistcontent.indexof("\n")).replace(/^\s+/g, '').replace(/\s+$/g, '')
				picklistcontent=picklistcontent.substr(picklistcontent.indexof("\n")+1,picklistcontent.length)
				i++
			} else break;
		}
	} else if (picklistcontent.replace(/^\s+/g, '').replace(/\s+$/g, '').length>0) {
		picklistary[0]=picklistcontent.replace(/^\s+/g, '').replace(/\s+$/g, '')
	}
	
	return picklistary;
}
function setdefaultlist() {
	var picklistary=new array()
	picklistary=splitvalues()
	
	getobj("defaultlist").innerhtml=""
	
	for (i=0;i<picklistary.length;i++) {
		var objoption=document.createelement("option")
		if (browser_ie) {
			objoption.innertext=picklistary[i]
			objoption.value=picklistary[i]
		} else if (browser_nn4 || browser_nn6) {
			objoption.text=picklistary[i]
			objoption.setattribute("value",picklistary[i])
		}
	
		getobj("defaultlist").appendchild(objoption)
	}
}
function validate() {
	if (emptycheck("listarea","picklist values"))	{
		var picklistary=new array()
		picklistary=splitvalues()
		//empty check validation
		for (i=0;i<picklistary.length;i++) {
			if (picklistary[i]=="") {
				alert("picklist value cannot be empty")
				picklistobj.focus()
				return false
			}
		}

		//duplicate values' validation
		for (i=0;i<picklistary.length;i++) {
			for (j=i+1;j<picklistary.length;j++) {
				if (picklistary[i]==picklistary[j]) {
					alert("duplicate values found")
					picklistobj.focus()
					return false
				}
			}
		}

		return true;
	}
}

{/literal}
</script>
<style type="text/css">@import url(themes/blue/style.css);</style>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	{include file='SettingsMenu.tpl'}
<td width="75%" valign="top">

<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
<td class="showPanelBg" valign="top" width="100%" colspan="3" style="padding-left:20px; "><br/>
<span class="lvtHeaderText"><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a> > {$MOD.LBL_STUDIO} > {$MOD.LBL_PICKLIST_SETTINGS}</b></span>
<hr noshade="noshade" size="1" />
</td>
</tr>
<tr>
<td width="75%" style="padding-left:20px;" valign="top">
	
	<table class="leadTable" align="center" cellpadding="5" cellspacing="0" width="95%">
	<tbody><tr>
	<td style="border-bottom: 2px dotted rgb(204, 204, 204); padding: 5px;" width="5%">
	<img src="{$IMAGE_PATH}picklistEditor.gif" align="left">
	</td>
	<td style="border-bottom: 2px dotted rgb(170, 170, 170); padding: 5px;">
	<span class="genHeaderGrayBig">{$MOD.LBL_PICKLIST_EDITOR}</span><br>
	
	</td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
	<td align="right"><img src="{$IMAGE_PATH}one.gif"></td>
	<td><b class="lvtHeaderText">{$MOD.LBL_SELECT_MODULE}</b></td>
	</tr>

	<tr>
	<td>&nbsp;</td>
	<td>
	{$MOD.LBL_SELECT_CRM_MODULE} :
	<select name="pickmodule" class="importBox" onChange="changeModule(this);">
	{foreach key=tabid item=module from=$MODULE_LISTS}
	<option value="{$module}">{$module}</option>
	{/foreach}
	</select>
	</td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>

	<td align="right" valign="top"><img src="{$IMAGE_PATH}two.gif" height="31" width="29"></td>
	<td rowspan="2">
	<div id="picklist_datas">	
		{include file='Settings/PickListContents.tpl'}
	</div>
	</td>
	</tr>
	<tr>
	<td>&nbsp;</td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	</tbody>
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
<div id="editdiv" style="display:block;position:absolute;width:510px;"></div>
{literal}
<script>
function SavePickList(fieldname,module)
{
	$("status").style.display="inline";
	Effect.Puff($('editdiv'),{duration:2});
	var body = $("picklist_values").value;
	new Ajax.Request(
        	'index.php',
	        {queue: {position: 'end', scope: 'command'},
        		method: 'post',
		        postBody: 'action=SettingsAjax&module=Settings&directmode=ajax&file=UpdateComboValues&table_name='+fieldname+'&fld_module='+module+'&listarea='+body,
		        onComplete: function(response) {
					$("status").style.display="none";
        				$("picklist_datas").innerHTML=response.responseText;
	                        }
        	}
	);
}
function changeModule(pickmodule)
{
	$("status").style.display="inline";
	var module=pickmodule.options[pickmodule.options.selectedIndex].value;
	new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: 'action=SettingsAjax&module=Settings&directmode=ajax&file=PickList&fld_module='+module,
                        onComplete: function(response) {
                                        $("status").style.display="none";
                                        $("picklist_datas").innerHTML=response.responseText;
                                }
                }
        );
}
function fetchEditPickList(module,fieldname)
{
	$("status").style.display="inline";
	new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: 'action=SettingsAjax&module=Settings&mode=edit&file=EditComboField&fld_module='+module+'&fieldname='+fieldname,
			onComplete: function(response) {
                                        $("status").style.display="none";
                                        $("editdiv").innerHTML=response.responseText;
					Effect.Grow('editdiv');
                	}
                }
        );
}
</script>
{/literal}

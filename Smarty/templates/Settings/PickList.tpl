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
<script language="JAVASCRIPT" type="text/javascript" src="include/js/smoothscroll.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
<script src="include/scriptaculous/scriptaculous.js" type="text/javascript"></script>
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
				{/literal}
                                alert("{$APP.PICKLIST_CANNOT_BE_EMPTY}")
				picklistobj.focus()
				return false
				{literal}
			}
		}

		//duplicate values' validation
		for (i=0;i<picklistary.length;i++) {
			for (j=i+1;j<picklistary.length;j++) {
				if (picklistary[i]==picklistary[j]) {
					{/literal}
                                        alert("{$APP.DUPLICATE_VALUES_FOUND}")
					picklistobj.focus()
					return false
					{literal}
				}
			}
		}

		return true;
	}
}

{/literal}
</script>
<br>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
<tbody><tr>
        <td valign="top"><img src="{$IMAGE_PATH}showPanelTopLeft.gif"></td>
        <td class="showPanelBg" style="padding: 10px;" valign="top" width="100%">
<br>
	<div align=center>
	
			{include file='SetMenu.tpl'}
				<!-- DISPLAY -->
				<table border=0 cellspacing=0 cellpadding=5 width=100% class="settingsSelUITopLine">
				<tr>
					<td width=50 rowspan=2 valign=top><img src="{$IMAGE_PATH}picklist.gif" width="48" height="48" border=0 ></td>
					<td class=heading2 valign=bottom><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS}</a> > {$MOD.LBL_PICKLIST_EDITOR}</b></td>
				</tr>
				<tr>
					<td valign=top class="small">{$MOD.LBL_PICKLIST_DESCRIPTION}</td>
				</tr>
				</table>
				
				
				<table border=0 cellspacing=0 cellpadding=10 width=100% >
				<tr>
				<td valign=top>
				
					<table border=0 cellspacing=0 cellpadding=5 width=100% class="tableHeading">
					<tr>
						<td class="big"><strong>1. {$MOD.LBL_SELECT_MODULE} & Role </strong></td>
						<td class="small" align=right>&nbsp;</td>
					</tr>
					</table>
					<table width="100%" border="0" cellpadding="5" cellspacing="0" class="small">
						<tr class="small">
                       			 	<td width="15%" class="small cellLabel"><strong>{$MOD.LBL_SELECT_ROLE} </strong></td>
				    <td width="28%" class="cellText" >
						<select name="pickrole" id="pickid" class="detailedViewTextBox" onChange="changeModule();">
						{foreach key=roleid item=role from=$ROLE_LISTS}
							{if $SEL_ROLEID eq $roleid}
							<option value="{$roleid}" selected>{$role}</option>
							{else}
							<option value="{$roleid}">{$role}</option>
							{/if}
						{/foreach}
						</select>
				</td>

                        	<td width="25%" class="small cellLabel"><strong>{$MOD.LBL_SELECT_CRM_MODULE} </strong></td>
	                        <td width="32%" class="cellText" >
					<select name="pickmodule" id="pickmodule" class="detailedViewTextBox" onChange="changeModule();">
					{foreach key=tabid item=module from=$MODULE_LISTS}
						{if $SEL_MODULE eq $module}
						<option value="{$module}" selected>{$APP.$module}</option>
						{else}
						<option value="{$module}">{$APP.$module}</option>
						{/if}
					{/foreach}
					</select>
				</td>

                      </tr>
		      
					</table>
					<br>
				<table border=0 cellspacing=0 cellpadding=5 width=100% class="tableHeading">
				<tr>
				    <td class="big" rowspan="2">
					<div id="picklist_datas">	
						{include file='Settings/PickListContents.tpl'}
					</div>
				    </td>	
				</td>
				</tr>
			    	</table>
				<table border=0 cellspacing=0 cellpadding=5 width=100% >
					<tr><td class="small" nowrap align=right><a href="#top">{$MOD.LBL_SCROLL}</a></td></tr>
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

</td>
        <td valign="top"><img src="{$IMAGE_PATH}showPanelTopRight.gif"></td>
   </tr>
</tbody>
</table>
<div id="editdiv" style="display:block;position:absolute;width:510px;"></div>
<div id="deletediv"  style="display:block;position:absolute;"></div>
<div id="transferdiv"  style="display:block;position:absolute;width:300px;z-index:50000000"></div>
{literal}
<script>

var selected_values='';
function SavePickList(fieldname,module,uitype)
{
	var oRolePick = $('pickid');
	var role=oRolePick.options[oRolePick.selectedIndex].value;

	$("status").style.display = "inline";
	Effect.Puff($('editdiv'),{duration:2});
	var body = escapeAll($("picklist_values").value);
	new Ajax.Request(
        	'index.php?action=SettingsAjax&module=Settings&directmode=ajax&file=UpdateComboValues&table_name='+fieldname+'&fld_module='+module+'&roleid='+role+'&listarea='+body+'&uitype='+uitype,
	        {queue: {position: 'end', scope: 'command'},
        		method: 'get',
		        postBody: null,
		        /* postBody: 'action=SettingsAjax&module=Settings&directmode=ajax&file=UpdateComboValues&table_name='+fieldname+'&fld_module='+module+'&listarea='+body, */
		        onComplete: function(response) {
					$("status").style.display="none";
        				$("picklist_datas").innerHTML=response.responseText;
	                        }
        	}
	);
}
function changeModule()
{
	$("status").style.display="inline";
	var oModulePick = $('pickmodule')
	var module=oModulePick.options[oModulePick.selectedIndex].value;
	var oRolePick = $('pickid');
	var role=oRolePick.options[oRolePick.selectedIndex].value;
	
	new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: 'action=SettingsAjax&module=Settings&directmode=ajax&file=PickList&fld_module='+module+'&roleid='+role,
                        onComplete: function(response) {
                                        $("status").style.display="none";
                                        $("picklist_datas").innerHTML=response.responseText;
                                }
                }
        );
}
function fetchEditPickList(module,fieldname,uitype)
{
	var oRolePick = $('pickid');
	var role=oRolePick.options[oRolePick.selectedIndex].value;

	$("status").style.display="inline";
	new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: 'action=SettingsAjax&module=Settings&mode=edit&file=EditComboField&fld_module='+module+'&fieldname='+fieldname+'&roleid='+role+'&uitype='+uitype,
			onComplete: function(response) {
                                        $("status").style.display="none";
                                        $("editdiv").innerHTML=response.responseText;
					Effect.Grow('editdiv');
                	}
                }
        );
}

function picklist_validate(mode,fieldname,module,uitype)
{
	
	var pick_arr=new Array();
	pick_arr=trim($("picklist_values").value).split('\n');	
	var noneditpick_arr=new Array();
	if($('nonedit_pl')){
	 	noneditpick_arr = trim($("nonedit_pl").innerHTML).split('<br>');
	}
	pick_arr = pick_arr.concat(noneditpick_arr)
	var len=pick_arr.length;
	for(i=0;i<len;i++)
	{
		var valone;
		curr_iter = i;
		valone=pick_arr[curr_iter];
		for(j=curr_iter+1;j<len;j++)
		{
			var valnext;
			valnext=pick_arr[j];
			var temp = valnext.toLowerCase();
			if(temp.match('(script).*(/script)'))
                        {
				valnext = temp.replace(/</g,'&lt;');
		                valnext = valnext.replace(/>/g,'&gt;');
                        }
			if(trim(valone).toUpperCase() == trim(valnext).toUpperCase())
			{
				alert("Duplicate entries found for the value '"+valone+"'");
				return false;
			}
		}
		i = curr_iter		

	}
	if(mode != 'nonedit')
	{
		if(trim($("picklist_values").value) == '')
		{
			alert("Picklist value cannot be empty");
			$("picklist_values").focus();	
			return false;
		}
	}
	SavePickList(fieldname,module,uitype)	
}
function pickListDelete(mod)
{

	var oDelPick = $('allpick');
	var fld_name=oDelPick.options[oDelPick.selectedIndex].value;
	var fld_label=oDelPick.options[oDelPick.selectedIndex].text;
	
	$("status").style.display="inline";
	new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: 'action=SettingsAjax&module=Settings&mode=delete&file=DeletePickList&fld_module='+mod+'&fieldname='+fld_name+'&fieldlabel='+fld_label,
			onComplete: function(response) {
                                        $("status").style.display="none";
                                        $("deletediv").style.display ='block';
                                        $("deletediv").innerHTML=response.responseText;
					//Effect.Grow('deletediv');
                	}
                }
        );


}
function delPickList(obj,module,nonedit_flag)
{
	var oDelPick = $('allpick');
	var fld_name=oDelPick.options[oDelPick.selectedIndex].value;
	var fld_label=oDelPick.options[oDelPick.selectedIndex].text;
	var oAvlPick = $('availPickList');
	var selectedColStr = "";
	var val_count=0;
	var xPos = findPosX(obj);
	var yPos = findPosX 	
	
	if (oAvlPick.options.selectedIndex > -1)
	{
		
		for (var k=0;k < oAvlPick.options.length;k++) 
		{
			
			if(oAvlPick.options[k].selected == true)
			{
				selectedColStr += escapeAll(oAvlPick.options[k].value)+ ",";
				val_count++;
			}
		}
		if(val_count == oAvlPick.options.length && nonedit_flag == false)
		{
			alert(alert_arr.LBL_CANT_REMOVE);
			return false;
		}

			str_length=selectedColStr.length;
			selected_values  = (selectedColStr.substr(0,str_length-1));
			

			$("status").style.display="inline";
	new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: 'action=SettingsAjax&module=Settings&mode=transfer&file=DeletePickList&fld_module='+module+'&fieldname='+fld_name+'&fieldlabel='+fld_label+'&selectedFields='+selected_values,
			onComplete: function(response) {
                                        $("status").style.display="none";
					$("transferdiv").style.display ='block';
                                        $("transferdiv").innerHTML=response.responseText;
					$("transferdiv").style.display='block';
					fnvshobj(obj,"transferdiv");
							
					
                	}
                }
        );


		
	}
	else
	{
		alert(alert_arr.LBL_SELECT_PICKLIST);
	}
		
}
function pickReplace(module,fld_name)
{
	
	var replaceObj = $('replacePick');
	var relplaceValue =replaceObj.options[replaceObj.selectedIndex].value;

	$("status").style.display="inline";
	new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: 'action=SettingsAjax&module=Settings&mode=replace&file=DeletePickList&fld_module='+module+'&fieldname='+fld_name+'&replaceFields='+relplaceValue+'&selectedFields='+selected_values,
			onComplete: function(response) {
						 var str = response.responseText
                                              if(str.indexOf('SUCCESS') > -1)
                                              {
							changeModule();
							Myhide('deletediv');

                                              }else
                                              {
                                                      alert(str);
                                              }
					
                                        $("status").style.display="none";
					
                	}
                }
        );
	
}
function Myhide(lay)
{
	$(lay).style.display='None';
	$('transferdiv').style.display='None';
}
</script>
{/literal}

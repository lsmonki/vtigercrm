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
<script language="javascript">

function dup_validation()
{ldelim}
	var mode = getObj('mode').value;
	var groupname = $('groupName').value;
	var groupid = getObj('groupId').value;
	if(mode == 'edit')
		var reminstr = '&mode='+mode+'&groupName='+groupname+'&groupid='+groupid;
	else
		var reminstr = '&groupName='+groupname;
	new Ajax.Request(
		'index.php',
		{ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
			method: 'post',
			postBody: 'module=Users&action=UsersAjax&file=SaveGroup&ajax=true&dup_check=true'+reminstr,
			onComplete: function(response) {ldelim}
				if(response.responseText == 'SUCESS')
					document.newGroupForm.submit();
				else
					alert(response.responseText);
			{rdelim}
		{rdelim}
	);
{rdelim}

var constructedOptionValue;
var constructedOptionName;

var roleIdArr=new Array({$ROLEIDSTR});
var roleNameArr=new Array({$ROLENAMESTR});
var userIdArr=new Array({$USERIDSTR});
var userNameArr=new Array({$USERNAMESTR});
var grpIdArr=new Array({$GROUPIDSTR});
var grpNameArr=new Array({$GROUPNAMESTR});

function showOptions()
{ldelim}
	var selectedOption=document.newGroupForm.memberType.value;
	//Completely clear the select box
	document.forms['newGroupForm'].availList.options.length = 0;

	if(selectedOption == 'groups')
	{ldelim}
		constructSelectOptions('groups',grpIdArr,grpNameArr);		
	{rdelim}
	else if(selectedOption == 'roles')
	{ldelim}
		constructSelectOptions('roles',roleIdArr,roleNameArr);		
	{rdelim}
	else if(selectedOption == 'rs')
	{ldelim}
	
		constructSelectOptions('rs',roleIdArr,roleNameArr);	
	{rdelim}
	else if(selectedOption == 'users')
	{ldelim}
		constructSelectOptions('users',userIdArr,userNameArr);		
	{rdelim}
{rdelim}

function constructSelectOptions(selectedMemberType,idArr,nameArr)
{ldelim}
	var i;
	var findStr=document.newGroupForm.findStr.value;
	if(findStr.replace(/^\s+/g, '').replace(/\s+$/g, '').length !=0)
	{ldelim}
		
		var k=0;
		for(i=0; i<nameArr.length; i++)
		{ldelim}
			if(nameArr[i].indexOf(findStr) ==0)
			{ldelim}
				constructedOptionName[k]=nameArr[i];
				constructedOptionValue[k]=idArr[i];
				k++;			
			{rdelim}
		{rdelim}
	{rdelim}
	else
	{ldelim}
		constructedOptionValue = idArr;
		constructedOptionName = nameArr;	
	{rdelim}
	
	//Constructing the selectoptions
	var j;
	var nowNamePrefix;	
	for(j=0;j<constructedOptionName.length;j++)
	{ldelim}
		if(selectedMemberType == 'roles')
		{ldelim}
			nowNamePrefix = 'Roles::'
		{rdelim}
		else if(selectedMemberType == 'rs')
		{ldelim}
			nowNamePrefix = 'RoleAndSubordinates::'
		{rdelim}
		else if(selectedMemberType == 'groups')
		{ldelim}
			nowNamePrefix = 'Group::'
		{rdelim}
		else if(selectedMemberType == 'users')
		{ldelim}
			nowNamePrefix = 'User::'
		{rdelim}

		var nowName = nowNamePrefix + constructedOptionName[j];
		var nowId = selectedMemberType + '::'  + constructedOptionValue[j]
		document.forms['newGroupForm'].availList.options[j] = new Option(nowName,nowId);	
	{rdelim}
	//clearing the array
	constructedOptionValue = new Array();
        constructedOptionName = new Array();	
				

{rdelim}

function validate()
{ldelim}
	formSelectColumnString();
	if( !emptyCheck( "groupName", "Group Name" ) )
		return false;

	//alert(document.newGroupForm.selectedColumnsString.value);
	if(document.newGroupForm.selectedColumnsString.value.replace(/^\s+/g, '').replace(/\s+$/g, '').length==0)
	{ldelim}

		alert('Group should have atleast one member. Select a member to the group');
		return false;
	{rdelim}
	dup_validation();	
{rdelim}
</script>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	{include file='SettingsMenu.tpl'}
<td width="75%" valign="top">

<form name="newGroupForm" action="index.php" method="post">
<input type="hidden" name="module" value="Users">
<input type="hidden" name="action" value="SaveGroup">
<input type="hidden" name="mode" value="{$MODE}">
<input type="hidden" name="parenttab" value="Settings">
<input type="hidden" name="groupId" value="{$GROUPID}">
<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
<tr>
<td class="showPanelBg" valign="top" width="100%" style="padding-left:20px; "><br />
{if $MODE eq 'edit'}
	<span class="lvtHeaderText"><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a> > {$MOD.LBL_USER_MANAGEMENT} > {$CMOD.LBL_EDIT_GROUP}</b></span>
{else}
	<span class="lvtHeaderText"><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a> > {$MOD.LBL_USER_MANAGEMENT} > {$CMOD.LBL_CREATE_NEW_GROUP}</b></span>
{/if}	
<hr noshade="noshade" size="1"/>
</td>
</tr>
<tr>
<td width="75%" style="padding-left:20px;" valign="top">

	<table align="center" border="0" cellpadding="5" cellspacing="0" width="100%" class="leadTable">
	<tbody>
	<tr>
			<td align="left" style="padding:10px;border-bottom:1px dashed #CCCCCC;" colspan="3">
					<img src="{$IMAGE_PATH}groups.gif" align="absmiddle">
					{if $MODE eq 'edit'}
						<span class="genHeaderGray">{$CMOD.LBL_EDIT_GROUP}</span>
					{else}
						<span class="genHeaderGray">{$CMOD.LBL_CREATE_NEW_GROUP}</span>
					{/if}
			</td>
	</tr>
	<tr>
	<td width="10%"></td>
	<td width="10%">&nbsp;</td>
	<td width="80%">&nbsp;</td>
	</tr>
	<tr>
	<td align="right"><img src="{$IMAGE_PATH}one.gif" align="absmiddle"> </td>
	<td style="padding-right: 10px;" align="right" width="20%">
			<b>{$CMOD.LBL_GROUP_NAME} {$CMOD.LBL_COLON}</b></td>
	<td style="padding-left: 10px;" align="left" width="80%">
	<input id="groupName"  name="groupName" class="importBox" style="width:40%;" type="text" value="{$GROUPNAME}">
	</td>
	</tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr>
	<td align="right"><img src="{$IMAGE_PATH}two.gif" align="absmiddle"></td>
	<td style="padding-right: 10px;" align="right" valign="top">
			<b>{$CMOD.LBL_DESCRIPTION} {$CMOD.LBL_COLON} </b></td>
	<td style="padding-left: 10px;" align="left">
	<textarea name="description"  class="txtBox" style="width:40%;" >{$DESCRIPTION}</textarea>
	</td>
	</tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr>
		<td align="right"><img src="{$IMAGE_PATH}three.gif" align="absmiddle"></td>
		<td style="padding-right: 10px;" align="right">
			<b>{$APP.LBL_VIEW} </b></td>
		<td><select id="memberType" name="memberType" onchange="showOptions()">
          <option value="groups" selected>{$CMOD.LBL_GROUPS}</option>
          <option value="roles">{$CMOD.LBL_ROLES}</option>
          <option value="rs">{$CMOD.LBL_ROLES_SUBORDINATES}</option>
          <option value="users">{$MOD.LBL_USERS}</option>
        </select>&nbsp;&nbsp;
		<input type="text" name="findStr">&nbsp;
          <input type="button" name="Find" value="{$APP.LBL_FIND_BUTTON}" class="classBtn" onClick="showOptions()">
		</td>
	</tr>
	<tr><td colspan="3">&nbsp;</td></tr>
	<tr>
	<td	colspan="3">
	<table align="center" border="0" cellpadding="0" cellspacing="0" width="75%">
	<tbody>
	<tr>
	<td align="center"><b>{$CMOD.LBL_MEMBER_AVLBL}</b><br>
	<select id="availList" name="availList" multiple size="10" style="width:200px; ">
	</select>
	<input type="hidden" name="selectedColumnsString"/>
	</td>
	<td align="center">
	<input type="button" name="Button" value="&nbsp;&rsaquo;&rsaquo;&nbsp;" onClick="addColumn()" class="classBtn"/><br /><br />
	<input type="button" name="Button1" value="&nbsp;&lsaquo;&lsaquo;&nbsp;" onClick="delColumn()" class="classBtn"/>
	</td>
	<td align="center"><b>{$CMOD.LBL_MEMBER_SELECTED}</b><br>
	<select id="selectedColumns" name="selectedColumns" multiple size="10" style="width:200px; ">
  	{foreach item=element from=$MEMBER}
	<option value="{$element.0}">{$element.1}</option>
	{/foreach}
	</select>
	
	</td>
	</tr>
	</tbody></table></td>
	</tr>

	<tr>
	<td colspan="3" style="border-bottom: 1px dashed rgb(204, 204, 204);">&nbsp;</td>
	</tr>
	<tr>
	<td colspan="3" align="center"> 	
	{if $MODE eq 'edit'}
		<input type="button" class="classBtn" name="add" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " onClick="return validate()">
	{else}
		<input type="button" class="classBtn" name="add" value="{$CMOD.LBL_ADD_GROUP_BUTTON}" onClick="return validate()">
	{/if}
	&nbsp;&nbsp;
	<input type="button" class="classBtn" name="cancel" value="{$APP.LBL_CANCEL_BUTTON_LABEL}" onClick="window.history.back()">
	</td>
	</tr>
	</tbody></table>

</tr>
</table>
</form>
</td>

</tr>
</table>
		
</td>
<td width="1%" style="border-right:1px dotted #CCCCCC;">&nbsp;</td>
</tr>
</table>
<script language="JavaScript" type="text/JavaScript">    
var moveupLinkObj,moveupDisabledObj,movedownLinkObj,movedownDisabledObj;
function setObjects() 
{ldelim}
availListObj=getObj("availList")
selectedColumnsObj=getObj("selectedColumns")

{rdelim}

function addColumn() 
{ldelim}
for (i=0;i<selectedColumnsObj.length;i++) 
{ldelim}
selectedColumnsObj.options[i].selected=false
{rdelim}

for (i=0;i<availListObj.length;i++) 
{ldelim}
if (availListObj.options[i].selected==true) 
{ldelim}
for (j=0;j<selectedColumnsObj.length;j++) 
{ldelim}
if (selectedColumnsObj.options[j].value==availListObj.options[i].value) 
{ldelim}
var rowFound=true
var existingObj=selectedColumnsObj.options[j]
break
{rdelim}
{rdelim}

if (rowFound!=true) 
{ldelim}
var newColObj=document.createElement("OPTION")
newColObj.value=availListObj.options[i].value
if (browser_ie) newColObj.innerText=availListObj.options[i].innerText
	else if (browser_nn4 || browser_nn6) newColObj.text=availListObj.options[i].text
selectedColumnsObj.appendChild(newColObj)
	availListObj.options[i].selected=false
	newColObj.selected=true
	rowFound=false
{rdelim}
else 
{ldelim}
existingObj.selected=true
{rdelim}
{rdelim}
{rdelim}
{rdelim}

function delColumn() 
{ldelim}
for (i=0;i<=selectedColumnsObj.options.length;i++) 
{ldelim}
	if (selectedColumnsObj.options.selectedIndex>=0)
selectedColumnsObj.remove(selectedColumnsObj.options.selectedIndex)
{rdelim}
{rdelim}

function formSelectColumnString()
{ldelim}
var selectedColStr = "";
for (i=0;i<selectedColumnsObj.options.length;i++) 
{ldelim}
selectedColStr += selectedColumnsObj.options[i].value + ";";
{rdelim}
document.newGroupForm.selectedColumnsString.value = selectedColStr;
{rdelim}
setObjects();
showOptions();
</script>
</td>
</tr>
</table>
</td>
</tr>
</table>
	{include file='SettingsSubMenu.tpl'}


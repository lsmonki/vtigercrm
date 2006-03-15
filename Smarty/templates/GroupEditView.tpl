<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
<style type="text/css">@import url(themes/blue/style.css);</style>
<script language="javascript">
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
	return true;
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
<td class="showPanelBg" valign="top" width="100%" colspan="3" style="padding-left:20px; "><br />
<span class="lvtHeaderText"><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a> > {$MOD.LBL_USER_MANAGEMENT} > {$CMOD.LBL_CREATE_NEW_GROUP}</b></span>
<hr noshade="noshade" size="1"/>
</td>
</tr>
<tr>
<td width="75%" style="padding-left:20px;" valign="top">

	<table align="center" border="0" cellpadding="5" cellspacing="0" width="90%">
	<tbody>
	<tr>
	<td colspan="2">&nbsp;</td>
	</tr>
	<tr>
	<td width="20%">&nbsp;</td>
	<td width="80%">&nbsp;</td>
	</tr>
	<tr>
	<td style="padding-right: 10px;" align="right" width="20%">Group Name</td>
	<td style="padding-left: 10px;" align="left" width="80%">
	<input name="groupName" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'" type="text" value="{$GROUPNAME}">
	</td>
	</tr>
	<tr>
	<td style="padding-right: 10px;" align="right">Description</td>
	<td style="padding-left: 10px;" align="left">
	<textarea name="description"  class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'">{$DESCRIPTION}</textarea>
	</td>
	
	</tr>
	<tr>
	<td colspan=2>
		<table align="center" border="0" cellpadding="0" cellspacing="0" width="90%">
		<tbody>
		<tr>
		<td style="padding-right: 10px;" align="right">Filters</td>
		<td>
		<select id="memberType" name="memberType" onchange="showOptions()">
		<option value="groups" selected>Groups</option>
		<option value="roles">Roles</option>
		<option value="rs">Roles and Subordinates</option>
		<option value="users">Users</option>
		</select>
		</td><td>
		<input type="text" name="findStr">
    	<input type="button" name="Find" value="Find" class="button" onClick="showOptions()">
		</td>
		</tr>
		</tbody>
		</table>
	</td>
	</tr>
	<tr>
	<td	colspan="2">
	<table align="center" border="0" cellpadding="0" cellspacing="0" width="75%">
	<tbody>
	<tr>
	<td align="center"><b>Member Available</b><br>
	<select id="availList" name="availList" multiple size="10" style="width:200px; ">
	</select>
	<input type="hidden" name="selectedColumnsString"/>
	</td>
	<td align="center">
	<input type="button" name="Button" value=" Add &rsaquo; " onClick="addColumn()"/><br /><br />
	<input type="button" name="Button1" value=" &lsaquo; Remove " onClick="delColumn()"/>
	</td>
	<td align="center"><b>Selected Member</b><br>
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
	<td colspan="2" style="border-bottom: 1px dashed rgb(204, 204, 204);">&nbsp;</td>
	</tr>
	<tr>
	<td colspan="2" align="right"> &nbsp;&nbsp;
	
	<input type="submit" class="button" name="add" value="Add Group" onClick="return validate()">
	&nbsp;&nbsp;
    <input type="button" class="button" name="cancel" value="Cancel" onClick="window.history.back()">
	</td>
	</tr>
	<tr>
	<td colspan="2" style="border-top: 1px solid rgb(204, 204, 204);">&nbsp;</td>
	</tr>
	</tbody></table>

<td colspan="2" style="border-top:1px solid #CCCCCC;">&nbsp;</td>
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


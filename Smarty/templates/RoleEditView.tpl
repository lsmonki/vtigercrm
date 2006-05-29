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
<script language="javascript">
function dup_validation()
{ldelim}
	//show("status");
	var ajaxObj = new VtigerAjax(ajaxSaveResponse);
	var rolename = document.getElementById('rolename').value;
	var mode = getObj('mode').value;
	var roleid = getObj('roleid').value;
	if(mode == 'edit')
		var urlstring ="module=Users&action=UsersAjax&file=SaveRole&ajax=true&dup_check=true&mode="+mode+"&roleName="+rolename+"&roleid="+roleid;
	else
		var urlstring ="module=Users&action=UsersAjax&file=SaveRole&ajax=true&dup_check=true&roleName="+rolename;

	ajaxObj.process("index.php?",urlstring);
{rdelim}
function ajaxSaveResponse(response)
{ldelim}
	if(response.responseText == 'SUCESS')
		document.newRoleForm.submit();
	else
		alert(response.responseText);
{rdelim}
function validate()
{ldelim}
	formSelectColumnString();
	if( !emptyCheck( "roleName", "Role Name" ) )
		return false;

	if(document.newRoleForm.selectedColumnsString.value.replace(/^\s+/g, '').replace(/\s+$/g, '').length==0)
	{ldelim}

		alert('Role should have atlease one profile');
		return false;
	{rdelim}
	dup_validation();
{rdelim}
</script>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
				{include file='SettingsMenu.tpl'}
				<td width="75%" valign="top">
						<form name="newRoleForm" action="index.php" method="post">
						<input type="hidden" name="module" value="Users">
						<input type="hidden" name="action" value="SaveRole">
						<input type="hidden" name="parenttab" value="Settings">
						<input type="hidden" name="returnaction" value="{$RETURN_ACTION}">
						<input type="hidden" name="roleid" value="{$ROLEID}">
						<input type="hidden" name="mode" value="{$MODE}">
						<input type="hidden" name="parent" value="{$PARENT}">
								<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
										<tr>
												<td class="showPanelBg" valign="top" width="100%" style="padding-left:20px; "><br />
															<span class="lvtHeaderText"><b>
															<a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a>
															 > {$MOD.LBL_USER_MANAGEMENT} > {$CMOD.LBL_CREATE_NEW_ROLE}</b></span>
															<hr noshade="noshade" size="1"/>
												</td>
										</tr>
																
										<tr>
												<td  valign="top" class="leadTable">
														<table width="100%" cellpadding="0" cellspacing="0">
																<tr>
																		<td align="left" style="padding:10px;border-bottom:1px dashed #CCCCCC;">
																				<img src="{$IMAGE_PATH}roles.gif" align="absmiddle">
																				<span class="genHeaderGray">{$CMOD.LBL_CREATE_NEW_ROLE}</span>
																		</td>
																</tr>
														</table>
														<table align="center" border="0" cellpadding="5" cellspacing="0" width="95%">
															<tbody>
																<tr><td colspan="2">&nbsp;</td></tr>
																<tr>
																		<td class="genHeaderSmall" style="padding-right: 10px;" align="right" nowrap valign="top" width="15%">
																				<img src="{$IMAGE_PATH}one.gif" align="absmiddle">
																		 </td>
																		<td align="left" nowrap="nowrap" width="85%"><b>{$CMOD.LBL_ROLE_NAME}</b><br>
																					{$CMOD.LBL_SPECIFY_ROLE_NAME}&nbsp;
																					<input type="text" id="rolename" name="roleName" class="importBox"  value="{$ROLENAME}">
																		</td>
																</tr>
																<tr><td colspan="2">&nbsp;</td></tr>
																<tr>
																		<td class="genHeaderSmall" style="padding-right: 10px;" align="right" nowrap valign="top">
																				<img src="{$IMAGE_PATH}two.gif" align="absmiddle">
																		 </td>
																		<td><b>{$CMOD.LBL_ASSIGN_PROFILE}</b><br>{$CMOD.LBL_PROFILE_SELECT_TEXT}</td>
																</tr>
																<tr>
																		<td class="genHeaderSmall" style="padding-right: 10px;" align="right" valign="top">&nbsp;</td>
																		<td>&nbsp;</td>
																</tr>
																<tr>
																		<td class="genHeaderSmall" style="padding-right: 10px;" align="right" valign="top">&nbsp;</td>
																		<td>
																				<table align="center" border="0" cellpadding="0" cellspacing="0" width="75%">
																					<tbody>
																							<tr>
																									<td align="center"><b>{$CMOD.LBL_PROFILES_AVLBL}</b><br>
																												<select id="availList" name="availList" size="10" multiple style="width: 150px;">
																												{foreach item=element from=$PROFILELISTS}
																														<option value="{$element.0}">{$element.1}</option>
																												{/foreach}
																												</select>
																									</td>
																									<td align="center">
																												<input type="hidden" name="selectedColumnsString"/>
																												<input name="Button" value="&nbsp;&rsaquo;&rsaquo;&nbsp;" type="button" class="classBtn" onClick="addColumn()">
																												<br><br>
																												<input type="button" name="Button1" value="&nbsp;&lsaquo;&lsaquo;&nbsp;" class="classBtn" onClick="delColumn()">
																									</td>
																									<td align="center"><b>{$CMOD.LBL_ASSIGN_PROFILES}</b><br>
																												<select id="selectedColumns" name="selectedColumns" multiple size="10" style="width: 150px;">
																												{foreach item=element from=$SELPROFILELISTS}
																													<option value="{$element.0}">{$element.1}</option>
																												{/foreach}
																												</select>
																									</td>
																							</tr>
																					</tbody>
																				</table>
																			</td>
																	</tr>
																	<tr>
																		<td class="genHeaderSmall" style="padding-right: 10px;" align="right" valign="top">&nbsp;</td>
																		<td>&nbsp;</td>
																</tr>
																	<tr>
																			<td align="right" nowrap><img src="{$IMAGE_PATH}three.gif" align="absmiddle"></td>
																			<td><b>{$CMOD.LBL_REPORTS_TO_ROLE}</b><br>{$PARENTNAME}</td>
																	</tr>
																	<tr><td colspan="2" style="border-bottom: 1px dashed rgb(204, 204, 204);">&nbsp;</td></tr>
																	<tr>
																			<td colspan="2" align="center"> &nbsp;&nbsp;
																						<input value=" {$APP.LBL_SAVE_BUTTON_LABEL} " name="Next" type="button" class="classBtn" onClick="return validate()" >&nbsp;&nbsp;
																						<input value=" {$APP.LBL_CANCEL_BUTTON_LABEL} " name="Cancel" type="button" class="classBtn" onClick="window.history.back()">
																			</td>
																	</tr>
																	
																</tbody>
															</table>
														</td>
												</tr>
										</table>
										</form>
								</td>
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
            document.newRoleForm.selectedColumnsString.value = selectedColStr;
        {rdelim}
	setObjects();			
</script>	
	
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

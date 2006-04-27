<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
<style type="text/css">@import url(themes/blue/style.css);</style>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	{include file='SettingsMenu.tpl'}
<td width="75%" valign="top">

<form id="form" name="new" action="index.php" method="post">
<input type="hidden" name="module" value="Users">
<input type="hidden" name="action" value="createrole">
<input type="hidden" name="parenttab" value="Settings">
<input type="hidden" name="returnaction" value="RoleDetailView">
<input type="hidden" name="roleid" value="{$ROLEID}">
<input type="hidden" name="mode" value="edit">

<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
	<tr>
		 <td class="showPanelBg" valign="top" width="95%"  style="padding-left:20px; "><br />
        	        <span class="lvtHeaderText"><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a> > {$MOD.LBL_USER_MANAGEMENT} > Role of {$ROLE_NAME} </b></span>
            	    <hr noshade="noshade" size="1" />
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
		<td width="95%" style="padding-left:20px;" valign="top">	  <table width="95%" cellpadding="5" cellspacing="0" class="leadTable" align="center">
          <tr>
            <td style="padding:5px;border-bottom:2px dotted #CCCCCC;" width="5%" ><img src="{$IMAGE_PATH}roles.gif" align="absmiddle" /> </td>
            <td style="padding:5px;border-bottom:2px dotted #AAAAAA;"><span class="genHeaderGrayBig">{$ROLE_NAME} Role</span><br />
                <span class="big">Detail view of {$ROLE_NAME} Role</span> </td>
          </tr>
          <tr><td colspan="2">&nbsp;</td></tr>
           <tr>
            <td colspan="2" >
					<table width="100%" cellpadding="5" cellspacing="0" border="0" >
							<tr>
									<td colspan="4" class="detailedViewHeader"><b>Group Details</b></td>
							</tr>
							<tr>
							  <td class="dvtCellLabel" width="5%">&nbsp;</td>
							  <td class="dvtCellLabel" align="right" width="25%"><b>Role Name :</b></td>
							  <td class="dvtCellInfo" align="left" width="25%">{$ROLE_NAME}</td>
							  <td class="dvtCellInfo" width="45%">&nbsp;</td>
					  </tr>
					  <tr><td colspan="4"  class="dvtCellInfo">&nbsp;</td></tr>
					   <tr>
                  <td colspan="4"  class="detailedViewHeader"><b>Member List</b></td>
                </tr>
               
				<tr>
						<td class="dvtCellLabel" align="left"><img src="{$IMAGE_PATH}profile_icon.gif" align="top"></td>
						<td class="dvtCellLabel" align="right" valign="top"><b>Associated profiles :</b></td>
						<td colspan="2" align="left" class="dvtCellInfo">
								{foreach item=elements from=$ROLEINFO.profileinfo}
										<a href="index.php?module=Users&action=profilePrivileges&parenttab=Settings&profileid={$elements.0}&mode=view">{$elements.1}</a><br>
								{/foreach}	
						</td>
				</tr>
				{if $ROLEINFO.userinfo.0 neq ''}
				 <tr>
						<td class="dvtCellLabel" align="left"><img src="{$IMAGE_PATH}user_icon.gif" align="top"></td>
						<td class="dvtCellLabel" align="right" valign="top"><b>Associated Users :</b></td>
						<td colspan="2" align="left" class="dvtCellInfo">
								{foreach item=elements from=$ROLEINFO.userinfo}
										<a href="index.php?module=Users&action=DetailView&parenttab=Settings&record={$elements.0}">{$elements.1}</a><br>
								{/foreach}	
						</td>
				</tr>
				{/if}
				 <tr><td colspan="4"  class="dvtCellInfo">&nbsp;</td></tr>
                 <tr>
                  <td colspan="4"  class="dvtCellInfo" align="center">
				  	<!-- <input title="Back" accessKey="C" class="classBtn" onclick="window.history.back();" type="button" name="New" value=" <  Back " > &nbsp; -->
					 <input value="   Edit   " title="Edit" accessKey="E" class="classBtn" type="submit" name="Edit" >
                    &nbsp;
					<input title="Delete" accessKey="D" class="classBtn" onclick="DeleteRole('{$ROLEID}')" type="button" name="Delete" value="Delete">
				</td>
                </tr>
          <tr><td colspan="2">&nbsp;</td></tr>
        </table></td>
		
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
</td>
</tr>
</table>
</td>
</tr>
</table>
	
	{include file='SettingsSubMenu.tpl'}
<div id="tempdiv" style="display:block;position:absolute;left:350px;top:200px;"></div>
<script>
	function DeleteRole(roleid)
	{ldelim}
    		//show("an_busy");
    		var ajaxObj = new Ajax(ajaxSaveResponse);
    		var urlstring = "module=Users&action=UsersAjax&file=RoleDeleteStep1&roleid="+roleid;
    		ajaxObj.process("index.php?",urlstring);
	{rdelim}
	
	function ajaxSaveResponse(response)
	{ldelim}
   		document.getElementById("tempdiv").innerHTML=response.responseText;
	{rdelim}
</script>


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
<script language="JavaScript" type="text/javascript" src="include/js/ColorPicker2.js"></script>
<script language="javascript" type="text/javascript" src="include/js/general.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/dtlviewajax.js"></script>
<span id="crmspanid" style="display:none;position:absolute;"  onmouseover="show('crmspanid');">
   <a class="link"  align="right" href="javascript:;">Edit</a>
</span>

<style type="text/css">@import url(themes/blue/style.css);</style>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
{if $CATEGORY eq 'Settings'}
	{include file='SettingsMenu.tpl'}
{/if}
<td width="75%" valign="top">

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr><td class="padTab" align="left">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<form name="DetailView" method="POST" action="index.php" ENCTYPE="multipart/form-data" id="form">
			<input type="hidden" name="module" value="Users">
			<input type="hidden" name="record" value="{$ID}">
			<input type="hidden" name="isDuplicate" value=false>
			<input type="hidden" name="action">

{if $CATEGORY eq 'Settings'}
			<input type="hidden" name="modechk" value="prefview">
{/if}
			<input type="hidden" name="user_name" value="{$USER_NAME}">
			<input type="hidden" name="old_password">
			<input type="hidden" name="new_password">
			<input type="hidden" name="return_module">
			<input type="hidden" name="return_action">
			<input type="hidden" name="return_id">
			<input type="hidden" name="forumDisplay">
{if $CATEGORY eq 'Settings'}
			<input type="hidden" name="parenttab" value="{$PARENTTAB}">
{/if}	

			<tr>
				<td style="border-bottom:1px dashed #CCCCCC;">
					<table width="100%" cellpadding="5" cellspacing="0" border="0">
					<tr>
						<td colspan=2 style="padding:5px;">
						{if $CATEGORY eq 'Settings'}
							<span class="lvtHeaderText">
							<b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a> > {$MOD.LBL_USER_MANAGEMENT} > {$MOD.LBL_USERS}</b></span>
						{else}
							<span class="lvtHeaderText">	
							<b>{$APP.LBL_MY_PREFERENCES}</b>
							</span>
						{/if}
						<span id="vtbusy_info" style="display:none;" valign="bottom"><img src="{$IMAGE_PATH}vtbusy.gif" border="0"></span>					
				<hr noshade="noshade" size="1" />
						</td>
						<td align="right"><hr noshade="noshade" size="1" /></td>
									  </tr>
									<tr>
											<td width="5%"><img src="{$IMAGE_PATH}user.gif" align="absmiddle"></td>
											<td width="95%"><span class="genHeaderGrayBig">{$USER_NAME}</span><br>
												<b class="small">{$UMOD.LBL_DETAIL_VIEW} {$FIRST_NAME} {$LAST_NAME}</b>
											</td>
									</tr>
							</table>
					</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr valign="center">
			        <td class="small" align="center">
					{$EDIT_BUTTON}
				{$CHANGE_PW_BUTTON}
				{$LOGIN_HISTORY_BUTTON}
			{if $CATEGORY eq 'Settings'}
				{$DUPLICATE_BUTTON}
			{/if}	
				{$CHANGE_HOMEPAGE_BUTTON}	
				{$LISTROLES_BUTTON}
	</td></tr>

		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2">
		<table align="center" border="0" cellpadding="0" cellspacing="0" width="99%">
		  
		  <tr>
			<td>
			  <table class="small" border="0" cellpadding="3" cellspacing="0" width="100%">
			  <tr >
			        <td id="prof" width="25%" align="center" nowrap="nowrap" class="dvtSelectedCell" onClick="fnVis('prof')"><b>{$UMOD.LBL_USER_LOGIN_ROLE}</b></td>
			     <td class="dvtTabCache" width="10" nowrap="nowrap">&nbsp;</td>
				    <td class="dvtTabCache" nowrap="nowrap" width="10">&nbsp;</td>
			  </tr>
			  </table>
			</td>
		  </tr>
		  <tr>
		  	<td align="left" valign="top">
				{foreach key=header item=detail from=$BLOCKS}
				<table border=0 cellspacing=0 cellpadding=0 width=100% class="small">
  		    	<tr>
    	               <td>&nbsp;</td>
        	           <td>&nbsp;</td>
            	       <td>&nbsp;</td>
                	   <td align=right>
				</tr>  
				<tr>
						{strip}
					     <td colspan=4 style="border-bottom:1px solid #999999;padding:5px;" bgcolor="#e5e5e5">
							<b>	{$header}</b>
						 </td>
						 {/strip}
			    </tr>
					{foreach item=detail from=$detail}
					<tr style="height:25px">
							{foreach key=label item=data from=$detail}
							   {assign var=keyid value=$data.ui}
							   {assign var=keyval value=$data.value}
							   {assign var=keytblname value=$data.tablename}
							   {assign var=keyfldname value=$data.fldname}
							   {assign var=keyoptions value=$data.options}
							   {assign var=keysecid value=$data.secid}
							   {assign var=keyseclink value=$data.link}
							   {assign var=keycursymb value=$data.cursymb}
							   {assign var=keysalut value=$data.salut}
							   {assign var=keycntimage value=$data.cntimage}
							   {assign var=keyadmin value=$data.isadmin}
							   
							   <input type="hidden" id="hdtxt_IsAdmin" value={$keyadmin}></input>
							   	{if $label ne ''}
									<td class="dvtCellLabel" align=right width=25%>{$label}</td>
									{include file="DetailViewUI.tpl"}
								{else}
                                    <td class="dvtCellLabel" align=right>&nbsp;</td>
                                    <td class="dvtCellInfo" align=left >&nbsp;</td>
				{/if}	
							{/foreach}
			 	</tr>
					{/foreach}
					</table>
				 {/foreach}

			</td>
			</tr>
			
	        <tr><td>&nbsp;</td></tr>
			
	  </table>



</td></tr>
</table>
</form>
</td></tr>
</table>
</td></tr>
</table>
</td>
</tr>
</table>

{$JAVASCRIPT}
{if $CATEGORY eq 'Settings'}
	{include file='SettingsSubMenu.tpl'}
{/if}

<!-- added for validation -->
<script language="javascript">
  var fieldname = new Array({$VALIDATION_DATA_FIELDNAME});
  var fieldlabel = new Array({$VALIDATION_DATA_FIELDLABEL});
  var fielddatatype = new Array({$VALIDATION_DATA_FIELDDATATYPE});
</script>

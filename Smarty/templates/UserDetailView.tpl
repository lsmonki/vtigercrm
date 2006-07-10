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
<script src="include/scriptaculous/scriptaculous.js" type="text/javascript"></script>
<span id="crmspanid" style="display:none;position:absolute;"  onmouseover="show('crmspanid');">
   <a class="link"  align="right" href="javascript:;">{$APP.LBL_EDIT_BUTTON}</a>
</span>

<br>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
<tr>
    <td valign="top"><img src="{$IMAGE_PATH}showPanelTopLeft.gif"></td>
    <td class="showPanelBg" style="padding: 10px;" valign="top" width="100%">
    <br>

    <div align=center>
    {if $CATEGORY eq 'Settings'}
        {include file='SetMenu.tpl'}
    {/if}
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
	    <td class="padTab" align="left">
		<form name="DetailView" method="POST" action="index.php" ENCTYPE="multipart/form-data" id="form">
		<input type="hidden" name="module" value="Users">
		<input type="hidden" name="record" id="userid" value="{$ID}">
		<input type="hidden" name="isDuplicate" value=false>
		<input type="hidden" name="action">
		<input type="hidden" name="changepassword">
		{if $CATEGORY neq 'Settings'}
			<input type="hidden" name="modechk" value="prefview">
		{/if}
		<input type="hidden" name="old_password">
		<input type="hidden" name="new_password">
		<input type="hidden" name="return_module">
		<input type="hidden" name="return_action">
		<input type="hidden" name="return_id">
		<input type="hidden" name="forumDisplay">
		{if $CATEGORY eq 'Settings'}
		<input type="hidden" name="parenttab" value="{$PARENTTAB}">
		{/if}	
	        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="settingsSelUITopLine">
		<tr>
		    <td style="border-bottom:1px dashed #CCCCCC;">
			<table width="100%" cellpadding="5" cellspacing="0" border="0">
			<tr>
			    <td rowspan="2"><img src="{$IMAGE_PATH}ico-users.gif" align="absmiddle"></td>	
			    <td>
			    {if $CATEGORY eq 'Settings'}
				<span class="heading2">
				<b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a> &gt; <a href="index.php?module=Administration&action=index&parenttab=Settings"> {$MOD.LBL_USERS} </a>&gt;"{$USERNAME}" </b></span>
			    {else}
				<span class="heading2">	
				<b>{$APP.LBL_MY_PREFERENCES}</b>
			   	</span>
			    {/if}
			    <span id="vtbusy_info" style="display:none;" valign="bottom"><img src="{$IMAGE_PATH}vtbusy.gif" border="0"></span>					
			    </td>
			    <td rowspan="2" nowrap>&nbsp;					
			    </td>	
			</tr>
			<tr>
			    <td>{$UMOD.LBL_USERDETAIL_INFO} "{$USERNAME}"</td>
			</tr>
		    </table>
		</td>
	    </tr>
	    <tr><td colspan="2">&nbsp;</td></tr>
	    <tr>
		<td rowspan="2" nowrap align="right">
		{if $IS_ADMIN eq 'true'}
			<input type="button" onclick="showAuditTrail();" value="{$MOD.LBL_VIEW_AUDIT_TRAIL}" class="crmButton small save"></input>
		{/if}
			{if $CATEGORY eq 'Settings'}
                               	{$DUPLICATE_BUTTON}
                       	{/if}
			{$EDIT_BUTTON}
		</td>&nbsp;</tr>
	    <tr><td colspan="2">&nbsp;</td></tr>
	    <tr>
		<td colspan="2">
		    <table align="center" border="0" cellpadding="0" cellspacing="0" width="99%">
		    <tr>
		  	<td align="left" valign="top">
			    {foreach key=header name=blockforeach item=detail from=$BLOCKS}
				<br>
				<table class="tableHeading" border="0" cellpadding="5" cellspacing="0" width="100%">
				<tr>
				    {strip}
				     <td class="big">	
					<strong>{$smarty.foreach.blockforeach.iteration}. {$header}</strong>
				     </td>
				     <td class="small" align="right">&nbsp;</td>	
				    {/strip}
			        </tr>
				</table>
				<table border="0" cellpadding="5" cellspacing="0" width="100%">
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
					   
					   {if $label ne ''}
					   <td class="dvtCellLabel" align=right width=25%><input type="hidden" id="hdtxt_IsAdmin" value={$keyadmin}></input>{$label}</td>
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
			    <br>
			    <table class="tableHeading" border="0" cellpadding="5" cellspacing="0" width="100%">
			    	<tr>
				     <td class="big">	
					<strong>4.{$UMOD.LBL_HOME_PAGE_COMP}</strong>
				     </td>
				     <td class="small" align="right"><img src="{$IMAGE_PATH}showDown.gif" alt="{$APP.LBL_EXPAND_COLLAPSE}" title="{$APP.LBL_EXPAND_COLLAPSE}" onClick="ShowHidefn('home_comp');"></td>	
			        </tr>
			    </table>
			<div style="float: none; display: none;" id="home_comp">	
			    <table border="0" cellpadding="5" cellspacing="0" width="100%">
				{foreach item=homeitems key=values from=$HOMEORDER}
					<tr><td class="dvtCellLabel" align="right" width="30%">{$UMOD.$values}</td>
					    {if $homeitems neq ''}
					    	<td class="dvtCellInfo" align="center" width="5%">
					   	<img src="{$IMAGE_PATH}prvPrfSelectedTick.gif" alt="{$UMOD.LBL_SHOWN}" height="12" width="12"></td><td class="dvtCellInfo" align="left">{$UMOD.LBL_SHOWN}</td> 		
					    {else}	
						<td class="dvtCellInfo" align="center" width="5%">
					   	<img src="{$IMAGE_PATH}no.gif" alt="{$UMOD.LBL_HIDDEN}" height="12" width="12"></td><td class="dvtCellInfo" align="left">{$UMOD.LBL_HIDDEN}</td> 		
					    {/if}	
					</tr>			
				{/foreach}
			    </table>	
			</div>
			<br>
			    <table class="tableHeading" border="0" cellpadding="5" cellspacing="0" width="100%">
			    	<tr>
				     <td class="big">	
					<strong>5.My Groups</strong>
				     </td>
				     <td class="small" align="right">
					{if $GROUP_COUNT > 0}
					<img src="{$IMAGE_PATH}showDown.gif" alt="{$APP.LBL_EXPAND_COLLAPSE}" title="{$APP.LBL_EXPAND_COLLAPSE}" onClick="fetchGroups_js({$ID});">
					{else}
						&nbsp;
					{/if}
					</td>	
			        </tr>
			    </table>
			    <table border="0" cellpadding="5" cellspacing="0" width="100%">
				<tr><td align="left"><div id="user_group_cont" style="display:none;"></div></td></tr>	
			    </table>	

			<br>
			    <table class="tableHeading" border="0" cellpadding="5" cellspacing="0" width="100%">
			    	<tr>
				     <td class="big">	
					<strong>6.{$UMOD.LBL_LOGIN_HISTORY}</strong>
				     </td>
				     <td class="small" align="right"><img src="{$IMAGE_PATH}showDown.gif" alt="{$APP.LBL_EXPAND_COLLAPSE}" title="{$APP.LBL_EXPAND_COLLAPSE}" onClick="fetchlogin_js({$ID});"></td>	
			        </tr>
			    </table>
			    <table border="0" cellpadding="5" cellspacing="0" width="100%">
				<tr><td align="left"><div id="login_history_cont" style="display:none;"></div></td><td></td></tr>	
			    </table>	

			</td>
			</tr>
			
		        <tr><td>&nbsp;</td></tr>
			
	  	</table>



</td></tr>
<tr><td class="small"><div align="right"><a href="#top">{$MOD.LBL_SCROLL}</a></div></td></tr>
</table>
</form>
</td></tr>
</table>
</td></tr>
</table>
</td>
</tr>
</table>
</div>
<td valign="top"><img src="{$IMAGE_PATH}showPanelTopRight.gif"></td>
</tr>
</table>
<br>
{$JAVASCRIPT}

<!-- added for validation -->
<script language="javascript">
  var fieldname = new Array({$VALIDATION_DATA_FIELDNAME});
  var fieldlabel = new Array({$VALIDATION_DATA_FIELDLABEL});
  var fielddatatype = new Array({$VALIDATION_DATA_FIELDDATATYPE});
function ShowHidefn(divid)
{ldelim}
	if($(divid).style.display != 'none')
		Effect.Fade(divid);
	else
		Effect.Appear(divid);
{rdelim}
{literal}
function fetchlogin_js(id)
{
	if($('login_history_cont').style.display != 'none')
		Effect.Fade('login_history_cont');
	else
		fetchLoginHistory(id);

}
function fetchLoginHistory(id)
{
        $("status").style.display="inline";
        new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: 'module=Users&action=UsersAjax&file=ShowHistory&ajax=true&record='+id,
                        onComplete: function(response) {
                                $("status").style.display="none";
                                $("login_history_cont").innerHTML= response.responseText;
				Effect.Appear('login_history_cont');
                        }
                }
        );

}
function fetchGroups_js(id)
{
	if($('user_group_cont').style.display != 'none')
		Effect.Fade('user_group_cont');
	else
		fetchUserGroups(id);
}
function fetchUserGroups(id)
{
        $("status").style.display="inline";
        new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: 'module=Users&action=UsersAjax&file=UserGroups&ajax=true&record='+id,
                        onComplete: function(response) {
                                $("status").style.display="none";
                                $("user_group_cont").innerHTML= response.responseText;
				Effect.Appear('user_group_cont');
                        }
                }
        );

}
function getListViewEntries_js(module,url)
{
	$("status").style.display="inline";
        new Ajax.Request(
        	'index.php',
                {queue: {position: 'end', scope: 'command'},
                	method: 'post',
                        postBody:"module="+module+"&action="+module+"Ajax&file=ShowHistory&ajax=true&"+url,
			onComplete: function(response) {
                        	$("status").style.display="none";
                                $("login_history_cont").innerHTML= response.responseText;
                  	}
                }
        );
}
function showAuditTrail()
{
	var userid =  document.getElementById('userid').value;
	window.open("index.php?module=Users&action=UsersAjax&file=ShowAuditTrail&userid="+userid,"","width=650,height=800,resizable=0,scrollbars=1,left=100");
}

{/literal}
</script>

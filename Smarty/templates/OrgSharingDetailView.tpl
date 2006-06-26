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
{literal}
<style>
DIV.fixedLay{
	border:3px solid #CCCCCC;
	background-color:#FFFFFF;
	width:500px;
	position:fixed;
	left:250px;
	top:98px;
	display:block;
}
</style>
{/literal}
{literal}
<!--[if lte IE 6]>
<STYLE type=text/css>
DIV.fixedLay {
	POSITION: absolute;
}
</STYLE>
<![endif]-->

{/literal}
<br>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
<tbody><tr>
        <td valign="top"><img src="{$IMAGE_PATH}showPanelTopLeft.gif"></td>
        <td class="showPanelBg" style="padding: 10px;" valign="top" width="100%">
        <br>

	<div align=center>
			{include file="SetMenu.tpl"}
				<!-- DISPLAY -->
				<table border=0 cellspacing=0 cellpadding=5 width=100% class="settingsSelUITopLine">
				<tr>
					<td width=50 rowspan=2 valign=top><img src="{$IMAGE_PATH}shareaccess.gif" alt="Users" width="48" height="48" border=0 title="Users"></td>
					<td class=heading2 valign=bottom><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS}</a> > {$MOD.LBL_SHARING_ACCESS} </b></td>
					<td rowspan=2 class="small" align=right>&nbsp;</td>
				</tr>
				<tr>
					<td valign=top class="small">{$MOD.LBL_SHARING_ACCESS_DESCRIPTION}</td>
				</tr>
				</table>

				<br>
			  	<!-- GLOBAL ACCESS MODULE -->
		  		<div id="globaldiv">
				<table border=0 cellspacing=0 cellpadding=5 width=100% class="tableHeading">
				<form action="index.php" method="post" name="new" id="form">
				<input type="hidden" name="module" value="Users">
				<input type="hidden" name="action" value="OrgSharingEditView">
				<input type="hidden" name="parenttab" value="Settings">
				<tr>
					<td class="big"><strong>1. {$CMOD.LBL_GLOBAL_ACCESS_PRIVILEGES}</strong></td>
					<td class="small" align=right>
						<input class="crmButton small cancel" title="{$CMOD.LBL_RECALCULATE_BUTTON}"  type="submit" name="recalculate" value="{$CMOD.LBL_RECALCULATE_BUTTON}" onclick="this.form.action.value='RecalculateSharingRules'; return confirm('Recalculate Sharing Rules will calculate the sharing rules for the whole organization. This Operation will take some time. Do you want to contunue? ')">	
	&nbsp;<input class="crmButton small edit" type="submit" name="Edit" value="{$CMOD.LBL_CHANGE} {$CMOD.LBL_PRIVILEGES}" ></td>
					</td>
				</tr>
				</table>
				<table cellspacing="0" cellpadding="5" class="listTable" width="100%">
				{foreach item=module from=$DEFAULT_SHARING}	
                  <tr>
                    <td width="20%" class="colHeader small" nowrap>{$APP[$module.0]}</td>
                    <td width="30%" class="listTableRow small" nowrap>
			{if $module.1 neq 'Private' && $module.1 neq 'Hide Details'}
				<img src="{$IMAGE_PATH}public.gif" align="absmiddle">
			{else}
				<img src="{$IMAGE_PATH}private.gif" align="absmiddle">
			{/if}
				{$CMOD[$module.1]}
		    </td>
                    <td width="50%" class="listTableRow small" nowrap>{$module.2}</td>
                  </tr>
		  {/foreach}
		</form>	
              </table>
		</div>	
		  <!-- END OF GLOBAL -->
				<br><br>
		  <!-- Custom Access Module Display Table -->
		  <div id="customdiv">
				<table border=0 cellspacing=0 cellpadding=5 width=100% class="tableHeading">
				<tr>
					<td class="big"><strong>2. {$CMOD.LBL_CUSTOM_ACCESS_PRIVILEGES}</strong></td>
					<td class="small" align=right>&nbsp;</td>
				</tr>
				</table>
				<!-- Start of Module Display -->
				{foreach  key=modulename item=details from=$MODSHARING}
				{if $details.0 neq ''}
				<table width="100%" border="0" cellpadding="5" cellspacing="0" class="listTableTopButtons">
                  		<tr>
		                    <td  style="padding-left:5px;" class="big"><img src="{$IMAGE_PATH}arrow.jpg" width="19" height="21" align="absmiddle" />&nbsp; <b>{$APP.$modulename}</b>&nbsp; </td>
                		    <td align="right">
					<input class="crmButton small save" type="button" name="Create" value="{$CMOD.LBL_ADD_PRIVILEGES_BUTTON}" onClick="callEditDiv('{$modulename}','create','')">
				    </td>
                  		</tr>
			  	</table>
				<table width="100%" cellpadding="5" cellspacing="0" class="listTable" >
                    		<tr>
                    		<td width="7%" class="colHeader small" nowrap>{$CMOD.LBL_RULE_NO}</td>
                          	<td width="20%" class="colHeader small" nowrap>{$APP.$modulename} {$CMOD.LBL_OF}</td>
                          	<td width="25%" class="colHeader small" nowrap>{$CMOD.LBL_CAN_BE_ACCESSED}</td>
                          	<td width="40%" class="colHeader small" nowrap>{$CMOD.LBL_PRIVILEGES}</td>
                          	<td width="8%" class="colHeader small" nowrap>{$APP.Tools}</td>
                        	</tr>
                        <tr >
			  {foreach key=sno item=elements from=$details}
                          <td class="listTableRow small">{$sno+1}</td>
                          <td class="listTableRow small">{$elements.1}</td>
                          <td class="listTableRow small">{$elements.2}</td>
                          <td class="listTableRow small">{$elements.3}</td>
                          <td align="center" class="listTableRow small">
				<a href="javascript:onClick=callEditDiv('{$modulename}','edit','{$elements.0}')"><img src="{$IMAGE_PATH}editfield.gif" title='edit' align="absmiddle" height="15" width="16" border=0></a>|<a href="index.php?module=Users&action=DeleteSharingRule&shareid={$elements.0}"><img src="{$IMAGE_PATH}delete.gif" title='del' align="absmiddle" height="15" width="16" border=0></a></td>
                        </tr>
                     {/foreach} 
                    </table>
	<!-- End of Module Display -->
	<!-- Start FOR NO DATA -->	
			<table border=0 cellspacing=0 cellpadding=5 width=100% class="tableHeading">
			<tr><td>&nbsp;</td></tr>
			</table>
		    {else}
                    <table width="100%" cellpadding="0" cellspacing="0" class="listTable"><tr><td>
		      <table width="100%" border="0" cellpadding="5" cellspacing="0" class="listTableTopButtons">
                      <tr>
                        <td  style="padding-left:5px;" class="big"><img src="{$IMAGE_PATH}arrow.jpg" width="19" height="21" align="absmiddle" />&nbsp; <b>{$APP.$modulename}</b>&nbsp; </td>
                        <td align="right">
				<input class="crmButton small save" type="button" name="Create" value="{$APP.LBL_ADD_ITEM} {$CMOD.LBL_PRIVILEGES}" onClick="callEditDiv('{$modulename}','create','')">
			</td>
                      </tr>
			<table width="100%" cellpadding="5" cellspacing="0">
			<tr>
			<td colspan="2"  style="padding:20px ;" align="center" class="small">
			   {$CMOD.LBL_CUSTOM_ACCESS_MESG} 
			   <a href="javascript:onClick=callEditDiv('{$modulename}','create','')">{$CMOD.LNK_CLICK_HERE}</a>
			   {$CMOD.LBL_CREATE_RULE_MESG}
			</td>
			</tr>
		    </table>
		    </table>	
			<table border=0 cellspacing=0 cellpadding=5 width=100% class="tableHeading">
			<tr><td>&nbsp;</td></tr>
			</table>
		    {/if}
		    {/foreach}			
		   </td></tr></table>
				<br>
		   </div>	
				<!-- Edit Button -->
				<table border=0 cellspacing=0 cellpadding=5 width=100% >
				<tr><td class="small" ><div align=right><a href="#top">{$MOD.LBL_SCROLL}</a></div></td></tr>				</table>
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
<div id="tempdiv" style="display:block;position:absolute;left:225px;top:150px;"></div>
<script>

function callEditDiv(modulename,mode,id)
{ldelim}
	$("status").style.display="inline";
	new Ajax.Request(
		'index.php',
		{ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
			method: 'post',
			postBody: 'module=Users&action=UsersAjax&orgajax=true&mode='+mode+'&sharing_module='+modulename+'&shareid='+id,
			onComplete: function(response) {ldelim}
				$("status").style.display="none";
				$("tempdiv").innerHTML=response.responseText;
			{rdelim}
		{rdelim}
	);
{rdelim}

function fnwriteRules(module,related)
{ldelim}
		var modulelists = new Array();
		modulelists = related.split('###');
		var relatedstring ='';
		var relatedtag;
		var relatedselect;
		var modulename;
		for(i=0;i < modulelists.length-1;i++)
		{ldelim}
			modulename = modulelists[i]+"_accessopt";
			relatedtag = document.getElementById(modulename);
			relatedselect = relatedtag.options[relatedtag.selectedIndex].text;
			relatedstring += modulelists[i]+':'+relatedselect+' ';
		{rdelim}	
		var tagName = document.getElementById(module+"_share");
		var tagName2 = document.getElementById(module+"_access");
		var tagName3 = document.getElementById('share_memberType');
		var soucre =  document.getElementById("rules");
		var soucre1 =  document.getElementById("relrules");
		var select1 = tagName.options[tagName.selectedIndex].text;
		var select2 = tagName2.options[tagName2.selectedIndex].text;
		var select3 = tagName3.options[tagName3.selectedIndex].text;
		soucre.innerHTML = module +" of <b>\"" + select1 + "\"</b> can be accessed by <b>\"" +select2 + "\"</b> in the permission "+select3;
		soucre1.innerHTML = "<b>Related Module Rights</b> "+ relatedstring;
{rdelim}

</script>

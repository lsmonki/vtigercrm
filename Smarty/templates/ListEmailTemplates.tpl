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
<script language="JavaScript" type="text/javascript" src="modules/{$MODULE}/{$SINGLE_MOD}.js"></script>
<style type="text/css">@import url(themes/blue/style.css);</style>

<script>
function massDelete()
{ldelim}
        x = document.massdelete.selected_id.length;
        idstring = "";

        if ( x == undefined)
        {ldelim}

                if (document.massdelete.selected_id.checked)
               {ldelim}
                        document.massdelete.idlist.value=document.massdelete.selected_id.value;
                {rdelim}
                else
                {ldelim}
                        alert("Please select atleast one entity");
                        return false;
                {rdelim}
        {rdelim}
        else
        {ldelim}
                xx = 0;
                for(i = 0; i < x ; i++)
                {ldelim}
                        if(document.massdelete.selected_id[i].checked)
                        {ldelim}
                                idstring = document.massdelete.selected_id[i].value +";"+idstring
                        xx++
                        {rdelim}
                {rdelim}
                if (xx != 0)
                {ldelim}
                        document.massdelete.idlist.value=idstring;
                {rdelim}
               else
                {ldelim}
                        alert("Please select atleast one entity");
                        return false;
                {rdelim}
       {rdelim}
		if(confirm("Are you sure you want to delete the selected "+xx+" records ?"))
		{ldelim}
	        document.massdelete.action="index.php?module=Users&action=deleteemailtemplate&return_module=Users&return_action=listemailtemplates";
		{rdelim}
		else
		{ldelim}
			return false;
		{rdelim}

{rdelim}
</script>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	{include file='SettingsMenu.tpl'}
<td width="75%" valign="top">
<!-- EMAIL TEMPLATE PAGE STARTS HERE -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
	<tr>
		 <td class="showPanelBg" valign="top" width="90%"  style="padding-left:20px; "><br />
        	        <span class="lvtHeaderText"><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS}</a>
			 <a href="index.php?module=Users&action=listemailtemplates&parenttab=Settings">&gt; {$UMOD.LBL_EMAIL_TEMPLATES_LIST}</a></b></span>
            	    <hr noshade="noshade" size="1" />
		</td>

		<td width="10%" class="showPanelBg">&nbsp;</td>
	</tr>
	<tr>
		<td width="90%" style="padding-left:20px;" valign="top">
			<table width="100%" cellpadding="3" cellspacing="0" border="0" class="small" >
			<form  name="massdelete" method="POST">
    			<input name="idlist" type="hidden">
    			<input name="module" type="hidden" value="Users">
    			<input name="action" type="hidden" value="deleteemailtemplate">
				<tr><td colspan="4" style="border:0px;">&nbsp;</td></tr>
				<tr>
					<td colspan="2" align="left" style="border:0px;"><input type="submit" value="{$UMOD.LBL_DELETE}" onclick="return massDelete();" class="classBtn" /></td>
					<td style="border:0px;">&nbsp;</td>

					<td align="right" style="border:0px;">
						<div align="right"><input type="submit" value="{$UMOD.LBL_NEW_TEMPLATE}" name="profile"  class="classBtn" onclick="this.form.action.value='createemailtemplate';this.form.parenttab.value='Settings';"/></div>
					</td>
				</tr>
				<tr>{*<td colspan="4" style="border:0px;">&nbsp;</td>*}</tr>
				<tr><td colspan="4">
					<table style="background-color: rgb(204, 204, 204);" class="small" border="0" cellpadding="5" cellspacing="1" width="100%">
						<tr>
				  <th width="5%" class="lvtCol" ><input type="checkbox" name="selectall" onClick=toggleSelect(this.checked,"selected_id") ></th>
				  <th width="20%" class="lvtCol"><b>{$UMOD.LBL_TEMPLATE_HEADER}</b></th>

				  <th width="50%" class="lvtCol"><b>{$UMOD.LBL_DESCRIPTION}</b></th>
				  <th width="25%" class="lvtCol"><b>{$UMOD.LBL_TEMPLATE_TOOLS}</b></th>
			  </tr>
				{foreach item=template from=$TEMPLATES}
				<tr class="lvtColData" onmouseover="this.className='lvtColDataHover'" onmouseout="this.className='lvtColData'" bgcolor="white">
				<td><input type="checkbox" name="selected_id" value="{$template.templateid}" onClick=toggleSelectAll(this.name,"selectall") /></td>
				<td nowrap>
				{if $template.foldername == "Public"}
					<img src="{$IMAGE_PATH}public.gif" align="absmiddle" />
				{else}
					<img src="{$IMAGE_PATH}private.gif" align="absmiddle" />
				{/if}				
				&nbsp;<a href="index.php?module=Users&action=detailviewemailtemplate&parenttab=Settings&templateid={$template.templateid}" >{$template.templatename}</a></td>
				<td>{$template.description}</td>
				<td><a href="index.php?module=Users&action=detailviewemailtemplate&parenttab=Settings&templateid={$template.templateid}">{$UMOD.LNK_SAMPLE_EMAIL}</a></td>
			  	</tr>
				
				{/foreach}
				<tr>
					</table>
				</td></tr>
				<tr>
				  <td colspan="3" style="border:0px">

				  	<img src="{$IMAGE_PATH}private.gif" align="absmiddle" />&nbsp;{$UMOD.LBL_TEMPLATE_PRIVATE}
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<img src="{$IMAGE_PATH}public.gif" align="absmiddle"/>&nbsp;{$UMOD.LBL_TEMPLATE_PUBLIC}
					
				  </td>
				  <td style="border:0px">
				  	<div align="right"><a href="#">{$UMOD.LNK_GO_TO_TOP}</a></div>
				  </td>
			  	</tr>
			</form>	
			</table>
			
		</td>
		<td>&nbsp;</td>
	</tr>
</table>
<!-- END -->

</td>
</tr>
</table>

{$JAVASCRIPT}
{include file='SettingsSubMenu.tpl'}



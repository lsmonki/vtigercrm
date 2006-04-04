<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
<script language="JavaScript" type="text/javascript" src="modules/{$MODULE}/{$SINGLE_MOD}.js"></script>
<style type="text/css">@import url(../themes/blue/style.css);</style>

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
	        document.massdelete.action="index.php?module=Users&action=deletewordtemplate&return_module=Users&return_action=listwordtemplates";
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
<!-- MAIL MERGE TEMPLATE PAGE STARTS HERE -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
	<tr>
		 <td class="showPanelBg" valign="top" width="90%"  style="padding-left:20px; "><br />
        	        <span class="lvtHeaderText"><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS}</a> {$UMOD.LBL_MAILMERGE_TEMPLATES_LIST}  </span>
            	    <hr noshade="noshade" size="1" />
		</td>

		<td width="10%" class="showPanelBg">&nbsp;</td>
	</tr>
	<tr>
		<td width="90%" style="padding-left:20px;" valign="top">
			<table width="100%" cellpadding="3" cellspacing="0" class="prdTab" >
			<form  name="massdelete" method="POST">
    			<input name="idlist" type="hidden">
    			<input name="module" type="hidden" value="Users">
    			<input name="action" type="hidden" value="deletewordtemplate">
				<tr><td colspan="4" style="border:0px;">&nbsp;</td></tr>
				<tr>
					<td colspan="2" align="left" style="border:0px;"><input type="submit" value="Delete" onclick="return massDelete();" class="small" /></td>
					<td style="border:0px;">&nbsp;</td>

					<td align="right" colspan="333" style="border:0px;">
						<div align="right" ><input type="submit" value="New Template" name="profile"  class="small" onclick="this.form.action.value='upload';"/></div>
					</td>
				</tr>
				<tr>{*<td colspan="4" style="border:0px;">&nbsp;</td>*}</tr>
				<tr>
				  <th width="5%"  style="border-top:1px solid #CCCCCC;height:30px;"><input type="checkbox" name="selectall" onClick=toggleSelect(this.checked,"selected_id") ></th>
				  <th width="20%" style="border-top:1px solid #CCCCCC; " nowrap><b>{$UMOD.LBL_FILE}</b></th>

				  <th width="50%" style="border-top:1px solid #CCCCCC; " nowrap><b>{$UMOD.LBL_DESCRIPTION}</b></th>
				  <th width="25%" style="border-top:1px solid #CCCCCC; " nowrap><b>{$UMOD.LBL_MODULENAMES}</b></th>
				  <th width="25%" style="border-top:1px solid #CCCCCC; " nowrap><b>{$UMOD.LBL_DOWNLOAD}</b></th>
				  <th width="25%" style="border-top:1px solid #CCCCCC; " nowrap><b>{$UMOD.LBL_FILE_TYPE}</b></th>
			  </tr>
				{foreach item=template from=$WORDTEMPLATES}
				<tr onMouseOver="this.className='prvPrfHoverOn'" onMouseOut="this.className='prvPrfHoverOut'">
				<td><input type="checkbox" name="selected_id" value="{$template.templateid}" onClick=toggleSelectAll(this.name,"selectall") /></td>
				<td nowrap>{$template.filename}</td>
				<td nowrap>{$template.description}</td>
				<td nowrap>{$template.module}</td>
				<td nowrap><a href="index.php?module=Users&action=downloadfile&record={$template.templateid}">{$UMOD.LBL_DOWNLOAD_NOW}</a></td>
				<td nowrap>{$template.filetype}</td>	
			  	</tr>
				
				{/foreach}
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


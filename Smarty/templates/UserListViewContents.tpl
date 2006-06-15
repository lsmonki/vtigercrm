	
<table border=0 cellspacing=0 cellpadding=5 width=100% class="tableHeading">
<tr>
	<td class="big"><strong>{$MOD.LBL_USERS_LIST}</strong></td>
	<td class="small" align=right>{$CMOD.LBL_TOTAL} {$USER_COUNT.user} {$MOD.LBL_USERS} ({$USER_COUNT.admin} {$CMOD.LBL_ADMINS}, {$USER_COUNT.nonadmin} {$CMOD.LBL_STD_USERS})</td>
</tr>
</table>
					
<table border=0 cellspacing=0 cellpadding=5 width=100% class="listTableTopButtons">
<tr>
	<td class=small align=right><input title="{$CMOD.LBL_NEW_USER_BUTTON_TITLE}" accessyKey="{$CMOD.LBL_NEW_USER_BUTTON_KEY}" type="submit" name="button" value="{$CMOD.LBL_NEW_USER_BUTTON_LABEL}" class="crmButton create small"></td>
</tr>
</table>
						
<table border=0 cellspacing=0 cellpadding=5 width=100% class="listTable">
<tr>
	<td class="colHeader small" valign=top>#</td>
	<td class="colHeader small" valign=top>{$APP.Tools}</td>
	<td class="colHeader small" valign=top>{$LIST_HEADER.3}</td>
	<td class="colHeader small" valign=top>{$LIST_HEADER.5}</td>
	<td class="colHeader small" valign=top>{$LIST_HEADER.7}</td>
	<td class="colHeader small" valign=top>{$LIST_HEADER.6}</td>
	<td class="colHeader small" valign=top>{$LIST_HEADER.4}</td>
</tr>
	{foreach name=userlist item=listvalues key=userid from=$LIST_ENTRIES}
<tr>
	<td class="listTableRow small" valign=top>{$smarty.foreach.userlist.iteration}</td>
	<td class="listTableRow small" valign=top><a href="index.php?action=EditView&return_action=ListView&return_module=Users&module=Users&parenttab=Settings&record={$userid}"><img src="{$IMAGE_PATH}editfield.gif" alt="Edit" title="Edit" border="0"></a>&nbsp;
	{if $userid neq 1 && $userid neq 2}	
	<img src="{$IMAGE_PATH}delete.gif" onclick="deleteUser('{$userid}')" border="0"  alt="Delete" title="Delete"/></a>
	{/if}
&nbsp;</td>
	<td class="listTableRow small" valign=top><b><a href="index.php?module=Users&action=DetailView&parenttab=Settings&record={$userid}"> {$listvalues.3} </a></b><br><a href="index.php?module=Users&action=DetailView&parenttab=Settings&record={$userid}"> {$listvalues.1} {$listvalues.0}</a> ({$listvalues.2})</td>
	<td class="listTableRow small" valign=top>{$listvalues.5}</td>
	<td class="listTableRow small" valign=top>{$listvalues.7}</td>
	<td class="listTableRow small" valign=top>{$listvalues.6}</td>
	{if $listvalues.4 eq 'Active'}
	<td class="listTableRow small active" valign=top>{$listvalues.4}</td>
	{else}
	<td class="listTableRow small inactive" valign=top>{$listvalues.4}</td>
	{/if}	

</tr>
	{/foreach}
</table>
<table border=0 cellspacing=0 cellpadding=5 width=100% >
	<tr><td class="small" nowrap align=right><a href="#top">{$MOD.LBL_SCROLL}</a></td></tr>
</table>


	
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
	{foreach item=header from=$LIST_HEADER} 
		<td class="colHeader small" valign=top>{$header}</td>
	{/foreach}
</tr>
	{foreach name=userlist item=listvalues from=$LIST_ENTRIES}
<tr>
	<td class="listTableRow small" valign=top>{$smarty.foreach.userlist.iteration}</td>
	<td class="listTableRow small" valign=top>{$listvalues.7}</td>
	<td class="listTableRow small" valign=top><b>{$listvalues.0}</b><br>{$listvalues.3} <a> (</a>{$listvalues.1})</td>
	<td class="listTableRow small" valign=top>{$listvalues.2}</td>
	<td class="listTableRow small" valign=top>{$listvalues.6}</td>
	<td class="listTableRow small" valign=top>{$listvalues.4}</td>
	{if $listvalues.5 eq 'Active'}
	<td class="listTableRow small active" valign=top>{$listvalues.5}</td>
	{else}
	<td class="listTableRow small inactive" valign=top>{$listvalues.5}</td>
	{/if}	

</tr>
	{/foreach}
</table>
<table border=0 cellspacing=0 cellpadding=5 width=100% >
	<tr><td class="small" nowrap align=right><a href="#top">{$MOD.LBL_SCROLL}</a></td></tr>
</table>


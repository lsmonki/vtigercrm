<div id="dvtUserOrg">
    <br>
    <!-- Organization assignments -->
    <table class="tableHeading" border="0" cellpadding="5" cellspacing="0" width="100%">
    <tr>
	<td class="big">	
	    <strong>5. {$UMOD.LBL_ORGANIZATION_INFORMATION}</strong>
	</td>
    </tr>
    </table>

    <table border="0" cellpadding="5" cellspacing="0" width="100%">
    <tr>
	{assign var=keyid value=115}
	{assign var=label value=$UMOD.LBL_PRIMARY_ORGANIZATION}
	{assign var=keyval value=$EDIT_USER_PRIMARY_ORGANIZATION}
	{assign var=keytblname value='vtiger_user2org'}
	{assign var=keyfldname value='primary_org'}
	{assign var=keyoptions value=$EDIT_USER_ORGANIZATIONS}
	{if $IS_ADMIN==true}
	    {assign var=keyadmin value=1}
	{elseif $ID==$CURRENT_USERID}
	    {assign var=keyadmin value=1}
	{else}
	    {assign var=keyadmin value=0}
	{/if}
	
	<td class="dvtCellLabel" align=right width=25%><input type="hidden" id="hdtxt_IsAdmin" value={$keyadmin}></input>{$label}</td>
	{include file="DetailViewUI.tpl"}
	<td align=right width=25%></td>
	<td align=right width=25%></td>
    </tr>
    <tr>
	{assign var=keyid value=34}
	{assign var=label value=$UMOD.LBL_ASSIGNED_ORGANIZATIONS}
	{assign var=keyval value=$EDIT_USER_ASSIGNED_ORGANIZATIONS}
	{assign var=keytblname value='vtiger_user2org'}
	{assign var=keyfldname value='assigned_org[]'}
	{assign var=keyoptions value=$ALL_USER_ORGANIZATIONS}
	{if $IS_ADMIN==true}
	    {assign var=keyadmin value=1}
	{else}
	    {assign var=keyadmin value=0}
	{/if}
	
	<td class="dvtCellLabel" align=right width=25%><input type="hidden" id="hdtxt_IsAdmin" value={$keyadmin}></input>{$label}</td>
	{include file="DetailViewUI.tpl"}
	<td align=right width=25%></td>
	<td align=right width=25%></td>
    </tr>
    <tr>
	{assign var=keyid value=34}
	{assign var=label value=$UMOD.LBL_PRIMARY_ORGUNITS}
	{assign var=keyval value=$EDIT_USER_PRIMARY_ORGUNITS}
	{assign var=keytblname value='vtiger_user2org'}
	{assign var=keyfldname value='primary_orgunits[]'}
	{assign var=keyoptions value=$EDIT_USER_ORGUNITS}
	{if $IS_ADMIN==true}
	    {assign var=keyadmin value=1}
	{elseif $ID==$CURRENT_USERID}
	    {assign var=keyadmin value=1}
	{else}
	    {assign var=keyadmin value=0}
	{/if}
	
	<td class="dvtCellLabel" align=right width=25%><input type="hidden" id="hdtxt_IsAdmin" value={$keyadmin}></input>{$label}</td>
	{include file="DetailViewUI.tpl"}
	<td align=right width=25%></td>
	<td align=right width=25%></td>
    </tr>
    </table>
</div>

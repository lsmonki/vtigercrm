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
	{assign var=label value=$UMOD.LBL_PRIMARY_ORGANIZATION}
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

	<td width=25% class="dvtCellInfo" align="left">&nbsp;
	    <select {if $keyadmin eq 0} disabled {/if} id="txtbox_{$label}" name="{$keyfldname}">
	    {foreach item=arr from=$keyoptions}
		{foreach key=sel_value item=value from=$arr}
		    <option value="{$sel_value}" {$value}>{$sel_value}</option>
		{/foreach}
	    {/foreach}
	    </select>
	</td>

	<td align=right width=25%></td>
	<td align=right width=25%></td>
    </tr>
    <tr>
	{assign var=label value=$UMOD.LBL_ASSIGNED_ORGANIZATIONS}
	{assign var=keyfldname value='assigned_org[]'}
	{assign var=keyoptions value=$ALL_USER_ORGANIZATIONS}
	{if $IS_ADMIN==true}
	    {assign var=keyadmin value=1}
	{else}
	    {assign var=keyadmin value=0}
	{/if}
	
	<td class="dvtCellLabel" align=right width=25%><input type="hidden" id="hdtxt_IsAdmin" value={$keyadmin}></input>{$label}</td>

	<td width=25% class="dvtCellInfo" align="left">&nbsp;
	    <select MULTIPLE
		{if $keyadmin eq 0} disabled {/if}
		id="txtbox_{$label}" name="{$keyfldname}" size="4" style="width:320px;" onchange="update_org(this);">
		{foreach item=arr from=$keyoptions}
		    {foreach key=sel_value item=value from=$arr}
			<option value="{$sel_value}" {$value}>{$sel_value}</option>
		    {/foreach}
		{/foreach}
	    </select>
	</td>

	<td align=right width=25%></td>
    </tr>
    <tr>
	{assign var=label value=$UMOD.LBL_PRIMARY_ORGUNITS}
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

	<td width=25% class="dvtCellInfo" align="left">&nbsp;
	    <select MULTIPLE
		{if $keyadmin eq 0} disabled {/if}
		id="txtbox_{$label}" name="{$keyfldname}" size="4" style="width:320px;">
		{foreach item=arr from=$keyoptions}
		    {foreach key=sel_value item=value from=$arr}
			<option value="{$sel_value}" {$value}>{$sel_value}</option>
		    {/foreach}
		{/foreach}
	    </select>
	</td>

	<td align=right width=25%></td>
    </tr>
    </table>
</div>

<script>
function update_org(userorg)
{ldelim}
    var primary_org_id = $("txtbox_{$UMOD.LBL_PRIMARY_ORGANIZATION}");
    var primary_org = "";
    if( primary_org_id.selectedIndex > -1)
	primary_org = primary_org_id.options[primary_org_id.selectedIndex].value;
    var assigned_org_id = $("txtbox_{$UMOD.LBL_ASSIGNED_ORGANIZATIONS}");
    var assigned_org = "";
    for( var i=0; i<assigned_org_id.length; i++) {ldelim}
	if( assigned_org_id.options[i].selected == true) {ldelim}
	    if( assigned_org == "")
		assigned_org = assigned_org_id.options[i].text;
	    else
		assigned_org += ":" + assigned_org_id.options[i].value;
	{rdelim}
    {rdelim}
    var primary_orgunits_id = $("txtbox_{$UMOD.LBL_PRIMARY_ORGUNITS}");
    var primary_orgunits = "";
    for( var i=0; i<primary_orgunits_id.length; i++) {ldelim}
	if( primary_orgunits_id.options[i].selected == true) {ldelim}
	    if( primary_orgunits == "")
		primary_orgunits = primary_orgunits_id.options[i].value;
	    else
		primary_orgunits += ":" + primary_orgunits_id.options[i].text;
	{rdelim}
    {rdelim}

    var data = "file=EditUserOrg&module=Users&action=UsersAjax";
    data += "&primary_org=" + primary_org;
    data += "&assigned_org=" + assigned_org;
    data += "&primary_orgunits=" + primary_orgunits;

    new Ajax.Request(
	'index.php',
	{ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
	    method: 'post',
	    postBody: data,
	    onComplete: function(response) {ldelim}
		$("dvtUserOrg").innerHTML = response.responseText;
	    {rdelim}
	{rdelim}
    );
{rdelim}
</script>


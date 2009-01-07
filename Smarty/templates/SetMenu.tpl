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
{php}
	//add the settings page values
	$this->assign("BLOCKS",getSettingsBlocks());
	$this->assign("FIELDS",getSettingsFields());
{/php}

<table border=0 cellspacing=0 cellpadding=20 width=90% class="settingsUI">
<tr>
<td valign=top>
	<table border=0 cellspacing=0 cellpadding=0 width=100%>
	<tr>
	<td valign=top>
		<!--Left Side Navigation Table-->
		<table border=0 cellspacing=0 cellpadding=0 width=100%>
			{foreach key=BLOCKID item=BLOCKLABEL from=$BLOCKS}
				<tr>
					<td class="settingsTabHeader" nowrap>
						{$MOD.$BLOCKLABEL}
					</td>
				</tr>
				{foreach item=data from=$FIELDS.$BLOCKID}
					{assign var=label value=$data.name}
					{if ($smarty.request.action eq $data.action && $smarty.request.module eq $data.module) ||  $smarty.request.action eq 'DetailView' || $smarty.request.action eq 'EditView' || $smarty.request.action eq 'ListView' }
						<tr>
						<td class="settingsTabSelected" nowrap>
							<a href="{$data.link}">
								{$MOD.$label}
							</a>
						</td>
						</tr>
					{else}
						<tr>
						<td class="settingsTabList" nowrap>
							<a href="{$data.link}">
								{$MOD.$label}
							</a>
						</td>
						</tr>
					{/if}
				{/foreach}
			{/foreach}
		</table>
		<!-- Left side navigation table ends -->
		
	</td>
	<td width=90% class="small settingsSelectedUI" valign=top align=left>

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

	{include file="Buttons_List1.tpl"}

<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
<tbody>
<tr>
	<td valign="top"><img src="{$IMAGES}showPanelTopLeft.gif"></td>
	<td class="showPanelBg" style="padding: 10px;" valign="top" width="100%">
	<br>
	<div align=center>
	<table border=0 cellspacing=0 cellpadding=20 width=90% class="settingsUI">
	<tr>
		<td>
		<table border=0 cellspacing=0 cellpadding=0 width=100%>
			{foreach key=BLOCKID item=BLOCKLABEL from=$BLOCKS}
				<tr>
					<td class="settingsTabHeader">
						{$MOD.$BLOCKLABEL}
					</td>
				</tr>
				<tr>
				<td class="settingsIconDisplay small">
					<table border=0 cellspacing=0 cellpadding=10 width=100%>
						<tr>
						{foreach item=data from=$FIELDS.$BLOCKID name=itr}
							<td width=25% valign=top>
							<table border=0 cellspacing=0 cellpadding=5 width=100%>
								<tr>
									{assign var=label value=$data.name}
									{assign var=count value=$smarty.foreach.itr.iteration}
									<td rowspan=2 valign=top>
										<a href="{$data.link}">
											<img src="{$IMAGES}{$data.icon}" alt="{$MOD.$label}" width="48" height="48" border=0 title="{$MOD.$label}">
										</a>
									</td>
									<td class=big valign=top>
										<a href="{$data.link}">
											{$MOD.$label}
										</a>
									</td>
								</tr>
								<tr>
									{assign var=description value=$data.description}
									<td class="small" valign=top>
										{$MOD.$description}
									</td>
								</tr>
							</table>
							</td>
						{if $count mod $NUMBER_OF_COLUMNS eq 0}
							</tr><tr>
						{/if}
				{/foreach}
						</table>
					</td>
					</tr>
			{/foreach}
		</table>
		</td>
	</tr>
	</table>
	</td>
</tr>
</table>

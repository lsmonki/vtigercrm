<table border=0 cellspacing=0 cellpadding=5 width=100% class="listRow">

<tr>
	<td class="big tableHeading"><strong>{$MOD.VTLIB_LBL_MODULE_MANAGER_HELP}</strong></td>
	<td class="big tableHeading" colspan=3 width=10% align="center">
		<form style="display: inline;" action="index.php?module=Settings&action=ModuleManager&module_import=Step1&parenttab=Settings" method="POST">
			<input type="submit" class="crmbutton small create" value='{$APP.LBL_IMPORT} {$APP.LBL_NEW}' title='{$APP.LBL_IMPORT}'>
		</form>
	</td>
</tr>

{foreach key=modulename item=modpresence from=$TOGGLE_MODINFO}
{assign var="modulelabel" value=$modulename}
{if $APP.$modulename}{assign var="modulelabel" value=$APP.$modulename}{/if}

<tr>
	<td class="cellLabel small">{$modulelabel}</td>
	<td class="cellText small" width="15px" align=center>
	{if $modpresence eq 0}
		<a href="javascript:void(0);" onclick="vtlib_toggleModule('{$modulename}', 'module_disable');"><img src="{$IMAGE_PATH}enabled.gif" border="0" align="absmiddle" alt="{$MOD.LBL_DISABLE} {$modulelabel}" title="{$MOD.LBL_DISABLE} {$modulelabel}"></a>
	{else}
		<a href="javascript:void(0);" onclick="vtlib_toggleModule('{$modulename}', 'module_enable');"><img src="{$IMAGE_PATH}disabled.gif" border="0" align="absmiddle" alt="{$MOD.LBL_ENABLE} {$modulelabel}" title="{$MOD.LBL_ENABLE} {$modulelabel}"></a>
	{/if}
	</td>
	<td class="cellText small" width="15px" align=center>
		{if $modulename eq 'Calendar' || $modulename eq 'Home'}
			<img src="{$IMAGE_PATH}menuDnArrow.gif" border="0" align="absmiddle">
		{else}
			<a href="index.php?modules=Settings&action=ModuleManagerExport&module_export={$modulename}"><img src="{$IMAGE_PATH}webmail_uparrow.gif" border="0" align="absmiddle" alt="{$APP.LBL_EXPORT} {$modulelabel}" title="{$APP.LBL_EXPORT} {$modulelabel}"></a>
		{/if}
	</td>
	<td class="cellText small" width="15px" align=center>
		<a href="index.php?module=Settings&action=ModuleManager&module_settings=true&formodule={$modulename}&parenttab=Settings"><img src="{$IMAGE_PATH}Settings.gif" border="0" align="absmiddle" alt="{$modulelabel} {$MOD.LBL_SETTINGS}" title="{$modulelabel} {$MOD.LBL_SETTINGS}"></a>
	</td>
</tr>
{/foreach}
</table>

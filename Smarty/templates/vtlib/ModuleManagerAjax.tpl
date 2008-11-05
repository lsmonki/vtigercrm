<table border=0 cellspacing=0 cellpadding=5 width=100% class="listRow">

<tr>
	<td class="big tableHeading"><strong>{$MOD.VTLIB_LBL_MODULE_MANAGER_HELP}</strong></td>
	<td class="big tableHeading" colspan=2 width=10% align="center">
		<form action="index.php?module=Settings&action=ModuleManager&module_import=Step1&parenttab=Settings" method="POST">
			<input type="submit" class="crmbutton small create" value='{$APP.LBL_IMPORT} {$APP.LBL_NEW}' title='{$APP.LBL_IMPORT}'>
		</form>
	</td>
</tr>

{foreach key=modulename item=modpresence from=$TOGGLE_MODINFO}
<tr>
	<td class="cellLabel small">{$APP.$modulename}</td>
	<td class="cellText small" width=5% align=center>
	{if $modpresence eq 0}
		<a href="javascript:void(0);" onclick="vtlib_toggleModule('{$modulename}', 'module_disable');"><img src="{$IMAGE_PATH}enabled.gif" border="0" align="absmiddle" alt="{$MOD.LBL_DISABLE} {$modulename}" title="{$MOD.LBL_DISABLE} {$modulename}"></a>
	{else}
		<a href="javascript:void(0);" onclick="vtlib_toggleModule('{$modulename}', 'module_enable');"><img src="{$IMAGE_PATH}disabled.gif" border="0" align="absmiddle" alt="{$MOD.LBL_ENABLE} {$modulename}" title="{$MOD.LBL_ENABLE} {$modulename}"></a>
	{/if}
	</td>
	<td class="cellText small" width=5% align=center>
		{if $modulename eq 'Calendar' || $modulename eq 'Home'}
			<img src="{$IMAGE_PATH}menuDnArrow.gif" border="0" align="absmiddle">
		{else}
			<a href="index.php?modules=Settings&action=ModuleManagerExport&module_export={$modulename}"><img src="{$IMAGE_PATH}webmail_uparrow.gif" border="0" align="absmiddle" alt="{$APP.LBL_EXPORT} {$modulename}" title="{$APP.LBL_EXPORT} {$modulename}"></a>
		{/if}
	</td>
</tr>
{/foreach}
</table>

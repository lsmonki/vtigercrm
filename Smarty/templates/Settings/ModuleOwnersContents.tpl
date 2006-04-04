	<form name="support_owners" method="POST" action="index.php">
	<input type="hidden" name="module" value="Settings">
	<input type="hidden" name="action" value="SettingsAjax">
	<input type="hidden" name="file" value="ListModuleOwners">
	<table class="prdTab" align="center" cellpadding="3" cellspacing="0">
	<tbody><tr><td colspan="3" style="border: 0px none ;">&nbsp;</td></tr>
	<tr>
	<td style="border: 0px none ;" colspan="2" align="left">&nbsp;</td>
	<td style="border: 0px none ;" align="right">
	<div align="right">
	{if $MODULE_MODE neq 'edit'}
	<input title="{$APP.LBL_EDIT_BUTTON_LABEL}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="classBtn" type="button" name="button" value="{$APP.LBL_EDIT_BUTTON_LABEL}" onClick="assignmodulefn('edit');">
	{else}
	<input title="{$APP.LBL_SAVE_BUTTON_LABEL}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="classBtn" type="button" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}" onClick="assignmodulefn('save');" >
	<input title="{$APP.LBL_CANCEL_BUTTON_LABEL}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="classBtn" onclick="this.form.action.value='index';" type="submit" name="button" value="{$APP.LBL_CANCEL_BUTTON_LABEL}" >
	{/if}
	</div>
	</td>
	</tr>
	<tr><td colspan="3" style="border: 0px none ;">&nbsp;</td></tr>
	
	<tr>
	<th style="border-top: 1px solid rgb(204, 204, 204); height: 30px;" width="10%"><b>#</b></th>
	<th style="border-top: 1px solid rgb(204, 204, 204);" width="45%"><b>Module</b></th>
	<th style="border-top: 1px solid rgb(204, 204, 204);" width="45%"><b>Owned By </b></th>
	</tr>
	{if $MODULE_MODE neq 'edit'}
	{foreach name=modulelists item=modules from=$USER_LIST}
	<tr class="prvPrfHoverOff" onmouseover="this.className='prvPrfHoverOn'" onmouseout="this.className='prvPrfHoverOff'">
		<td>{$smarty.foreach.modulelists.iteration}</td>
		<td>{$modules.0}</td>
		<td><a href="index.php?module=Users&action=DetailView&record={$modules.1}">{$modules.2}</a></td>	
	</tr>
	{/foreach}
	{else}
	{foreach name=modulelists item=modules from=$USER_LIST}
	<tr class="prvPrfHoverOff">
		<td>{$smarty.foreach.modulelists.iteration}</td>
		<td>{$modules.0}</td>
		<td>{$modules.1}</td>	
	</tr>
	{/foreach}
	{/if}
	</tbody></table>
	</form>

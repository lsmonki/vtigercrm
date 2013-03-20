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
{strip}
<form class="modal-backdrop" style="margin:0;" action="?module=Users&action=Login" method="POST">
	<div class="modal">
		<div class="modal-header">
			<h3>vtiger CRM <small>please enter your credentials</small></h3>
		</div>
		<div class="modal-body">
			{if isset($smarty.request.error)}
			<div class="alert alert-error">
				<p>Invalid username or password.</p>
			</div>
			{/if}
			<div class="control-group">
				<label class="control-label">Username</label>
				<div class="controls">
					<input type="text" name="username" value="" required="true" autofocus="true">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label">Password</label>
				<div class="controls">
					<input type="password" name="password" value="" required="true">
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button type="submit" class="btn btn-primary vtButton">Login</button>
		</div>
	</div>
</form>
<div class="clearfix" style="min-height:500px"></div>
{/strip}

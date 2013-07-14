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
<div id="massEditContainer" class='modelContainer'>
	<div class="modal-header backgroundImageNone" style="background-color: white;">
		<button data-dismiss="modal" class="close" title="{vtranslate('LBL_CLOSE')}">x</button>
		<h3 id="massEditHeader">
			{vtranslate('LBL_INSTALLATION', $QUALIFIED_MODULE)}
			{if $ERROR}
				<input type="hidden" name="installationStatus" value="error" />
				<span style="color:red;">{vtranslate('LBL_FAILED', $QUALIFIED_MODULE)}</span>
			{else}
				<input type="hidden" name="installationStatus" value="success" />
				<span style="color:green;">{vtranslate('LBL_SUCCESSFULL', $QUALIFIED_MODULE)}</span>
			{/if}
		</h3>
	</div>
	<div id="installationLog">
		<div class="modal-body tabbable" style="background-color: #EEF6FE;">
			<div class="row-fluid">
				<span class="font-x-x-large">{vtranslate('LBL_INSTALLATION_LOG', $QUALIFIED_MODULE)}</span>
			</div>
			<div id="extensionInstallationInfo" class="backgroundImageNone" style="background-color: white;padding: 2%;">
				{if $MODULE_ACTION eq "Upgrade"}
					{$MODULE_PACKAGE->update($TARGET_MODULE_INSTANCE, $MODULE_FILE_NAME)}
				{else}
					{$MODULE_PACKAGE->import($MODULE_FILE_NAME, 'false')}
				{/if}
				{assign var=UNLINK_RESULT value={unlink($MODULE_FILE_NAME)}}
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<span class="pull-right">
			<button class="btn btn-success" id="importCompleted" data-dismiss="modal">{vtranslate('LBL_OK', $QUALIFIED_MODULE)}</button>
		</span>
	</div>
</div>
{/strip}
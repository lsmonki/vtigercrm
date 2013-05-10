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
	<div class="container-fluid">
		{if !($ERROR)}
			<input type="hidden" name="extensionId" value="{$EXTENSION_ID}" />
			<input type="hidden" name="targetModule" value="{$MODULE_NAME}" />
			<input type="hidden" name="moduleType" value="{$MODULE_TYPE}" />
			<input type="hidden" name="moduleAction" value="{$MODULE_ACTION}" />
			<input type="hidden" name="fileName" value="{$FILE_NAME}" />
			<div class="row-fluid">
				<span class="font-x-x-large">{$MODULE_NAME}</span>
			</div>
			<div class="extensionInfo padding10">
				<div class="row-fluid">
					<span class="span2">{vtranslate('LBL_VERSION', $QUALIFIED_MODULE)}</span>
					{$SUPPORTED_VTVERSION}
				</div>
				<div class="license-info pushDown2per">
					<div class="row-fluid">
						<span class="span2"><b>{vtranslate('LBL_LICENSE', $QUALIFIED_MODULE)}</b></span>
					</div>
					<div class="row-fluid pushDown2per" id="extensionLicense">
						<pre>
							{if !empty($MODULE_LICENSE)}
								{$MODULE_LICENSE}
							{else}
								{vtranslate('LBL_NO_LICENSE_PROVIDED', $QUALIFIED_MODULE)}
							{/if}
						</pre>
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<span class="pull-right">
					<span class="span1"><button class="btn btn-danger" id="declineExtension">{vtranslate('LBL_DECLINE', $QUALIFIED_MODULE)}</button></span>
					<span class="span2"><button class="btn btn-success" id="installExtension">{vtranslate('LBL_ACCEPT_AND_INSTALL', $QUALIFIED_MODULE)}</button></span>
				</span>
			</div>
		{else}
			<div class="row-fluid">{$ERROR_MESSAGE}</div>
		{/if}
	</div>
{/strip}
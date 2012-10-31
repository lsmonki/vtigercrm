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
<div id="popupPageContainer">
	<div class="emailTemplatesContainer">
		<h3>{vtranslate($QUALIFIED_MODULE,QUALIFIED_MODULE)}</h3>
		<hr>
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<tr class="listViewHeaders">
					<td>
						{vtranslate('LBL_TEMPLATE_NAME',$QUALIFIED_MODULE)}
					</td>
					<td>
						{vtranslate('LBL_DESCRIPTION',$QUALIFIED_MODULE)}
					</td>
				</tr>
			</thead>
			{foreach item=EMAIL_TEMPLATE from=$EMAIL_TEMPLATES}
			<tr class="listViewEntries" data-id="{$EMAIL_TEMPLATE->get('templateid')}" data-name="{$EMAIL_TEMPLATE->get('subject')}" data-info="{$EMAIL_TEMPLATE->get('body')}">
				<td><a class="cursorPointer">{vtranslate($EMAIL_TEMPLATE->get('subject',$QUALIFIED_MODULE))}</a></td>
				<td>{vtranslate($EMAIL_TEMPLATE->get('description',$QUALIFIED_MODULE))}</td>
			</tr>
			{/foreach}
		</table>
	</div>
		<input type="hidden" class="triggerEventName" value="{$smarty.request.triggerEventName}"/>
</div>
{/strip}
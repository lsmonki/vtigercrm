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
	<span class="recordLabel lead">{$RECORD->getName()}</span>
	<div>
		<label>
		{assign var=RELATED_TO value=$RECORD->get('related_to')}
		{if !empty($RELATED_TO)}
			<div>
				<span class="muted">{vtranslate('Related to',$MODULE_NAME)} </span>
				{$RECORD->getDisplayValue('related_to')}
			</div>
		{/if}
		<label>
	</div>
{/strip}
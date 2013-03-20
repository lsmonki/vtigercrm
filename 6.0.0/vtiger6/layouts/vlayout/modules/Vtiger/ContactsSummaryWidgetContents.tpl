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
	{foreach item=RELATED_RECORD from=$RELATED_RECORDS}
		<div class="recentActivitiesContainer">
			<ul class="unstyled">
				<li>
					<div class="row-fluid">
						<div class="textOverflowEllipsis">
							<a href="{$RELATED_RECORD->getDetailViewUrl()}" id="{$MODULE}_{$RELATED_MODULE}_Related_Record_{$RELATED_RECORD->get('id')}" title="{$RELATED_RECORD->getDisplayValue('lastname')}">
								{$RELATED_RECORD->getDisplayValue('lastname')}
							</a>
						</div>
						<div>{$RELATED_RECORD->getDisplayValue('email')}</div>
						<div class="textOverflowEllipsis" title="{$RELATED_RECORD->getDisplayValue('phone')}">{$RELATED_RECORD->getDisplayValue('phone')}</div>
					</div>
				</li>
			</ul>
		</div>
	{/foreach}
{/strip}
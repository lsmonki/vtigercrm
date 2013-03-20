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
					<div>
						<div class="textOverflowEllipsis width27em">
							<a href="{$RELATED_RECORD->getDetailViewUrl()}" title="{$RELATED_RECORD->getDisplayValue('ticket_title')}">
								{$RELATED_RECORD->getDisplayValue('ticket_title')}
							</a>
						</div>
						<div>{vtranslate('LBL_TICKET_PRIORITY',$MODULE)} : <strong>{$RELATED_RECORD->getDisplayValue('ticketpriorities')}</strong></div>
						{assign var=DESCRIPTION value="{$RELATED_RECORD->getDescriptionValue()}"}
						{if !empty($DESCRIPTION)}
							<div class="row-fluid">
								<span class="span8 textOverflowEllipsis width27em">{$DESCRIPTION}</span>
								<span class="span3"><a href="{$RELATED_RECORD->getDetailViewUrl()}">{vtranslate('LBL_MORE',$MODULE)}</a></span>
							</div>
						{/if}
					</div>
				</li>
			</ul>
		</div>
	{/foreach}
{/strip}
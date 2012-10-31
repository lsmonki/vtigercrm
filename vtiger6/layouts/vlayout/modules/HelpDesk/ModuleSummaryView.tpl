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
<div class="row-fluid">
	<span class="span6">
		
		{assign var=TICKET_STATUS value=$RECORD->getDisplayValue('ticketstatus')}
		{assign var=RELATED_TO value=$RECORD->getDisplayValue('parent_id')}
		{assign var=SEVERITY value=$RECORD->getDisplayValue('ticketseverities')}
		{if empty($TICKET_STATUS) && empty($RELATED_TO) && empty($SEVERITY)}
			&nbsp;
		{else}
			{if !empty($TICKET_STATUS)}
				<div>
					<span class="muted">{vtranslate('LBL_STATUS',$MODULE_NAME)}</span>
					<strong>
						&nbsp;:&nbsp;{$TICKET_STATUS}
					</strong>

				</div>
			{/if}
			{if $RELATED_TO}
				<div>
					<span class="muted">
						{vtranslate('LBL_RELATED_TO',$MODULE_NAME)}&nbsp;
					</span>
					<strong>
						&nbsp;:&nbsp;{$RELATED_TO}
					</strong>
				</div>
			{/if}
			{if !empty($SEVERITY)}
				<div>
					<span class="muted">{vtranslate('LBL_SEVERITY',$MODULE_NAME)}</span>
					<strong>&nbsp;:&nbsp;{$SEVERITY}</strong>
				</div>
			{/if}
		{/if}	
	</span>
	<span class="span6">
		<p class="clearfix pull-right">
			<strong>{vtranslate('LBL_OWNER',$MODULE_NAME)} : </strong>
			{$RECORD->getDisplayValue('assigned_user_id')}
		</p>

		<p class="clearfix pull-right">
			<small>
				<em>{vtranslate('LBL_CREATED_ON',$MODULE_NAME)} {$RECORD->get('createdtime')}</em>
			</small>
		</p>
		<p class="clearfix pull-right">
			<small>
				<em>{vtranslate('LBL_MODIFIED_ON',$MODULE_NAME)} {$RECORD->get('modifiedtime')}</em>
			</small>
		</p>
	</span>
</div>
{/strip}
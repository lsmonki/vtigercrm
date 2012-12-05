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
		{assign var=AMOUNT value=$RECORD->getDisplayValue('amount')}
		{assign var=SALES_STAGE value=$RECORD->getDisplayValue('sales_stage')}
		{assign var=EXPECTED_CLOSE_DATE value=$RECORD->getDisplayValue('closingdate')}
		{if empty($AMOUNT) && empty($SALES_STAGE) && empty($EXPECTED_CLOSE_DATE)}
			&nbsp;
		{else}
			{if $AMOUNT && $RECORD->getField('amount')->isViewableInDetailView()}
				<div>
					<strong>
						{$USER_MODEL->get('currency_symbol')}
						{$AMOUNT}
					</strong>
				</div>
			{/if}
			{if $SALES_STAGE && $RECORD->getField('sales_stage')->isViewableInDetailView()}
				<div>
					<strong>
						{$SALES_STAGE}
					</strong>
				</div>
			{/if}
			{if $EXPECTED_CLOSE_DATE && $RECORD->getField('closingdate')->isViewableInDetailView()}
				<div class="muted">
					{vtranslate('LBL_EXPECTED_CLOSE_DATE_ON',$MODULE_NAME)}&nbsp;
					{$EXPECTED_CLOSE_DATE}
				</div>
			{/if}
		{/if}
	</span>
	<span class="span6">
		<p class="clearfix pull-right">
			<strong>{vtranslate('LBL_OWNER',$MODULE_NAME)} : </strong>
			{getOwnerName($RECORD->get('assigned_user_id'))}
		</p>

		<p class="clearfix pull-right">
			<small>
				<em>{vtranslate('LBL_CREATED_ON',$MODULE_NAME)} {$RECORD->getDisplayValue('createdtime')}</em>
			</small>
		</p>
		<p class="clearfix pull-right">
			<small>
				<em>{vtranslate('LBL_MODIFIED_ON',$MODULE_NAME)} {$RECORD->getDisplayValue('modifiedtime')}</em>
			</small>
		</p>
	</span>
</div>
{/strip}
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
		
		{assign var=AMOUNT value=$RECORD->get('amount')}
		{assign var=SALES_STAGE value=$RECORD->get('sales_stage')}
		{assign var=EXPECTED_CLOSE_DATE value=$RECORD->get('closingdate')}
		{if empty($AMOUNT) && empty($SALES_STAGE) && empty($EXPECTED_CLOSE_DATE)}
			&nbsp;
		{else}	
			{if !empty($AMOUNT)}
				<div>
					<strong>
						{$USER_MODEL->get('currency_symbol')}
						{$RECORD->getDisplayValue('amount')}
					</strong>
				</div>
			{/if}
			{if $SALES_STAGE}
				<div>
					<strong>
						{$SALES_STAGE}
					</strong>
				</div>
			{/if}
			{if !empty($EXPECTED_CLOSE_DATE)}
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
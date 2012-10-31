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
		{assign var=EMAIL value= $RECORD->get('email')}
		{assign var=PHONE value= $RECORD->get('phone')}
		{assign var=BILLING_CITY value=$RECORD->get('city')}
		{assign var=BILLING_COUNTRY value=$RECORD->get('country')}
		{if empty($EMAIL) && empty($PHONE) && empty($BILLING_CITY) && empty($BILLING_COUNTRY)}
			&nbsp;
		{else}	
			{if !empty($EMAIL)}	
				<div>
					<i class="icon-envelope alignMiddle"></i>&nbsp;&nbsp;
					{* TODO : Introduce the feature where clicking on email should open the compose email *}
					<a href="javascript:void(0)">
						{$EMAIL}
					</a>
				</div>
			{/if}
			{if !empty($PHONE)}
				<img src="{vimage_path('phone.png')}" alt="{vtranslate('LBL_PHONE',$MODULE)}" title="{vtranslate('LBL_PHONE',$MODULE)}" />&nbsp;&nbsp;
				<a href="javascript:void(0)">
					{$RECORD->get('phone')}
				</a>
			{/if}
			{if !empty($BILLING_CITY) || !empty($BILLING_COUNTRY)}
				<div class="row-fluid">
				<i class="icon-map-marker alignMiddle pull-left span"></i>&nbsp;
					<address class="span10 pull-left">
						{$BILLING_CITY}
						{if !empty($BILLING_CITY) && !empty($BILLING_COUNTRY)}
							,
						{/if}
						{$BILLING_COUNTRY}<br>
					</address>
				</div>
			{/if}
		{/if}
	</span>
	<span class="span6">
		<p class="pull-right">
			<strong>{vtranslate('LBL_OWNER',$MODULE_NAME)} : </strong>
			{$RECORD->getDisplayValue('assigned_user_id')}
		</p>
		<p class="clearfix pull-right">
			{if $RECORD->getDisplayValue('leadsource')}
			<strong>{vtranslate('LBL_LEAD_SOURCE',$MODULE_NAME)} : </strong>
			{$RECORD->getDisplayValue('leadsource')}
			{/if}
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
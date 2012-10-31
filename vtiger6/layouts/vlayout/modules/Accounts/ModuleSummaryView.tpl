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
		{assign var=WEBSITE value=$RECORD->get('website')}
		{assign var=EMAIL value= $RECORD->get('email1')}
		{assign var=PHONE value= $RECORD->get('phone')}
		{assign var=BILLING_CITY value=$RECORD->get('bill_city')}
		{assign var=BILLING_COUNTRY value=$RECORD->get('bill_country')}
		{if empty($WEBSITE) && empty($EMAIL) && empty($PHONE) && empty($BILLING_CITY) && empty($BILLING_COUNTRY)}
			&nbsp;
		{else}	
			{if $WEBSITE}
				<div>
					<a href="{$RECORD->get('website')}">
						{$RECORD->get('website')}
					</a>
				</div>
			{/if}	
			<div>
				{if !empty($EMAIL)}
					{* TODO : Introduce the feature where clicking on email should open the compose email *}
					<i class="icon-envelope alignMiddle"></i>&nbsp;&nbsp;
					<a href="javascript:void(0)">
						{$EMAIL}
					</a>
				{/if}
			</div>
			{if !empty($PHONE)}
				<p>
					<img src="{vimage_path('phone.png')}" alt="{vtranslate('LBL_PHONE',$MODULE)}" title="{vtranslate('LBL_PHONE',$MODULE)}" />&nbsp;&nbsp;
					<small>
						<a href="javascript:void(0)">
							{$PHONE}
						</a>
					</small>
				</p>
			{/if}
			{if !empty($BILLING_CITY) || !empty($BILLING_COUNTRY)}
				<div class="row-fluid">
					<i class="icon-map-marker alignMiddle pull-left span"></i>&nbsp;
					<address class="pull-left span10">
						{$BILLING_CITY}
						{if !empty($BILLING_CITY) && !empty($BILLING_COUNTRY)}
							,
						{/if}
						{$BILLING_COUNTRY}
					</address>
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
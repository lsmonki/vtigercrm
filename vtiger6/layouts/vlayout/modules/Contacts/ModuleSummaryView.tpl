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
		<div class="row-fluid">
			{foreach key=ITER item=IMAGE_INFO from=$RECORD->getImageDetails()} 
				{if !empty($IMAGE_INFO.path)}
					<img src="../{$IMAGE_INFO.path}_{$IMAGE_INFO.orgname}" alt="{$IMAGE_INFO.orgname}" title="{$IMAGE_INFO.orgname}" width="150" height="80" align="left"><br> 
				{/if}
			{/foreach}
			<div class="span7">
				{assign var=EMAIL value= $RECORD->get('email')}
				{assign var=PHONE value= $RECORD->get('phone')}
				{assign var=BILLING_CITY value=$RECORD->get('mailingcity')}
				{assign var=BILLING_COUNTRY value=$RECORD->get('mailingcountry')}
				{if empty($EMAIL) && empty($PHONE) && empty($BILLING_CITY) && empty($BILLING_COUNTRY)}
					&nbsp;
				{else}
					{if !empty($EMAIL)}
						<div>
							{* TODO : Introduce the feature where clicking on email should open the compose email *}
							<i class="icon-envelope alignMiddle"></i>&nbsp;&nbsp;
							<a href="javascript:void(0)">
								{$RECORD->get('email')}
							</a>
						</div>
					{/if}
					{if !empty($PHONE)}
						<div>
							<img src="{vimage_path('phone.png')}" alt="{vtranslate('LBL_PHONE',$MODULE)}" title="{vtranslate('LBL_PHONE',$MODULE)}" />&nbsp;&nbsp;
							<a href="javascript:void(0)">
								{$PHONE}
							</a>
						</div>
					{/if}
					{if !empty($BILLING_CITY) || !empty($BILLING_COUNTRY)}
						<div class="row-fluid">
							<i class="icon-map-marker alignMiddle pull-left span"></i>&nbsp;
							<address class="pull-left span10">
								{$BILLING_CITY}
								{if !empty($BILLING_COUNTRY)}
									,
								{/if}
								{$BILLING_COUNTRY}
							</address>
						</div>
					{/if}
				{/if}	
			</div>
		</div>
	</span>
	<span class="span6">
		<p class="pull-right">
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
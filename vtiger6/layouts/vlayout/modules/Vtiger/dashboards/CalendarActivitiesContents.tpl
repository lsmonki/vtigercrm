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

<div style='padding:5px'>
	{foreach from=$ACTIVITIES key=INDEX item=ACTIVITY}
	<div>
		<div class='pull-left'>
			{if $ACTIVITY->get('activitytype') == 'Task'}
				<image src="{vimage_path('tasks.png')}" width="24px"/>&nbsp;&nbsp;
			{else}
				<image src="{vimage_path('calendar.png')}" width="24px" />&nbsp;&nbsp;
			{/if}
		</div>
		<div>
			<div class='pull-left' style='margin-top:5px'>
				{assign var=PARENT_ID value=$ACTIVITY->get('parent_id')}
				<a href="{$ACTIVITY->getDetailViewUrl()}">{$ACTIVITY->get('subject')}</a>{if $PARENT_ID} {vtranslate('LBL_FOR')} {$ACTIVITY->getDisplayValue('parent_id')}{/if}
			</div>
				{assign var=DUE_DATE value=$ACTIVITY->get('due_date')}
				{assign var=DUE_TIME value=$ACTIVITY->get('time_end')}
			<p class='pull-right muted' style='margin-top:5px;padding-right:5px;'><small>{Vtiger_Util_Helper::formatDateDiffInStrings("$DUE_DATE $DUE_TIME")}</small></p>
			<div class='clearfix'></div>
		</div>
		<div class='clearfix'></div>
	</div>
	{foreachelse}
		<span class="noDataMsg">
			{if $smarty.request.name eq 'OverdueActivities'}
				{vtranslate('LBL_NO_OVERDUE_ACTIVITIES', $MODULE_NAME)}
			{else}
				{vtranslate('LBL_NO_SCHEDULED_ACTIVITIES', $MODULE_NAME)}
			{/if}
		</span>
	{/foreach}
</div>
{if $ACTIVITIES|@count eq 10}
	<div><a href="#" class="pull-right" name="history_more" data-url="{$WIDGET->getUrl()}&page={$PAGING->getNextPage()}">{vtranslate('LBL_MORE')}...</a></div>
{/if}
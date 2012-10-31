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
<div class="recentActivitiesContainer">
	<div>
		{if !empty($RECENT_ACTIVITIES)}
			<ul class="unstyled">
				{foreach item=RECENT_ACTIVITY from=$RECENT_ACTIVITIES}
					{if $RECENT_ACTIVITY->isCreate()}
						<li>{$RECENT_ACTIVITY->getModifiedBy()->getName()} {vtranslate('LBL_CREATED', $MODULE_NAME)} {vtranslate('LBL_ON', $MODULE_NAME)} {$RECENT_ACTIVITY->getParent()->get('createdtime')}
						</li>
					{else if $RECENT_ACTIVITY->isUpdate()}
						<li>
							{$RECENT_ACTIVITY->getModifiedBy()->getDisplayName()} {vtranslate('LBL_UPDATED', $MODULE_NAME)} {vtranslate('LBL_ON', $MODULE_NAME)} {$RECENT_ACTIVITY->getActivityTime()}
							
							{foreach item=FIELDMODEL from=$RECENT_ACTIVITY->getFieldInstances()}
								<div class='font-x-small'>
									<i>{$FIELDMODEL->getName()}</i>:&nbsp;
									{if $FIELDMODEL->get('prevalue') neq ''}
										{$FIELDMODEL->getDisplayValue($FIELDMODEL->get('prevalue'))}&nbsp;
									{else}
										{* First time change *}
									{/if}
									&rightarrow; <b>{$FIELDMODEL->getDisplayValue($FIELDMODEL->get('postvalue'))}</b>
								</div>
							{/foreach}
							
						</li>
					{else if $RECENT_ACTIVITY->isRelationLink()}
						<li>
							{assign var=RELATION value=$RECENT_ACTIVITY->getRelationInstance()}
							{$RELATION->getLinkedRecord()->getModuleName()} {vtranslate('LBL_ADDED', $MODULE_NAME)} {$RELATION->getLinkedRecord()->getName()}
						</li>
					{else if $RECENT_ACTIVITY->isRelationUnLink()}
						<li>
							{assign var=URELATION value=$RECENT_ACTIVITY->getRelationInstance()}
							{$URELATION->getUnLinkedRecord()->getModuleName()} {vtranslate('LBL_REMOVED', $MODULE_NAME)} {$URELATION->getUnLinkedRecord()->getName()}
						</li>
					{else if $RECENT_ACTIVITY->isRestore()}
						<li>

						</li>
					{/if}
				{/foreach}
			</ul>
			{else}
				<div class="well">
					<p class="textAlignCenter">{vtranslate('LBL_NO_RECENT_UPDATES')}</p>
				</div>
		{/if}
	</div>
	{if $PAGING_MODEL->isNextPageExists()}
		<div class="row-fluid">
			<div class="pull-right">
				<a href="javascript:void(0)" class="moreRecentActivities">{vtranslate('LBL_MORE',$MODULE_NAME)}..</a>
			</div>
		</div>
	{/if}
	<span class="clearfix"></span>
</div>
{/strip}
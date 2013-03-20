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

<div style='padding:5px;'>
{if $HISTORIES neq false}
	{foreach key=$index item=HISTORY from=$HISTORIES}
		{assign var=MODELNAME value=get_class($HISTORY)}
		{if $MODELNAME == 'ModTracker_Record_Model'}
			{assign var=USER value=$HISTORY->getModifiedBy()}
			{assign var=TIME value=$HISTORY->getActivityTime()}
			{assign var=PARENT value=$HISTORY->getParent()}
			{assign var=MOD_NAME value=$HISTORY->getParent()->getModule()->getName()}
			{assign var=SINGLE_MODULE_NAME value='SINGLE_'|cat:$MOD_NAME}
			{assign var=TRANSLATED_MODULE_NAME value = vtranslate($SINGLE_MODULE_NAME ,$MOD_NAME)}
			<div class="row-fluid">
				<div class='span1'>
					<img width='24px' src="{vimage_path($MOD_NAME|cat:'.png')}" alt="{$TRANSLATED_MODULE_NAME}" title="{$TRANSLATED_MODULE_NAME}" />&nbsp;&nbsp;
				</div>
				<div class="span11">
				<p class="pull-right muted" style="padding-right:5px;"><small title="{Vtiger_Util_Helper::formatDateTimeIntoDayString("$TIME")}">{Vtiger_Util_Helper::formatDateDiffInStrings("$TIME")}</small></p>
				{if $HISTORY->isUpdate()}
					{assign var=FIELDS value=$HISTORY->getFieldInstances()}
					<div class="">
						<div><b>{$USER->getName()}</b> {vtranslate('LBL_UPDATED')} <a href="{$PARENT->getDetailViewUrl()}">{$PARENT->getName()}</a>
						</div>
						{foreach from=$FIELDS key=INDEX item=FIELD}
						{if $INDEX lt 2}
						<div class='font-x-small'>
							<i>{$FIELD->getName()}</i>
							{if $FIELD->get('prevalue') neq ''}
								{vtranslate('LBL_FROM')} <b>{Vtiger_Util_Helper::toSafeHTML($FIELD->getDisplayValue(decode_html($FIELD->get('prevalue'))))}</b>
							{else}
								{vtranslate('LBL_CHANGED')}
							{/if}
								{vtranslate('LBL_TO')} <b>{Vtiger_Util_Helper::toSafeHTML($FIELD->getDisplayValue(decode_html($FIELD->get('postvalue'))))}</b>
						</div>
						{else}
							<a href="{$PARENT->getUpdatesUrl()}">{vtranslate('LBL_MORE')}</a>
							{break}
						{/if}
						{/foreach}
					</div>
				{else if $HISTORY->isCreate()}
					<div class=''  style='margin-top:5px'>
						<b>{$USER->getName()}</b> {vtranslate('LBL_ADDED')} <a href="{$HISTORY->getParent()->getDetailViewUrl()}">{$HISTORY->getParent()->getName()}</a>
					</div>
				{else if $HISTORY->isRelationLink()}
					{assign var=RELATION value=$HISTORY->getRelationInstance()}
					<div class='' style='margin-top:5px'>
						<b>{$USER->getName()}</b> {vtranslate('LBL_ADDED')} <a href="{$RELATION->getLinkedRecord()->getDetailViewUrl()}">{$RELATION->getLinkedRecord()->getName()}</a>
						{vtranslate('LBL_FOR')} <a href="{$RELATION->getParent()->getParent()->getDetailViewUrl()}}">{$RELATION->getParent()->getParent()->getName()}</a>
					</div>
				{else if $HISTORY->isRelationUnLink()}
					{assign var=RELATION value=$HISTORY->getRelationInstance()}
					<div class='' style='margin-top:5px'>
						<b>{$USER->getName()}</b> {vtranslate('LBL_REMOVED')} <a href="{$RELATION->getLinkedRecord()->getDetailViewUrl()}">{$RELATION->getLinkedRecord()->getName()}</a>
						{vtranslate('LBL_FOR')} <a href="{$RELATION->getParent()->getParent()->getDetailViewUrl()}">{$RELATION->getParent()->getParent()->getName()}</a>
					</div>
				{/if}
				</div>
			</div>
			{else if $MODELNAME == 'ModComments_Record_Model'}
			<div class="row-fluid">
				<div class="span1">
					<image width='24px' src="{vimage_path('Comments.png')}"/>&nbsp;&nbsp;
				</div>
				<div class="span11">
					{assign var=COMMENT_TIME value=$HISTORY->getCommentedTime()}
					<p class="pull-right muted" style="padding-right:5px;"><small title="{Vtiger_Util_Helper::formatDateTimeIntoDayString("$COMMENT_TIME")}">{Vtiger_Util_Helper::formatDateDiffInStrings("$COMMENT_TIME")}</small></p>
					<div>
						<b>{$HISTORY->getCommentedByModel()->getName()}</b> {vtranslate('LBL_COMMENTED')} {vtranslate('LBL_ON')} <a class="textOverflowEllipsis" href="{$HISTORY->getParentRecordModel()->getDetailViewUrl()}">{$HISTORY->getParentRecordModel()->getName()}</a>
					</div>
					<div class='font-x-small'><i>"{$HISTORY->get('commentcontent')}"</i></div>
				</div>
			</div>
		{/if}
	{/foreach}
{else}
	<span class="noDataMsg">
		{vtranslate('LBL_NO_UPDATES_OR_COMMENTS', $MODULE_NAME)}
	</span>
{/if}
</div>

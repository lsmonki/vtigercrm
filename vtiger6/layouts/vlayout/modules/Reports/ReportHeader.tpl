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
	<div class="reportsDetailHeader row-fluid">
		<div class="reportHeader row-fluid span12">
			<div class='span4' style="position:relative;left:10px">
				{if $REPORT_MODEL->isEditable() eq true}
					<button onclick='window.location.href="{$REPORT_MODEL->getEditViewUrl()}"' type="button" class="cursorPointer btn"><strong>{vtranslate('LBL_CUSTOMIZE',$MODULE)}</strong>&nbsp;<i class="icon-pencil"></i></button>
				{else}
					&nbsp;
				{/if}
			</div>
			<div class='span4'><h3>{$REPORT_MODEL->getName()}</h3></div>
			<div class='span4'>
				<span class="pull-right">
					{foreach item=DETAILVIEW_LINK from=$DETAILVIEW_LINKS}
						<img class="cursorPointer alignBottom" onclick='window.location.href="{$DETAILVIEW_LINK->getUrl()}"' src="{vimage_path({$DETAILVIEW_LINK->get('linkicon')})}" alt="{vtranslate($DETAILVIEW_LINK->getLabel(), $MODULE)}" title="{vtranslate($DETAILVIEW_LINK->getLabel(), $MODULE)}" />&nbsp;
					{/foreach}
				</span>
			</div>
		</div>
		<div class="well contentsBackground span11">
			<input type="hidden" id="recordId" value="{$RECORD_ID}" />
 			{assign var=RECORD_STRUCTURE value=array()}
			{assign var=PRIMARY_MODULE_LABEL value=vtranslate($PRIMARY_MODULE, $PRIMARY_MODULE)}
			{foreach key=BLOCK_LABEL item=BLOCK_FIELDS from=$PRIMARY_MODULE_RECORD_STRUCTURE->getStructure()}
				{assign var=PRIMARY_MODULE_BLOCK_LABEL value=vtranslate($BLOCK_LABEL, $PRIMARY_MODULE)}
				{assign var=key value="$PRIMARY_MODULE_LABEL $PRIMARY_MODULE_BLOCK_LABEL"}
				{$RECORD_STRUCTURE[$key] = $BLOCK_FIELDS}
			{/foreach}
			{foreach key=MODULE_LABEL item=SECONDARY_MODULE_RECORD_STRUCTURE from=$SECONDARY_MODULE_RECORD_STRUCTURES}
				{assign var=SECONDARY_MODULE_LABEL value=vtranslate($MODULE_LABEL, $MODULE_LABEL)}
				{foreach key=BLOCK_LABEL item=BLOCK_FIELDS from=$SECONDARY_MODULE_RECORD_STRUCTURE->getStructure()}
					{assign var=SECONDARY_MODULE_BLOCK_LABEL value=vtranslate($BLOCK_LABEL, $MODULE_LABEL)}
					{assign var=key value="$SECONDARY_MODULE_LABEL $SECONDARY_MODULE_BLOCK_LABEL"}
					{$RECORD_STRUCTURE[$key] = $BLOCK_FIELDS}
				{/foreach}
			{/foreach}
			{include file='AdvanceFilter.tpl'|@vtemplate_path RECORD_STRUCTURE=$RECORD_STRUCTURE ADVANCE_CRITERIA=$SELECTED_ADVANCED_FILTER_FIELDS COLUMNNAME_API=getReportFilterColumnName}
			<div class="row">
				<div class="span4 offset4">
					<input type="button" class="btn generateReport" data-mode="generate" value="{vtranslate('LBL_GENERATE_NOW',$MODULE)}"/>&nbsp;
					<input type="button" class="btn btn-success generateReport" data-mode="save" value="{vtranslate('LBL_SAVE',$MODULE)}" />
				</div>
			</div>
		</div>
	</div>				
{/strip}
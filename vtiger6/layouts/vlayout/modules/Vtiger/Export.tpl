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
<div class="contentsDiv span10 marginLeftZero">
	<form id="exportForm" class="form-horizontal row-fluid" method="post" action="index.php">
		<input type="hidden" name="module" value="{$MODULE}" />
		<input type="hidden" name="source_module" value="{$SOURCE_MODULE}" />
		<input type="hidden" name="action" value="ExportData" />
		<input type="hidden" name="viewname" value="{$VIEWID}" />
		<input type="hidden" name="selected_ids" value={ZEND_JSON::encode($SELECTED_IDS)}>
		<input type="hidden" name="excluded_ids" value={ZEND_JSON::encode($EXCLUDED_IDS)}>
		<input type="hidden" id="page" name="page" value="{$PAGE}" />
		<div class="row-fluid">
			<div class="span">&nbsp;</div>
			<div class="span8">
				<h4>{vtranslate('LBL_EXPORT_RECORDS',$MODULE)}</h4>
				<div class="well exportContents marginLeftZero">
					<div class="row-fluid">
						<div class="row-fluid" style="height:30px">
							<div class="span6 textAlignRight row-fluid">
								<div class="span8">{vtranslate('LBL_EXPORT_SELECTED_RECORDS',$MODULE)}&nbsp;</div>
								<div class="span3"><input type="radio" name="mode" value="ExportSelectedRecords" {if !empty($SELECTED_IDS)} checked="checked" {else} disabled="disabled"{/if}/></div>
							</div>
							{if empty($SELECTED_IDS)}&nbsp; <span class="redColor">{vtranslate('LBL_NO_RECORD_SELECTED',$MODULE)}</span>{/if}
						</div>
						<div class="row-fluid" style="height:30px">
							<div class="span6 textAlignRight row-fluid">
								<div class="span8">{vtranslate('LBL_EXPORT_DATA_IN_CURRENT_PAGE',$MODULE)}&nbsp;</div>
								<div class="span3"><input type="radio" name="mode" value="ExportCurrentPage" /></div>
							</div>
						</div>
						<div class="row-fluid" style="height:30px">
							<div class="span6 textAlignRight row-fluid">
								<div class="span8">{vtranslate('LBL_EXPORT_ALL_DATA',$MODULE)}&nbsp;</div>
								<div class="span3"><input type="radio"  name="mode" value="ExportAllData"  {if empty($SELECTED_IDS)} checked="checked" {/if} /></div>
							</div>
						</div>
					</div>
					<div class="row-fluid">
						<span class="span7">&nbsp;</span>
						<span class="span5">
							<button class="btn btn-success" type="submit"><strong>{vtranslate($MODULE, $MODULE)}&nbsp;{vtranslate($SOURCE_MODULE, $MODULE)}</strong></button>
							<a class="cancelLink" type="reset" onclick='window.history.back()'>{vtranslate('LBL_CANCEL', $MODULE)}</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
{/strip}

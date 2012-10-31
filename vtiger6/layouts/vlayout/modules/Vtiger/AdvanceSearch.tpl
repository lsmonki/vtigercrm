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
<div id="advanceSearchContainer">
	<div class="row-fluid padding1per">
		<div class="span"> &nbsp; </div>
		<div class="span11">
			<div class="row-fluid">
				<span class="span">
					<label class="highLight pushDown"><strong>{vtranslate('LBL_SEARCH_IN',$MODULE)}</strong></label>
				</span>
				<span class="span">
					<select class="chzn-select" id="searchModuleList">
						<option value="">{vtranslate('LBL_SELECT_MODULE')}</option>
						{foreach key=MODULE_NAME item=fieldObject from=$SEARCHABLE_MODULES}
							<option value="{$MODULE_NAME}" {if $MODULE_NAME eq $SOURCE_MODULE}selected="selected"{/if}>{vtranslate($MODULE_NAME,$MODULE_NAME)}</option>
						{/foreach}
					</select>
				</span>
			</div>
			<div class="filterElements">
				<form name="advanceFilterForm">
					{include file='AdvanceFilter.tpl'|@vtemplate_path}
				</form>
				<div class="row-fluid actions">
					<!-- TODO: should be done in better way to show right elements -->
					<div class="span5">
						<div class="row-fluid">
							<span class="span4">&nbsp;</span>
							<span class="span7">
								<input class="zeroOpacity row-fluid" type="text" value="" name="viewname"/>
							</span>
						</div>
					</div>
					<div class="span7">
						<span class="btn-toolbar">
							<span class="btn-group">
							</span>
							<span class="btn-group  pull-right pushDown">
								<a class="cancelLink" type="reset" id="advanceSearchCancel" data-dismiss="modal">{vtranslate('LBL_CANCEL', $MODULE)}</a>
							</span>
							<span class="btn-group pull-right">
								<button class="btn" id="advanceSearchButton" type="submit"><strong>{vtranslate('LBL_SEARCH', $MODULE)}</strong></button>
							</span>
							<span class="btn-group pull-right ">
								<button class="btn hide" id="advanceSave"><strong>{vtranslate('LBL_SAVE_FILTER', $MODULE)}</strong></button>
							</span>
							<span class="btn-group pull-right">
								<button class="btn" id="advanceIntiateSave"><strong>{vtranslate('LBL_SAVE_AS_FILTER', $MODULE)}</strong></button>
							</span>

						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
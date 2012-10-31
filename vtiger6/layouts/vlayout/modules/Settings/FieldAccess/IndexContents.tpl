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
	<div class="well contentsBackground">
		<div class="row-fluid">
			<span class="span5">
				<select class="chzn-select" id="modulesList">
					{foreach item=MODULE_MODEL key=MODULE_ID from=$ALL_MODULES}
						<option value="{$MODULE_MODEL->get('name')}" {if ($SELECTED_MODULE->getId()) == $MODULE_ID}selected{/if} data-url={$MODULE_MODEL->getFieldPermissionsUrl()} >
							{vtranslate($MODULE_MODEL->get('label'), $MODULE_MODEL->get('name'))}
						</option>
					{/foreach}
				</select>
			</span>
			<span class="pull-right">
				<span class="btn-group"> <button class="btn-small vtButton edit">{vtranslate('LBL_EDIT', $QUALIFIED_MODULE)}</button> </span>
				<span class="formActions hide span3">
				<span><a class="cancel cancelLink pull-right">{vtranslate('LBL_CANCEL', $QUALIFIED_MODULE)}</a>
					  <button class="btn-small vtButton save pull-right saveButton" data-url="{$SELECTED_MODULE->getSaveFieldPermissionsUrl()}">{vtranslate('LBL_SAVE', $QUALIFIED_MODULE)}</button></span>
				</span>
			</span>
		</div>
		{assign var=FIELD_ROWS value=4}
		{assign var=FIELD_COUNTER value=0}
		{assign var=FIELDS value=$SELECTED_MODULE->getFields() }
		<input type="hidden" id="loadUrl" data-url={$SELECTED_MODULE->getFieldPermissionsUrl()} />
		<form class="fieldAccessContents">
			<div class="well">
				<div class="row-fluid padding-bottom1per">
					{foreach item=FIELD key=FIELD_NAME from=$FIELDS name=FIELD_COUNTER}
						<div class="span3 row-fluid fieldContainer">
							<span class="span2 edit hide">
								<input type="hidden" name="field_permissions[{$FIELD->getId()}]" value="{if $FIELD->isReadOnly()}1{else}0{/if}" />
								<input type="checkbox" {if $FIELD->isEnabled()}checked="checked"{/if} name="field_permissions[{$FIELD->getId()}]" value="1"
										{if $FIELD->isReadOnly()}disabled{/if}/>
							</span>
							<span class="span2 detail">
								{if $FIELD->isEnabled()}
									<i class="icon-ok"></i>
								{else}
									<i class="icon-remove"></i>
								{/if}
							</span>
							<span class="span8">
								{vtranslate($FIELD->get('label'),$SELECTED_MODULE->get('name'))}
							</span>
						</div>
						{if ($smarty.foreach.FIELD_COUNTER.index+1) % $FIELD_ROWS == 0}
						</div><div class="row-fluid padding-bottom1per">
						{/if}
					{/foreach}
				</div>
			</div>
		</form>
	</div>
{/strip}
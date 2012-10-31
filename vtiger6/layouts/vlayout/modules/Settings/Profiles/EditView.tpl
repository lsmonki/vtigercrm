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
<div class="container-fluid">
	<div class="titleBar row-fluid">
		<div class="span8">
			<h3 class="title">{vtranslate('LBL_CREATE_PROFILE', $QUALIFIED_MODULE)}</h3>
			<p>&nbsp;</p>
		</div>
		<div class="span4">
			<div class="pull-right">
				<div class="btn-toolbar">
					<span class="btn-group">
						<a class="btn btn-mini vtButton" href="javascript:window.history.back();">Back ...</a>
					</span>
				</div>
			</div>
		</div>
	</div>

	<form name="EditProfile" action="index.php" method="post" class="form-horizontal" id="EditView">
		<input type="hidden" name="module" value="Profiles" />
		<input type="hidden" name="action" value="Save" />
		<input type="hidden" name="parent" value="Settings" />
		<input type="hidden" name="record" value="{$RECORD_MODEL->getId()}" />
		<input type="hidden" name="mode" value="{$MODE}" />

		<table class="table table-striped table-bordered table-condensed">
			<tbody>
			<tr>
				<td class="fieldLabel">
					{vtranslate('LBL_PROFILE_NAME', $QUALIFIED_MODULE)} <span class="redColor">*</span>
				</td>
				<td class="fieldValue">
					<input type="text" name="profilename" id="profilename" value="{$RECORD_MODEL->getName()}" required="true"  />
				</td>
			</tr>
			<tr>
				<td class="fieldLabel">
					{vtranslate('LBL_DESCRIPTION', $QUALIFIED_MODULE)}
				</td>
				<td class="fieldValue">
					<textarea class="input-xxlarge" name="description" id="description">{$RECORD_MODEL->getDescription()}</textarea>
				</td>
			</tr>
			</tbody>
		</table>

		<table class="table table-striped table-bordered table-condensed">
			<thead>
				<tr>
					<th width="30%">
						{vtranslate('LBL_MODULE', $QUALIFIED_MODULE)}
					</th>
					<th class="row-fluid">
						<div class="span3">
							{'LBL_VIEW_PRVILIGE'|vtranslate:$QUALIFIED_MODULE}
						</div>
						<div class="span3">
							{'LBL_EDIT_PRVILIGE'|vtranslate:$QUALIFIED_MODULE}
						</div>
						<div class="span3">
							{'LBL_DELETE_PRVILIGE'|vtranslate:$QUALIFIED_MODULE}
						</div>
					</th>
					<th nowrap="nowrap">{'LBL_FIELD_PRVILIGES'|vtranslate:$QUALIFIED_MODULE}</th>
					<th nowrap="nowrap">{'LBL_TOOL_PRVILIGES'|vtranslate:$QUALIFIED_MODULE}</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$RECORD_MODEL->getModulePermissions() key=TABID item=PROFILE_MODULE}
				<tr>
					<td>
					 <input type="checkbox" name="permissions[{$TABID}][is_permitted]" data-value="{$TABID}" data-module-state="" {if $RECORD_MODEL->hasModulePermission($PROFILE_MODULE)}checked="true"{/if}> {$PROFILE_MODULE->get('label')|vtranslate:$PROFILE_MODULE->getName()}
					</td>
					<td class="row-fluid">
						{assign var="BASIC_ACTION_ORDER" value=array(2,1,0)}

						{foreach from=$BASIC_ACTION_ORDER item=ACTION_ID}
							{assign var="ACTION_MODEL" value=$ALL_BASIC_ACTIONS[$ACTION_ID]}
							{if $ACTION_MODEL->isModuleEnabled($PROFILE_MODULE)}
								<div class="span3"><input type="checkbox" name="permissions[{$TABID}][actions][{$ACTION_ID}]" data-action-state="{$ACTION_MODEL->getName()}" {if $RECORD_MODEL->hasModuleActionPermission($PROFILE_MODULE, $ACTION_MODEL)}checked="true"{/if}></div>
							{/if}
						{/foreach}
					</td>
					<td>
						{if $PROFILE_MODULE->getFields()}
						<a href="javascript:;" data-handlerfor="fields" data-togglehandler="{$TABID}-fields" class="icon-chevron-down"></a>
						{/if}
					</td>
					<td>
						<a href="javascript:;" data-handlerfor="tools" data-togglehandler="{$TABID}-tools" class="icon-chevron-down"></a>
					</td>
				</tr>
				<tr class="hide" data-togglecontent="{$TABID}-fields">
					<td colspan="4" class="row-fluid">
						{if $PROFILE_MODULE->getFields()}
						<div class="span12">
							<div class="pull-right">
								<span class="mini-slider-control ui-slider" data-value="0">
									<a class="ui-slider-handle"></a>
								</span>
								<span style="margin-left:15px;">Invisible</span>
								<span class="mini-slider-control ui-slider" data-value="1">
									<a class="ui-slider-handle"></a>
								</span>
								<span style="margin-left:15px;">Read Only</span>
								<span class="mini-slider-control ui-slider" data-value="2">
									<a class="ui-slider-handle"></a>
								</span>
								<span style="margin-left:15px;">Write</span>
							</div>
							<div class="clearfix"></div>
						</div>
						{foreach from=$PROFILE_MODULE->getFields() key=FIELD_NAME item=FIELD_MODEL}
							{assign var="FIELD_ID" value=$FIELD_MODEL->getId()}
							<div class="span3">
								{assign var="FIELD_LOCKED" value=$RECORD_MODEL->isModuleFieldLocked($PROFILE_MODULE, $FIELD_MODEL)}
								<input type="hidden" name="permissions[{$TABID}][fields][{$FIELD_ID}]" data-range-input="{$FIELD_ID}" value="{$RECORD_MODEL->getModuleFieldPermissionValue($PROFILE_MODULE, $FIELD_MODEL)}" readonly="true">
								<div class="mini-slider-control pull-left" data-locked="{$FIELD_LOCKED}" data-range="{$FIELD_ID}" data-value="{$RECORD_MODEL->getModuleFieldPermissionValue($PROFILE_MODULE, $FIELD_MODEL)}"></div>
								<div class="pull-left">
									{if $FIELD_MODEL->isMandatory()}<span class="redColor">*</span>{/if} {$FIELD_MODEL->get('label')}
								</div>
								<div class="clearfix"></div>
							</div>
						{/foreach}
						</ul>
						{/if}
					</td>
				</tr>
				<tr class="hide" data-togglecontent="{$TABID}-tools">
					<td colspan="4" class="row-fluid">
						<div class="span12"></div>
						{foreach from=$ALL_UTILITY_ACTIONS key=ACTION_ID item=ACTION_MODEL}
							{if $ACTION_MODEL->isModuleEnabled($PROFILE_MODULE)}
								<div class="span3"><input type="checkbox" name="permissions[{$TABID}][actions][{$ACTION_ID}]" {if $RECORD_MODEL->hasModuleActionPermission($PROFILE_MODULE, $ACTION_ID)}checked="true"{/if}> {$ACTION_MODEL->getName()}</div>
							{/if}
						{/foreach}
					</td>
				</tr>
				{/foreach}
			</tbody>
		</table>
		<div class="form-actions">
			<button class="vtButton saveButton" type="submit">{vtranslate('LBL_SAVE', $MODULE)}</button>
			<a class="cancelLink" type="reset" onclick="javascript:window.history.back();">{vtranslate('LBL_CANCEL', $MODULE)}</a>
		</div>
	</form>
</div>
<script type="text/javascript">
	jQuery('body').ready(Settings_Profiles_Js.initEditView);
</script>
{/strip}
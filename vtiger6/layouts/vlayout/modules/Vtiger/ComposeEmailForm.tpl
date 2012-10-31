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
<div class="SendEmailFormStep2" id="composeEmailContainer">
	<div class="modal-header">
		<h3>{vtranslate('LBL_COMPOSE_EMAIL', $MODULE)}</h3>
	</div>
	<form class="form-horizontal" id="massEmailForm" method="post" action="index.php" enctype="multipart/form-data">
		<input type="hidden" name="selected_ids" value={ZEND_JSON::encode($SELECTED_IDS)} />
		<input type="hidden" name="excluded_ids" value={ZEND_JSON::encode($EXCLUDED_IDS)} />
		<input type="hidden" name="viewname" value="{$VIEWNAME}" />
		<input type="hidden" name="module" value="{$MODULE}"/>
		<input type="hidden" name="mode" value="massSave" />
		<input type="hidden" name="toemailinfo" value={ZEND_JSON::encode($TOMAIL_INFO)} />
		<input type="hidden" name="view" value="MassSaveAjax" />
		<input type="hidden"  name="to" value={ZEND_JSON::encode($TO)} />
		<input type="hidden" id="flag" name="flag" value="" />
		<input type="hidden" id="maxUploadSize" value="500000" />
		<input type="hidden" id="documentIds" name="documentids" value="" />
		<div class="row-fluid padding-bottom1per">
			<span class="span2">{vtranslate('LBL_TO',$MODULE)}<span class="redColor">*</span></span>
			{if !empty($TO)}
				{assign var=TO_EMAILS value=","|implode:$TO}
			{/if}
			<input data-validation-engine='validate[required]' class="span8" type="text" value="{$TO_EMAILS}" disabled/>
		</div>
		<div class="row-fluid padding-bottom1per hide" id="ccContainer">
			<span class="span2">{vtranslate('LBL_CC',$MODULE)}</span>
			<input class="span8"  data-validation-engine="validate[funcCall[Vtiger_MultiEmails_Validator_Js.invokeValidation]]" type="text" name="cc" value=""/>
		</div>
		<div class="row-fluid padding-bottom1per hide" id="bccContainer">
			<span class="span2">{vtranslate('LBL_BCC',$MODULE)}</span>
			<input class="span8" data-validation-engine="validate[funcCall[Vtiger_MultiEmails_Validator_Js.invokeValidation]]" type="text" name="bcc" value=""/>
		</div>
		<div class="row-fluid padding-bottom1per">
			<span class="span2">&nbsp;</span>
			<span class="span8">
				<a class="cursorPointer" id="ccLink">{vtranslate('LBL_ADD_CC', $MODULE)}</a>&nbsp;&nbsp;
				<a class="cursorPointer" id="bccLink">{vtranslate('LBL_ADD_BCC', $MODULE)}</a>
			</span>
		</div>
		<div class="row-fluid padding-bottom1per">
			<span class="span2">{vtranslate('LBL_SUBJECT',$MODULE)}<span class="redColor">*</span></span>
			<input data-validation-engine='validate[required]' class="span8" type="text" name="subject" value="" id="subject"/>
		</div>
		<div class="row-fluid padding-bottom1per">
			<span class="span2">{vtranslate('LBL_ATTACHMENT',$MODULE)}</span>
			<span class="span8">
				<input type="file" name="file[]" class="multiFile" />&nbsp;
				<button type="button" class="btn-x-small" id="browseCrm" data-url="{$DOCUMENTS_URL}">{vtranslate('LBL_BROWSE_CRM',$MODULE)}</button>
			</span>
		</div>
		<div class="padding-bottom1per">
			<button class="btn btn-success" id="sendEmail" type="submit"><strong>{vtranslate('LBL_SEND',$MODULE)}</strong></button>&nbsp;&nbsp;
			<button type="submit" class="btn" id="saveDraft"><strong>{vtranslate('LBL_SAVE_AS_DRAFT',$MODULE)}</strong></button>
			<button type="button" class="pull-right btn" id="selectEmailTemplate" data-url="{$EMAIL_TEMPLATE_URL}"><strong>{vtranslate('LBL_SELECT_EMAIL_TEMPLATE',$MODULE)}</strong></button>
		</div>
		<textarea id="description" name="description"></textarea>
	</form>
</div>
{/strip}

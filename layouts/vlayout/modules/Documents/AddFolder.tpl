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
	<div class="modelContainer">
		<div class="modal-header">
			<button title="{vtranslate('LBL_CLOSE')}" class="close" data-dismiss="modal">x</button>
			<h3>{vtranslate('LBL_ADD_NEW_FOLDER', $MODULE)}</h3>
		</div>
		<form class="form-horizontal contentsBackground" id="addDocumentsFolder" method="post" action="index.php">
			<input type="hidden" name="module" value="{$MODULE}" />
			<input type="hidden" name="action" value="Folder" />
			<input type="hidden" name="mode" value="save" />
			<div class="modal-body">
				<div class="row-fluid verticalBottomSpacing">
					<span class="span4">{vtranslate('LBL_FOLDER_NAME', $MODULE)}<span class="redColor">*</span></span>
					<span class="span7 row-fluid">
						<input data-validator='{Zend_Json::encode([['name'=>'FolderName']])}' data-validation-engine="validate[required, funcCall[Vtiger_Base_Validator_Js.invokeValidation]]" id="documentsFolderName" name="foldername" class="span12" type="text" value=""/>
					</span>
				</div>
				<div class="row-fluid">
					<span class="span4">{vtranslate('LBL_FOLDER_DESCRIPTION', $MODULE)}</span>
					<span class="span7 row-fluid">
						<input class="span12" name="folderdesc" type="text" value=""/>
					</span>
				</div>
			</div>
			{include file='ModalFooter.tpl'|@vtemplate_path:$MODULE}
		</form>
	</div>
{/strip}
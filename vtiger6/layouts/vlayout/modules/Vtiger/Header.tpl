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
<!DOCTYPE html>
<html>
	<head>
		<title>
			{vtranslate($PAGETITLE, $MODULE_NAME)}
		</title>
		<link REL="SHORTCUT ICON" HREF="layouts/vlayout/skins/images/favicon.ico">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

		<link rel="stylesheet" href="libraries/jquery/chosen/chosen.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="libraries/jquery/jquery-ui/css/custom-theme/jquery-ui-1.8.16.custom.css" type="text/css" media="screen" />

		<link rel="stylesheet" href="libraries/jquery/select2/select2.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="libraries/bootstrap/css/bootstrap.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="resources/styles.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="libraries/jquery/posabsolute-jQuery-Validation-Engine/css/validationEngine.jquery.css" />

		<link rel="stylesheet" href="libraries/jquery/select2/select2.css" />

		<link rel="stylesheet" href="libraries/guidersjs/guiders-1.2.6.css"/>
		<link rel="stylesheet" href="libraries/jquery/pnotify/jquery.pnotify.default.css"/>
		<link rel="stylesheet" href="libraries/jquery/pnotify/use for pines style icons/jquery.pnotify.default.icons.css"/>
		<link rel="stylesheet" media="screen" type="text/css" href="libraries/jquery/datepicker/css/datepicker.css" />

		<script type="text/javascript" src="libraries/html5shim/html5.js"></script>

		<script type="text/javascript" src="libraries/jquery/jquery.min.js"></script>
		<script type="text/javascript" src="libraries/jquery/jquery.blockUI.js"></script>
		<script type="text/javascript" src="libraries/jquery/chosen/chosen.jquery.min.js"></script>
		<script type="text/javascript" src="libraries/jquery/select2/select2.min.js"></script>
		<script type="text/javascript" src="libraries/jquery/jquery-ui/js/jquery-ui-1.8.16.custom.min.js"></script>
		<script type="text/javascript" src="libraries/jquery/jquery.class.min.js"></script>
		<script type="text/javascript" src="libraries/jquery/defunkt-jquery-pjax/jquery.pjax.js"></script>
		<script type="text/javascript" src="libraries/jquery/jstorage.js"></script>

		<script type="text/javascript" src="libraries/jquery/rochal-jQuery-slimScroll/slimScroll.min.js"></script>
		<script type="text/javascript" src="libraries/jquery/pnotify/jquery.pnotify.min.js"></script>

		<script type="text/javascript" src="libraries/bootstrap/js/bootstrap-alert.js"></script>
		<script type="text/javascript" src="libraries/bootstrap/js/bootstrap-tooltip.js"></script>
		<script type="text/javascript" src="libraries/bootstrap/js/bootstrap-tab.js"></script>
		<script type="text/javascript" src="libraries/bootstrap/js/bootstrap-collapse.js"></script>
		<script type="text/javascript" src="libraries/bootstrap/js/bootstrap-modal.js"></script>
		<script type="text/javascript" src="libraries/bootstrap/js/bootstrap-dropdown.js"></script>
		<script type="text/javascript" src="libraries/bootstrap/js/bootbox.min.js"></script>
		<script type="text/javascript" src="resources/jquery.additions.js"></script>
		<script type="text/javascript" src="resources/app.js"></script>
        <script type="text/javascript" src="resources/helper.js"></script>
		<script type="text/javascript" src="resources/Connector.js"></script>
		<script type="text/javascript" src="resources/ProgressIndicator.js" ></script>
		<script type="text/javascript" src="libraries/jquery/posabsolute-jQuery-Validation-Engine/js/jquery.validationEngine.js" ></script>
		<script type="text/javascript" src="libraries/jquery/posabsolute-jQuery-Validation-Engine/js/jquery.validationEngine-en.js" ></script>
		<script type="text/javascript" src="libraries/guidersjs/guiders-1.2.6.js"></script>
		<script type="text/javascript" src="libraries/jquery/datepicker/js/datepicker.js"></script>
		<script type="text/javascript" src="libraries/jquery/dangrossman-bootstrap-daterangepicker/date.js"></script>
		<script type="text/javascript" src="libraries/jquery/jquery.ba-outside-events.min.js"></script>

		{foreach key=index item=cssModel from=$STYLES}
			<link rel="{$cssModel->getRel()}" href="{$cssModel->getHref()}" type="{$cssModel->getType()}" media="{$cssModel->getMedia()}" />
		{/foreach}
		{foreach key=index item=jsModel from=$SCRIPTS}
			<script type="{$jsModel->getType()}" src="{$jsModel->getSrc()}"></script>
		{/foreach}

		<!-- Added in the end since it should be after less file loaded -->
		<script type="text/javascript" src="libraries/bootstrap/js/less.min.js"></script>

	</head>

	<body data-skinpath="{$SKIN_PATH}">
		<div id="js_strings" class="hide">{Zend_Json::encode($LANGUAGE_STRINGS)}</div>
		<div id="page">
			<!-- container which holds data temporarly for pjax calls -->
			<div id="pjaxContainer" class="hide"></div>
{/strip}

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
		<title>Vtiger</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		
		<link REL="SHORTCUT ICON" HREF="vtiger6/layouts/vlayout/skins/images/favicon.ico">
		<link rel="stylesheet" href="vtiger6/libraries/bootstrap/css/bootstrap.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="vtiger6/resources/styles.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="vtiger6/libraries/jquery/select2/select2.css" />
		<link rel="stylesheet" href="vtiger6/libraries/jquery/posabsolute-jQuery-Validation-Engine/css/validationEngine.jquery.css" />
		
		<script type="text/javascript" src="vtiger6/libraries/jquery/jquery.min.js"></script>
		<script type="text/javascript" src="vtiger6/libraries/bootstrap/js/bootstrap-tooltip.js"></script>
		<script type="text/javascript" src="vtiger6/libraries/jquery/select2/select2.min.js"></script>
		<script type="text/javascript" src="vtiger6/libraries/jquery/posabsolute-jQuery-Validation-Engine/js/jquery.validationEngine.js" ></script>
		<script type="text/javascript" src="vtiger6/libraries/jquery/posabsolute-jQuery-Validation-Engine/js/jquery.validationEngine-en.js" ></script>
		
		<script type="text/javascript">{literal}
			jQuery(function(){ 
				jQuery('select').select2({blurOnChange:true}); 
				jQuery('[rel="tooltip"]').tooltip();
				jQuery('form').validationEngine({
					prettySelect: true,
					usePrefix: 's2id_',
					autoPositionUpdate: true,
					promptPosition : "topLeft",
					showOneMessage: true
				});
				jQuery('#currency_name_controls').mouseenter(function() {
					jQuery('#currency_name_tooltip').tooltip('show');
				});
				jQuery('#currency_name_controls').mouseleave(function() {
					jQuery('#currency_name_tooltip').tooltip('hide');
				});
			});
		{/literal}</script>
		<style type="text/css">{literal}
			 body { background: #ffffff url('themes/images/usersetupbg.png') no-repeat center top; background-size: 100%; font-size: 14px; }
			.modal-backdrop { opacity: 0.65; }
			.tooltip { z-index: 1055; }
			input, select, textarea { font-size: 14px; }
		{/literal}</style>
	</head>
	<body>
		
		<div class="container">
			<div class="modal-backdrop"></div>
			<form class="form" method="POST" action="index.php?module=Users&action=UserSetupSave">
				<div class="modal" {if $IS_FIRST_USER}style="width: 700px;"{/if}>
					<div class="modal-header">
						<h3>Almost there!</h3>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="span4">
								<label class="control-label"><strong>Preferences</strong> <span class="muted">(All fields below are required)</label>

								{if $IS_FIRST_USER}
								<div class="controls" id="currency_name_controls">
									<select name="currency_name" id="currency_name" placeholder="Base Currency" data-errormessage="Choose Base Currency" class="validate[required]" style="width:250px;">
										<option value=""></option>
										{foreach key=header item=currency from=$CURRENCIES}
											<option value="{$header}">{$header|@getTranslatedCurrencyString}({$currency.1})</option>
											{*if $header eq 'USA, Dollars'}
												<option value="{$header}" selected>{$header|@getTranslatedCurrencyString}({$currency.1})</option>
											{else}
												<option value="{$header}">{$header|@getTranslatedCurrencyString}({$currency.1})</option>
											{/if*}
										{/foreach}
									</select>
									&nbsp;
									<span rel="tooltip" title="Base currency cannot be modified later. Select your operating currency" id="currency_name_tooltip" class="icon-info-sign"></span>
									<div style="padding-top:10px;"></div>
								</div>
								{/if}

								<div class="controls">
									<select name="lang_name" id="lang_name" style="width:250px;" placeholder="Language" data-errormessage="Choose Language" class="validate[required]">
										<option value=""></option>
										{foreach key=header item=language from=$LANGUAGES}
											<option value="{$header}">{$language|@getTranslatedString:$MODULE}</option>
											{* Avoiding auto selection *}
											{*if $language eq 'US English'}
												<option value="{$header}" selected>{$language|@getTranslatedString:$MODULE}</option>
											{else}
												<option value="{$header}">{$language|@getTranslatedString:$MODULE}</option>
											{/if*}
										{/foreach}
									</select>
									<div style="padding-top:10px;"></div>
								</div>
								<div class="controls">
									<select name="time_zone" id="time_zone" style="width:250px;" placeholder="Choose Timezone" data-errormessage="Choose Timezone" class="validate[required]">
										<option value=""></option>
										{foreach key=header item=time_zone from=$TIME_ZONES}
											<option value="{$header}">{$time_zone|@getTranslatedString:$MODULE}</option>
											{* Avoiding auto selection *}
											{*if $time_zone eq 'UTC'}
												<option value="{$header}" selected>{$time_zone|@getTranslatedString:$MODULE}</option>
											{else}
												<option value="{$header}">{$time_zone|@getTranslatedString:$MODULE}</option>
											{/if*}
										{/foreach}
									</select>
									<div style="padding-top:10px;"></div>
								</div>
								<div class="controls">
									<select name="date_format" id="date_format" style="width:250px;" placeholder="Date Format" data-errormessage="Choose Date Format" class="validate[required]">
										<option value=""></option>
										<option value="dd-mm-yyyy">dd-mm-yyyy</option>
										<option value="mm-dd-yyyy">mm-dd-yyyy</option>
										<option value="yyyy-mm-dd">yyyy-mm-dd</option>
										{* Avoiding auto selection *}
										{* <option value="yyyy-mm-dd" selected>yyyy-mm-dd</option> *}
									</select>
									<div style="padding-top:10px;"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button class="btn btn-success" type="submit">Get Started</button>
					</div>
				</div>
			</form>						
		</div>
		
	</body>
</html>
{/strip}

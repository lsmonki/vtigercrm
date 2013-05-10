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
<div class="container-fluid" id="importModules">
	<div class="widget_header row-fluid">
		<h3>{vtranslate('LBL_VTIGER_EXTENSION_STORE', $QUALIFIED_MODULE)}</h3>
	</div><hr>
	<div class="contents">
		<div class="row-fluid">
				{foreach item=EXTENSION from=$EXTENSIONS name=extensions}
					<div class="span6 {if $smarty.foreach.extensions.index % 2 != 0} margin0px{/if}">
						<div class="extension_container extensionWidgetContainer">
							<div class="extension_header row-fluid widget_header">
								<span class="span8 font-x-x-large">{vtranslate($EXTENSION->get('label'), $QUALIFIED_MODULE)}</span>
								<input type="hidden" name="extensionName" value="{$EXTENSION->get('name')}" />
								<input type="hidden" name="extensionUrl" value="{$EXTENSION->get('downloadURL')}" />
								<input type="hidden" name="moduleAction" value="{if $EXTENSION->isUpgradable()}Upgrade{else}Install{/if}" />
								<input type="hidden" name="extensionId" value="{$EXTENSION->get('id')}" />
							</div>
							<div class="extension_contents">
								<div class="row-fluid">
									<span class="span8">
										<div class="row-fluid extensionDescription" style="word-wrap:break-word;">
											{$EXTENSION->get('description')}
										</div>
									</span>
									<span class="span3"><img src="{$EXTENSION->get('imageURL')}" /></span>
								</div>
								<div class="extensionInfo">
									<div class="row-fluid">
										<span class="span3"><b>{vtranslate('LBL_PUBLISHER', $QUALIFIED_MODULE)}</b></span>
										<span class="span5">{$EXTENSION->get('publisher')}</span>
									</div>
									<div class="row-fluid">
										<span class="span3"><b>{vtranslate('LBL_LICENSE', $QUALIFIED_MODULE)}</b></span>
										<span class="span5">{$EXTENSION->get('license')}</span>
									</div>
									<div class="row-fluid">
										<span class="span3"><b>{vtranslate('LBL_PUBLISHED_ON', $QUALIFIED_MODULE)}</b></span>
										<span class="span4">{$EXTENSION->get('pubDate')}</span>
										<span class="span5">
											<span class="pull-right">
												{if $EXTENSION->isVtigerCompatible()}
													{if $EXTENSION->isAlreadyExists()}
														{if $EXTENSION->isUpgradable()}
															<button class="installExtension btn btn-success">
																{vtranslate('LBL_UPGRADE', $QUALIFIED_MODULE)}
															</button>
														{else}
															<span class="alert alert-info">{vtranslate('LBL_ALREADY_EXISTS', $QUALIFIED_MODULE)}</span>
														{/if}
													{else}
														<button class="installExtension btn btn-success">
															{vtranslate('LBL_INSTALL', $QUALIFIED_MODULE)}
														</button>
													{/if}
												{else}
													<span class="alert alert-error">{vtranslate('LBL_EXTENSION_NOT_COMPATABLE', $QUALIFIED_MODULE)}</span>
												{/if}
											</span>
										</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				{if $smarty.foreach.extensions.index % 2 != 0}</div><div class="row-fluid">{/if}
				{/foreach}
		</div>
	</div>
</div>
{/strip}
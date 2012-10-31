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

<div style='padding:10px;padding-left:20px;'>
	{if $DASHBOARDHEADER_TITLE}
		<h2 class="pull-left">{$DASHBOARDHEADER_TITLE}</h2>
	{/if}
	<span class="btn-toolbar pull-right" style="margin: 0;">
		<span class="btn-group">
			{if $WIDGETS|count gt 0}
			<button class='btn addButton dropdown-toggle' data-toggle='dropdown'>
				<strong>{vtranslate('LBL_ADD_WIDGET')}</strong>
				<i class="caret"></i>
			</button>

			<ul class="dropdown-menu widgetsList pull-right" style="min-width:100%;text-align:left;">
				{foreach from=$WIDGETS item=WIDGET}
					<li>
						<a onclick="Vtiger_DashBoard_Js.addWidget(this, '{$WIDGET->getUrl()}')" href="javascript:void(0);"
							data-linkid="{$WIDGET->get('linkid')}" data-name="{$WIDGET->getName()}" data-width="{$WIDGET->getWidth()}" data-height="{$WIDGET->getHeight()}">
							{vtranslate($WIDGET->getTitle(), $MODULE_NAME)}</a>
					</li>
				{/foreach}
			</ul>
			{else}
				<button class='btn addButton dropdown-toggle' disabled="disabled" data-toggle='dropdown'>
					<strong>{vtranslate('LBL_ADD_WIDGET')}</strong> &nbsp;&nbsp;
				<i class="caret"></i>
			</button>
			{/if}
		</span>
	</span>
	<hr class="clearfix" style="margin: 0;"/>
</div>
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
	<div>
		<div class="reportHeader row-fluid">
			<div class='span4' style="position:relative;left:10px">
				<button onclick='window.location.href="{$REPORT_MODEL->getEditViewUrl()}"' type="button" class="cursorPointer btn"><strong>{vtranslate('LBL_CUSTOMIZE',$MODULE)}</strong>&nbsp;<i class="icon-pencil"></i></button>
			</div>
			<div class='span4'><h3>{$REPORT_MODEL->getName()}</h3></div>
			<div class='span4'>
				<span class="pull-right">
					{foreach item=DETAILVIEW_LINK from=$DETAILVIEW_LINKS}
						<img class="cursorPointer alignBottom" onclick='window.location.href="{$DETAILVIEW_LINK->getUrl()}"' src="{vimage_path({$DETAILVIEW_LINK->get('linkicon')})}" alt="{vtranslate($DETAILVIEW_LINK->getLabel(), $MODULE)}" title="{vtranslate($DETAILVIEW_LINK->getLabel(), $MODULE)}" />&nbsp;
					{/foreach}
				</span>
			</div>
		</div>
	</div>
{/strip}
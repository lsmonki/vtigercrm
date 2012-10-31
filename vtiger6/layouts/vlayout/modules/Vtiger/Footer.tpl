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
		{* Feedback side-panel button *}
		{if $HEADER_LINKS}
		{assign var="FIRSTHEADERLINK" value=$HEADER_LINKS.0}
		{assign var="FIRSTHEADERLINKCHILDRENS" value=$FIRSTHEADERLINK->get('childlinks')}
		{assign var="FEEDBACKLINKMODEL" value=$FIRSTHEADERLINKCHILDRENS.1}
		<div id="userfeedback" class="feedback">
			<a href="javascript:;" onclick="{$FEEDBACKLINKMODEL->get('linkurl')}" class="handle">Feedback</a>
		</div>
		{/if}
		
		<footer>
			<p style="margin-top:5px;margin-bottom:0;" align="center">
				{vtranslate('POWEREDBY')} - 6.0 beta {*$VTIGER_VERSION*} 
				&copy; 2004 - {date('Y')}&nbsp;
				<a href="//www.vtiger.com" target="_blank">vtiger.com</a>
				&nbsp;|&nbsp;
				<a href="#" onclick="window.open('../copyright.html','copyright', 'height=115,width=575').moveTo(210,620)">{vtranslate('LBL_READ_LICENSE')}</a>
				&nbsp;|&nbsp;
				<a href="//www.vtiger.com/products/crm/privacy_policy.html" target="_blank">{vtranslate('LBL_PRIVACY_POLICY')}</a>
			</p>
		</footer>

		</div>
	</body>
</html>
{/strip}
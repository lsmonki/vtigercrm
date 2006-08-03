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
<!-- BEGIN: main -->
<div id="roleLay" style="z-index:12;display:block;width:400px;">
	<table width="100%" border="0" cellpadding="5" cellspacing="0" class="small">
		<tr>
			<td width="90%" align="left" class="genHeaderSmall">{$MOD.SELECT_EMAIL}
			{if $ONE_RECORD neq 'true'}
				({$MOD.LBL_MULTIPLE} {$APP[$FROM_MODULE]})
			{/if}
			</td>
			<td width="10%" align="right"><a href="javascript:fninvsh('roleLay');"><img src="{$IMAGE_PATH}close.gif" border="0"  align="absmiddle" /></a></td>
		</tr>
		<tr><td colspan="2"><hr /></td></tr>
		<td colspan="2" align="left">
			{if $ONE_RECORD eq 'true'}
				<b>{$ENTITY_NAME}</b> {$MOD.LBL_MAILSELECT_INFO}.<br><br>
			{else}
				{$MOD.LBL_MAILSELECT_INFO1} {$APP[$FROM_MODULE]}.{$MOD.LBL_MAILSELECT_INFO2}<br><br>
			{/if}
			<div id="sendMail" align="center">
				<table border="0" cellpadding="5" cellspacing="0" width="90%">
				{foreach name=emailids key=fieldid item=elements from=$MAILINFO}
				<tr>
					{if $smarty.foreach.emailids.iteration eq 1}	
						<td align="center"><input checked type="checkbox" value="{$fieldid}" name="email"/></td>
					{else}
						<td align="center"><input type="checkbox" value="{$fieldid}" name="email"/></td>
					{/if}
					{if $ONE_RECORD eq 'true'}	
						<td align="left"><b>{$elements.0} </b><br>{$MAILDATA[$smarty.foreach.emailids.iteration]}</td>
					{else}
						<td align="left"><b>{$elements.0} </b></td>
					{/if}
						
				</tr>
				{/foreach}
				</table>
			</div>
		</td>	
		</tr>
		<tr><td style="border-bottom:1px dashed #CCCCCC;" colspan="2">&nbsp;</td></tr>
		<tr>
			<td colspan="2" align="center">
					<input type="button" name="{$APP.LBL_SELECT_BUTTON_LABEL}" value=" {$APP.LBL_SELECT_BUTTON_LABEL} " class="crmbutton small create" onClick="validate_sendmail('{$IDLIST}','{$FROM_MODULE}');"/>&nbsp;&nbsp;
					<input type="button" name="{$APP.LBL_CANCEL_BUTTON_LABEL}" value=" {$APP.LBL_CANCEL_BUTTON_LABEL} " class="crmbutton small cancel" onclick="fninvsh('roleLay');" />
			</td>
		</tr>
		<tr><td colspan="2" style="border-top:1px dashed #CCCCCC;">&nbsp;</td></tr>
	</table>
</div> 


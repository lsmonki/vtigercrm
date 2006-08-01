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
<div id="roleLay" style="z-index:12;display:block;">
	<table width="100%" border="0" cellpadding="5" cellspacing="0" class="small">
		<tr>
			<td width="50%" align="left" class="genHeaderSmall">{$MOD.CHOSE_EMAIL}</td>
			<td width="50%" align="right"><a href="javascript:fninvsh('roleLay');"><img src="{$IMAGE_PATH}close.gif" border="0"  align="absmiddle" /></a></td>
		</tr>
		<tr><td colspan="2"><hr /></td></tr>
		{foreach key=fieldid item=elements from=$MAILINFO}
		<tr>
			<td align="right"><b>{$elements.0} :</b></td>
			<td align="left"><input type="checkbox" value="{$fieldid}" name="email" /></td>
		</tr>
		{/foreach}
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


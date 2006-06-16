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

{include file='Buttons_List1.tpl'}	

<table  cellpadding="0" cellspacing="0" width="100%">
   <tr>
	<td width="75%" valign=top>
		<form enctype="multipart/form-data" name="Import" method="POST" action="index.php">
		<input type="hidden" name="module" value="{$MODULE}">
 		<input type="hidden" name="return_module" value="{$MODULE}">
		<input type="hidden" name="return_action" value="index">
		<input type="hidden" name="step" value="1">
		<input type="hidden" name="action" value="Import">

		<!-- IMPORT ERROR STARTS HERE  -->
		<br /><br /><br />

		<table align="center" cellpadding="5" cellspacing="0" width="95%" class="leadTable">
		   <tr>
			<td colspan="2" bgcolor="#FFFFFF" height="50" valign="middle" align="left" class="genHeaderSmall">{$MOD.LBL_MODULE_NAME} {$MODULE}</td>
		   </tr>
		   <tr bgcolor="#ECECEC">
			<td colspan="2" align="left" valign="top">&nbsp;</td>
		   </tr>
		   <tr bgcolor="#ECECEC">
			<td colspan="2" align="left" valign="top" style="padding-left:40px;">
				<span class="genHeaderSmall">{$MOD.LBL_MODULE_NAME} {$MOD.LBL_ERROR}</span> 
			</td>
		   </tr>
	
		   <tr bgcolor="#ECECEC"><td align="left" valign="top" colspan="2">&nbsp;</td></tr>
		   <tr bgcolor="#ECECEC">
			<td align="left" valign="top" colspan="2" style="padding-left:80px;"><font color="red" size="2px">{$MESSAGE}</font></td>
		   </tr>

		   <tr bgcolor="#ECECEC"><td colspan="2" height="50">&nbsp;</td></tr>
		   <tr bgcolor="#ECECEC"><td colspan="2"><hr /></td></tr>
		   <tr bgcolor="#ECECEC">
			<td colspan="2" align="right" style="padding-right:40px;">
				<input title="{$MOD.LBL_TRY_AGAIN}" accessKey="" class="classBtn" type="submit" name="button" value=" {$MOD.LBL_TRY_AGAIN} "  >
			</td>
		   </tr>
		   <tr bgcolor="#ECECEC"><td colspan="2" align="right" valign="top">&nbsp;</td>
		   </tr>
		</table>
		<br />
		</form>
	</td>
   </tr>
</table>

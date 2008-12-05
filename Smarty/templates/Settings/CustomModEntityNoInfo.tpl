{*
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/ *}

<table width="100%"  border="0" cellspacing="0" cellpadding="5">

<tr>
	<td colspan=2 nowrap class="small cellLabel">
		<strong>{$SELMODULE} Module Numbering</strong> {$STATUSMSG}
	</td>
</tr>

<tr>
	<td width="20%" nowrap class="small cellLabel"><strong>Use Prefix</strong></td>
    <td width="80%" class="small cellText">
	<input type="text" name="recprefix" class="small" style="width:30%" value="{$MODNUM_PREFIX}"  />
	</td>
</tr>
<tr>
	<td width="20%" nowrap class="small cellLabel"><strong>Start Sequence <font color='red'>*</font></strong></td>
	<td width="80%" class="small cellText">
	<input type="text" name="recnumber" class="small" style="width:30%" value="{$MODNUM}"  />
	</td>
</tr>

<tr>
	<td width="20%" nowrap colspan="2" align ="center">
		<input type="button" name="Button" class="crmbutton small save" value="Apply" onclick="updateModEntityNoSetting(this, this.form);" />
		<input type="button" name="Button" class="crmbutton small cancel" value="Cancel" onclick="history.back(-1);" /></td>
	</td>
</tr>
</table>


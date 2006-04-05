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
<form action="index.php" method="post" name="index" id="form">
<input type="hidden" name="module" value="Settings">
<input type="hidden" name="action" value="index">
<input type="hidden" name="record" value="{$ID}">
<input type="hidden" name="return_module" value="{$RETURN_MODULE}">
<input type="hidden" name="return_action" value="{$RETURN_ACTION}">
<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
	<tr>
		<td class="showPanelBg" valign="top" width="95%"  style="padding-left:20px; "><br />
			<span class="lvtHeaderText"> {$MOD.LBL_MODULE_NAME} &gt;{$MOD.LBL_CONFIGURATION} &gt; {$MOD.LBL_CURRENCY_INFO} </span>
			<hr noshade="noshade" size="1" />
		</td>
		<td width="5%" class="showPanelBg">&nbsp;</td>
	</tr>
	<tr>
		<td width="98%" style="padding-left:20px;" valign="top">
			<!-- module Select Table -->
			<table width="95%"  border="0" cellspacing="0" cellpadding="0" align="center">
				<tr>
					<td width="7" height="6" style="font-size:1px;font-family:Arial, Helvetica, sans-serif;"><img src="{$IMAGE_PATH}top_left.jpg" align="top"  /></td>
					<td bgcolor="#EBEBEB" style="font-size:1px;font-family:Arial, Helvetica, sans-serif;height:6px;"></td>
					<td width="8" height="6" style="font-size:1px;font-family:Arial, Helvetica, sans-serif;"><img src="{$IMAGE_PATH}top_right.jpg" width="8" height="6" align="top" /></td>
				</tr>
				<tr>
					<td bgcolor="#EBEBEB" width="7"></td>
					<td bgcolor="#ECECEC" style="padding-left:10px;padding-top:10px;vertical-align:top;">
					<table width="100%"  border="0" cellspacing="0" cellpadding="10">
						<tr>
							<td rowspan="11" bgcolor="#FFFFFF"  width="25%" style="background-image:url({$IMAGE_PATH}noimage.gif);background-position:center;background-repeat:no-repeat; " >&nbsp;</td>
							<td colspan="2" class="genHeaderBig" width="75%">Currency<br />
							<hr /> </td>
						</tr>
					        <tr>
							<td align="right" width="25%"><b>{$MOD.LBL_CURRENCY_NAME} : </b></td>
							<td width="50%" align="left" ><input type="text" name="currency_name" value="{$CURRENCY_NAME}" class="txtBox" /></td>
						</tr>
						<tr>
							<td align="right"><b>{$MOD.LBL_CURRENCY_CODE} : </b></td>
							<td><input type="text" name="currency_code" value="{$CURRENCY_CODE}" class="txtBox" /></td>
						</tr>
						<tr>
							<td align="right"><b>{$MOD.LBL_CURRENCY_SYMBOL} : </b></td>
							<td><input type="text" name="currency_symbol" value="{$CURRENCY_SYMBOL}" class="txtBox" /></td>
						</tr>
						<tr>
							<td align="right"><b>{$MOD.LBL_CURRENCY_CRATE}  : </b></td>
							<td><input type="text" name="conversion_rate" value="{$CONVERSION_RATE}" class="txtBox" /></td>
						</tr>
						<tr>
							<td align="right"><b>{$MOD.LBL_CURRENCY_STATUS}  : </b></td>
							<td><select name="currency_status" value="{$CURRENCY_STATUS}" class="importBox">
								<option value="Active" {$SELECT}>{$MOD.LBL_ACTIVE}</option>
					        	        <option value="Inactive" {$SELECT}>{$MOD.LBL_INACTIVE}</option>
					                    </select>
							</td>
						</tr>
						<tr>
							<td colspan="2"  width="75%" style="padding-bottom:0px;padding-top:0px; "><hr /> </td>
						</tr>
						<tr>
							<td colspan="2" align="center">
							<input title="{$APP.LBL_SAVE_BUTTON_LABEL}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="classBtn" onclick="this.form.action.value='SaveCurrencyInfo'; return validate()" type="submit" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}" >&nbsp;&nbsp;
							<input title="{$APP.LBL_CANCEL_BUTTON_LABEL}>" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="classBtn" onclick="this.form.action.value='index'; this.form.module.value='Settings'; this.form.return_action.value='index'; this.form.return_module.value='Settings';" type="submit" name="button" value="{$APP.LBL_CANCEL_BUTTON_LABEL}">
							</td>
						</tr>
					</table>
					</td>
					<td bgcolor="#EBEBEB" width="8"></td>
				</tr>
				<tr>
					<td width="7" height="8" style="font-size:1px;font-family:Arial, Helvetica, sans-serif;"><img src="{$IMAGE_PATH}bottom_left.jpg" align="bottom"  /></td>
					<td bgcolor="#ECECEC" height="8" style="font-size:1px;" ></td>
					<td width="8" height="8" style="font-size:1px;font-family:Arial, Helvetica, sans-serif;"><img src="{$IMAGE_PATH}bottom_right.jpg" align="bottom" /></td>
				</tr>
			</table><br />
		</td>
		<td>&nbsp;</td>
	</tr>
</table>
</form>

<script>
        function validate() {ldelim}
                if (!emptyCheck("currency_name","Currency Name","text")) return false
                        if (!emptyCheck("currency_code","Currency Code","text")) return false
                                if (!emptyCheck("currency_symbol","Currency Symbol","text")) return false
                                        if (!emptyCheck("conversion_rate","Conversion Rate","text")) return false
                                                if (!emptyCheck("currency_status","Currency Status","text")) return false
                                                        return true;

        {rdelim}
</script>

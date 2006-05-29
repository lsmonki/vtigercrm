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


<table style="background-color: rgb(204, 204, 204);" class="small" border="0" cellpadding="5" cellspacing="1" width="100%">
	<tr>
        	<td class="lvtCol" width="25%">{$MOD.LBL_CURRENCY_NAME}</td>
                <td class="lvtCol" width="20%">{$MOD.LBL_CURRENCY_CODE}</td>
                <td class="lvtCol" width="5%">{$MOD.LBL_CURRENCY_SYMBOL}</td>
                <td class="lvtCol" width="20%">{$MOD.LBL_CURRENCY_CRATE}</td>
                <td class="lvtCol" width="15%">{$MOD.LBL_CURRENCY_STATUS}</td>
                <td class="lvtCol" width="15%">{$MOD.LBL_CURRENCY_TOOL}</td>
	</tr>
	{foreach item=currency_array key=id from=$CURRENCY_LIST}
		<tr class="lvtColData" onmouseover="this.className='lvtColDataHover'" onmouseout="this.className='lvtColData'" bgcolor="white">
			{foreach item=currencyvalues from=$currency_array}
				<td nowrap>{$currencyvalues}</td>
			{/foreach}
                 </tr>
        {/foreach}
</table>


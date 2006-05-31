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

<div id="calc" style="z-index:10000002">
	<table class="leftFormBorder1" border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr class="lvtCol" style="cursor:move;" >
			<td style="text-align:left;padding-left:5px;border-bottom:1px solid #666666;" id="calc_Handle">Calculator</td>
			<td align="right" style="padding:5px;border-bottom:1px solid #666666;">
			<a href="javascript:;">
			<img src="{$IMAGEPATH}close.gif" border="0"  onClick="fninvsh('calc')" hspace="5" align="absmiddle">
			</a>
			</td>
		</tr>
	</tr>
	<tr><td style="padding:10px;" colspan="2">{$CALC}</td></tr>
	</table>
</div>

<script>

	var cal_Handle = document.getElementById("calc_Handle");
	var cal_Root   = document.getElementById("calc");
	Drag.init(cal_Handle, cal_Root);
</script>	

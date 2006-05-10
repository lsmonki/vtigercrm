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
<div id="rssScroll">
	<table class="rssTable" cellspacing="0" cellpadding="0">
        <tr>
			<th width="5%"><input type="checkbox"  name="selectall" onClick=toggleSelect(this.checked,"selected_id")></th>
			{foreach item=element from=$LISTHEADER}
            <th>{$element}</th>
			{/foreach}
        </tr>
		{foreach key=id item=row from=$LISTENTITY}
	    <tr onmouseover="this.className='prvPrfHoverOn'" onmouseout="this.className='prvPrfHoverOff'">
			<td>
			<input type="checkbox" name="selected_id" value= '{$id}' onClick=toggleSelectAll(this.name,"selectall")>
</td>
			{foreach item=row_values from=$row}
			<td><b>{$row_values}</b></td>
			{/foreach}
        </tr>
		{/foreach}
    </table>
</div>


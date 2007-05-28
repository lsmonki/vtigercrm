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
	<table class="rssTable" cellspacing="0" cellpadding="0" width=100%>
        <tr>
			<th width="5%"><input type="checkbox"  name="selectall" onClick=toggleSelect(this.checked,"selected_id")></th>
            <th width="65%">{$LISTHEADER.0}</th>
            <th width="15%">{$LISTHEADER.1}</th>
            <th width="15%">{$LISTHEADER.2}</th>
        </tr>
		{foreach key=id item=row from=$LISTENTITY}
	    <tr id="row_{$id}">
			<td>
			<span><input type="checkbox" name="selected_id" value= '{$id}' onClick=toggleSelectAll(this.name,"selectall")>
</span></td>
			<td onClick="getEmailContents('{$id}'),setSubject('{$row.0}');" style="cursor:pointer;"><b>{$row.0}</b></td>
			<td onClick="getEmailContents('{$id}'),setSubject('{$row.0}');" style="cursor:pointer;">{$row.1}</td>
			<td onClick="getEmailContents('{$id}'),setSubject('{$row.0}');" style="cursor:pointer;">{$row.2}</td>
        </tr>
		{/foreach}
    </table>
</div>
<SCRIPT>
	if(gselectedrowid != 0)
	{ldelim}
		var rowid = 'row_'+gselectedrowid;
	    getObj(rowid).className = 'emailSelected';
	{rdelim}
</SCRIPT>

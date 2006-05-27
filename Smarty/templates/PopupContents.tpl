<table width="100%" border="0" cellspacing="0" cellpadding="0" class="small">
	<tr><td style="padding-right:10px;" align="right">{$RECORD_COUNTS}</td></tr>
   	<tr>
	    <td style="padding:10px;">

	    <form name="selectall" method="POST">
       	<input name="module" type="hidden" value="{$RETURN_MODULE}">
		<input name="action" type="hidden" value="{$RETURN_ACTION}">
        <input name="pmodule" type="hidden" value="{$MODULE}">
		<input type="hidden" name="curr_row" value="{$CURR_ROW}">	
		<input name="entityid" type="hidden" value="">
		<input name="popup_type" id="popup_type" type="hidden" value="{$POPUP_TYPE}">
		<input name="idlist" type="hidden" value="">
		{if $SELECT eq 'enable'}
		<td><input class="small" type="button" value="Add Contacts" onclick="if(SelectAll()) window.close();"/></td>
		{/if}

		<table style="background-color: rgb(204, 204, 204);" class="small" border="0" cellpadding="5" cellspacing="1" width="100%">
		<tbody>
		<tr>
		    {foreach item=header from=$LISTHEADER}
		        <td class="lvtCol">{$header}</td>
		    {/foreach}
		</tr>
		{foreach key=entity_id item=entity from=$LISTENTITY}
	        <tr bgcolor=white onMouseOver="this.className='lvtColDataHover'" onMouseOut="this.className='lvtColData'"  >
		   {if $SELECT eq 'enable'}
			<td><input type="checkbox" NAME="selected_id" value= '{$entity_id}' onClick=toggleSelectAll(this.name,"selectall")></td>
		   {/if}
                   {foreach item=data from=$entity}
		        <td>{$data}</td>
                   {/foreach}
		</tr>
                {/foreach}
	      	</tbody>
	    	</table>
			</form>
	    </td>
	</tr>
	<tr>
	    <td align="center" style="padding:10px;" background="{$IMAGE_PATH}report_bottom.gif">
		<table width="100%" align="center">
		<tr>
			{$NAVIGATION}	
		</tr>
		</table>
	    </td>
	</tr>
</table>


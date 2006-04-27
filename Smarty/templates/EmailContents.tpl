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
				<input type="checkbox" NAME="selected_id" value= '{$id}' onClick=toggleSelectAll(this.name,"selectall")>
</td>
						  	{foreach item=row_values from=$row}
                	        <td><b>{$row_values}</b></td>
							{/foreach}
                    	  </tr>
						  {/foreach}
                    </table>
					</div>


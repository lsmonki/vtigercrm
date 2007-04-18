<!-- Table to display the mails list -  Starts -->
				<div id="navTemp" style="display:none">
					<span style="float:left">{$ACCOUNT} &gt; {$MAILBOX}</span> <span style="float:right">{$NAVIGATION}</span>	
				</div>
				<span id="{$MAILBOX}_tempcount" style="display:none" >{$UNREAD_COUNT}</span>
				<div id="temp_boxlist" style="display:none">
					<ul style="list-style-type:none;">
					{foreach item=row from=$BOXLIST}
						{foreach item=row_values from=$row}                                                                                                 {$row_values}                                                                                                       {/foreach}                                                                                                          {/foreach}
					</ul>
				</div>
				<div id="temp_movepane" style="display:none">
					<input type="button" name="mass_del" value=" {$MOD.LBL_DELETE} "  class="crmbutton small delete" onclick="mass_delete();"/>
                                        {$FOLDER_SELECT}
				</div>
			<div id="show_msg" class="layerPopup" align="center" style="padding: 5px;font-weight:bold;font-size:30;width: 400px;display:none;z-index:10000"></div>	
                                <form name="massdelete" method="post">
                                <table class="rssTable" cellspacing="0" cellpadding="0" border="0" width="100%" id="message_table">
                                   <tr>
                                <th><input type="checkbox" name="select_all" value="checkbox"  onclick="toggleSelect(this.checked,'selected_id');"/></th>
                                        {foreach item=element from=$LISTHEADER}
                                                {$element}
                                        {/foreach}
                                   </tr>
                                        {foreach item=row from=$LISTENTITY}
                                                {foreach item=row_values from=$row}
                                                        {$row_values}
                                                {/foreach}
                                        {/foreach}
				</table>
                                </form>
                                <!-- Table to display the mails list - Ends -->

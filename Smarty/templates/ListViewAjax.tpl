<!-- Table to display the mails list -  Starts -->
				<div id="navTemp" style="display:none">{$NAVIGATION}</div>
				<span id="{$MAILBOX}_tempcount" style="display:none" >{$UNREAD_COUNT}</span>
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

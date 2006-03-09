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
<script language="JavaScript" type="text/javascript" src="modules/PriceBooks/PriceBook.js"></script>
<TABLE border=0 cellspacing=0 cellpadding=0 width=100% class=small>
<tr><td style="height:2px"></td></tr>
<tr>
	<td style="padding-left:10px;padding-right:10px" class="moduleName" nowrap>{$CATEGORY} > <a class="hdrLink" href="index.php?action=ListView&module={$MODULE}">{$MODULE}</a></td>
	<td class="sep1" style="width:1px"></td>
	<td class=small >
		<table border=0 cellspacing=0 cellpadding=0>
		<tr>
			<td>
				<table border=0 cellspacing=0 cellpadding=5>
				<tr>
					<td style="padding-right:0px"><a href="#"><img src="themes/blue/images/btnL3Add.gif" alt="Create {$MODULE}..." title="Create {$MODULE}..." border=0></a></td>
					<td style="padding-right:0px"><a href="#"><img src="themes/blue/images/btnL3Search.gif" alt="Search in {$MODULE}..." title="Search in {$MODULE}..." border=0></a></a></td>
				</tr>
				</table>
			</td>
			<td nowrap width=50>&nbsp;</td>
			<td>
				<table border=0 cellspacing=0 cellpadding=5>
				<tr>
					<td style="padding-right:0px"><a href="#"><img src="themes/blue/images/btnL3Calendar.gif" alt="Open Calendar..." title="Open Calendar..." border=0></a></a></td>
					<td style="padding-right:0px"><a href="#"><img src="themes/blue/images/btnL3Clock.gif" alt="Show World Clock..." title="Show World Clock..." border=0 onClick="fnvshobj(this,'wclock')"></a></a></td>
					<td style="padding-right:0px"><a href="#"><img src="themes/blue/images/btnL3Calc.gif" alt="Open Calculator..." title="Open Calculator..." border=0 onClick="fnvshobj(this,'calc')"></a></a></td>
				</tr>
				</table>
			</td>
			
			<td>
				<table border=0 cellspacing=0 cellpadding=5>
				<tr>
				</tr>
				</table>
			</td>
		</tr>
		</table>
	</td>
	{if $MODULE eq 'Contacts' || $MODULE eq 'Leads' || $MODULE eq 'Accounts' || $MODULE eq 'Potentials' || $MODULE eq 'Products' || $MODULE eq 'Notes' || $MODULE eq 'Emails'}
	<td class="sep1" style="width:1px"></td>
	<td nowrap style="width:50%;padding:10px">
		{if $MODULE ne 'Notes' && $MODULE ne 'Emails'}
			<a href="index.php?module={$MODULE}&action=Import&step=1&return_module={$MODULE}&return_action=index">Import {$MODULE}</a> | 
		{/if}
		<a href="index.php?module={$MODULE}&action=Export&all=1">Export {$MODULE}</a>
		{if $MODULE eq 'Contacts'}
			&nbsp;|&nbsp;<a href="index.php?module={$MODULE}&action=AddBusinessCard&return_module={$MODULE}&return_action=ListView">Add Business Card</a>
		{/if}
	</td>
	{else}
	<td nowrap style="width:50%;padding:10px">&nbsp;</td>
	{/if}	
	
</tr>
<tr><td style="height:2px"></td></tr>

</TABLE>

<!-- Contents -->
<table border=0 cellspacing=0 cellpadding=0 width=98% align=center>
<tr>
	<td valign=top><img src="themes/blue/images/showPanelTopLeft.gif"></td>
	<td class="showPanelBg" valign=top width=100%>
		<!-- PUBLIC CONTENTS STARTS-->
		<div class="small" style="padding:20px">
		
		
		 <span class="lvtHeaderText"><font color="purple">[ {$ID} ] </font>{$NAME} -  {$SINGLE_MOD} More Information</span> <br>
		 Updated 14 days ago (18 Nov 2005)
		 <hr noshade size=1>
		 <br> 
		
		<!-- Account details tabs -->
		<table border=0 cellspacing=0 cellpadding=0 width=95% align=center>
		<tr>
			<td>
				<table border=0 cellspacing=0 cellpadding=3 width=100% class="small">
				<tr>
					<td class="dvtTabCache" style="width:10px" nowrap>&nbsp;</td>
					{if $MODULE eq 'Activities'}
                                                <td class="dvtUnSelectedCell" align=center nowrap><a href="index.php?action=DetailView&module={$MODULE}&record={$ID}&activity_mode={$ACTIVITY_MODE}&parenttab={$CATEGORY}">{$SINGLE_MOD} Information</a></td>
                                        {else}
                                        <td class="dvtUnSelectedCell" align=center nowrap><a href="index.php?action=DetailView&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}">{$SINGLE_MOD} Information</a></td>
                                        {/if}
					<td class="dvtTabCache" style="width:10px">&nbsp;</td>
					<td class="dvtSelectedCell" align=center nowrap>More Information</td>
					<td class="dvtTabCache" style="width:100%">&nbsp;</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td valign=top align=left >
                           <table border=0 cellspacing=0 cellpadding=3 width=100% class="dvtContentSpace">
				<tr>

					<td align=left>
					<!-- content cache -->
					
					<table border=0 cellspacing=0 cellpadding=0 width=100%>
			                <tr>
					<td style="padding:10px">
					   <!-- General details -->
				        <table border=0 cellspacing=0 cellpadding=0 width=100%>

					{include file='RelatedListsHidden.tpl'}

					  {foreach key=header item=detail from=$RELATEDLISTS}
						<table border=0 cellspacing=0 cellpadding=0 width=100% class="small">
							<tr >
							<td  valign=bottom style="border-bottom:1px solid #999999;padding:5px;" ><b>{$header}</b></td>
							<td align=right style="border-bottom:1px solid #999999;padding:5px;">
							{if $header eq 'Potentials'}
						
							<input title="New Potential" accessyKey="F" class="small" onclick="this.form.action.value='EditView';this.form.module.value='Potentials'" type="submit" name="button" value="Add new Potential"></td>
							{elseif $header eq 'PriceBooks'}
							{if $MODULE eq 'Products'}
							<input title="Add to PriceBook" accessKey="" class="small" value="Add to PriceBook" LANGUAGE=javascript onclick="this.form.action.value='AddProductToPriceBooks';this.form.module.value='Products'"  type="submit" name="button">
							{/if}
							
							{elseif $header eq 'Products'}
							{if $MODULE eq 'PriceBooks'}
							<input title="Select Product" accessKey="" class="small" value="Select Product" LANGUAGE=javascript onclick="this.form.action.value='AddProductsToPriceBook';this.form.module.value='Products';this.form.return_module.value='Products';this.form.return_action.value='PriceBookDetailView'"  type="submit" name="button"></td>

							{else}
							<input title="New Product" accessyKey="F" class="small" onclick="this.form.action.value='EditView';this.form.module.value='Products';this.form.return_module.value='{$MODULE}';this.form.return_action.value='CallRelatedList'" type="submit" name="button" value="Add new Product"></td>				
							{/if}	
							{elseif $header eq 'Leads'}
                                                        <input title="New Lead" accessyKey="F" class="small" onclick="this.form.action.value='EditView';this.form.module.value='Leads'" type="submit" name="button" value="Add new Lead"></td>
							{elseif $header eq 'Contacts' }
							{if $MODULE eq 'Activities' || $MODULE eq 'Potentials' || $MODULE eq 'Vendors'}
                                                        <input title="Change" accessKey="" class="small" value="Select Contact" LANGUAGE=javascript onclick='return window.open("index.php?module=Contacts&return_module={$MODULE}&action=Popup&popuptype=detailview&select=enable&form=EditView&form_submit=false&recordid={$ID}","test","width=600,height=400,resizable=1,scrollbars=1");' type="button"  name="button"></td>
							{elseif $MODULE eq 'Emails'}
							<input title="Bulk Mail" accessykey="F" class="small" onclick="this.form.action.value='sendmail';this.form.return_action.value='DetailView';this.form.module.value='Emails';this.form.return_module.value='Emails';" name="button" value="Bulk Mails" type="submit">&nbsp;
                                                        <input title="Change" accessKey="" class="small" value="Select Contact" LANGUAGE=javascript onclick='return window.open("index.php?module=Contacts&return_module=Emails&action=Popup&popuptype=detailview&form=EditView&form_submit=false&recordid={$ID}","test","width=600,height=400,resizable=1,scrollbars=1");' type="button"  name="button"></td>
							{else}
                                                        <input title="New Contact" accessyKey="F" class="small" onclick="this.form.action.value='EditView';this.form.module.value='Contacts'" type="submit" name="button" value="Add new Contact"></td>
                                                        {/if}

							{elseif $header eq 'Activities'}
							{if $MODULE eq 'PurchaseOrder' || $MODULE eq 'Invoice' || $MODULE eq 'SalesOrder' || $MODULE eq 'Quotes'}	
							<input type="hidden" name="activity_mode">
							<input title="New Task" accessyKey="F" class="small" onclick="this.form.action.value='EditView'; this.form.return_action.value='CallRelatedList'; this.form.module.value='Activities'; this.form.return_module.value='{$MODULE}'; this.form.activity_mode.value='Task'" type="submit" name="button" value="Add new Task"></td>
							{else}
							<input type="hidden" name="activity_mode">
							<input title="New Task" accessyKey="F" class="small" onclick="this.form.action.value='EditView'; this.form.return_action.value='CallRelatedList'; this.form.module.value='Activities'; this.form.return_module.value='{$MODULE}'; this.form.activity_mode.value='Task'" type="submit" name="button" value="Add new Task">&nbsp;
							<input title="New Event" accessyKey="F" class="small" onclick="this.form.action.value='EditView'; this.form.return_action.value='CallRelatedList'; this.form.module.value='Activities'; this.form.return_module.value='{$MODULE}'; this.form.activity_mode.value='Events'" type="submit" name="button" value="Add new Event"></td>
							{/if}
							{elseif $header eq 'HelpDesk'}
							<input title="New Ticket" accessyKey="F" class="small" onclick="this.form.action.value='EditView';this.form.module.value='HelpDesk'" type="submit" name="button" value="Add new Ticket"></td>
							{elseif $header eq 'Attachments'}
							<input title="New Notes" accessyKey="F" class="small" onclick="this.form.action.value='EditView'; this.form.return_action.value='CallRelatedList'; this.form.module.value='Notes'" type="submit" name="button" value="Add new Note">&nbsp;
							<input type="hidden" name="fileid">
							<input title="New Attachment" accessyKey="F" class="small" onclick="this.form.action.value='upload'; this.form.module.value='uploads'" type="submit" name="button" value="Add new Attachment"></td>
							{elseif $header eq 'Quotes'}
							<input title="New Quote" accessyKey="F" class="small" onclick="this.form.action.value='EditView';this.form.module.value='Quotes'" type="submit" name="button" value="Add new Quote"></td>
							
							{elseif $header eq 'Invoice'}
							{if $MODULE eq 'SalesOrder'}
							<input type="hidden">
							{else}
							<input title="New Invoice" accessyKey="F" class="small" onclick="this.form.action.value='EditView';this.form.module.value='Invoice'" type="submit" name="button" value="Add new Invoice"></td>
							{/if}

							{elseif $header eq 'Sales Order'}
							{if $MODULE eq 'Quotes'}
							<input type="hidden">
							{else}
							<input title="New SalesOrder" accessyKey="F" class="small" onclick="this.form.action.value='EditView';this.form.module.value='SalesOrder'" type="submit" name="button" value="Add new Sales Order"></td>
							{/if}

							{elseif $header eq 'Purchase Order'}
							<input title="New Purchase Order" accessyKey="O" class="small" onclick="this.form.action.value='EditView'; this.form.module.value='PurchaseOrder'; this.form.return_module.value='{$MODULE}'; this.form.return_action.value='CallRelatedList'" type="submit" name="button" value="Add new Purchase Order"></td>
							
							{elseif $header eq 'Emails'}
							<input type="hidden" name="email_directing_module">
							<input type="hidden" name="record">
							<input title="New Email" accessyKey="F" class="small" onclick="this.form.action.value='EditView'; this.form.module.value='Emails'; this.form.email_directing_module.value='{$REDIR_MOD}'; this.form.record.value='{$id}'; this.form.return_action.value='CallRelatedList'" type="submit" name="button" value="Add new Email"></td>
							{elseif $header eq 'Users'}
                                                                {if $MODULE eq 'Activities'}
                                                                <input title="Change" accessKey="" tabindex="2" type="button" class="small" value="Select User" name="button" LANGUAGE=javascript onclick='return window.open("index.php?module=Users&return_module=Activities&return_action=CallRelatedList&activity_mode=Events&action=Popup&popuptype=detailview&form=EditView&form_submit=true&return_id={$id}&recordid={$ID}","test","width=600,height=400,resizable=1,scrollbars=1")';>
								{elseif $MODULE	eq 'Emails'}
								<input title="Bulk Mail" accessykey="F" class="small" onclick="this.form.action.value='sendmail';this.form.return_action.value='DetailView';this.form.module.value='Emails';this.form.return_module.value='Emails';" name="button" value="Bulk Mails" type="submit">&nbsp;
								<input title="Change" accesskey="" tabindex="2" class="small" value="Select User" name="Button" language="javascript" onclick='return window.open("index.php?module=Users&return_module=Emails&action=Popup&popuptype=detailview&form=EditView&form_submit=true&return_id={$id}&recordid={$ID}","test","width=600,height=400,resizable=1,scrollbars=1");' type="button">&nbsp;</td>
                                                                {/if}
							{elseif $header eq 'History'}
                                                        &nbsp;</td>

							{/if}
							</tr>
						</table>	
	                                        {if $detail ne ''} 
				      	   	  {foreach key=header item=detail from=$detail}
					      
							{if $header eq 'header'}
								<table border=0 cellspacing=1 cellpadding=3 width=100% style="background-color:#eaeaea;" class="small">
								<tr style="height:25px" bgcolor=white>	
							          {foreach key=header item=headerfields from=$detail}	
								    <td class="lvtCol">{$headerfields}</td> 
							          {/foreach}			
								</tr>
							{elseif $header eq 'entries'}	 
					    			{foreach key=header item=detail from=$detail}
								  <tr bgcolor=white>
							            {foreach key=header item=listfields from=$detail}
        	        	                                      <td>{$listfields}</td>
                	        	                            {/foreach}		
								  </tr>	
								{/foreach}
								</table>	 
		                                        {/if}	
						    {/foreach}
						
					   	{else}
							<table style="background-color:#eaeaea;color:eeeeee" border="0" cellpadding="3" cellspacing="1" width="100%" class="small">
							<tbody>
								<tr style="height: 25px;" bgcolor="white">
									<td><i>None included</i></td>
								</tr>
							</tbody>
						
							</table>
								
					   	{/if}		
				  	  <br><br>
					{/foreach}
						</form>
				</table>			
                    {*-- End of Blocks--*} 
			</td>
                </tr>
		</table>
		</td>
				</tr>
				</table>
				
			</td>
		</tr>
		</table>
		
			
			
		
		</div>
		<!-- PUBLIC CONTENTS STOPS-->
	</td>
	<td align=right valign=top><img src="themes/blue/images/showPanelTopRight.gif"></td>
</tr>
</table>

</td></tr></table>


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
					<td style="padding-right:0px"><a href="#"><img src="themes/blue/images/btnL3Clock.gif" alt="Show World Clock..." title="Show World Clock..." border=0></a></a></td>
					<td style="padding-right:0px"><a href="#"><img src="themes/blue/images/btnL3Calc.gif" alt="Open Calculator..." title="Open Calculator..." border=0></a></a></td>
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
	<td class="sep1" style="width:1px"></td>
	<td nowrap style="width:50%;padding:10px">
		<a href="#">Import {$MODULE}</a> | <a href="#">Export {$MODULE}</a>
	</td>
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
		
		
		 <span class="lvtHeaderText"><font color="purple">[ {$ID} ] </font>{$NAME} -  {$MODULE} More Information</span> <br>
		 Updated 14 days ago (18 Nov 2005)
		 <hr noshade size=1>
		 <br> 
		
		<!-- Account details tabs -->
		<table border=0 cellspacing=0 cellpadding=0 width=95% align=center>
		<tr>
			<td>
				<table border=0 cellspacing=0 cellpadding=3 width=100%>
				<tr>
					<td class="dvtTabCache" style="width:10px" nowrap>&nbsp;</td>
					<td class="dvtUnSelectedCell" align=center nowrap><a href="index.php?action=DetailView&module={$MODULE}&record={$ID}&parenttab={$CATEGORY}">{$MODULE} Information</a></td>
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
					{$HIDDEN}							
					  {foreach key=header item=detail from=$RELATEDLISTS}
						<table border=0 cellspacing=0 cellpadding=0 width=100% >
							<tr >
							<td  valign=bottom style="border-bottom:1px solid #999999;padding:5px;" ><b>{$header}</b> for {$NAME}</td>
							<td align=right style="border-bottom:1px solid #999999;padding:5px;">
							{if $header eq 'Potentials'}
						
							<input title="New Potential" accessyKey="F" class="small" onclick="this.form.action.value='EditView';this.form.module.value='Potentials'" type="submit" name="button" value="New Potential"></td>
							
							{elseif $header eq 'Contacts'}
							<input title="New Contact" accessyKey="F" class="small" onclick="this.form.action.value='EditView';this.form.module.value='Contacts'" type="submit" name="button" value="New Contact"></td>
							{elseif $header eq 'Activities'}
							<input type="hidden" name="activity_mode">
							<input title="New Task" accessyKey="F" class="small" onclick="this.form.action.value='EditView'; this.form.return_action.value='CallRelatedList'; this.form.module.value='Activities'; this.form.return_module.value='{$MODULE}'; this.form.activity_mode.value='Task'" type="submit" name="button" value="New Task">&nbsp;
							<input title="New Event" accessyKey="F" class="small" onclick="this.form.action.value='EditView'; this.form.return_action.value='CallRelatedList'; this.form.module.value='Activities'; this.form.return_module.value='{$MODULE}'; this.form.activity_mode.value='Events'" type="submit" name="button" value="New Event"></td>
							{elseif $header eq 'HelpDesk'}
							<input title="New Ticket" accessyKey="F" class="small" onclick="this.form.action.value='EditView';this.form.module.value='HelpDesk'" type="submit" name="button" value="New Ticket"></td>
							{elseif $header eq 'Attachments'}
							<input title="New Notes" accessyKey="F" class="small" onclick="this.form.action.value='EditView'; this.form.return_action.value='CallRelatedList'; this.form.module.value='Notes'" type="submit" name="button" value="Add new Note">&nbsp;
							<input type="hidden" name="fileid">
							<input title="New Attachment" accessyKey="F" class="small" onclick="this.form.action.value='upload'; this.form.module.value='uploads'" type="submit" name="button" value="Add new Attachment"></td>
							{elseif $header eq 'Quotes'}
							<input title="New Quote" accessyKey="F" class="small" onclick="this.form.action.value='EditView';this.form.module.value='Quotes'" type="submit" name="button" value="New Quote"></td>
							{elseif $header eq 'Invoice'}
							
							<input title="New Invoice" accessyKey="F" class="small" onclick="this.form.action.value='EditView';this.form.module.value='Invoice'" type="submit" name="button" value="New Invoice"></td>
							{elseif $header eq 'Sales Order'}
							<input title="New SalesOrder" accessyKey="F" class="small" onclick="this.form.action.value='EditView';this.form.module.value='SalesOrder'" type="submit" name="button" value="New Sales Order"></td>
							{elseif $header eq 'Purchase Order'}
							<input title="New Purchase Order" accessyKey="O" class="button" onclick="this.form.action.value='EditView'; this.form.module.value='PurchaseOrder'; this.form.return_module.value='{$MODULE}'; this.form.return_action.value='CallRelatedList'" type="submit" name="button" value="New Purchase Order"></td>
							{elseif $header eq 'Products'}
							<input title="New Product" accessyKey="F" class="small" onclick="this.form.action.value='EditView';this.form.module.value='Products';this.form.return_module.value='{$MODULE}';this.form.return_action.value='CallRelatedList'" type="submit" name="button" value="New Product"></td>
							{elseif $header eq 'Emails'}
							<input type="hidden" name="email_directing_module">
							<input type="hidden" name="record">
							<input title="New Email" accessyKey="F" class="small" onclick="this.form.action.value='EditView'; this.form.module.value='Emails'; this.form.email_directing_module.value='contacts'; this.form.record.value='{$id}'; this.form.return_action.value='CallRelatedList'" type="submit" name="button" value="New Email"></td>
							{/if}
							</tr>
						</table>	
	                                        {if $detail ne ''} 
				      	   	  {foreach key=header item=detail from=$detail}
					      
							{if $header eq 'header'}
								<table border=0 cellspacing=1 cellpadding=3 width=100% style="background-color:#eaeaea;">
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
							<table style="background-color:#eaeaea;color:eeeeee" border="0" cellpadding="3" cellspacing="1" width="100%">
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
		<td width=20% valign=top style="border-left:2px dashed #cccccc;padding:13px">
						<!-- right side relevant info -->

					<!-- Mail Merge-->
					<table border=0 cellspacing=0 cellpadding=0 width=100% class="rightMailMerge">
					<tr>
					<td class="rightMailMergeHeader"><b>{$WORDTEMPLATEOPTIONS}</b></td>
						</tr>
						<tr style="height:25px">
						<td class="rightMailMergeContent">
							<table border=0 cellspacing=0 cellpadding=2 width=100%>
								<tr>
								<td >
								<select class=small style="width:100%" name="mergefile">
									<option>Select template...</option>
									{html_options options=$TOPTIONS name=merge_option}
								</select>
								</td>
								</tr>
								<tr>
								<td >
  								[ <a href="#" onClick="showhide('mailMergeOptions')">Options...</a> ]
								<div id="mailMergeOptions" align=left style="display:none">
								<input type="checkbox" checked> Include Account Information <br>
								<input type="checkbox" checked> Include More Information <br>
								</div>
								</td>
								</tr>
								<tr>
								<td>
								{$MERGEBUTTON} 
								</td>
								</tr>
								</table>
							</td>

						</tr>
						</table>
						<br>

						
						<!-- Upcoming Activities / Calendar-->
						<table border=0 cellspacing=0 cellpadding=0 width=100% style="border:1px solid #ddddcc">
						<tr>
							<td style="border-bottom:1px solid #ddddcc;padding:5px;background-color:#ffffdd;"><b> Upcoming Activities :</b></td>
						</tr>
						<tr style="height:25px">
							<td style="padding:5px" bgcolor="#ffffef">
								<table border=0 cellspacing=0 cellpadding=2 width=100%>
								<tr><td valign=top >1.</td><td width=100% style="color:#727272"><b>API License renewal </b><br>On 23 Nov 2006 <br> <i>14 months 3 days to go</i></td></tr>
								<tr><td></td><td style="border-top:1px dotted #e2e2e2"></td></tr>
								</table>
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


<table width="100%" cellpadding="2" cellspacing="0" border="0">
<form action="index.php" method="post" name="DetailView" id="form">
<tr><td>&nbsp;</td>
	<td>
                <table cellpadding="0" cellspacing="5" border="0">
                        <input type="hidden" name="module" value="{$MODULE}">
                        <input type="hidden" name="record" value="{$ID}">
                        <input type="hidden" name="isDuplicate" value=false>
                        <input type="hidden" name="action">
                        <input type="hidden" name="return_module">
                        <input type="hidden" name="return_action">
                        <input type="hidden" name="return_id">
                        <input type="hidden" name="contact_id">
                        <input type="hidden" name="member_id">
                        <input type="hidden" name="opportunity_id">
                        <input type="hidden" name="case_id">
                        <input type="hidden" name="task_id">
                        <input type="hidden" name="meeting_id">
                        <input type="hidden" name="call_id">
                        <input type="hidden" name="email_id">
                        <input type="hidden" name="source_module">
                        <input type="hidden" name="entity_id">
		</table>	




<TABLE border=0 cellspacing=0 cellpadding=0 width=100% class=small>
<tr><td style="height:2px"></td></tr>
<tr>
	<td style="padding-left:10px;padding-right:10px" class="moduleName" nowrap>Sales > <a class="hdrLink" href="salesAccListView.html">{$MODULE}</a></td>
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
		
		
		 <span class="lvtHeaderText">{$NAME} -  {$SINGLE_MOD} Information</span> <br>
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
					{if $MODULE eq 'Notes'}
					<td class="dvtSelectedCell" align=center nowrap>{$SINGLE_MOD} Information</td>
					{else}
					<td class="dvtSelectedCell" align=center nowrap>{$SINGLE_MOD} Informartion</td>	
					<td class="dvtTabCache" style="width:10px">&nbsp;</td>
					<td class="dvtUnSelectedCell" align=center nowrap><a href="index.php?action=CallRelatedList&module={$MODULE}&record={$ID}">More Information</a></td>

					<td class="dvtTabCache" style="width:100%">&nbsp;</td>
					{/if}
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
						     {strip}<tr nowrap>
							<td  colspan=4 style="padding:5px">
								{$EDITBUTTON}
								{$DUPLICATEBUTTON}
								{$DELETEBUTTON}
					
							{if $MODULE ne 'Accounts' && $MODULE ne 'Potentials'}
							
								{$SENDMAILBUTTON}
						
							{/if}
							{if $MODULE eq 'Potentials'}
                                                                {$CONVERTINVOICE}
                                                        {/if}
							{if $MODULE eq 'Leads'}
                                                                {$CONVERTLEAD}
                                                        {/if}
							</td>


						     </tr>{/strip}	
							{foreach key=header item=detail from=$BLOCKS}
							<table border=0 cellspacing=0 cellpadding=0 width=100%>
						     <tr>{strip}
						     <td colspan=4 style="border-bottom:1px solid #999999;padding:5px;" bgcolor="#e5e5e5"><b>
						        {$header}
		        			     </b></td>{/strip}
					             </tr>
						   {foreach item=detail from=$detail}
						     <tr style="height:25px">
							{foreach key=label item=data from=$detail}
								{foreach key=value item=uitype from=$data}
								{if $label ne ''}
									<td class="dvtCellLabel" align=right width=25%>{$label}</td>
                                                        		<td class="dvtCellInfo" align=left >{$value}</td>
								{else}
									<td class="dvtCellLabel" align=right></td>
                                                        		<td class="dvtCellInfo" align=left ></td>
								{/if}	
								{/foreach}
                                                        {/foreach}

						      </tr>	
						   {/foreach}	
						     </table>
                     	                      </td>
					   </tr>
		<tr>                                                                                                               <td style="padding:10px">
			{/foreach}
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

</td></tr></table></form>

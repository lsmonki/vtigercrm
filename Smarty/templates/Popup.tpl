<link rel="stylesheet" type="text/css" href="{$THEME_PATH}style.css"/>
<table border=0 cellspacing=0 cellpadding=0 width=98% align=center>
	<tr>
	        <td valign=top><img src="{$IMAGE_PATH}showPanelTopLeft.gif"></td>

        	<td class="showPanelBg" valign=top width=100%>
                	<div class="small" style="padding:20px">
			       <form name="massdelete" method="POST">
		               <table border=0 cellspacing=1 cellpadding=0 width=100% class="lvtBg">
                		<tr style="background-color:#efefef">
                        		<td >
						<table border=0 cellspacing=0 cellpadding=2 width=100%>
		                                 <tr>
						  <script type="text/javascript" src="modules/{$MODULE}/{$SINGLE_MOD}.js"></script>
                  		                  <input name="module" type="hidden" value="Emails">
		                                  <input name="action" type="hidden" value="ChooseEmail">
                  		                  <input name="pmodule" type="hidden" value="{$MODULE}">
		                                  <input name="entityid" type="hidden" value="">
                        	                  <td style="padding-right:20px" nowrap>&nbsp;{$RECORD_COUNTS}</td>
			                          <td nowrap>{$NAVIGATION}</td>
						 </tr>
						</table>
						<div  style="overflow:auto;width:100%;height:300px; border-top:1px solid #999999;border-bottom:1px solid #999999">
			                        <table border=0 cellspacing=1 cellpadding=3 width=100% style="background-color:#cccccc;">
                        				<tr>
                        					{foreach item=header from=$LISTHEADER}
				                                  <td class="lvtCol">{$header}</td>
					                        {/foreach}
				                        </tr>
							{foreach key=entity_id item=entity from=$LISTENTITY}
			                                <tr bgcolor=white onMouseOver="this.className='lvtColDataHover'" onMouseOut="this.className='lvtColData'"  >
                                				{foreach item=data from=$entity}
			                                        <td>
                        			                        {$data}
			                                        </td>
                        				        {/foreach}
			                                </tr>
                        				{/foreach}
                        			</table>
                       				</div>
					</td>
				 </tr>
				</table>
				</form>    
			</div>
		</td>
	</tr>
</table>
				

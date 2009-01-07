<br>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
<tbody><tr>
	<td valign="top"><img src="{$IMAGE_PATH}showPanelTopLeft.gif"></td>
	<td valign="top" width="100%">
	<div align=center>
		<table border=0 cellspacing=0 cellpadding=5 width=100% class="settingsSelUITopLine">
		</table>
		
		<table border=0 cellspacing=0 cellpadding=5 width=100% class="tableHeading">
		<tr>
			<td class="small" align=right width="100%">
				<input title="edit" class="crmButton small edit" type="button" name="edit" onClick="displayEditView({$FIELDID});" value="{$APP.LBL_EDIT_BUTTON}">
			</td>
		</tr>
		</table>
		
		<div id="{$module}_fields" style="display:block">	
	 	<table cellspacing=0 cellpadding=5 width=100% class="listTable small">
			<tr>
        	<td valign=top width="25%" >
        	{if $COUNT eq 0}
        		No Fields Selected.
        		</td>
        	{else}
				{foreach item=label from=$LABELS name=itr}
					{assign var=count value=$smarty.foreach.itr.iteration}
					<table border=0 cellspacing=0 cellpadding=5 width=100% class=small>
						<tr>
							<td width="25%" onMouseOver="this.className='prvPrfHoverOn';" onMouseOut="this.className='prvPrfHoverOff';">
								<table cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<img src={$IMAGES}prvPrfSelectedTick.gif>
										&nbsp;
									</td>
									<td>
										{$label}
									</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					</td>
					<td>
					{if $count mod 4 eq 0}
						</td></tr><tr><td>
					{/if}
				{/foreach}
			{/if}
			</td>
	        </tr>
        </table>
		</div>
		</form>
		</div>
	</td>
	<td valign="top">
		<img src="{$IMAGE_PATH}showPanelTopRight.gif">
	</td>
	</tr>
</tbody>
</table>

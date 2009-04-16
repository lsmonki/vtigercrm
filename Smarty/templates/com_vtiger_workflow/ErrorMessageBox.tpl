<div id="error_message_box" class="layerPopup"  style="display:none">
	<table width="100%" cellspacing="0" cellpadding="5" border="0" class="layerHeadingULine">
		<tr>
			<td width="60%" align="left" class="layerPopupHeading">
				{$MOD.LBL_VALIDATION_ERROR}
			</td>
			<td width="40%" align="right">
				<a href="javascript:void(0);" id="error_message_box_close">
					<img border="0" align="absmiddle" src="{'close.gif'|@vtiger_imageurl:$THEME}"/>
				</a>
			</td>
		</tr>
	</table>
	<div class="popup_content">
		<ol>
			<li id="empty_fields_message" style="display:none">
				{$MOD.LBL_VALIDATION_MISSING_MANDATORY_FIELDS}
			</li>
			<li id="invalid_date_range_message" style="display:none">
				{$MOD.LBL_VALIDATION_INVALID_DATE_RANGE}
			</li>
		</ol>
	</div>
</div>
	

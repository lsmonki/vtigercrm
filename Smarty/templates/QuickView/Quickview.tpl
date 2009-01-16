<script language="JavaScript" type="text/javascript" src="include/js/quickview.js"></script>

<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
<tbody><tr>
	<td valign="top"><img src="{$IMAGE_PATH}showPanelTopLeft.gif"></td>
	<td class="showPanelBg" style="padding: 10px;" valign="top" width="100%">
	<br>

	<div align=center>
		{include file='SetMenu.tpl'}
		<table class="settingsSelUITopLine" border="0" cellpadding="5" cellspacing="0" width="100%">
		<tbody>
			<tr>
				<td rowspan="2" valign="top" width="50"><img src="{$IMAGES}quickview.png" alt="{$MOD.LBL_USERS}" title="{$MOD.LBL_USERS}" border="0" height="48" width="48"></td>
				<td class="heading2" valign="bottom"><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS}</a> &gt; {$MOD.LBL_TOOLTIP_MANAGEMENT}</b></td>
			</tr>
	
			<tr>
				<td class="small" valign="top">{$MOD.LBL_TOOLTIP_MANAGEMENT_DESCRIPTION}</td>
			</tr>
		</tbody>
		</table>
		
		<br>
		<table border="0" cellpadding="10" cellspacing="0" width="100%">
		<tbody>
			<tr>
			<td>
	
			<table class="tableHeading" border="0" cellpadding="5" cellspacing="0" width="100%">
			<tbody><tr>
				<td width='20%'>
					<strong><span id="module_info">{$APP.LBL_SELECT} {$APP.LBL_MODULE}: </span></strong>
				</td>
				<td id='module_pick_list'>
					<select name="pick_module" id="pick_module" class="importBox" onChange="getFieldInfo(this)">
						<option value="" disabled="true" selected>
							{$APP.LBL_SELECT} {$APP.LBL_MODULE}
						</option>
						{foreach key=sel_value item=value from=$MODULES}
						    <option value="{$sel_value}">
								{$value}
							</option>
						{/foreach}
					</select>
				</td>
				</tr>
			</tbody>
			</table>
			
			<table class="tableHeading" border="0" cellpadding="5" cellspacing="0" width="100%">
			<tbody><tr>
				<td width='20%'>
					<strong><span id="field_info">{$APP.LBL_SELECT} Field: </span></strong>
				</td>
				<td id='pick_field_list'>
				</td>
				</tr>
			</tbody>
			</table>
			
			
			<div id="fieldList">
		    </div>	
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
	</td>
    <td valign="top"><img src="{$IMAGE_PATH}showPanelTopRight.gif"></td>
    </tr>
</tbody>
</table>
<br>

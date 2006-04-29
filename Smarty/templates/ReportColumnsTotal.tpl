<table class="small" bgcolor="#ffffff" border="0" cellpadding="5" cellspacing="0" width="100%">
	<tbody><tr>
	<td colspan="2">
	<span class="genHeaderGray">Calculations</span><br>
	Select Columns To Total
	<hr>
	</td>
	</tr>
	<tr><td colspan="2">
	<div style="overflow:auto;height:416px">
	<table style="background-color: rgb(204, 204, 204);" class="small" border="0" cellpadding="5" cellspacing="1" width="100%">
		<tbody>
		<tr>	
		<td class="lvtCol" nowrap width="40%">{$MOD.LBL_COLUMNS}</td>
		<td class="lvtCol" nowrap width="15%">{$MOD.LBL_COLUMNS_SUM}</td>
		<td class="lvtCol" nowrap width="15%">{$MOD.LBL_COLUMNS_AVERAGE}</td>
		<td class="lvtCol" nowrap width="15%">{$MOD.LBL_COLUMNS_LOW_VALUE}</td>
		<td class="lvtCol" nowrap width="15%">{$MOD.LBL_COLUMNS_LARGE_VALUE}</td>
		</tr>
		{foreach item=modules from=$BLOCK1}
		{foreach item=row from=$modules}
		<tr class="lvtColData" onmouseover="this.className='lvtColDataHover'" onmouseout="this.className='lvtColData'" bgcolor="white">
		<td><b>{$row.0}</b></td>
		<td>{$row.1}</td>
		<td>{$row.2}</td>
		<td>{$row.3}</td>
		<td>{$row.4}</td>
		</tr>
		{/foreach}
		{/foreach}
		</tbody>
	</table>
	</div>
	</td></tr>
	</tbody>
</table>

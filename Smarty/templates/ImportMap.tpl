{assign var="Firstrow" value=$FIRSTROW}
	<table border="0" style="background-color: rgb(204, 204, 204);" class="small" cellpadding="4" cellspacing="1" width="100%">
		{foreach name=iter item=row from=$Firstrow}
		{assign var="counter" value=$smarty.foreach.iter.iteration}
		{math assign="num" equation="x - y" x=$counter y=1}
		<tr bgcolor="white">
			<td class="lvtCol" align="center">
				{$SELECTFIELD[$counter]}
			</td>
		</tr>
		{/foreach}
	</table>



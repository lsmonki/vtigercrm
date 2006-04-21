{foreach item=row from=$BLOCKS}	
{foreach item=elements key=title from=$row}	
{if $title eq 'Subject'}
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr><td width="20%" align="right"><b>From :</b></td><td width="2%">&nbsp;</td><td>&nbsp;</td></tr>
	<tr><td align="right">CC :</td><td>&nbsp;</td><td>&nbsp;</td></tr>
	<tr><td align="right">BCC : </td><td>&nbsp;</td><td>&nbsp;</td></tr>
	<tr><td align="right"><b>Subject  :</b></td><td>&nbsp;</td><td>{$BLOCKS.3.Subject.value}</td></tr>
	<tr><td align="right" style="border-bottom:1px solid #666666;" colspan="3">&nbsp;</td></tr>
</table>
{elseif $title eq 'Description'}
<div>
{$BLOCKS.4.Description.value}
</div>
{/if}
{/foreach}
{/foreach}

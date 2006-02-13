
<h1>{L_WEBLOG_MANAGEMENT}</h1>

<p>{L_WEBLOG_MANAGEMENT_EXPLAIN}</p>

<br/>
<table width="100%" cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
	<tr>
	   <th class="thHead" align="center" colspan="6">{L_WEBLOGS}</th>
	</tr>
	<tr>
	   <td class="catLeft" align="left" colspan="3"><span class="cattitle">{L_WEBLOG}</span></td>
	   <td class="catLeft" align="left"><span class="cattitle">{L_WEBLOG_OWNER}</span></td>
	   <td class="catLeft" align="left" colspan="2"></td>
	</tr>
    <!-- BEGIN weblog_row -->
	<tr><td class="row1" colspan="3"><span class="gen"><a href="{weblog_row.U_WEBLOG}" target="_blank" class="gen">{weblog_row.WEBLOG_NAME}</a></span><br/>
		<span class="gensmall">{weblog_row.WEBLOG_DESC}</span></td>
   		<td class="row1" align="center" width="200" nowrap>
            <a href="{weblog_row.U_OWNER}" target="_blank" class="gen">{weblog_row.WEBLOG_OWNER}</a>
       	</td>
   		<td class="row2" align="center" width="50" nowrap>
            <a href="{weblog_row.U_EDIT}" class="gen">{L_WEBLOG_EDIT}</a>
       	</td>
   		<td class="row1" align="center" width="50" nowrap>
            <a href="{weblog_row.U_REMOVE}" class="gen">{L_WEBLOG_REMOVE}</a>
       	</td>
    </tr>
    <!-- END weblog_row -->
</table>
</form>
<br/>
<br/>

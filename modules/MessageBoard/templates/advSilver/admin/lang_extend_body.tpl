<h1>{L_TITLE}</h1>

<p>{L_TITLE_EXPLAIN}</p>

<form action="{S_ACTION}" name="post" method="post">
<table cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
<tr>
	<th nowrap="nowrap" colspan="3">{L_PACK}</th>
</tr>
<!-- BEGIN row -->
<tr>
	<td class="{row.COLOR}"><span class="gen">{row.PACK}</span></td>
	<td class="{row.COLOR}" nowrap="nowrap"><a href="{row.U_PACK_NORMAL}" class="gen">{L_NORMAL}</a></td>
	<td class="{row.COLOR}" nowrap="nowrap"><a href="{row.U_PACK_ADMIN}" class="gen">{L_ADMIN}</a></td>
</tr>
<!-- END row -->
<!-- BEGIN none -->
<tr>
	<td class="row1" align="center" colspan="3"><span class="gen">{L_NONE}</span>
</tr>
<!-- END none -->
</table>

<br class="gen" />
<table cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
<tr>
	<th colspan="2">{L_SEARCH}</th>
</tr>
<tr>
	<td class="row1" valign="top"><span class="row1">{L_SEARCH_WORDS}</span><span class="gensmall"><br />{L_SEARCH_WORDS_EXPLAIN}<br /></span></td>
	<td class="row2">
		<span class="gen">
			<input type="post" name="search_words" value="{SEARCH_WORDS}" size="64" class="post" /><br />
			<input type="radio" name="search_logic" value="0" {SEARCH_ALL} />{L_SEARCH_ALL}&nbsp;&nbsp;<input type="radio" name="search_logic" value="1" {SEARCH_ONE} />{L_SEARCH_ONE}
		</span>
	</td>
</tr>
<tr>
	<td class="row1" valign="top"><span class="row1">{L_SEARCH_IN}</span><span class="gensmall"><br />{L_SEARCH_IN_EXPLAIN}<br /></span></td>
	<td class="row2">
		<span class="gen">
			&nbsp;<input type="radio" name="search_in" value="0" {SEARCH_IN_KEY} />{L_SEARCH_IN_KEY}<br />
			&nbsp;<input type="radio" name="search_in" value="1" {SEARCH_IN_VALUE} />{L_SEARCH_IN_VALUE}<br />
			&nbsp;<input type="radio" name="search_in" value="2" {SEARCH_IN_BOTH} />{L_SEARCH_IN_BOTH}<hr />
			&nbsp;{S_LANGUAGES}&nbsp;<input type="radio" name="search_admin" value="0" {SEARCH_LEVEL_ADMIN} />{L_SEARCH_LEVEL_ADMIN}&nbsp;<input type="radio" name="search_admin" value="1" {SEARCH_LEVEL_NORMAL} />{L_SEARCH_LEVEL_NORMAL}&nbsp;<input type="radio" name="search_admin" value="2" checked="checked" {SEARCH_LEVEL_BOTH} />{L_SEARCH_LEVEL_BOTH}&nbsp;
		</span>
	</td>
</tr>
<tr>
	<td class="catBottom" align="center" colspan="2">
		<input type="submit" name="submit" class="mainoption" value="{L_SUBMIT}" />
	</td>
</tr>
</table>
{S_HIDDEN_FIELDS}</form>
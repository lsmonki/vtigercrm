
<h1>{L_WEBLOG_CONFIG_TITLE}</h1>

<p>{L_WEBLOG_CONFIG_EXPLAIN}</p>

<form action="{S_CONFIG_ACTION}" method="post"><table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr>
	  <th class="thHead" colspan="2">{L_WEBLOG_MAIN}</th>
	</tr>
	<tr>
		<td class="row1">{L_WEBLOG_DISPLAY_STATS}<br /><span class="gensmall">{L_WEBLOG_DISPLAY_STATS_EXPLAIN}</span></td>
		<td class="row2"><input type="radio" name="display_stats" value="1" {S_DISPLAY_STATS_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="display_stats" value="0" {S_DISPLAY_STATS_NO} /> {L_NO}</td>
	</tr>
	<tr>
		<td class="row1">{L_WEBLOGS_PER_PAGE}</span></td>
		<td class="row2">{WEBLOGS_PER_PAGE_SELECT}</td>
	</tr>
	<tr>
		<td class="row1">{L_WEBLOG_SORT}<br /></td>
		<td class="row2">{WEBLOG_SORT_SELECT}</td>
	</tr>
	<tr>
		<td class="row1">{L_WEBLOG_ORDER}<br /></td>
		<td class="row2"><input type="radio" name="display_order" value="desc" {S_DISPLAY_ORDER_DESC} /> {L_WEBLOG_DESCENDING}&nbsp;&nbsp;<input type="radio" name="display_order" value="asc" {S_DISPLAY_ORDER_ASC} /> {L_WEBLOG_ASCENDING}</td>
	</tr>
	<tr>
		<td class="row1">{L_WEBLOG_CREATE}<br /><span class="gensmall">{L_WEBLOG_CREATE_EXPLAIN}</span></td>
		<td class="row2"><input type="radio" name="weblog_create" value="1" {S_WEBLOG_CREATE_GROUP} /> {L_WEBLOG_CREATE_GROUP}&nbsp;&nbsp;<input type="radio" name="weblog_create" value="0" {S_WEBLOG_CREATE_ALL} /> {L_WEBLOG_CREATE_ALL}</td>
	</tr>
	<tr>
		<th class="thHead" colspan="2">{L_WEBLOG_MYWEBLOG}</th>
	</tr>
	<tr>
		<td class="row1">{L_WEBLOG_ACCESS}</td>
		<td class="row2">{WEBLOG_ACCESS_SELECT}</td>
	</tr>
	<tr>
		<td class="row1">{L_WEBLOG_ENTRIES}</td>
		<td class="row2"><input class="post" type="text" maxlength="2" size="3" name="weblog_entries" value="{WEBLOG_ENTRIES}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_WEBLOG_NAME}<br /><span class="gensmall">{L_WEBLOG_NAME_EXPLAIN}</span></td>
		<td class="row2"><input class="post" type="text" maxlength="255" name="weblog_name" value="{WEBLOG_NAME}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_WEBLOG_DESC}<br /><span class="gensmall">{L_WEBLOG_DESC_EXPLAIN}</span></td>
		<td class="row2"><input class="post" type="text" maxlength="255" name="weblog_desc" value="{WEBLOG_DESC}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_WEBLOG_COMMENT}</td>
		<td class="row2"><input class="post" type="text" maxlength="255" name="weblog_comment" value="{WEBLOG_COMMENT}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_WEBLOG_POST_COMMENT}</td>
		<td class="row2"><input class="post" type="text" maxlength="255" name="weblog_post_comment" value="{WEBLOG_POST_COMMENT}" /></td>
	</tr>
	<tr>
		<td class="row1">{L_WEBLOG_ADVANCED}<span class="gensmall">{L_WEBLOG_ADVANCED_EXPLAIN}</span></td>
		<td class="row2"><input type="radio" name="weblog_advanced" value="1" {S_WEBLOG_ADVANCED_EASY} /> {L_EASY}&nbsp;&nbsp;<input type="radio" name="weblog_advanced" value="2" {S_WEBLOG_ADVANCED} /> {L_ADVANCED}&nbsp;&nbsp;<input type="radio" name="weblog_advanced" value="0" {S_WEBLOG_ADVANCED_USER} /> {L_LET_USER}</td>
	</tr>
	<tr>
		<th class="thHead" colspan="2">{L_MOD_SUPPORT}</th>
	</tr>
	<tr>
		<td class="row1">{L_BIRTHDAY_MOD_INSTALLED}</td>
		<td class="row2"><input type="radio" name="birthday_mod" value="1" {S_BIRTHDAY_MOD_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="birthday_mod" value="0" {S_BIRTHDAY_MOD_NO} /> {L_NO}</td>
	</tr>
	<tr>
		<td class="row1">{L_BIRTHDAY_ZODIAC_MOD_INSTALLED}</td>
		<td class="row2"><input type="radio" name="birthday_zodiac_mod" value="1" {S_BIRTHDAY_ZODIAC_MOD_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="birthday_zodiac_mod" value="0" {S_BIRTHDAY_ZODIAC_MOD_NO} /> {L_NO}</td>
	</tr>
	<tr>
		<td class="row1">{L_GENDER_MOD_INSTALLED}</td>
		<td class="row2"><input type="radio" name="gender_mod" value="1" {S_GENDER_MOD_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="gender_mod" value="0" {S_GENDER_MOD_NO} /> {L_NO}</td>
	</tr>
	<tr>
		<td class="row1">{L_LAST_VISIT_MOD_INSTALLED}</td>
		<td class="row2"><input type="radio" name="last_visit_mod" value="1" {S_LAST_VISIT_MOD_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="last_visit_mod" value="0" {S_LAST_VISIT_MOD_NO} /> {L_NO}</td>
	</tr>
	<tr>
		<td class="catBottom" colspan="2" align="center">{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />&nbsp;&nbsp;<input type="reset" value="{L_RESET}" class="liteoption" />
		</td>
	</tr>
</table></form>

<br clear="all" />

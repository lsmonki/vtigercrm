<table>
  <tr>
    <td align="left" valign="middle" class="nav" width="100%">
      <span class="nav">&nbsp;&nbsp;&nbsp;<a href="{U_INDEX}" class="nav">{L_INDEX}</a>
        </span>
    </td>
  </tr>
</table>
<hr>

<form action="{S_FORM_ACTION}" method="post">
<input type="hidden" name="total_forums" value="{H_TOTAL_FORUMS}">
<table width="98%" cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
	<tr>
	  <th class="thHead" colspan="2">{L_TOPICS_ANYWHERE}</th>
	</tr>
	<tr>
	  <td class="row2" colspan="2"><span class="gensmall">{L_TOPICS_ANYWHERE_EXPLAIN}</span></td>
	</tr>
	<tr>
	  <td class="row1" width="45%" valign="top"><span class="gen">{L_OUTPUT}:</span><br /><span class="gensmall">{L_OUTPUT_EXPLAIN}</span></td>
	  <td class="row2">
		{OUTPUT_SELECT}
	  </td>
	</tr>
	<tr>
	  <td class="row1" valign="top"><span class="gen">{L_SELECT_FORUM}:</span><br /><span class="gensmall">{L_SELECT_FORUM_EXPLAIN}</span></td>
	  <td class="row2">
	    <span class="gen">{SELECT_FORUM_SELECT} &nbsp;&nbsp;<b>{L_OR}</b>&nbsp;&nbsp;<input type="checkbox" name="allfora">&nbsp;{L_CHECK_ALLFORA}</span>
	  </td>
	</tr>
	<tr>
	  <td class="row1" valign="top"><span class="gen">{L_AMOUNT_TOPICS}:</span><br /><span class="gensmall">{L_AMOUNT_TOPICS_EXPLAIN}</span></td>
	  <td class="row2">
		<input type="text" class="post" name="amount_topics" size="10" maxlength="2" value="10" />
	  </td>
	</tr>
	<tr>
	  <td class="row1" valign="top"><span class="gen">{L_TOPICS_LIFESPAN}:</span><br /><span class="gensmall">{L_TOPICS_LIFESPAN_EXPLAIN}</span></td>
	  <td class="row2">
            <span class="gen"><input type="checkbox" name="noreply">&nbsp;{L_TOPICS_LIFESPAN_NOREPLY}&nbsp;&nbsp;<input type="text" class="post" name="noreply_timespan" size="5" maxlength="3" value="0" />&nbsp;&nbsp;<select name="noreply_unit"><option value="hour">{L_HOURS}</option><option value="day">{L_DAYS}</option></select>
            <br /><input type="checkbox" name="startdate">&nbsp;{L_TOPICS_LIFESPAN_STARTDATE}&nbsp;&nbsp;<input type="text" class="post" name="startdate_timespan" size="5" maxlength="3" value="0" />&nbsp;&nbsp;<select name="startdate_unit"><option value="hour">{L_HOURS}</option><option value="day">{L_DAYS}</option></select></span>
	  </td>
	</tr>
	<tr>
	  <td class="row1" valign="top"><span class="gen">{L_JUMP_LAST_POST}:</span><br /><span class="gensmall">{L_JUMP_LAST_POST_EXPLAIN}</span></td>
	  <td class="row2">
		<input type="radio" name="jump_last_post" value="1" />
		<span class="gen">{L_YES}</span>&nbsp;&nbsp;
		<input type="radio" name="jump_last_post" value="0" checked />
		<span class="gen">{L_NO}</span>
	  </td>
	</tr>
	<tr>
	  <td class="row1" valign="top"><span class="gen">{L_SHOW_FORUM_NAME}:</span><br /><span class="gensmall">{L_SHOW_FORUM_NAME_EXPLAIN}</span></td>
	  <td class="row2">
		<input type="radio" name="show_forum_name" value="1" checked />
		<span class="gen">{L_YES}</span>&nbsp;&nbsp;
		<input type="radio" name="show_forum_name" value="0" />
		<span class="gen">{L_NO}</span>
	  </td>
	</tr>
	<tr>
	  <td class="row1" valign="top"><span class="gen">{L_LINK_FORUM_NAME}:</span><br /><span class="gensmall">{L_LINK_FORUM_NAME_EXPLAIN}</span></td>
	  <td class="row2">
		<input type="radio" name="link_forum_name" value="1" checked />
		<span class="gen">{L_YES}</span>&nbsp;&nbsp;
		<input type="radio" name="link_forum_name" value="0" />
		<span class="gen">{L_NO}</span>
	  </td>
	</tr>
	<tr>
	  <td class="row1" valign="top"><span class="gen">{L_SHOW_REPLIES}:</span><br /><span class="gensmall">{L_SHOW_REPLIES_EXPLAIN}</span></td>
	  <td class="row2">
		<input type="radio" name="show_replies" value="1" checked />
		<span class="gen">{L_YES}</span>&nbsp;&nbsp;
		<input type="radio" name="show_replies" value="0" />
		<span class="gen">{L_NO}</span>
	  </td>
	</tr>
	<tr>
	  <td class="row1" valign="top"><span class="gen">{L_SHOW_REPLIES_WORD}:</span><br /><span class="gensmall">{L_SHOW_REPLIES_WORD_EXPLAIN}</span></td>
	  <td class="row2">
		<input type="radio" name="show_replies_word" value="1" checked />
		<span class="gen">{L_YES}</span>&nbsp;&nbsp;
		<input type="radio" name="show_replies_word" value="0" />
		<span class="gen">{L_NO}</span>
	  </td>
	</tr>
	<tr>
	  <td class="row1" valign="top"><span class="gen">{L_SHOW_REGULAR}:</span></td>
	  <td class="row2">
		<input type="radio" name="show_regular" value="1" checked />
		<span class="gen">{L_YES}</span>&nbsp;&nbsp;
		<input type="radio" name="show_regular" value="0" />
		<span class="gen">{L_NO}</span>
	  </td>
	</tr>
	<tr>
	  <td class="row1" valign="top"><span class="gen">{L_SHOW_ANNOUNCEMENTS}:</span></td>
	  <td class="row2">
		<input type="radio" name="show_announce" value="1" />
		<span class="gen">{L_YES}</span>&nbsp;&nbsp;
		<input type="radio" name="show_announce" value="0" checked />
		<span class="gen">{L_NO}</span>
	  </td>
	</tr>
	<tr>
	  <td class="row1" valign="top"><span class="gen">{L_SHOW_STICKYS}:</span></td>
	  <td class="row2">
		<input type="radio" name="show_sticky" value="1" />
		<span class="gen">{L_YES}</span>&nbsp;&nbsp;
		<input type="radio" name="show_sticky" value="0" checked />
		<span class="gen">{L_NO}</span>
	  </td>
	</tr>
	<tr>
	  <td class="row1" valign="top"><span class="gen">{L_SHOW_LOCKED}:</span></td>
	  <td class="row2">
		<input type="radio" name="show_locked" value="1" />
		<span class="gen">{L_YES}</span>&nbsp;&nbsp;
		<input type="radio" name="show_locked" value="0" checked />
		<span class="gen">{L_NO}</span>
	  </td>
	</tr>
	<tr>
	  <td class="row1" valign="top"><span class="gen">{L_SHOW_MOVED}:</span></td>
	  <td class="row2">
		<input type="radio" name="show_moved" value="1" />
		<span class="gen">{L_YES}</span>&nbsp;&nbsp;
		<input type="radio" name="show_moved" value="0" checked />
		<span class="gen">{L_NO}</span>
	  </td>
	</tr>
	<tr>
	  <td class="row1" valign="top"><span class="gen">{L_HIDE_LABELS}:</span><br /><span class="gensmall">{L_HIDE_LABELS_EXPLAIN}</span></td>
	  <td class="row2">
	  <span class="gen">
	  	<input type="checkbox" name="hidelabel['a']">&nbsp;{O_ANNOUNCEMENT}<br />
	  	<input type="checkbox" name="hidelabel['s']">&nbsp;{O_STICKY}<br />
	  	<input type="checkbox" name="hidelabel['m']">&nbsp;{O_MOVED}<br />
	  	<input type="checkbox" name="hidelabel['p']">&nbsp;{O_POLL}<br />
	  	<input type="checkbox" name="hidelabel['l']">&nbsp;{O_LOCKED}
	  </span>
	  </td>
	</tr>
	<tr>
	  <td class="row1" valign="top"><span class="gen">{L_SORT_ORDER}:</span><br /><span class="gensmall">{L_SORT_ORDER_EXPLAIN}</span></td>
	  <td class="row2">
		<select name="sort_order">
		<option value="priority">{O_PRIORITY}</option>
		<option value="ondate">{O_ON_DATE}</option>
		</select>
	  </td>
	</tr>
	<tr>
	  <td class="row1" valign="top"><span class="gen">{L_BULLET_TYPE}:</span><br /><span class="gensmall">{L_BULLET_TYPE_EXPLAIN}</span></td>
	  <td class="row2">
		{BULLET_TYPE_SELECT}
	  </td>
	</tr>
	<tr>
	  <td class="row1" valign="top"><span class="gen">{L_SHOW_LASTPOSTBY}:</span><br /><span class="gensmall">{L_SHOW_LASTPOSTBY_EXPLAIN}</span></td>
	  <td class="row2">
		<input type="radio" name="show_lastpostby" value="1" checked />
		<span class="gen">{L_YES}</span>&nbsp;&nbsp;
		<input type="radio" name="show_lastpostby" value="0" />
		<span class="gen">{L_NO}</span>
	  </td>
	</tr>
	<tr>
	  <td class="row1" valign="top"><span class="gen">{L_LASTPOSTBY_FORMAT}:</span><br /><span class="gensmall">{L_LASTPOSTBY_FORMAT_EXPLAIN}</span></td>
	  <td class="row2">
		{LASTPOSTBY_FORMAT_SELECT}
	  </td>
	</tr>
	<tr>
	  <td class="row1" valign="top"><span class="gen">{L_SHOW_LASTPOSTDATE}:</span><br /><span class="gensmall">{L_SHOW_LASTPOSTDATE_EXPLAIN}</span></td>
	  <td class="row2">
		<input type="radio" name="show_lastpostdate" value="1" checked />
		<span class="gen">{L_YES}</span>&nbsp;&nbsp;
		<input type="radio" name="show_lastpostdate" value="0" />
		<span class="gen">{L_NO}</span>
	  </td>
	</tr>
	<tr>
	  <td class="row1" valign="top"><span class="gen">{L_LASTPOSTDATE_FORMAT}:</span><br /><span class="gensmall">{L_LASTPOSTDATE_FORMAT_EXPLAIN}</span></td>
	  <td class="row2">
		{LASTPOSTDATE_FORMAT_SELECT}
	  </td>
	</tr>
	<tr>
	  <td class="row1" valign="top"><span class="gen">{L_SHOW_LASTPOSTICON}:</span><br /><span class="gensmall">{L_SHOW_LASTPOSTICON_EXPLAIN}</span></td>
	  <td class="row2">
		<input type="radio" name="show_lastposticon" value="1" checked />
		<span class="gen">{L_YES}</span>&nbsp;&nbsp;
		<input type="radio" name="show_lastposticon" value="0" />
		<span class="gen">{L_NO}</span>
	  </td>
	</tr>
	<tr>
	  <td class="row1" valign="top"><span class="gen">{L_LASTPOSTICON_AS_BULLET}:</span><br /><span class="gensmall">{L_LASTPOSTICON_AS_BULLET_EXPLAIN}</span></td>
	  <td class="row2">
		<input type="radio" name="lastposticon_bullet" value="1" />
		<span class="gen">{L_YES}</span>&nbsp;&nbsp;
		<input type="radio" name="lastposticon_bullet" value="0" checked/>
		<span class="gen">{L_NO}</span>
	  </td>
	</tr>
	<tr>
	  <td class="row1" valign="top"><span class="gen">{L_ADD_BREAK}:</span><br /><span class="gensmall">{L_ADD_BREAK_EXPLAIN}</span></td>
	  <td class="row2">
		<input type="radio" name="add_break" value="1" />
		<span class="gen">{L_YES}</span>&nbsp;&nbsp;
		<input type="radio" name="add_break" value="0" checked />
		<span class="gen">{L_NO}</span>
	  </td>
	</tr>
	<tr>
	  <td class="row1" valign="top"><span class="gen">{L_ADD_BLANK_LINE}:</span><br /><span class="gensmall">{L_ADD_BLANK_LINE_EXPLAIN}</span></td>
	  <td class="row2">
		<input type="radio" name="add_blank_line" value="1" />
		<span class="gen">{L_YES}</span>&nbsp;&nbsp;
		<input type="radio" name="add_blank_line" value="0" checked />
		<span class="gen">{L_NO}</span>
	  </td>
	</tr>
	<tr>
	  <td class="row1" valign="top"><span class="gen">{L_LIMIT_LENGTH}:</span><br /><span class="gensmall">{L_LIMIT_LENGTH_EXPLAIN}</span></td>
	  <td class="row2">
		<input type="text" class="post" name="limit_length" size="10" maxlength="3" value="0" />
	  </td>
	</tr>
	<tr>
	  <td class="row1" valign="top"><span class="gen">{L_LIMIT_WHERE}:</span><br /><span class="gensmall">{L_LIMIT_WHERE_EXPLAIN}</span></td>
	  <td class="row2">
		<input type="radio" name="limit_where" value="p" />
		<span class="gen">{L_PREVIOUS_SPACE}</span>&nbsp;&nbsp;
		<input type="radio" name="limit_where" value="e" checked />
		<span class="gen">{L_EXACT}</span>&nbsp;&nbsp;
		<input type="radio" name="limit_where" value="n" />
		<span class="gen">{L_NEXT_SPACE}</span>
	  </td>
	</tr>
	<tr>
	  <td class="row1" valign="top"><span class="gen">{L_CSS_LINK}:</span><br /><span class="gensmall">{L_CSS_LINK_EXPLAIN}</span></td>
	  <td class="row2">
		<input type="text" class="post" name="css_link" size="35" maxlength="25" value="" />
	  </td>
	</tr>
	<tr>
	  <td class="row1" valign="top"><span class="gen">{L_CSS_TEXT}:</span><br /><span class="gensmall">{L_CSS_TEXT_EXPLAIN}</span></td>
	  <td class="row2">
		<input type="text" class="post" name="css_text" size="35" maxlength="25" value="" />
	  </td>
	</tr>
	<tr>
	  <td class="row1" valign="top"><span class="gen">{L_TARGET_LINK}:</span><br /><span class="gensmall">{L_TARGET_LINK_EXPLAIN}</span></td>
	  <td class="row2">
		<input type="text" class="post" name="target_link" size="35" maxlength="10" value="" />
	  </td>
	</tr>
	<tr>
	  <td class="row1" valign="top"><span class="gen">{L_ADV_FORM}:</span><br /><span class="gensmall">{L_ADV_FORM_EXPLAIN}</span></td>
	  <td class="row2">
		<input type="radio" name="adv_form_enable" value="1" />
		<span class="gen">{L_YES}</span>&nbsp;&nbsp;
		<input type="radio" name="adv_form_enable" value="0" checked />
		<span class="gen">{L_NO}</span>
	  </td>
	</tr>
	<tr>
	  <td class="row1" valign="top"><span class="gen">{L_ADV_FORM_STRING}:</span><br /><span class="gensmall">{L_ADV_FORM_STRING_EXPLAIN}</span></td>
	  <td class="row2">
		<input type="text" class="post" name="adv_form" size="50" maxlength="100" value="{L_ADV_FORM_DEFAULT}" /><br /><span class="gensmall">{L_ADV_FORM_VARS}</span>
	  </td>
	</tr>
	<tr>
	  <td class="catBottom" colspan="2" align="center">
		<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />
		&nbsp;&nbsp;
		<input type="reset" value="{L_RESET}" class="liteoption" />
	  </td>
	</tr>
</table>
</form>

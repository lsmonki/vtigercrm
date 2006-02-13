<form method="post" action="{S_FORM_ACTION}">
  <table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
    <tr> 
	<th colspan="2" height="25" class="thTop" nowrap="nowrap">{L_WEBLOG}</th>
    </tr>
    <tr>
	<td colspan="2" class="cat">&nbsp;</td>
    </tr>
    <tr>
	<td class="row1"><span class="gen">{L_WEBLOG_CAT}:</span></td><td class="row2">{S_WEBLOG_SELECT}</td>
    </tr>
    <tr>
	<td class="row1"><span class="gen">{L_WEBLOG_NAME}:</span></td>
	<td class="row2"><input type="text" name="weblog_name" class="post" value="{S_WEBLOG_NAME}"></td>
    </tr>
    <tr>
	<td class="row1" valign="top"><span class="gen">{L_WEBLOG_DESC}:</span></td>
	<td class="row2"><textarea name="weblog_desc" cols="60" rows="15" class="post">{S_WEBLOG_DESCRIPTION}</textarea></td>
    </tr>
    <tr>
	<td class="row1"><span class="gen">{L_WEBLOG_ACCESSIBILITY}:</span></td>
	<td class="row2">{S_WEBLOG_RIGHTS_SELECT}</td>
    </tr>
    <tr>
	<td class="row1"><span class="gen">{L_WEBLOG_VISIBLE}:</span></td>
	<td class="row2"><span class="gen"><input type="radio" name="weblog_visible" value="1" {S_WEBLOG_VISIBLE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="weblog_visible" value="0" {S_WEBLOG_VISIBLE_NO} /> {L_NO}</span></td>
    </tr>
    <tr>
	<td class="row1"><span class="gen">{L_WEBLOG_SHOW_ENTRIES}:</span></td>
	<td class="row2"><input type="text" name="weblog_show_entries" size="3" maxlength="3" class="post" value="{S_WEBLOG_SHOW_ENTRIES}"></td>
    </tr>
    <tr>
	<td class="row1"><span class="gen">{L_WEBLOG_COMMENTS_LABEL}:</span><br /><span class="gensmall"></span></td>
	<td class="row2"><input type="text" name="comment_name" maxlength="255" class="post" value="{S_WEBLOG_COMMENT_NAME}"></td>
    </tr>
    <tr>
	<td class="row1"><span class="gen">{L_WEBLOG_POST_COMMENT_LABEL}:</span><br /><span class="gensmall"></span></td>
	<td class="row2"><input type="text" name="post_comment_name" maxlength="255" class="post" value="{S_WEBLOG_POST_COMMENT_NAME}"></td>
    </tr>
    <!-- BEGIN switch_edit_new -->
    <tr>
	<td class="row1" colspan="2"><span class="gen">{L_WEBLOG_FACE}:</span><br /><span class="gensmall"></span></td>
    </tr>
    <tr>
	<td class="row2" colspan="2"><textarea name="weblog_face" cols="160" rows="15" class="post">{S_WEBLOG_FACE}</textarea></td>
    </tr>
    <tr>
	<td class="row1" colspan="2"><span class="gen">{L_WEBLOG_PAGE}:</span><br /><span class="gensmall"></span></td>
    </tr>
    <tr>
	<td class="row2" colspan="2"><textarea name="weblog_page" cols="160" rows="30" class="post">{S_WEBLOG_PAGE}</textarea></td>
    </tr>
    <!-- END switch_edit_new -->
    <!-- BEGIN switch_edit_old -->
    <tr>
	<td class="row1"><span class="gen">{L_WEBLOG_SHOW_PROFILE}:</span></td>
	<td class="row2"><input type="radio" name="showprofile" value="1" {SHOW_PROFILE_YES} /><span class="gen">{L_YES}</span>&nbsp;&nbsp;<input type="radio" name="showprofile" value="0" {SHOW_PROFILE_NO} /><span class="gen">{L_NO}</span>&nbsp;&nbsp;</td>
    </tr>
    <tr>
	<td class="row1"><span class="gen">{L_WEBLOG_DISPLAY_DESCRIPTION}:</span></td>
	<td class="row2"><input type="radio" name="showdesc" value="1" {SHOW_DESC_CENTER} /><span class="gen">Center</span>&nbsp;&nbsp;<input type="radio" name="showdesc" value="0" {SHOW_DESC_RIGHT} /><span class="gen">Right</span>&nbsp;&nbsp;</td>
    </tr>
    <tr>
	<td class="row1"><span class="gen">{L_WEBLOG_SHOW_CONTACT}:</span></td>
	<td class="row2"><input type="radio" name="showcontact" value="1" {SHOW_CONTACT_YES} /><span class="gen">{L_YES}</span>&nbsp;&nbsp;<input type="radio" name="showcontact" value="0" {SHOW_CONTACT_NO} /><span class="gen">{L_NO}</span>&nbsp;&nbsp;</td>
    </tr>
    <tr>
	<td class="row1"><span class="gen">{L_WEBLOG_SHOW_INFO}:</span></td>
	<td class="row2"><input type="radio" name="showinfo" value="1" {SHOW_INFO_YES} /><span class="gen">{L_YES}</span>&nbsp;&nbsp;<input type="radio" name="showinfo" value="0" {SHOW_INFO_NO} /><span class="gen">{L_NO}</span>&nbsp;&nbsp;</td>
    </tr>
    <!-- END switch_edit_old -->
    <tr>
	<td class="spaceRow" colspan="2" height="1"><img src="templates/advSilver/images/spacer.gif" alt="" width="1" height="1" /></td>
    </tr>
    <tr>
	<td colspan="2" class="catBottom" align="center">
	  <input type="submit" name="editweblog" value="{L_WEBLOG_UPDATE}" class="mainoption">
	</td>
    </tr>
  </table>
</form>
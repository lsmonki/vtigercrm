<form method="post" action="{S_FORM_ACTION}">
<!-- table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
  <tr>
	<td align="left"><span class="nav"><a href="{U_INDEX}" class="nav">{L_INDEX}</a></span></td>
  </tr>
</table -->

<script language="javascript" type="text/javascript">
<!--
function update_preview(newimage)
{
	document.preview_image.src = "./weblogs/styles/" + newimage + "/weblog_preview.gif";
}
//-->
</script>

<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
  <tr>
	<th colspan="2" height="25" class="thTop" nowrap="nowrap">{L_WEBLOG}</th>
  </tr>
  <tr>
	<td colspan="2" class="row1"><span class="gen">{L_WEBLOG_CP_EXPLAIN}</span></td>
  </tr>
  <tr>
	<td colspan="2" class="cat">&nbsp;</td>
  </tr>
  <!-- BEGIN switch_edit_weblog -->
  <tr>
	<td class="spaceRow" colspan="2" height="1"><img src="templates/sub/images/spacer.gif" alt="" width="1" height="1" /></td>
  </tr>
  <tr>
	<td align="left" valign="middle" nowrap="nowrap" colspan="2" class="row2"><span class="nav"><a href="{U_POST_NEW_TOPIC}"><img src="{POST_IMG}" border="0" alt="{L_POST_NEW_TOPIC}" align="middle" /></a></span></td>
  </tr>
  <!-- END switch_edit_weblog -->
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
	<td class="row1"><span class="gen">{L_WEBLOG_ACCESSIBILITY}:</span><span class="gensmall"><br />{L_WEBLOG_ACCESS_EXPLAIN}</span></td>
	<td class="row2">{S_WEBLOG_RIGHTS_SELECT} &nbsp;&nbsp; <a href="{U_WEBLOG_MANAGE_GROUP}" class="gen">{L_WEBLOG_MANAGE_GROUP}</a></td>
  </tr>
  <tr>
	<td class="row1"><span class="gen">{L_WEBLOG_COMMENTS_AUTH}:</span><span class="gensmall"><br />{L_WEBLOG_COMMENTS_AUTH_EXPLAIN}</span></td>
	<td class="row2">{S_WEBLOG_COMMENTS_AUTH_SELECT}</td>
  </tr>
  <tr>
	<td class="row1"><span class="gen">{L_WEBLOG_VISIBLE}:</span><span class="gensmall"><br />{L_WEBLOG_VISIBLE_EXPLAIN}</span></td>
	<td class="row2"><span class="gen"><input type="radio" name="weblog_visible" value="1" {S_WEBLOG_VISIBLE_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="weblog_visible" value="0" {S_WEBLOG_VISIBLE_NO} /> {L_NO}</span></td>
  </tr>
  <tr>
	<td class="row1"><span class="gen">{L_WEBLOG_SHOW_ENTRIES}:</span></td>
	<td class="row2"><input type="text" name="weblog_show_entries" size="3" maxlength="3" class="post" value="{S_WEBLOG_SHOW_ENTRIES}"></td>
  </tr>
  <tr>
	<td class="row1"><span class="gen">{L_WEBLOG_COMMENTS_LABEL}:</span><br /><span class="gensmall">{L_WEBLOG_COMMENT_EXPLAIN}</span></td>
	<td class="row2"><input type="text" name="comment_name" maxlength="255" class="post" value="{S_WEBLOG_COMMENT_NAME}"></td>
  </tr>
  <tr>
	<td class="row1"><span class="gen">{L_WEBLOG_POST_COMMENT_LABEL}:</span><br /><span class="gensmall">{L_WEBLOG_COMMENT_EXPLAIN}</span></td>
	<td class="row2"><input type="text" name="post_comment_name" maxlength="255" class="post" value="{S_WEBLOG_POST_COMMENT_NAME}"></td>
  </tr>
  <!-- BEGIN switch_create_weblog -->
  <tr>
	<td class="row1"><span class="gen">{L_WEBLOG_TEMPLATE}:</span><br /><span class="gensmall">{L_WEBLOG_TEMPLATE_EXPLAIN}</span></td>
	<td class="row2"><span class="gen">{MODE_RADIO}</span>{WEBLOG_STYLE_SELECT}{PREVIEW_IMAGE}</td>
  </tr>
  <!-- END switch_create_weblog -->
  <!-- BEGIN switch_edit_weblog -->
  <tr>
	<td class="row1"><span class="gen">{L_WEBLOG_TEMPLATE}:</span><br /><span class="gensmall">{L_WEBLOG_TEMPLATE_EXPLAIN}</span></td>
	<td class="row2"><span class="gen">{KEEP_RADIO}{SWITCH_STYLE_RADIO}{SWITCH_MODE_RADIO}{WEBLOG_STYLE_SELECT}{PREVIEW_IMAGE}</span></td>
  </tr>
  <!-- END switch_edit_weblog -->
  <!-- BEGIN switch_edit_new_weblog -->
  <tr>
	<td class="row1" colspan="2"><span class="gen">{L_WEBLOG_FACE}:</span><br /><span class="gensmall">{L_WEBLOG_FACE_EXPLAIN}</span><br/><span class="gensmall">{L_WEBLOG_FACE_TIP}</span></td>
  </tr>
  <tr>
	<td class="row2" colspan="2"><textarea name="weblog_face" cols="140" rows="15" class="post">{S_WEBLOG_FACE}</textarea></td>
  </tr>
  <tr>
	<td class="row1" colspan="2"><span class="gen">{L_WEBLOG_PAGE}:</span><br /><span class="gensmall">{L_WEBLOG_PAGE_EXPLAIN}</span><br/><span class="gensmall">{L_WEBLOG_PAGE_TIP}</span></td>
  </tr>
  <tr>
	<td class="row2" colspan="2"><textarea name="weblog_page" cols="140" rows="30" class="post">{S_WEBLOG_PAGE}</textarea></td>
  </tr>
  <!-- END switch_edit_new_weblog -->
  <!-- BEGIN switch_edit_old_weblog -->
  <tr>
	<td class="row1"><span class="gen">{L_WEBLOG_SHOW_PROFILE}:</span></td>
	<td class="row2"><input type="radio" name="showprofile" value="1" {SHOW_PROFILE_YES} /><span class="gen">{L_YES}</span>&nbsp;&nbsp;<input type="radio" name="showprofile" value="0" {SHOW_PROFILE_NO} /><span class="gen">{L_NO}</span>&nbsp;&nbsp;</td>
  </tr>
  <tr>
	<td class="row1"><span class="gen">{L_WEBLOG_SHOW_CONTACT}:</span></td>
	<td class="row2"><input type="radio" name="showcontact" value="1" {SHOW_CONTACT_YES} /><span class="gen">{L_YES}</span>&nbsp;&nbsp;<input type="radio" name="showcontact" value="0" {SHOW_CONTACT_NO} /><span class="gen">{L_NO}</span>&nbsp;&nbsp;</td>
  </tr>
  <tr>
	<td class="row1"><span class="gen">{L_WEBLOG_SHOW_INFO}:</span></td>
	<td class="row2"><input type="radio" name="showinfo" value="1" {SHOW_INFO_YES} /><span class="gen">{L_YES}</span>&nbsp;&nbsp;<input type="radio" name="showinfo" value="0" {SHOW_INFO_NO} /><span class="gen">{L_NO}</span>&nbsp;&nbsp;</td>
  </tr>
  <tr>
	<td class="row1"><span class="gen">{L_WEBLOG_DISPLAY_DESCRIPTION}:</span></td>
	<td class="row2"><input type="radio" name="showdesc" value="1" {SHOW_DESC_CENTER} /><span class="gen">{L_CENTER}</span>&nbsp;&nbsp;<input type="radio" name="showdesc" value="0" {SHOW_DESC_RIGHT} /><span class="gen">{L_RIGHT}</span>&nbsp;&nbsp;</td>
  </tr>
  <!-- END switch_edit_old_weblog -->
  <tr>
	<td class="spaceRow" colspan="2" height="1"><img src="templates/advSilver/images/spacer.gif" alt="" width="1" height="1" /></td>
  </tr>
  <!-- BEGIN switch_create_weblog -->
  <tr>
	<td colspan="2" class="catBottom" align="center"><input type="submit" name="addweblog" value="{L_WEBLOG_CREATE}" class="mainoption"></td>
  </tr>
  <!-- END switch_create_weblog -->
  <!-- BEGIN switch_edit_weblog -->
  <tr>
	<td class="row1"><span class="gen">{L_WEBLOG_DELETE}:</span></td>
	<td class="row2"><input type="Checkbox" name="deleteweblog"></td>
  </tr>
  <tr>
	<td colspan="2" class="catBottom" align="center"><input type="submit" name="editweblog" value="{L_WEBLOG_UPDATE}" class="mainoption"></td>
  </tr>
  <!-- END switch_edit_weblog -->
</table>
</form>
<div align="center"><span class="copyright">Powered by Forum Weblogs Mod {MOD_VERSION} by <a href="http://vince.dynalias.com/phpBB2/" class="copyright">Hyperion</a><br />Powered by <a href="http://www.vtiger.com/" target="_blank">vtiger.com</a></span></div>

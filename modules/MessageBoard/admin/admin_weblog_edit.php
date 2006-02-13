<?php
/***************************************************************************
 *							   myweblog.php
 *							-------------------
 *
 *   author	: hyperion - http://www.phpbb.com/phpBB/profile.php?mode=viewprofile&u=6288
 *   original author	: netclectic - http://www.phpbb.com/phpBB/profile.php?mode=viewprofile&u=6288
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

define('IN_PHPBB', 1);


if( !empty($setmodules) )
{
	$filename = basename(__FILE__);

	return;
}

$no_page_header = TRUE;
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'includes/functions_selects.'.$phpEx); 
include($phpbb_root_path . 'includes/functions_weblog.'.$phpEx); 
include($phpbb_root_path . 'includes/functions_post.'.$phpEx); 
// include the weblog lang file
$use_lang = ( !file_exists($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_weblog_admin.'.$phpEx) ) ? 'english' : $board_config['default_lang'];
include($phpbb_root_path . 'language/lang_' . $use_lang . '/lang_weblog_admin.' . $phpEx);
// include the weblog lang file
$use_lang = ( !file_exists($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_weblog_main.'.$phpEx) ) ? 'english' : $board_config['default_lang'];
include($phpbb_root_path . 'language/lang_' . $use_lang . '/lang_weblog_main.' . $phpEx);

//	
// Start initial var setup
//
if( isset($HTTP_GET_VARS[POST_FORUM_URL]) || isset($HTTP_POST_VARS[POST_FORUM_URL]) )
{
	$forum_id = ( isset($HTTP_GET_VARS[POST_FORUM_URL]) ) ? intval($HTTP_GET_VARS[POST_FORUM_URL]) : intval($HTTP_POST_VARS[POST_FORUM_URL]);
}
else if( isset($HTTP_GET_VARS['forum']))
{
	$forum_id = $HTTP_GET_VARS['forum'];
}
else
{
	$forum_id = "";
}

if ( $forum_id )
{
	$sql = "SELECT u.*
		FROM " . AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g, " . USERS_TABLE . " u
		WHERE aa.forum_id = $forum_id 
			AND aa.auth_mod = " . TRUE . " 
			AND g.group_single_user = 1
			AND ug.group_id = aa.group_id 
			AND g.group_id = aa.group_id 
			AND u.user_id = ug.user_id 
		GROUP BY u.user_id, u.username  
		ORDER BY u.user_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Could not obtain post/user information.", '', __LINE__, __FILE__, $sql);
	}
	
	while( $row = $db->sql_fetchrow($result) )
	{
		$profiledata = get_userdata($row['user_id']);
	}
}
else
{
	message_die(GENERAL_ERROR, "The selected weblog you chose does not exist.", '', __LINE__, __FILE__, $sql);
}

$userid = $profiledata['user_id'];
$username = $profiledata['username'];
$weblog_auth_types = array(0, 1, 2, 3 );

// does our user belong to an authorised weblog group?
$sql_weblog = "SELECT * FROM " . USER_GROUP_TABLE . " g INNER JOIN " . WEBLOG_GROUPS_TABLE . " w ON w.group_id = g.group_id 
	WHERE g.user_id = " . $profiledata['user_id'] . "
		AND g.user_pending = 0;";
if( !($result_weblog = $db->sql_query($sql_weblog)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain weblog group information', '', __LINE__, __FILE__, $sql_weblog);
}
if ( !$row_weblog = $db->sql_fetchrow($result_weblog) )
{
	message_die(GENERAL_MESSAGE, $lang['Weblog_Not_Authorised'], $lang['Not_Authorised']);
}
$db->sql_freeresult($result_weblog);

$template->set_filenames(array(
	"body" => "admin/weblog_edit_body.tpl")
);

$template->assign_vars(array(
	'L_WEBLOG' => $lang['Weblog'],
	'L_WEBLOG_CAT' => $lang['Weblog_Category'],
	'L_WEBLOG_NAME' => $lang['Weblog_Name'],
	'L_WEBLOG_DESC' => $lang['Weblog_Description'],
	'L_WEBLOG_SHOW_ENTRIES' => $lang['Weblog_Limit'],
	'L_WEBLOG_ACCESSIBILITY' => $lang['Weblog_Access'],
	'L_WEBLOG_ACCESS_EXPLAIN' => $lang['Weblog_access_explain'],
	'L_WEBLOG_VISIBLE' => $lang['Weblog_Visible'],
	'L_WEBLOG_COMMENTS_LABEL' => $lang['Weblog_comments_label'],
	'L_WEBLOG_POST_COMMENT_LABEL' => $lang['Weblog_post_comment_label'],
	'L_WEBLOG_COMMENT_EXPLAIN' => $lang['Weblog_comment_explain'],
	'L_WEBLOG_SHOW_PROFILE' => $lang['Weblog_show_profile'],
	'L_WEBLOG_DISPLAY_DESCRIPTION' => $lang['Weblog_display_description'],
	'L_WEBLOG_SHOW_CONTACT' => $lang['Weblog_show_contact'],
	'L_WEBLOG_SHOW_INFO' => $lang['Weblog_show_info'],
	'L_WEBLOG_TEMPLATE' => $lang['Weblog_template'],
	'L_WEBLOG_TEMPLATE_EXPLAIN' => $lang['Weblog_template_explain'],
	'L_WEBLOG_PAGE' => $lang['Weblog_page'],
	'L_WEBLOG_PAGE_EXPLAIN' => $lang['Weblog_page_explain'],
	'L_WEBLOG_FACE' => $lang['Weblog_face'],
	'L_WEBLOG_FACE_EXPLAIN' => $lang['Weblog_face_explain'],
	'L_WEBLOG_COMMENT_DEFAULT' => $lang['Weblog_comment_default'],
	'L_WEBLOG_POSTCOMMENT_DEFAULT' => $lang['Weblog_postcomment_default'],
	'L_YES' => $lang['Yes'],
	'L_NO' => $lang['No'],
	'S_USERNAME' => $username,
	'S_FORM_ACTION' =>  append_sid("admin_weblog_edit.$phpEx?&amp;" . POST_FORUM_URL . "=$forum_id"))
);

// check to see if the user has already created a weblog
$sql = 'SELECT * FROM ' . FORUMS_TABLE . ' WHERE forum_id = ' . intval($profiledata['user_weblog']) . ';';
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Error querying to find user weblog information', '', __LINE__, __FILE__, $sql);
}
if ($forum_data = $db->sql_fetchrow($result) )
{
	$weblog_exists = TRUE;
	$weblog_id = $forum_data['forum_id'];
	$weblog_name = $forum_data['forum_name'];
	$weblog_desc = $forum_data['forum_desc'];
	$weblog_cat =  $forum_data['cat_id'];
	$weblog_status = $forum_data['forum_status'];
	$weblog_access = $profiledata['weblog_access'];
	$weblog_visible_yes = ( $profiledata['weblog_visible'] == 1 ) ? "checked=\"checked\"" : "";
	$weblog_visible_no = ( $profiledata['weblog_visible'] == 0 ) ? "checked=\"checked\"" : "";
	$weblog_show_entries = $profiledata['weblog_show_entries'];
	$weblog_group = $profiledata['weblog_group'];
	$weblog_show_profile = $profiledata['weblog_show_profile'];
	$weblog_show_desc = $profiledata['weblog_show_desc'];
	$weblog_contact_info = $profiledata['weblog_contact_info'];
	$weblog_show_info = $profiledata['weblog_show_info'];
	$weblog_comment_name = $profiledata['weblog_comment_name'];
	$weblog_post_comment_name = $profiledata['weblog_post_comment_name'];
}


// set the mode
$mode = '';
if ( isset($HTTP_POST_VARS['editweblog']) )
{
	if ( !$weblog_exists )
	{
  		message_die(GENERAL_ERROR, "Error locating your weblog information.");
	}
	$mode = 'edit_weblog';
}

if ( $mode )
{
	$weblog_cat = ( isset($HTTP_POST_VARS[POST_CAT_URL]) ) ? intval($HTTP_POST_VARS[POST_CAT_URL]) : 0;
	$weblog_name = ( isset($HTTP_POST_VARS['weblog_name']) ) ? $HTTP_POST_VARS['weblog_name'] : '';
	$weblog_desc = ( isset($HTTP_POST_VARS['weblog_desc']) ) ? $HTTP_POST_VARS['weblog_desc'] : '';
	$weblog_access = ( isset($HTTP_POST_VARS['weblog_access']) ) ? intval($HTTP_POST_VARS['weblog_access']) : 0;
	$weblog_visible = ( isset($HTTP_POST_VARS['weblog_visible']) ) ? intval($HTTP_POST_VARS['weblog_visible']) : 1;
	$weblog_show_entries = ( isset($HTTP_POST_VARS['weblog_show_entries']) ) ? intval($HTTP_POST_VARS['weblog_show_entries']) : 0;
	$weblog_comment_name = ( isset($HTTP_POST_VARS['comment_name']) ) ? $HTTP_POST_VARS['comment_name'] : '';
	$weblog_post_comment_name = ( isset($HTTP_POST_VARS['post_comment_name']) ) ? $HTTP_POST_VARS['post_comment_name'] : '';
	$weblog_style = ( isset($HTTP_POST_VARS['weblog_style']) ) ? $HTTP_POST_VARS['weblog_style'] : 0;
	$weblog_page = ( isset($HTTP_POST_VARS['weblog_page']) ) ? $HTTP_POST_VARS['weblog_page'] : 0;
	$weblog_face = ( isset($HTTP_POST_VARS['weblog_face']) ) ? $HTTP_POST_VARS['weblog_face'] : 0;
	$weblog_style_name = ( isset($HTTP_POST_VARS['weblog_style']) ) ? intval($HTTP_POST_VARS['weblog_style']) : $board_config['default_style'];
	$weblog_show_profile = ( isset($HTTP_POST_VARS['showprofile']) ) ? $HTTP_POST_VARS['showprofile'] : 1;
	$weblog_show_desc = ( isset($HTTP_POST_VARS['showdesc']) ) ? $HTTP_POST_VARS['showdesc'] : 1;
	$weblog_contact_info = ( isset($HTTP_POST_VARS['showcontact']) ) ? $HTTP_POST_VARS['showcontact'] : 1;
	$weblog_show_info = ( isset($HTTP_POST_VARS['showinfo']) ) ? $HTTP_POST_VARS['showinfo'] : 1;

	$sql = "SELECT themes_id, style_name
		FROM " . THEMES_TABLE . "
		ORDER BY template_name, themes_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Couldn't query themes table", "", __LINE__, __FILE__, $sql);
	}

	while ( $row = $db->sql_fetchrow($result) )
	{
		if ( $row['themes_id'] == $weblog_style )
		{
			$weblog_style_name = $row['style_name'];
		}
	}

	if ( !$weblog_cat || !$weblog_name || !$weblog_desc )
	{
		message_die(GENERAL_ERROR, $lang['Weblog_create_error']);
	}
	
	switch($mode)
	{
		case 'edit_weblog':
			$filename = '../weblogs/weblog_' . $profiledata['user_id'] . '.cfg';

			@CHMOD($filename, 0666);
			@unlink($filename);
			$f = @fopen($filename, 'w' );

			$weblog_page = weblog_process_html ($weblog_page);

			@fputs( $f, $weblog_page );
			@fclose( $f );

			$filename = '../weblogs/face_' . $profiledata['user_id'] . '.cfg';

			// Get a default template as the user's starting template.
			@CHMOD($filename, 0666);
			@unlink($filename);
			$f = @fopen($filename, 'w' );

			$weblog_face = weblog_process_html ($weblog_face);

			@fputs( $f, $weblog_face );
			@fclose( $f );

			update_user_weblog($weblog_id, $weblog_name, $weblog_desc, $weblog_cat, $weblog_access, $weblog_visible, $weblog_group, $weblog_show_entries, $weblog_comment_name, $weblog_post_comment_name, $weblog_show_profile, $weblog_show_desc, $weblog_contact_info, $weblog_show_info);
			$weblog_exists = true;
			
			$message = $lang['Weblog_updated'] . "<br /><br />" . sprintf($lang['Click_return_weblogmanagement'], "<a href=\"" . append_sid("admin_weblog_management.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);

			break;
		default :
			message_die(GENERAL_ERROR, "No mode set.");
			break;
	}	 
}

$sql = "SELECT c.* 
	FROM " . CATEGORIES_TABLE . " c 
	WHERE cat_allow_weblogs = 1 
	ORDER BY c.cat_order ASC";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, "Couldn't obtain categories list", "", __LINE__, __FILE__, $sql);
}

$cat_rows = $db->sql_fetchrowset($result);
$db->sql_freeresult($result);

$select_list = '<select name="' . POST_CAT_URL . '">';
for($i = 0; $i < count($cat_rows); $i++)
{
	$select_list .= '<option value="' . $cat_rows[$i]['cat_id'] . '"' . ( ( $cat_rows[$i]['cat_id'] == $weblog_cat ) ? ' selected' : '' ) . '>' . $cat_rows[$i]['cat_title'] . '</option>';
}
$select_list .= '</select>';

$weblog_auth_desc[0] = $lang['Weblog_auth_all'];
$weblog_auth_desc[1] = $lang['Weblog_auth_reg'];
$weblog_auth_desc[2] = $lang['Weblog_auth_group'];
$weblog_auth_desc[3] = $lang['Weblog_auth_owner'];

$rights_select = '<select name="weblog_access">';
for($i = 0; $i < count($weblog_auth_types); $i++)
{
	$selected = ( $weblog_access == $i ) ? ' selected="selected"' : '';
	$rights_select .= '<option value="' . $i . '"' . $selected . '>' . $weblog_auth_desc[$i] . '</option>';
}
$rights_select .= '</select>';

$template->assign_vars(array(
	'SHOW_PROFILE_YES' => ( $weblog_show_profile ) ? 'checked="checked"' : '',
	'SHOW_PROFILE_NO' => ( !$weblog_show_profile ) ? 'checked="checked"' : '',

	'SHOW_DESC_CENTER' => ( $weblog_show_desc ) ? 'checked="checked"' : '',
	'SHOW_DESC_RIGHT' => ( !$weblog_show_desc ) ? 'checked="checked"' : '',

	'SHOW_CONTACT_YES' => ( $weblog_contact_info ) ? 'checked="checked"' : '',
	'SHOW_CONTACT_NO' => ( !$weblog_contact_info ) ? 'checked="checked"' : '',

	'SHOW_INFO_YES' => ( $weblog_show_info ) ? 'checked="checked"' : '',
	'SHOW_INFO_NO' => ( !$weblog_show_info ) ? 'checked="checked"' : '',

	'S_WEBLOG_SELECT' => $select_list,
	'S_WEBLOG_RIGHTS_SELECT' => $rights_select,
	'S_WEBLOG_SELECT2' => $select_list2,
	'S_WEBLOG_RIGHTS_SELECT2' => $rights_select2,
	)
);

$new_topic_url = append_sid("weblog_posting.$phpEx?mode=newtopic&amp;" . POST_FORUM_URL . "=$weblog_id");
$post_img = ( $weblog_status == FORUM_LOCKED ) ? $images['post_locked'] : $images['post_new_entry'];
$post_alt = ( $weblog_status == FORUM_LOCKED ) ? $lang['Forum_locked'] : $lang['Post_new_topic'];

if ( file_exists("../weblogs/weblog_" . $profiledata['user_id'] . ".cfg") )
	$file1 = file("../weblogs/weblog_" . $profiledata['user_id'] . ".cfg");

$weblog_page = '';
for ($i=0;$i<count($file1);$i++)
{
	$weblog_page .= $file1[$i];
}

if ( file_exists("../weblogs/face_" . $profiledata['user_id'] . ".cfg") )
	$file2 = file("../weblogs/face_" . $profiledata['user_id'] . ".cfg");

$weblog_face = '';
for ($i=0;$i<count($file2);$i++)
{
	$weblog_face .= $file2[$i];
}

if ( $profiledata['weblog_advanced'] == 2 )
	$template->assign_block_vars('switch_edit_new', array());
else if ( $profiledata['weblog_advanced'] == 1 )
	$template->assign_block_vars('switch_edit_old', array());

$template->assign_vars(array(
	'L_POST_NEW_TOPIC' => $post_alt,
	'L_WEBLOG_DELETE' => $lang['Delete'],
	'L_WEBLOG_UPDATE' => $lang['Weblog_Update'],
	'L_WEBLOG_UPDATE_EXPLAIN' => $lang['Weblog_Update_Explain'],
	'L_WEBLOG_MANAGE_GROUP' => $lang['Weblog_Manage_Group'],

	'U_WEBLOG_MANAGE_GROUP' => append_sid("groupcp.$phpEx?" . POST_GROUPS_URL . "=" . $weblog_group),
	'U_POST_NEW_TOPIC' => $new_topic_url,

	'POST_IMG' => $post_img,
	'S_WEBLOG_VISIBLE_YES' => $weblog_visible_yes,
	'S_WEBLOG_VISIBLE_NO' => $weblog_visible_no,
	'S_WEBLOG_NAME' => stripslashes($weblog_name),
	'S_WEBLOG_DESCRIPTION' => stripslashes($weblog_desc),
	'S_WEBLOG_COMMENT_NAME' => stripslashes($weblog_comment_name),
	'S_WEBLOG_POST_COMMENT_NAME' => stripslashes($weblog_post_comment_name),
	'S_WEBLOG_SHOW_ENTRIES' => $weblog_show_entries,
	'S_WEBLOG_PAGE' => $weblog_page,
	'S_WEBLOG_FACE' => $weblog_face)
);

include('./page_header_admin.'.$phpEx);

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>

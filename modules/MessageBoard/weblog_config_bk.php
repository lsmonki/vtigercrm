<?php
/***************************************************************************
 *							 weblog_config.php
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

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);
include($phpbb_root_path . 'includes/functions_selects.'.$phpEx);
include($phpbb_root_path . 'includes/functions_weblog.'.$phpEx);
include($phpbb_root_path . 'includes/functions_post.'.$phpEx);
include($phpbb_root_path . 'includes/db.'.$phpEx);
include($phpbb_root_path . 'includes/weblogs_common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management
//

//added by isaac to prevent anonymous bloggers
if ( !$userdata['session_logged_in'] )
{
    message_die(GENERAL_INFORMATION, 'Please log in to access your Blog control panel');
}


// Check to see if the user is allowed to have a weblog.
if ( ($weblog_config['weblog_create'] == 1 && $userdata['user_level'] == ADMIN ) || $weblog_config['weblog_create'] == 0 )
{
	$sql = "SELECT g.user_id, g.user_pending, g.group_id
		FROM " . USER_GROUP_TABLE . " g
			INNER JOIN " . WEBLOG_GROUPS_TABLE . " w ON w.group_id = g.group_id
		WHERE g.user_id = " . $userdata['user_id'] . "
			AND g.user_pending = 0";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain weblog group information', '', __LINE__, __FILE__, $sql_weblog);
	}

	if ( !$db->sql_fetchrow($result) )
	{
		message_die(GENERAL_MESSAGE, $lang['Weblog_Not_Authorised'], $lang['Not_Authorised']);
	}

	// Get the weblog's group information
	$sql = "SELECT * FROM " . USER_GROUP_TABLE . " g INNER JOIN " . WEBLOG_GROUPS_TABLE . " w ON w.group_id = g.group_id
		WHERE g.user_id = " . $userdata['user_id'] . "
			AND g.user_pending = 0;";
	if( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not obtain weblog group information', '', __LINE__, __FILE__, $sql_weblog);
	}

	if ( !$row = $db->sql_fetchrow($result) )
	{
		message_die(GENERAL_MESSAGE, $lang['Weblog_Not_Authorised'], $lang['Not_Authorised']);
	}
	$db->sql_freeresult($result);
}

if ( $userdata['user_weblog'] != 0 )
{
	$weblog_exists = TRUE;

	//
	// Get Weblog Information
	//
	$sql = "SELECT * FROM " . FORUMS_TABLE . " WHERE forum_id = " . intval($userdata['user_weblog']);
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Error querying to find user weblog information', '', __LINE__, __FILE__, $sql);
	}

	$forum_data = $db->sql_fetchrow($result);
	$weblog_name = $forum_data['forum_name'];
	$weblog_desc = $forum_data['forum_desc'];
	$weblog_id = $forum_data['forum_id'];
	$weblog_status = $forum_data['forum_status'];
	$weblog_cat =  $forum_data['cat_id'];

	$weblog_start_date = $userdata['weblog_start_date'];
	$weblog_show_entries = $userdata['weblog_show_entries'];
	$weblog_access = $userdata['weblog_access'];
	$weblog_access2 = $userdata['weblog_access2'];
	$weblog_visible = $userdata['weblog_visible'];
	$weblog_group = $userdata['weblog_group'];
	$weblog_show_profile = $userdata['weblog_show_profile'];
	$weblog_show_desc = $userdata['weblog_show_desc'];
	$weblog_contact_info = $userdata['weblog_contact_info'];
	$weblog_show_info = $userdata['weblog_show_info'];
	$weblog_advanced = $userdata['weblog_advanced'];
	$weblog_comment_name = $userdata['weblog_comment_name'];
	$weblog_post_comment_name = $userdata['weblog_post_comment_name'];
	$weblog_style = $userdata['weblog_style'];

	$weblog_advanced_yes = ( $userdata['weblog_advanced'] == WEBLOG_ADVANCED ) ? "checked=\"checked\"" : "";
	$weblog_advanced_no = ( $userdata['weblog_advanced'] == WEBLOG_SIMPLE ) ? "checked=\"checked\"" : "";
	$weblog_advanced2 = $userdata['weblog_advanced'];
}
else
{
	// Defaults if no weblog has been created
	$weblog_name = sprintf($weblog_config['default_name'], $userdata['username']);
	$weblog_desc = sprintf($weblog_config['default_desc'], $userdata['username']);
	$weblog_show_entries = $weblog_config['default_entries'];
	$weblog_access = $weblog_config['default_access'];
	$weblog_access2 = $weblog_config['default_access2'];
	$weblog_visible = WEBLOG_SHOW;
	$weblog_show_profile = WEBLOG_SHOW;
	$weblog_show_desc = WEBLOG_SHOW;
	$weblog_contact_info = WEBLOG_SHOW;
	$weblog_show_info = WEBLOG_SHOW;
	$weblog_advanced = ( $weblog_config['allow_advanced'] == WEBLOG_ALLOW_ADVANCED ) ? WEBLOG_ADVANCED : WEBLOG_SIMPLE;
	$weblog_comment_name = $weblog_config['default_comment'];
	$weblog_post_comment_name = $weblog_config['default_post_comment'];
}

$style_explain = $lang['Weblog_style_explain'];
if ( $userdata['weblog_advanced'] == WEBLOG_ADVANCED )
	$style_explain .= '<br />' . $lang['Weblog_style_explain_advanced'];
if ( $weblog_config['allow_advanced'] == WEBLOG_ALLOW_CHOOSE )
	$style_explain .= '<br />' . $lang['Weblog_style_switch'];

//
// Include the Header
//
define('IN_WEBLOGS', true);   //added by isaac for new blog_header
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	"body" => "weblog_config_body.tpl")
);

//
// Set the initial label vars
//
$template->assign_vars(array(
	'L_WEBLOG' => $lang['Weblog_CP'],
	'L_WEBLOG_CAT' => $lang['Weblog_Category'],
	'L_WEBLOG_NAME' => $lang['Weblog_Name2'],
	'L_WEBLOG_DESC' => $lang['Weblog_Description'],
	'L_WEBLOG_ACCESSIBILITY' => $lang['Weblog_Access'],
	'L_WEBLOG_ACCESS_EXPLAIN' => $lang['Weblog_access_explain'],
	'L_WEBLOG_COMMENTS_AUTH' => $lang['Weblog_comments_auth'],
	'L_WEBLOG_COMMENTS_AUTH_EXPLAIN' => $lang['Weblog_comments_auth_explain'],
	'L_WEBLOG_VISIBLE' => $lang['Weblog_Visible'],
	'L_WEBLOG_VISIBLE_EXPLAIN' => $lang['Weblog_visible_explain'],
	'L_WEBLOG_SHOW_ENTRIES' => $lang['Weblog_Limit'],
	'L_WEBLOG_COMMENTS_LABEL' => $lang['Weblog_comments_label'],
	'L_WEBLOG_POST_COMMENT_LABEL' => $lang['Weblog_post_comment_label'],
	'L_WEBLOG_COMMENT_EXPLAIN' => $lang['Weblog_comment_explain'],
	'L_WEBLOG_TEMPLATE' => $lang['Weblog_template'],
	'L_WEBLOG_TEMPLATE_EXPLAIN' => $lang['Weblog_template_explain'],
	'L_KEEP' => $lang['Keep'],
	'L_YES' => $lang['Yes'],
	'L_NO' => $lang['No'],

	'MOD_VERSION' => $mod_version,

	'S_FORM_ACTION' => append_sid($HTTP_SERVER_VARS['PHP_SELF']))
);

//
// Mode
//
$mode = '';
if ( isset($HTTP_POST_VARS['deleteweblog']) )
{
	$mode = 'delete_weblog';
}
else if ( isset($HTTP_POST_VARS['editweblog']) )
{
	$mode = 'edit_weblog';
}
else if ( isset($HTTP_POST_VARS['addweblog']) )
{
	$mode = 'create_weblog';
}

if ( $mode )
{
	$weblog_cat = ( isset($HTTP_POST_VARS[POST_CAT_URL]) ) ? intval($HTTP_POST_VARS[POST_CAT_URL]) : 0;
	$weblog_name = ( isset($HTTP_POST_VARS['weblog_name']) ) ? $HTTP_POST_VARS['weblog_name'] : '';	// Weblog Namge
	$weblog_desc = ( isset($HTTP_POST_VARS['weblog_desc']) ) ? $HTTP_POST_VARS['weblog_desc'] : '';	// Weblog Description
	$weblog_access = ( isset($HTTP_POST_VARS['weblog_access']) ) ? intval($HTTP_POST_VARS['weblog_access']) : 0;	// Weblog View Auth
	$weblog_access2 = ( isset($HTTP_POST_VARS['weblog_access2']) ) ? intval($HTTP_POST_VARS['weblog_access2']) : 0;	// Weblog Reply Auth
	$weblog_visible = ( isset($HTTP_POST_VARS['weblog_visible']) ) ? intval($HTTP_POST_VARS['weblog_visible']) : 1;	// Weblog Visible
	$weblog_show_entries = ( isset($HTTP_POST_VARS['weblog_show_entries']) ) ? intval($HTTP_POST_VARS['weblog_show_entries']) : 0;	// Num entries on weblog page.
	$weblog_comment_name = ( isset($HTTP_POST_VARS['comment_name']) ) ? $HTTP_POST_VARS['comment_name'] : '';	// Name of weblog comments
	$weblog_post_comment_name = ( isset($HTTP_POST_VARS['post_comment_name']) ) ? $HTTP_POST_VARS['post_comment_name'] : '';	// Name of "post a comment" in weblog
	$weblog_mode = ( isset($HTTP_POST_VARS['weblog_mode']) ) ? $HTTP_POST_VARS['weblog_mode'] : WEBLOG_STYLE_KEEP;
	$weblog_style = ( isset($HTTP_POST_VARS['weblog_style']) ) ? $HTTP_POST_VARS['weblog_style'] : '';	// Weblog Style Name
	$weblog_face = ( isset($HTTP_POST_VARS['weblog_face']) ) ? $HTTP_POST_VARS['weblog_face'] : '';	// Weblog face content
	$weblog_page = ( isset($HTTP_POST_VARS['weblog_page']) ) ? $HTTP_POST_VARS['weblog_page'] : '';	// Weblog page content
	$weblog_show_desc = ( isset($HTTP_POST_VARS['showdesc']) ) ? $HTTP_POST_VARS['showdesc'] : WEBLOG_SHOW;
	$weblog_contact_info = ( isset($HTTP_POST_VARS['showcontact']) ) ? $HTTP_POST_VARS['showcontact'] : WEBLOG_SHOW;
	$weblog_show_info = ( isset($HTTP_POST_VARS['showinfo']) ) ? $HTTP_POST_VARS['showinfo'] : WEBLOG_SHOW;
	$weblog_show_profile = ( isset($HTTP_POST_VARS['showprofile']) ) ? $HTTP_POST_VARS['showprofile'] : WEBLOG_SHOW;
	$weblog_advanced2 = ( isset($HTTP_POST_VARS['weblog_advanced']) ) ? $HTTP_POST_VARS['weblog_advanced'] : WEBLOG_SIMPLE;
	$weblog_advanced2 = ( $weblog_advanced2 == 0 ) ? WEBLOG_SIMPLE : $weblog_advanced2;

	// Check to see if a group of users are given the ability to reply, and yet they cannot see the weblog.
	if ( $weblog_access > $weblog_access2 )
	{
		$message = $lang['Weblog_access_error'] . '<br /><br />' . sprintf($lang['Weblog_click_return_cp'], "<a href=\"weblog_config.$phpEx\" class=\"nav\">", "</a>");

		message_die (GENERAL_ERROR, $message);
	}

	switch ( $mode )
	{
		case 'create_weblog':

		    if ( file_exists("weblogs/styles/" . $weblog_style . "/weblog_header.cfg") )
			{
				$file_header = file("weblogs/styles/" . $weblog_style . "/weblog_header.cfg");
			}
			else
			{
				message_die (GENERAL_MESSAGE, $lang['Weblog_no_header']);
			}

			if ( file_exists("weblogs/styles/" . $weblog_style . "/weblog_header.cfg") )
			{
				$file_header = file("weblogs/styles/" . $weblog_style . "/weblog_header.cfg");
			}
			else
			{
				message_die (GENERAL_MESSAGE, $lang['Weblog_no_header']);
			}

			if ( file_exists("weblogs/styles/" . $weblog_style . "/weblog_footer.cfg") )
			{
				$file_footer = file("weblogs/styles/" . $weblog_style . "/weblog_footer.cfg");
			}
			else
			{
				message_die (GENERAL_MESSAGE, $lang['Weblog_no_header']);
			}

			if ( $weblog_advanced2 == WEBLOG_SIMPLE )
			{
				if ( file_exists("weblogs/styles/" . $weblog_style . "/weblog_s_body.cfg") )
				{
					$file_body = file("weblogs/styles/" . $weblog_style . "/weblog_s_body.cfg");
				}
				else
				{
					message_die (GENERAL_MESSAGE, $lang['Weblog_no_body']);
				}
			}
			else if ( $weblog_advanced2 == WEBLOG_ADVANCED )
			{
				if ( file_exists("weblogs/styles/" . $weblog_style . "/weblog_body.cfg") )
				{
					$file_body = file("weblogs/styles/" . $weblog_style . "/weblog_body.cfg");
				}
				else
				{
					message_die (GENERAL_MESSAGE, $lang['Weblog_no_body']);
				}
			}
			else
			{
				message_die (GENERAL_MESSAGE, "Error determining whether or not the user wants a simple or advanced weblog.");
			}

			if ( file_exists("weblogs/styles/" . $weblog_style . "/weblog_face.cfg") )
			{
				$file_face = file("weblogs/styles/" . $weblog_style . "/weblog_face.cfg");
			}
			else
			{
				message_die (GENERAL_MESSAGE, $lang['Weblog_no_face']);
			}

			$weblog_body = '';
			$weblog_face = '';

			for ( $i = 0; $i < count($file_header); $i++) { $weblog_body .= $file_header[$i]; }
			for ( $i = 0; $i < count($file_body); $i++)   { $weblog_body .= $file_body[$i]; }
			for ( $i = 0; $i < count($file_footer); $i++) { $weblog_body .= $file_footer[$i]; }
			for ( $i = 0; $i < count($file_face); $i++)   { $weblog_face .= $file_face[$i]; }

			$filename = 'weblogs/weblog_' . $userdata['user_id'] . '.cfg';

			// Get a default template as the user's starting template.
			@CHMOD($filename, 0666);
			@unlink($filename);
			$f = @fopen($filename, 'w' );

			@fputs( $f, $weblog_body );
			@fclose( $f );

			$filename = 'weblogs/face_' . $userdata['user_id'] . '.cfg';

			// Get a default template as the user's starting template.
			@CHMOD($filename, 0666);
			@unlink($filename);
			$f = @fopen($filename, 'w' );

			@fputs( $f, $weblog_face );
			@fclose( $f );

			$weblog_id = create_user_weblog($userdata['user_id'], $weblog_name, $weblog_desc, $weblog_cat, $weblog_access, $weblog_visible, $weblog_show_entries, $weblog_comment_name, $weblog_post_comment_name, $weblog_advanced2, $weblog_style, $weblog_access2);
			$weblog_exists = true;

			$message = sprintf($lang['Weblog_created'], "<a href=\"weblog_config.$phpEx\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);

			break;

		case 'edit_weblog':
			if ( $weblog_mode == WEBLOG_STYLE_SWITCH )
			{
				if ( file_exists("weblogs/styles/" . $weblog_style . "/weblog_header.cfg") )
				{
					$file_header = file("weblogs/styles/" . $weblog_style . "/weblog_header.cfg");
				}
				else
				{
					message_die (GENERAL_MESSAGE, $lang['Weblog_no_header']);
				}

				if ( file_exists("weblogs/styles/" . $weblog_style . "/weblog_footer.cfg") )
				{
					$file_footer = file("weblogs/styles/" . $weblog_style . "/weblog_footer.cfg");
				}
				else
				{
					message_die (GENERAL_MESSAGE, $lang['Weblog_no_footer']);
				}

				if ( file_exists("weblogs/styles/" . $weblog_style . "/weblog_face.cfg") )
				{
					$file_face = file("weblogs/styles/" . $weblog_style . "/weblog_face.cfg");
				}
				else
				{
					message_die (GENERAL_MESSAGE, $lang['Weblog_no_face']);
				}

				if ( $weblog_advanced == WEBLOG_ADVANCED )
				{
					if ( file_exists("weblogs/styles/" . $weblog_style . "/weblog_body.cfg") )
					{
						$file_body = file("weblogs/styles/" . $weblog_style . "/weblog_body.cfg");
					}
					else
					{
						message_die (GENERAL_MESSAGE, $lang['Weblog_no_body']);
					}
				}
				else if ( $weblog_advanced == WEBLOG_SIMPLE )
				{
					if ( file_exists("weblogs/styles/" . $weblog_style . "/weblog_s_body.cfg") )
					{
						$file_body = file("weblogs/styles/" . $weblog_style . "/weblog_s_body.cfg");
					}
					else
					{
						message_die (GENERAL_MESSAGE, $lang['Weblog_no_body']);
					}

				}

				$weblog_body = '';
				$weblog_face = '';

				for ( $i = 0; $i < count($file_header); $i++) { $weblog_body .= $file_header[$i]; }
				for ( $i = 0; $i < count($file_body); $i++)   { $weblog_body .= $file_body[$i]; }
				for ( $i = 0; $i < count($file_footer); $i++) { $weblog_body .= $file_footer[$i]; }
				for ( $i = 0; $i < count($file_face); $i++)   { $weblog_face .= $file_face[$i]; }

				$filename = 'weblogs/weblog_' . $userdata['user_id'] . '.cfg';

				// Get a default template as the user's starting template.
				@CHMOD($filename, 0666);
				@unlink($filename);
				$f = @fopen($filename, 'w' );

				@fputs( $f, $weblog_body );
				@fclose( $f );

				$filename = 'weblogs/face_' . $userdata['user_id'] . '.cfg';

				// Get a default template as the user's starting template.
				@CHMOD($filename, 0666);
				@unlink($filename);
				$f = @fopen($filename, 'w' );

				@fputs( $f, $weblog_face );
				@fclose( $f );
			}
			else if ( $weblog_mode == WEBLOG_STYLE_SWITCH_MODE )
			{
				if ( file_exists("weblogs/styles/" . $weblog_style . "/weblog_header.cfg") )
				{
					$file_header = file("weblogs/styles/" . $weblog_style . "/weblog_header.cfg");
				}
				else
				{
					message_die (GENERAL_MESSAGE, $lang['Weblog_no_header']);
				}

				if ( file_exists("weblogs/styles/" . $weblog_style . "/weblog_footer.cfg") )
				{
					$file_footer = file("weblogs/styles/" . $weblog_style . "/weblog_footer.cfg");
				}
				else
				{
					message_die (GENERAL_MESSAGE, $lang['Weblog_no_footer']);
				}

				if ( file_exists("weblogs/styles/" . $weblog_style . "/weblog_face.cfg") )
				{
					$file_face = file("weblogs/styles/" . $weblog_style . "/weblog_face.cfg");
				}
				else
				{
					message_die (GENERAL_MESSAGE, $lang['Weblog_no_face']);
				}

				if ( $userdata['weblog_advanced'] == WEBLOG_SIMPLE )
				{
					if ( file_exists("weblogs/styles/" . $weblog_style . "/weblog_body.cfg") )
					{
						$file_body = file("weblogs/styles/" . $weblog_style . "/weblog_body.cfg");
					}
					else
					{
						message_die (GENERAL_MESSAGE, $lang['Weblog_no_body']);
					}

					// The user's weblog is now set as advanced
					$userdata['weblog_advanced'] = WEBLOG_ADVANCED;
				}
				else if ( $userdata['weblog_advanced'] == WEBLOG_ADVANCED )
				{
					if ( file_exists("weblogs/styles/" . $weblog_style . "/weblog_s_body.cfg") )
					{
						$file_body = file("weblogs/styles/" . $weblog_style . "/weblog_s_body.cfg");
					}
					else
					{
						message_die (GENERAL_MESSAGE, $lang['Weblog_no_body']);
					}

					// The user's weblog is now set as advanced
					$userdata['weblog_advanced'] = WEBLOG_SIMPLE;
				}
				else
				{
					message_die (GENERAL_MESSAGE, "Error determining whether or not the user wants a simple or advanced weblog.");
				}

				$weblog_body = '';
				$weblog_face = '';

				for ( $i = 0; $i < count($file_header); $i++) { $weblog_body .= $file_header[$i]; }
				for ( $i = 0; $i < count($file_body); $i++)   { $weblog_body .= $file_body[$i]; }
				for ( $i = 0; $i < count($file_footer); $i++) { $weblog_body .= $file_footer[$i]; }
				for ( $i = 0; $i < count($file_face); $i++)   { $weblog_face .= $file_face[$i]; }

				$filename = 'weblogs/weblog_' . $userdata['user_id'] . '.cfg';

				// Get a default template as the user's starting template.
				@CHMOD($filename, 0666);
				@unlink($filename);
				$f = @fopen($filename, 'w' );

				@fputs( $f, $weblog_body );
				@fclose( $f );
			}
			else if ( $userdata['weblog_advanced'] == WEBLOG_ADVANCED )
			{
				// Update the weblog owner's .cfg file
				$filename = 'weblogs/weblog_' . $userdata['user_id'] . '.cfg';

				@CHMOD($filename, 0666);
				@unlink($filename);
				$f = @fopen($filename, 'w' );

				$weblog_page = weblog_process_html ($weblog_page);

				@fputs( $f, $weblog_page );
				@fclose( $f );

				$filename = 'weblogs/face_' . $userdata['user_id'] . '.cfg';

				// Get a default template as the user's starting template.
				@CHMOD($filename, 0666);
				@unlink($filename);
				$f = @fopen($filename, 'w' );

				$weblog_face = weblog_process_html ($weblog_face);

				@fputs( $f, $weblog_face );
				@fclose( $f );
			}

			update_user_weblog($weblog_id, $weblog_name, $weblog_desc, $weblog_cat, $weblog_access, $weblog_visible, $weblog_group, $weblog_show_entries, $weblog_comment_name, $weblog_post_comment_name, $weblog_show_profile, $weblog_show_desc, $weblog_contact_info, $weblog_show_info, $userdata['weblog_advanced'], $weblog_style, $weblog_access2);

			$message = sprintf($lang['Weblog_edited'], "<a href=\"weblog_config.$phpEx\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);

			break;
		case 'delete_weblog':
			delete_user_weblog($weblog_id, $weblog_group);

			$message = sprintf($lang['Weblog_deleted'], "<a href=\"weblogs.$phpEx\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);

			break;
		default:
			break;
	}
}

//
// Start Page Proper
//

// Category Box
$sql = "SELECT c.*
	FROM " . CATEGORIES_TABLE . " c
	WHERE cat_allow_weblogs = 1
	ORDER BY c.cat_order ASC";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, "Couldn't obtain categories list", "", __LINE__, __FILE__, $sql);
}
$row = $db->sql_fetchrowset($result);

$select_list = '<select name="' . POST_CAT_URL . '">';
for($i = 0; $i < count($row); $i++)
{
	$select_list .= '<option value="' . $row[$i]['cat_id'] . '"' . ( ( $row[$i]['cat_id'] == $weblog_cat ) ? ' selected' : '' ) . '>' . $row[$i]['cat_title'] . '</option>';
}
$select_list .= '</select>';

// Weblog Auth Box
$rights_select = '<select name="weblog_access">';
for($i = 0; $i < count($weblog_auth_types); $i++)
{
	$selected = ( $weblog_access == $i ) ? ' selected="selected"' : '';
	$rights_select .= '<option value="' . $i . '"' . $selected . '>' . $weblog_auth_desc[$i] . '</option>';
}
$rights_select .= '</select>';

// Second Weblog Auth Box (for reply auth)
$rights_select2 = '<select name="weblog_access2">';
//'$i = 0' changed to '$i = 1' for removing 'All' option from reply auth box - Isaac
for($i = 1; $i < count($weblog_auth_types); $i++)
{
	$selected = ( $weblog_access2 == $i ) ? ' selected="selected"' : '';
	$rights_select2 .= '<option value="' . $i . '"' . $selected . '>' . $weblog_auth_desc[$i] . '</option>';
}
$rights_select2 .= '</select>';

// Style Box
$sql = "SELECT style_id, style_name, style_dir
	FROM " . WEBLOG_STYLE_TABLE . "
	ORDER BY style_id";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, "Couldn't query weblog styles table", "", __LINE__, __FILE__, $sql);
}
while ($row = $db->sql_fetchrow($result))
{
	$style_data[] = $row;
}

if ( !$style_data )
{
	message_die (GENERAL_MESSAGE, $lang['Weblog_no_styles']);
}

$style_select = '<br /><select name="weblog_style" onchange="update_preview(this.options[selectedIndex].name);">';
for ($i = 0; $i < count($style_data); $i++)
{
	$selected = ( $weblog_style == $style_data[$i]['style_dir'] ) ? 'selected="selected"' : '';
	$style_select .= '<option name="' . $style_data[$i]['style_dir'] . '" value="' . $style_data[$i]['style_dir'] . '"' . $selected . '>' . $style_data[$i]['style_name'] . '</option>';
}
$style_select .= "</select>";

if ( $weblog_exists )
{
	// If the weblog exists, then the user must be wanting to edit their weblog
	$template->assign_block_vars('switch_edit_weblog', array());

	// Post new entry image
	$new_topic_url = append_sid("weblog_posting.$phpEx?mode=newtopic&amp;" . POST_WEBLOG_URL . "=$weblog_id");
	$post_img = ( $weblog_status == FORUM_LOCKED ) ? $images['post_locked'] : $images['post_new_entry'];
	$post_alt = ( $weblog_status == FORUM_LOCKED ) ? $lang['Weblog_locked'] : $lang['Post_new_topic'];

	$weblog_visible_yes = ( $weblog_visible == 1 ) ? 'checked="checked"' : '';
	$weblog_visible_no = ( $weblog_visible == 0 ) ? 'checked="checked"' : '';

	$template->assign_vars(array(
		'L_POST_NEW_TOPIC' => $post_alt,
		'L_WEBLOG_DELETE' => $lang['Delete'],
		'L_WEBLOG_UPDATE' => $lang['Weblog_Update'],
		'L_WEBLOG_CP_EXPLAIN' => $lang['Weblog_Update_Explain'],
		'L_WEBLOG_MANAGE_GROUP' => $lang['Weblog_Manage_Group'],
		'L_WEBLOG_RESET' => $lang['Weblog_reset'],
		'L_WEBLOG_RESET_EXPLAIN' => $lang['Weblog_reset_explain'],
		'U_WEBLOG_MANAGE_GROUP' => append_sid("groupcp.$phpEx?" . POST_GROUPS_URL . "=" . $weblog_group),
		'U_POST_NEW_TOPIC' => $new_topic_url,

		'POST_IMG' => $post_img,
		'KEEP_RADIO' => '<input type="radio" name="weblog_mode" value="' . WEBLOG_STYLE_KEEP . '" checked="checked">&nbsp;' . $lang['Keep'] . '&nbsp;&nbsp;',
		'SWITCH_STYLE_RADIO' => '<input type="radio" name="weblog_mode" value="' . WEBLOG_STYLE_SWITCH . '">&nbsp;' . $lang['Weblog_switch_style'] . '&nbsp;&nbsp;',

		'S_WEBLOG_VISIBLE_YES' => $weblog_visible_yes,
		'S_WEBLOG_VISIBLE_NO' => $weblog_visible_no,
		'S_WEBLOG_NAME' => stripslashes($weblog_name),
		'S_WEBLOG_DESCRIPTION' => stripslashes($weblog_desc),
		'S_WEBLOG_COMMENT_NAME' => stripslashes($weblog_comment_name),
		'S_WEBLOG_POST_COMMENT_NAME' => stripslashes($weblog_post_comment_name),
		'S_WEBLOG_SHOW_ENTRIES' => $weblog_show_entries)
	);

	if ( $weblog_advanced == WEBLOG_ADVANCED )
	{
		$template->assign_block_vars('switch_edit_new_weblog', array());

		$switch_style = $lang['Weblog_switch_easy'];

		// Load files
		if ( file_exists("weblogs/weblog_" . $userdata['user_id'] . ".cfg") )
		{
			$file_body = file("weblogs/weblog_" . $userdata['user_id'] . ".cfg");
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['Weblog_no_body']);
		}

		if ( file_exists("weblogs/face_" . $userdata['user_id'] . ".cfg") )
		{
			$file_face = file("weblogs/face_" . $userdata['user_id'] . ".cfg");
		}
		else
		{
			message_die(GENERAL_MESSAGE, $lang['Weblog_no_face']);
		}

		$weblog_body = '';
		for ($i = 0;$i < count($file_body); $i++)
		{
			$weblog_body .= $file_body[$i];
		}

		$weblog_face = '';
		for ($i = 0;$i < count($file_face); $i++)
		{
			$weblog_face .= $file_face[$i];
		}

		$template->assign_vars(array(
			'L_WEBLOG_PAGE' => $lang['Weblog_page'],
			'L_WEBLOG_PAGE_EXPLAIN' => $lang['Weblog_page_explain'],
			'L_WEBLOG_PAGE_TIP' => $lang['Weblog_page_tip'],
			'L_WEBLOG_FACE' => $lang['Weblog_face'],
			'L_WEBLOG_FACE_EXPLAIN' => $lang['Weblog_face_explain'],
			'L_WEBLOG_FACE_TIP' => $lang['Weblog_face_tip'],

			'S_WEBLOG_PAGE' => $weblog_body,
			'S_WEBLOG_FACE' => $weblog_face)
		);
	}
	else if ( $weblog_advanced == WEBLOG_SIMPLE )
	{
		$switch_style = $lang['Weblog_switch_advanced'];

		$template->assign_block_vars('switch_edit_old_weblog', array());
	}

	if ( $weblog_config['allow_advanced'] == WEBLOG_ALLOW_CHOOSE )
	{
		$template->assign_vars(array(
			'SWITCH_MODE_RADIO' => '<input type="radio" name="weblog_mode" value="' . WEBLOG_STYLE_SWITCH_MODE . '">&nbsp;' . $switch_style . '&nbsp;&nbsp;')
		);
	}
}
else
{
	$template->assign_block_vars('switch_create_weblog', array());

	$template->assign_vars(array(
		'L_WEBLOG_CREATE' => $lang['Weblog_Create'],
		'L_WEBLOG_CP_EXPLAIN' => $lang['Weblog_Create_Explain'],
		'S_WEBLOG_VISIBLE_YES' => 'checked="checked"',
		'S_WEBLOG_NAME' => stripslashes($weblog_name),
		'S_WEBLOG_DESCRIPTION' => stripslashes($weblog_desc),
		'S_WEBLOG_COMMENT_NAME' => stripslashes($weblog_comment_name),
		'S_WEBLOG_POST_COMMENT_NAME' => stripslashes($weblog_post_comment_name),

		'S_WEBLOG_SHOW_ENTRIES' => $weblog_config['default_entries'])
	);

	if ( $weblog_config['allow_advanced'] == WEBLOG_ALLOW_CHOOSE )
	{
		$template->assign_vars(array(
			'MODE_RADIO' => '<input type="radio" name="weblog_advanced" value="' . WEBLOG_SIMPLE . '" checked="checked">&nbsp;' . $lang['Weblog_easy_mode'] . '&nbsp;&nbsp;<input type="radio" name="weblog_advanced" value="' . WEBLOG_ADVANCED . '">&nbsp;' . $lang['Weblog_advanced_mode'] . '&nbsp;&nbsp;')
		);
	}
}

if ( $userdata['weblog_advanced'] == WEBLOG_SIMPLE )
	$switch_radio = '<input type="radio" name="weblog_mode" value="1" /> ' . $lang['Weblog_switch_advanced'] . '&nbsp;&nbsp;';
else if ( $userdata['weblog_advanced'] == WEBLOG_ADVANCED )
	$switch_radio = '<input type="radio" name="weblog_mode" value="1" /> ' . $lang['Weblog_switch_easy'] . '&nbsp;&nbsp;';

$template->assign_vars(array(
	'WEBLOG_STYLE_SELECT' => ( $weblog_config['allow_advanced'] == WEBLOG_ALLOW_CHOOSE ) ? $style_select : '',
	'WEBLOG_RESET_SELECT' => $reset_select,
	'RESET_RADIO' => ( $userdata['weblog_advanced'] == WEBLOG_ADVANCED ) ? '<input type="radio" name="weblog_mode" value="2" /> ' . $lang['Weblog_reset'] : '',
	'SWITCH_RADIO' => ( $weblog_config['allow_advanced'] == WEBLOG_ALLOW_CHOOSE ) ? $switch_radio : '',

	'L_WEBLOG_SHOW_PROFILE' => $lang['Weblog_show_profile'],
	'L_WEBLOG_DISPLAY_DESCRIPTION' => $lang['Weblog_display_description'],
	'L_WEBLOG_SHOW_CONTACT' => $lang['Weblog_show_contact'],
	'L_WEBLOG_SHOW_INFO' => $lang['Weblog_show_info'],

	'L_CENTER' => $lang['Weblog_center'],
	'L_RIGHT' => $lang['Weblog_right'],

	'SHOW_PROFILE_YES' => ( $weblog_show_profile ) ? 'checked="checked"' : '',
	'SHOW_PROFILE_NO' => ( !$weblog_show_profile ) ? 'checked="checked"' : '',

	'SHOW_DESC_CENTER' => ( $weblog_show_desc ) ? 'checked="checked"' : '',
	'SHOW_DESC_RIGHT' => ( !$weblog_show_desc ) ? 'checked="checked"' : '',

	'SHOW_CONTACT_YES' => ( $weblog_contact_info ) ? 'checked="checked"' : '',
	'SHOW_CONTACT_NO' => ( !$weblog_contact_info ) ? 'checked="checked"' : '',

	'SHOW_INFO_YES' => ( $weblog_show_info ) ? 'checked="checked"' : '',
	'SHOW_INFO_NO' => ( !$weblog_show_info ) ? 'checked="checked"' : '',

	'PREVIEW_IMAGE' => ( $weblog_style ) ? '<br /><img src="' . $phpbb_root_path . 'weblogs/styles/' . $weblog_style . '/weblog_preview.gif" name="preview_image" border="0">' : '',

	'S_WEBLOG_COMMENTS_AUTH_SELECT' => $rights_select2,
	'S_WEBLOG_SELECT' => $select_list,
	'S_WEBLOG_RIGHTS_SELECT' => $rights_select)
);

$template->pparse("body");

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
